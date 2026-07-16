<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\CropTemplate;
use App\Models\FarmingCycle;
use App\Models\ScheduleStage;
use App\Models\ScheduleItem;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    // ============ MAIN INDEX ============
    public function index()
    {
        $schedules = Schedule::where('user_id', auth()->id())->orderBy('date', 'asc')->get();
        $cycles = FarmingCycle::where('user_id', auth()->id())
            ->with(['cropTemplate', 'stages.items'])
            ->orderBy('created_at', 'desc')
            ->get();
        $templates = CropTemplate::where('is_active', true)->get();

        return view('schedule.index', compact('schedules', 'cycles', 'templates'));
    }

    // ============ SIMPLE SCHEDULE CRUD (backward compatible) ============
    public function store(Request $request)
    {
        $request->validateWithBag('simple_schedule', [
            'activity' => 'required|string|max:255',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        Schedule::create([
            'user_id' => auth()->id(),
            'activity' => trim($request->activity),
            'date' => $request->date,
            'status' => 'pending',
            'notes' => $request->filled('notes') ? trim($request->notes) : null,
        ]);

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function update(Request $request, Schedule $schedule)
    {
        if ($schedule->user_id !== auth()->id()) abort(403);

        $schedule->update([
            'status' => $schedule->status === 'pending' ? 'completed' : 'pending'
        ]);

        return redirect()->back()->with('success', 'Status jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        if ($schedule->user_id !== auth()->id()) abort(403);
        $schedule->delete();
        return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
    }

    // ============ FARMING CYCLE OPERATIONS ============
    public function storeCycle(Request $request)
    {
        $request->validate([
            'crop_template_id' => 'required|exists:crop_templates,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'area_hectares' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $template = CropTemplate::findOrFail($request->crop_template_id);
        $startDate = Carbon::parse($request->start_date);
        $endDate = $startDate->copy()->addDays($template->duration_days - 1);

        $cycle = FarmingCycle::create([
            'user_id' => auth()->id(),
            'crop_template_id' => $template->id,
            'name' => $request->name,
            'start_date' => $startDate,
            'estimated_end_date' => $endDate,
            'location' => $request->location,
            'area_hectares' => $request->area_hectares,
            'status' => 'active',
            'notes' => $request->notes,
        ]);

        // Auto-generate stages based on crop type
        $this->generateStages($cycle, $template, $startDate);

        return redirect()->back()->with('success', 'Siklus pertanian "' . $cycle->name . '" berhasil dibuat beserta jadwal tahapan otomatis.');
    }

    public function destroyCycle(FarmingCycle $cycle)
    {
        if ($cycle->user_id !== auth()->id()) abort(403);
        $cycle->delete();
        return redirect()->route('schedule.index')->with('success', 'Siklus pertanian berhasil dihapus.');
    }

    public function completeCycle(FarmingCycle $cycle)
    {
        if ($cycle->user_id !== auth()->id()) abort(403);

        $cycle->update([
            'status' => 'completed',
            'actual_end_date' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Siklus pertanian berhasil diselesaikan.');
    }

    // ============ STAGE OPERATIONS ============
    public function updateStage(Request $request, ScheduleStage $stage)
    {
        $cycle = $stage->cycle;
        if ($cycle->user_id !== auth()->id()) abort(403);

        $status = $request->input('status', $stage->status);
        $stage->update([
            'status' => $status,
            'notes' => $request->input('notes', $stage->notes),
        ]);

        return redirect()->back()->with('success', 'Status tahapan berhasil diperbarui.');
    }

    // ============ SCHEDULE ITEM OPERATIONS ============
    public function storeItem(Request $request, ScheduleStage $stage)
    {
        $cycle = $stage->cycle;
        if ($cycle->user_id !== auth()->id()) abort(403);

        $request->validate([
            'activity' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'nullable|string|max:10',
            'notes' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'products' => 'nullable|string',
        ]);

        ScheduleItem::create([
            'schedule_stage_id' => $stage->id,
            'user_id' => auth()->id(),
            'activity' => $request->activity,
            'date' => $request->date,
            'time' => $request->time,
            'status' => 'pending',
            'notes' => $request->notes,
            'recommendations' => $request->recommendations,
            'products' => $request->products,
        ]);

        return redirect()->back()->with('success', 'Kegiatan berhasil ditambahkan ke tahapan.');
    }

    public function updateItem(Request $request, ScheduleItem $item)
    {
        if ($item->user_id !== auth()->id()) abort(403);

        $status = $request->input('status', $item->status);
        $item->update([
            'status' => $status,
            'notes' => $request->input('notes', $item->notes),
        ]);

        return redirect()->back()->with('success', 'Status kegiatan berhasil diperbarui.');
    }

    public function updateItemDate(Request $request, ScheduleItem $item)
    {
        if ($item->user_id !== auth()->id()) abort(403);

        $request->validate(['date' => 'required|date']);

        $item->update(['date' => $request->date]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'date' => $item->date->format('Y-m-d')]);
        }

        return redirect()->back()->with('success', 'Tanggal kegiatan berhasil diperbarui.');
    }

    public function updateItemStage(Request $request, ScheduleItem $item)
    {
        if ($item->user_id !== auth()->id()) abort(403);

        $request->validate([
            'schedule_stage_id' => 'required|exists:schedule_stages,id',
        ]);

        $newStage = ScheduleStage::findOrFail($request->schedule_stage_id);
        if ($newStage->farming_cycle_id !== $item->stage->farming_cycle_id) abort(403);

        $item->update([
            'schedule_stage_id' => $newStage->id,
            'date' => $request->input('date', $newStage->start_date->format('Y-m-d')),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Kegiatan berhasil dipindahkan.');
    }

    public function destroyItem(ScheduleItem $item)
    {
        if ($item->user_id !== auth()->id()) abort(403);
        $item->delete();
        return redirect()->back()->with('success', 'Kegiatan berhasil dihapus.');
    }

    // ============ HELPER: Generate Stages from Template ============
    private function generateStages(FarmingCycle $cycle, CropTemplate $template, Carbon $startDate): void
    {
        $stagesData = $this->getStagesForCrop($template->slug);

        $currentDate = $startDate->copy();

        foreach ($stagesData as $index => $stageData) {
            $stage = ScheduleStage::create([
                'farming_cycle_id' => $cycle->id,
                'name' => $stageData['name'],
                'order' => $index + 1,
                'start_date' => $currentDate->copy(),
                'end_date' => $currentDate->copy()->addDays($stageData['duration_days'] - 1),
                'duration_days' => $stageData['duration_days'],
                'status' => 'pending',
            ]);

            // Generate items for each stage
            if (isset($stageData['items'])) {
                foreach ($stageData['items'] as $itemData) {
                    $itemDate = $currentDate->copy()->addDays($itemData['day_offset'] ?? 0);
                    ScheduleItem::create([
                        'schedule_stage_id' => $stage->id,
                        'user_id' => $cycle->user_id,
                        'activity' => $itemData['activity'],
                        'date' => $itemDate,
                        'time' => $itemData['time'] ?? null,
                        'status' => 'pending',
                        'notes' => $itemData['notes'] ?? null,
                        'recommendations' => $itemData['recommendations'] ?? null,
                        'products' => isset($itemData['products']) ? json_encode($itemData['products']) : null,
                    ]);
                }
            }

            $currentDate->addDays($stageData['duration_days']);
        }
    }

    private function getStagesForCrop(string $slug): array
    {
        return match ($slug) {
            'padi' => $this->getPadiStages(),
            'jagung' => $this->getJagungStages(),
            'kedelai' => $this->getKedelaiStages(),
            'cabe' => $this->getCabeStages(),
            'kangkung' => $this->getKangkungStages(),
            default => [],
        };
    }

    private function getPadiStages(): array
    {
        return [
            [
                'name' => 'Pengolahan Tanah',
                'duration_days' => 14,
                'items' => [
                    ['activity' => 'Pengeringan lahan', 'day_offset' => 0, 'time' => '06:00', 'notes' => 'Keringkan lahan selama 3-5 hari', 'recommendations' => 'Pastikan air surut sempurna dari sawah'],
                    ['activity' => 'Pembajakan pertama', 'day_offset' => 3, 'time' => '07:00', 'notes' => 'Bajak sedalam 20-25 cm', 'recommendations' => 'Gunakan traktor atau bajak sawah tradisional', 'products' => ['Traktor tangan', 'Bajak rotary']],
                    ['activity' => 'Pengolahan tanah (penggemburan)', 'day_offset' => 7, 'time' => '07:00', 'notes' => 'Gemburkan tanah hingga kedalaman 15 cm', 'recommendations' => 'Lakukan penggemburan 2-3 kali agar tanah benar-benar hancur'],
                    ['activity' => 'Pengairan dan perataan', 'day_offset' => 10, 'time' => '08:00', 'notes' => 'Masukkan air dan ratakan permukaan sawah', 'recommendations' => 'Ketinggian air 3-5 cm saat perataan', 'products' => ['Batang rata']],
                ],
            ],
            [
                'name' => 'Pemupukan Dasar',
                'duration_days' => 5,
                'items' => [
                    ['activity' => 'Pemberian pupuk dasar (NPK)', 'day_offset' => 0, 'time' => '07:00', 'notes' => 'Taburkan NPK 16-16-16 sebanyak 200-250 kg/ha', 'recommendations' => 'Sebar merata saat air dangkal (2-3 cm)', 'products' => ['NPK Mutiara 16-16-16', 'Pupuk Urea']],
                    ['activity' => 'Pemberian pupuk kandang/kompos', 'day_offset' => 1, 'time' => '08:00', 'notes' => 'Taburkan pupuk kandang 5-10 ton/ha', 'recommendations' => 'Pupuk kandang harus sudah matang/difermentasi'],
                    ['activity' => 'Pengolahan tanah final', 'day_offset' => 3, 'time' => '07:00', 'notes' => 'Incorporation pupuk ke dalam tanah', 'recommendations' => 'Bajak ulang untuk mencampur pupuk ke tanah'],
                ],
            ],
            [
                'name' => 'Penyemaian Benih',
                'duration_days' => 14,
                'items' => [
                    ['activity' => 'Perendaman benih', 'day_offset' => 0, 'time' => '06:00', 'notes' => 'Rendam benih dalam air selama 24 jam', 'recommendations' => 'Gunakan benih bermutu tinggi varietas unggul', 'products' => ['Benih padi Ciherang/Mentik Wangi', 'Garam dapur']],
                    ['activity' => 'Perawatan benih semaian', 'day_offset' => 2, 'time' => '06:00', 'notes' => 'Siram semaian 2x sehari (pagi dan sore)', 'recommendations' => 'Jaga kelembapan media semaian'],
                    ['activity' => 'Pemupukan bibit semaian', 'day_offset' => 5, 'time' => '07:00', 'notes' => 'Berikan Urea 5 g/m² untuk pertumbuhan awal', 'recommendations' => 'Pupuk saat bibit sudah memiliki 2-3 daun', 'products' => ['Urea prill']],
                    ['activity' => 'Penyemprotan hama bibit', 'day_offset' => 8, 'time' => '07:00', 'notes' => 'Semprotkan insektisida untuk pencegahan hama', 'recommendations' => 'Waspadai hama ulat grayak dan penggerek batang', 'products' => ['Insektisida karate boron']],
                    ['activity' => 'Siap pindah tanam (umur 21-25 hari)', 'day_offset' => 12, 'time' => '06:00', 'notes' => 'Pastikan bibit sudah memiliki 3-4 daun dan tinggi 15-20 cm', 'recommendations' => 'Pindah tanam saat sore hari untuk mengurangi stres tanaman'],
                ],
            ],
            [
                'name' => 'Tanam',
                'duration_days' => 7,
                'items' => [
                    ['activity' => 'Pengaturan ketinggian air', 'day_offset' => 0, 'time' => '06:00', 'notes' => 'Atur air sawah setinggi 2-3 cm', 'recommendations' => 'Air terlalu tinggi menyebabkan bibit melayang'],
                    ['activity' => 'Pindah tanam bibit', 'day_offset' => 1, 'time' => '08:00', 'notes' => 'Tanam bibit dengan jarak 20x20 cm atau 25x25 cm', 'recommendations' => 'Tanam 2-3 bibit per lubang tanam', 'products' => ['Tali rafia untuk benang ajir']],
                    ['activity' => 'Perawatan pasca tanam', 'day_offset' => 3, 'time' => '06:00', 'notes' => 'Periksa kondisi bibit, ganti yang mati', 'recommendations' => 'Pastikan semua bibit berdiri tegak dan akar menempel tanah'],
                    ['activity' => 'Pengaturan air pasca tanam', 'day_offset' => 5, 'time' => '06:00', 'notes' => 'Jaga ketinggian air 2-3 cm', 'recommendations' => 'Hindari genangan berlebih yang menyebabkan pembusukan akar'],
                ],
            ],
            [
                'name' => 'Pemupukan Susulan',
                'duration_days' => 45,
                'items' => [
                    ['activity' => 'Pemupukan susulan I (Urea)', 'day_offset' => 14, 'time' => '07:00', 'notes' => 'Tabur Urea 100-150 kg/ha saat umur 14 HST', 'recommendations' => 'Tabur saat air dangkal untuk penyerapan optimal', 'products' => ['Urea prill 46%']],
                    ['activity' => 'Pemupukan susulan II (NPK)', 'day_offset' => 35, 'time' => '07:00', 'notes' => 'Tabur NPK 16-16-16 sebanyak 100 kg/ha saat umur 35 HST', 'recommendations' => 'Pupuk ini penting untuk pembentukan malai', 'products' => ['NPK Mutiara 16-16-16']],
                    ['activity' => 'Pemupukan susulan III (Urea)', 'day_offset' => 50, 'time' => '07:00', 'notes' => 'Tabur Urea 50-75 kg/ha saat umur 50 HST (fase bunting)', 'recommendations' => 'Pemupukan terakhir untuk pengisian bulir', 'products' => ['Urea prill 46%']],
                ],
            ],
            [
                'name' => 'Pengairan & Pemeliharaan',
                'duration_days' => 35,
                'items' => [
                    ['activity' => 'Pengaturan air fase vegetatif', 'day_offset' => 0, 'time' => '06:00', 'notes' => 'Jaga air 3-5 cm selama fase pertumbuhan', 'recommendations' => 'Lakukan pengaturan air secara teratur'],
                    ['activity' => 'Pengaturan air fase generatif', 'day_offset' => 15, 'time' => '06:00', 'notes' => 'Kurangi air menjadi 2-3 cm saat fase generatif', 'recommendations' => 'Pengeringan ringan merangsang pembentukan malai'],
                    ['activity' => 'Pengaturan air fase pengisian bulir', 'day_offset' => 25, 'time' => '06:00', 'notes' => 'Naikkan air kembali 3-5 cm saat pengisian bulir', 'recommendations' => 'Air cukup untuk mendukung pengisian bulir'],
                ],
            ],
            [
                'name' => 'Pengendalian Hama & Penyakit',
                'duration_days' => 50,
                'items' => [
                    ['activity' => 'Monitoring hama mingguan', 'day_offset' => 0, 'time' => '07:00', 'notes' => 'Periksa keberadaan hama dan penyakit', 'recommendations' => 'Catat jenis dan jumlah hama yang ditemukan'],
                    ['activity' => 'Penyemprotan insektisida (jika diperlukan)', 'day_offset' => 20, 'time' => '06:00', 'notes' => 'Semprotkan insektisida jika populasi hama melebihi ambang batas', 'recommendations' => 'Semprot saat cuaca cerah, pagi atau sore hari', 'products' => ['Insektisida abamektin', 'Insektisida BPMC']],
                    ['activity' => 'Pengendalian penyakit blas', 'day_offset' => 30, 'time' => '07:00', 'notes' => 'Semprot fungisida jika terdeteksi gejala blas', 'recommendations' => 'Gunakan varietas tahan blas dan pengaturan air yang baik', 'products' => ['Fungisida trisiklazol']],
                    ['activity' => 'Pengendalian wereng batang coklat', 'day_offset' => 40, 'time' => '07:00', 'notes' => 'Waspadai serangan wereng pada fase dewasa', 'recommendations' => 'Gunakan varietas tahan wereng, lakukan pengaturan pupuk nitrogen'],
                ],
            ],
            [
                'name' => 'Panen',
                'duration_days' => 7,
                'items' => [
                    ['activity' => 'Pematangan padi (fase masak kuning)', 'day_offset' => 0, 'time' => '06:00', 'notes' => 'Tunggu hingga 80-85% bulir menguning', 'recommendations' => 'Panen terlalu cepat menurunkan kualitas gabah'],
                    ['activity' => 'Pemanenan padi', 'day_offset' => 3, 'time' => '06:00', 'notes' => 'Potong tanaman pada bagian bawah batang (30 cm dari tanah)', 'recommendations' => 'Gunakan ani-ani atau mesin potong padi', 'products' => ['Sabit/ani-ani', 'Karung panen']],
                    ['activity' => 'Perontokan padi', 'day_offset' => 4, 'time' => '07:00', 'notes' => 'Rontokkan bulir gabah dari batang', 'recommendations' => 'Gunakan mesin perontok untuk efisiensi', 'products' => ['Mesin perontok padi']],
                    ['activity' => 'Pengeringan gabah', 'day_offset' => 5, 'time' => '06:00', 'notes' => 'Jemur gabah hingga kadar air < 14%', 'recommendations' => 'Jemur di bawah sinar matahari langsung selama 2-3 hari'],
                    ['activity' => 'Penggilingan & penyimpanan', 'day_offset' => 6, 'time' => '08:00', 'notes' => 'Giling gabah menjadi beras', 'recommendations' => 'Simpan di tempat kering dan kedap udara'],
                ],
            ],
        ];
    }

    private function getJagungStages(): array
    {
        return [
            [
                'name' => 'Pengolahan Tanah',
                'duration_days' => 10,
                'items' => [
                    ['activity' => 'Pembajakan lahan', 'day_offset' => 0, 'time' => '07:00', 'notes' => 'Bajak lahan sedalam 20-25 cm', 'recommendations' => 'Lakukan 1-2 kali pembajakan'],
                    ['activity' => 'Pengolahan tanah final', 'day_offset' => 5, 'time' => '07:00', 'notes' => 'Gemburkan dan ratakan lahan', 'recommendations' => 'Buat bedengan lebar 60-80 cm'],
                ],
            ],
            [
                'name' => 'Penanaman',
                'duration_days' => 5,
                'items' => [
                    ['activity' => 'Penyemprotan herbisida pra-tanam', 'day_offset' => 0, 'time' => '06:00', 'notes' => 'Semprotkan herbisida sebelum penanaman', 'recommendations' => 'Gunakan herbisida selektif', 'products' => ['Herbisida atrazin']],
                    ['activity' => 'Penanaman benih jagung', 'day_offset' => 2, 'time' => '08:00', 'notes' => 'Tanam benih dengan jarak 75x25 cm', 'recommendations' => 'Tanam 2 benih per lubang, tutup tanah 2-3 cm'],
                ],
            ],
            [
                'name' => 'Pemupukan',
                'duration_days' => 60,
                'items' => [
                    ['activity' => 'Pemupukan dasar', 'day_offset' => 0, 'time' => '07:00', 'notes' => 'Berikan NPK 15-15-15 sebanyak 200 kg/ha', 'recommendations' => 'Campurkan dengan tanah saat penanaman', 'products' => ['NPK 15-15-15']],
                    ['activity' => 'Pemupukan susulan I', 'day_offset' => 21, 'time' => '07:00', 'notes' => 'Berikan Urea 100 kg/ha', 'recommendations' => 'Tabur di sekitar tanaman dan tutup tanah', 'products' => ['Urea 46%']],
                    ['activity' => 'Pemupukan susulan II', 'day_offset' => 42, 'time' => '07:00', 'notes' => 'Berikan Urea 50 kg/ha', 'recommendations' => 'Pada fase pembentukan tongkol', 'products' => ['Urea 46%']],
                ],
            ],
            [
                'name' => 'Pemeliharaan',
                'duration_days' => 35,
                'items' => [
                    ['activity' => 'Penyiangan gulma', 'day_offset' => 14, 'time' => '07:00', 'notes' => 'Cabut gulma di sekitar tanaman', 'recommendations' => 'Lakukan penyiangan 2-3 kali selama musim tanam'],
                    ['activity' => 'Pengendalian hama', 'day_offset' => 25, 'time' => '07:00', 'notes' => 'Periksa dan kendalikan hama ulat tongkol', 'recommendations' => 'Gunakan insektisida jika populasi tinggi', 'products' => ['Insektisida lambda cyhalothrin']],
                ],
            ],
            [
                'name' => 'Panen',
                'duration_days' => 10,
                'items' => [
                    ['activity' => 'Pemeriksaan kematangan', 'day_offset' => 0, 'time' => '06:00', 'notes' => 'Periksa kadar air tongkol (20-25%)', 'recommendations' => 'Kulit tongkol mengering dan berwarna kuning kecoklatan'],
                    ['activity' => 'Pemanenan', 'day_offset' => 3, 'time' => '06:00', 'notes' => 'Petik tongkol yang sudah matang', 'recommendations' => 'Panen saat cuaca cerah'],
                    ['activity' => 'Pengeringan & penyimpanan', 'day_offset' => 5, 'time' => '07:00', 'notes' => 'Jemur jagung hingga kadar air < 14%', 'recommendations' => 'Simpan di tempat kering dan berventilasi'],
                ],
            ],
        ];
    }

    private function getKedelaiStages(): array
    {
        return [
            [
                'name' => 'Pengolahan Tanah',
                'duration_days' => 7,
                'items' => [
                    ['activity' => 'Pembajakan lahan', 'day_offset' => 0, 'time' => '07:00', 'notes' => 'Bajak lahan sedalam 15-20 cm', 'recommendations' => 'Kedelai tidak memerlukan pengolahan tanah terlalu dalam'],
                    ['activity' => 'Pembuatan bedengan', 'day_offset' => 3, 'time' => '07:00', 'notes' => 'Buat bedengan lebar 60-80 cm', 'recommendations' => 'Pastikan drainase baik'],
                ],
            ],
            [
                'name' => 'Penanaman',
                'duration_days' => 3,
                'items' => [
                    ['activity' => 'Inokulasi benih (Rhizobium)', 'day_offset' => 0, 'time' => '06:00', 'notes' => 'Campurkan benih dengan bakteri Rhizobium', 'recommendations' => 'Gunakan inokulan yang sesuai', 'products' => ['Inokulan Rhizobium']],
                    ['activity' => 'Penanaman benih', 'day_offset' => 1, 'time' => '08:00', 'notes' => 'Tanam benih dengan jarak 40x15 cm', 'recommendations' => 'Tanam 2-3 benih per lubang, kedalaman 2-3 cm'],
                ],
            ],
            [
                'name' => 'Pemeliharaan',
                'duration_days' => 50,
                'items' => [
                    ['activity' => 'Penyiangan dan pendangiran', 'day_offset' => 14, 'time' => '07:00', 'notes' => 'Cabut gulma dan dangir tanah', 'recommendations' => 'Lakukan saat tanaman mulai tumbuh'],
                    ['activity' => 'Pemupukan susulan', 'day_offset' => 21, 'time' => '07:00', 'notes' => 'Berikan pupuk Fosfor (SP-36) 100 kg/ha', 'recommendations' => 'Fosfor penting untuk pembentukan bunga', 'products' => ['SP-36']],
                    ['activity' => 'Pengendalian hama', 'day_offset' => 30, 'time' => '07:00', 'notes' => 'Kendalikan ulat dan kutu daun', 'recommendations' => 'Gunakan insektisida nabati jika memungkinkan', 'products' => ['Insektisida nabati']],
                ],
            ],
            [
                'name' => 'Panen',
                'duration_days' => 10,
                'items' => [
                    ['activity' => 'Pemeriksaan kematangan', 'day_offset' => 0, 'time' => '06:00', 'notes' => 'Periksa polong yang mengering (80% kuning)', 'recommendations' => 'Panen terlalu cepat menurunkan mutu'],
                    ['activity' => 'Pemanenan', 'day_offset' => 3, 'time' => '06:00', 'notes' => 'Petik atau cabut tanaman kedelai', 'recommendations' => 'Panen saat cuaca cerah dan kering'],
                    ['activity' => 'Perontokan & pengeringan', 'day_offset' => 5, 'time' => '07:00', 'notes' => 'Rontokkan biji dan jemur hingga kering', 'recommendations' => 'Kadar air akhir < 12%'],
                ],
            ],
        ];
    }

    private function getCabeStages(): array
    {
        return [
            [
                'name' => 'Penyemaian',
                'duration_days' => 30,
                'items' => [
                    ['activity' => 'Persiapan media semai', 'day_offset' => 0, 'time' => '07:00', 'notes' => 'Siapkan campuran tanah dan pupuk kandang', 'recommendations' => 'Gunakan tray semai atau polybag kecil'],
                    ['activity' => 'Penyemaian benih', 'day_offset' => 1, 'time' => '08:00', 'notes' => 'Tanam benih cabe di media semai', 'recommendations' => 'Tanam 1-2 benih per lubang'],
                    ['activity' => 'Perawatan bibit', 'day_offset' => 7, 'time' => '06:00', 'notes' => 'Siram dan jaga kelembapan semaian', 'recommendations' => 'Pindahkan ke tempat teduh jika terlalu panas'],
                ],
            ],
            [
                'name' => 'Persiapan Lahan',
                'duration_days' => 10,
                'items' => [
                    ['activity' => 'Pengolahan tanah', 'day_offset' => 0, 'time' => '07:00', 'notes' => 'Bajak dan gemburkan lahan', 'recommendations' => 'Campurkan pupuk kandang saat pengolahan'],
                    ['activity' => 'Pembuatan bedengan', 'day_offset' => 5, 'time' => '07:00', 'notes' => 'Buat bedengan tinggi 30 cm, lebar 80-100 cm', 'recommendations' => 'Mulsa bedengan dengan plastik hitam perak'],
                ],
            ],
            [
                'name' => 'Penanaman',
                'duration_days' => 5,
                'items' => [
                    ['activity' => 'Penanaman bibit', 'day_offset' => 0, 'time' => '16:00', 'notes' => 'Tanam bibit cabe berumur 30 hari', 'recommendations' => 'Tanam saat sore hari, jarak 50x60 cm'],
                    ['activity' => 'Penyiraman pasca tanam', 'day_offset' => 1, 'time' => '06:00', 'notes' => 'Siram langsung setelah tanam', 'recommendations' => 'Pastikan akar tidak kopong'],
                ],
            ],
            [
                'name' => 'Pemeliharaan',
                'duration_days' => 80,
                'items' => [
                    ['activity' => 'Pemupukan susulan I', 'day_offset' => 14, 'time' => '07:00', 'notes' => 'Berikan pupuk NPK 15-15-15', 'recommendations' => 'Larutkan dalam air dan kocorkan', 'products' => ['NPK 15-15-15']],
                    ['activity' => 'Pemupukan susulan II', 'day_offset' => 35, 'time' => '07:00', 'notes' => 'Berikan pupuk KCl untuk pembentukan buah', 'recommendations' => 'Pupuk kalium penting untuk rasa dan warna', 'products' => ['KCl 60%']],
                    ['activity' => 'Pengendalian hama', 'day_offset' => 25, 'time' => '07:00', 'notes' => 'Kendalikan kutu daun dan ulat', 'recommendations' => 'Gunakan insektisida sesuai anjuran', 'products' => ['Insektisida imidakloprid']],
                ],
            ],
            [
                'name' => 'Panen',
                'duration_days' => 45,
                'items' => [
                    ['activity' => 'Panen raya pertama', 'day_offset' => 0, 'time' => '06:00', 'notes' => 'Petik cabe yang sudah merah tua', 'recommendations' => 'Panen setiap 3-5 hari sekali'],
                    ['activity' => 'Panen susulan', 'day_offset' => 5, 'time' => '06:00', 'notes' => 'Lanjutkan pemetikan secara berkala', 'recommendations' => 'Panas total bisa mencapai 15-20 kg/tanaman'],
                ],
            ],
        ];
    }

    private function getKangkungStages(): array
    {
        return [
            [
                'name' => 'Pengolahan Tanah',
                'duration_days' => 3,
                'items' => [
                    ['activity' => 'Pengolahan tanah', 'day_offset' => 0, 'time' => '07:00', 'notes' => 'Gemburkan tanah sedalam 15 cm', 'recommendations' => 'Kangkung tumbuh baik di tanah lembap'],
                    ['activity' => 'Pemberian pupuk dasar', 'day_offset' => 1, 'time' => '08:00', 'notes' => 'Taburkan pupuk kandang 2-3 kg/m²', 'recommendations' => 'Campurkan rata dengan tanah', 'products' => ['Pupuk kandang']],
                ],
            ],
            [
                'name' => 'Penanaman',
                'duration_days' => 2,
                'items' => [
                    ['activity' => 'Penyebaran benih', 'day_offset' => 0, 'time' => '07:00', 'notes' => 'Tabur benih kangkung merata', 'recommendations' => 'Takaran 5-10 g/m²'],
                ],
            ],
            [
                'name' => 'Pemeliharaan',
                'duration_days' => 25,
                'items' => [
                    ['activity' => 'Penyiraman', 'day_offset' => 0, 'time' => '06:00', 'notes' => 'Siram 2x sehari (pagi dan sore)', 'recommendations' => 'Jaga kelembapan tanah tetap tinggi'],
                    ['activity' => 'Pemupukan susulan', 'day_offset' => 10, 'time' => '07:00', 'notes' => 'Berikan pupuk Urea 5 g/m²', 'recommendations' => 'Larutkan dalam air dan siramkan', 'products' => ['Urea 46%']],
                ],
            ],
            [
                'name' => 'Panen',
                'duration_days' => 5,
                'items' => [
                    ['activity' => 'Pemanenan', 'day_offset' => 0, 'time' => '06:00', 'notes' => 'Potong kangkung 3-5 cm dari permukaan tanah', 'recommendations' => 'Panen saat daun segar dan hijau'],
                    ['activity' => 'Panen susulan', 'day_offset' => 3, 'time' => '06:00', 'notes' => 'Panen lagi setelah daun tumbuh kembali', 'recommendations' => 'Kangkung bisa dipanen 2-3 kali'],
                ],
            ],
        ];
    }
}
