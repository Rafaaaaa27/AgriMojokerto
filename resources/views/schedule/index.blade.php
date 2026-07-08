@extends('layouts.native')

@section('content')
<div class="container section" style="padding-top: 8rem;">
    {{-- Back link --}}
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('profile.edit') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.4rem;">
            <i class="fas fa-arrow-left"></i> Kembali ke Profil
        </a>
    </div>

    {{-- Header card --}}
    <div class="glass-card" style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--primary-dark); display: flex; align-items: center; gap: 0.6rem;">
                    <i class="fas fa-calendar-alt"></i> Jadwal Pertanian
                </h2>
                <p style="color: var(--text-muted); margin-top: 0.25rem;">Kelola siklus pertanian dari penanaman hingga panen secara terstruktur.</p>
            </div>
            <div style="display: flex; gap: 0.6rem; flex-wrap: wrap;">
                <button class="btn btn-secondary btn-sm" onclick="openModal('modalSimpleSchedule')">
                    <i class="fas fa-plus"></i> Jadwal Manual
                </button>
                <button class="btn btn-primary btn-sm" onclick="openModal('modalNewCycle')">
                    <i class="fas fa-seedling"></i> Siklus Baru
                </button>
            </div>
        </div>

        {{-- Flash message --}}
        @if(session('success'))
        <div style="background: rgba(16,185,129,0.08); color: var(--success); padding: 0.85rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.6rem; font-weight: 600; font-size: 0.9rem; border: 1px solid rgba(16,185,129,0.15);">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div style="background: rgba(239,68,68,0.08); color: var(--danger); padding: 0.85rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.6rem; font-weight: 600; font-size: 0.9rem; border: 1px solid rgba(239,68,68,0.15);">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        {{-- Stats --}}
        @php
            $totalItems = 0;
            $completedItems = 0;
            foreach($cycles as $c) {
                foreach($c->stages as $s) {
                    $totalItems += $s->items->count();
                    $completedItems += $s->items->where('status', 'completed')->count();
                }
            }
        @endphp
        <div class="schedule-stats-grid">
            <div class="schedule-stat-card">
                <div class="schedule-stat-value" style="color: var(--primary);">{{ $cycles->where('status', 'active')->count() }}</div>
                <div class="schedule-stat-label">Siklus Aktif</div>
            </div>
            <div class="schedule-stat-card">
                <div class="schedule-stat-value" style="color: var(--warning);">{{ $cycles->where('status', 'completed')->count() }}</div>
                <div class="schedule-stat-label">Selesai</div>
            </div>
            <div class="schedule-stat-card">
                <div class="schedule-stat-value" style="color: var(--info);">{{ $schedules->count() }}</div>
                <div class="schedule-stat-label">Jadwal Manual</div>
            </div>
            <div class="schedule-stat-card">
                <div class="schedule-stat-value" style="color: var(--primary-dark);">{{ $completedItems }}/{{ $totalItems }}</div>
                <div class="schedule-stat-label">Kegiatan Selesai</div>
            </div>
        </div>
    </div>

    {{-- ============ EMPTY STATE ============ --}}
    @if($cycles->isEmpty() && $schedules->isEmpty())
    <div class="glass-card schedule-empty">
        <div class="schedule-empty-icon">
            <i class="fas fa-seedling"></i>
        </div>
        <h3>Belum Ada Jadwal Pertanian</h3>
        <p>Mulai buat siklus pertanian untuk mendapatkan jadwal otomatis dari penanaman hingga panen. Pilih jenis tanaman dan tentukan tanggal mulai.</p>
        <button class="btn btn-primary" onclick="openModal('modalNewCycle')">
            <i class="fas fa-seedling"></i> Mulai Siklus Baru
        </button>
    </div>

    @else

        {{-- ============ ACTIVE CYCLES ============ --}}
        @if($cycles->where('status', 'active')->isNotEmpty())
        <div style="margin-bottom: 2.5rem;">
            <div class="schedule-section-title primary">
                <h3>Siklus Aktif</h3>
            </div>

            @foreach($cycles->where('status', 'active') as $cycle)
            @php
                $totalStages = $cycle->stages->count();
                $completedStages = $cycle->stages->where('status', 'completed')->count();
                $progress = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;
                $cropEmoji = match($cycle->cropTemplate->slug) {
                    'padi' => '🌾', 'jagung' => '🌽', 'kedelai' => '🫘',
                    'cabe' => '🌶️', 'kangkung' => '🥬', default => '🌱'
                };
            @endphp
            <div class="glass-card" style="margin-bottom: 1rem; border-left: 4px solid var(--primary);">

                {{-- Cycle header --}}
                <div class="schedule-cycle-header">
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.35rem; flex-wrap: wrap;">
                            <span style="font-size: 1.4rem; line-height: 1;">{{ $cropEmoji }}</span>
                            <h4 style="font-size: 1.1rem; font-weight: 800;">{{ $cycle->name }}</h4>
                            <span class="schedule-badge completed" style="background: rgba(16,185,129,0.1); color: var(--success);">
                                <i class="fas fa-circle" style="font-size: 0.35rem;"></i> Aktif
                            </span>
                        </div>
                        <div class="schedule-cycle-info">
                            <span><i class="fas fa-{{ $cycle->cropTemplate->icon }}"></i> {{ $cycle->cropTemplate->name }}</span>
                            @if($cycle->location)
                            <span><i class="fas fa-map-marker-alt"></i> {{ $cycle->location }}</span>
                            @endif
                            @if($cycle->area_hectares)
                            <span><i class="fas fa-expand-arrows-alt"></i> {{ $cycle->area_hectares }} ha</span>
                            @endif
                            <span><i class="fas fa-calendar"></i> {{ $cycle->start_date->format('d M Y') }} — {{ $cycle->estimated_end_date->format('d M Y') }}</span>
                        </div>
                    </div>
                    <div style="display: flex; gap: 0.4rem; flex-shrink: 0;">
                        <form action="{{ route('farming-cycles.complete', $cycle) }}" method="POST" onsubmit="return confirm('Tandai siklus ini sebagai selesai? Semua tahapan yang belum selesai akan ditandai selesai.')">
                            @csrf @method('PATCH')
                            <button type="submit" class="schedule-action-btn success">
                                <i class="fas fa-check"></i> Selesai
                            </button>
                        </form>
                        <form action="{{ route('farming-cycles.destroy', $cycle) }}" method="POST" onsubmit="return confirm('Hapus siklus ini beserta semua jadwalnya? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="schedule-action-btn danger">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Progress bar --}}
                <div class="schedule-progress-wrap">
                    <div class="schedule-progress-header">
                        <span class="schedule-progress-label">Progress Siklus</span>
                        <span class="schedule-progress-value">{{ $progress }}%</span>
                    </div>
                    <div class="schedule-progress-bar">
                        <div class="schedule-progress-fill" style="width: {{ $progress }}%;"></div>
                    </div>
                </div>

                {{-- Timeline stages --}}
                <div class="schedule-timeline">
                    <div class="schedule-timeline-line"></div>

                    @foreach($cycle->stages->sortBy('order') as $stageIndex => $stage)
                    @php
                        $stageProgress = $stage->progress;
                        $isLast = $stageIndex === $cycle->stages->count() - 1;
                    @endphp
                    <div class="schedule-timeline-item">

                        {{-- Dot --}}
                        <div class="schedule-timeline-dot {{ $stage->status }}">
                            @if($stage->status === 'completed')
                                <i class="fas fa-check"></i>
                            @else
                                <span>{{ $stage->order }}</span>
                            @endif
                        </div>

                        {{-- Stage card --}}
                        <div class="schedule-stage-card {{ $stage->status }}">

                            {{-- Stage header --}}
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem; gap: 0.5rem; flex-wrap: wrap;">
                                <h5 style="font-size: 0.95rem; font-weight: 800; @if($stage->status === 'completed') color: var(--success); @endif">
                                    {{ $stage->name }}
                                </h5>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span class="schedule-badge {{ $stage->status }}">
                                        @if($stage->status === 'completed')
                                            <i class="fas fa-check"></i> Selesai
                                        @elseif($stage->status === 'in_progress')
                                            <i class="fas fa-spinner fa-spin"></i> Berlangsung
                                        @else
                                            <i class="fas fa-clock"></i> Menunggu
                                        @endif
                                    </span>
                                    @if($stageProgress > 0 && $stageProgress < 100)
                                    <span style="font-size: 0.7rem; color: var(--text-muted); font-weight: 700;">{{ $stageProgress }}%</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Stage meta --}}
                            <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.6rem; display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                                <span><i class="fas fa-calendar-day"></i> {{ $stage->start_date->format('d M') }} — {{ $stage->end_date->format('d M Y') }}</span>
                                <span><i class="fas fa-clock"></i> {{ $stage->duration_days }} hari</span>
                                <span><i class="fas fa-tasks"></i> {{ $stage->items->count() }} kegiatan</span>
                            </div>

                            {{-- Stage progress bar (partial) --}}
                            @if($stageProgress > 0 && $stageProgress < 100)
                            <div class="schedule-stage-progress-bar">
                                <div class="schedule-progress-fill" style="width: {{ $stageProgress }}%;"></div>
                            </div>
                            @endif

                            {{-- Stage action buttons --}}
                            @if($stage->status === 'pending')
                            <form action="{{ route('schedule-stages.update', $stage) }}" method="POST" style="display: inline;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="in_progress">
                                <button type="submit" class="schedule-action-btn warning" style="margin-top: 0.25rem;">
                                    <i class="fas fa-play"></i> Mulai Tahapan
                                </button>
                            </form>
                            @elseif($stage->status === 'in_progress')
                            <form action="{{ route('schedule-stages.update', $stage) }}" method="POST" style="display: inline;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="schedule-action-btn success" style="margin-top: 0.25rem;">
                                    <i class="fas fa-check"></i> Tandai Selesai
                                </button>
                            </form>
                            @endif

                            {{-- Items list --}}
                            @if($stage->items->isNotEmpty())
                            <div style="margin-top: 0.85rem; border-top: 1px solid var(--border-color); padding-top: 0.5rem;">
                                @foreach($stage->items as $item)
                                <div class="schedule-item">

                                    {{-- Checkbox --}}
                                    <div class="schedule-item-check">
                                        @if($item->status === 'completed')
                                        <form action="{{ route('schedule-items.update', $item) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="pending">
                                            <button type="submit" title="Batalkan" style="color: var(--success);">
                                                <i class="fas fa-check-circle" style="font-size: 1.15rem;"></i>
                                            </button>
                                        </form>
                                        @elseif($item->status === 'skipped')
                                        <form action="{{ route('schedule-items.update', $item) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="pending">
                                            <button type="submit" title="Batalkan lewati" style="color: var(--text-muted);">
                                                <i class="fas fa-minus-circle" style="font-size: 1.15rem;"></i>
                                            </button>
                                        </form>
                                        @else
                                        <form action="{{ route('schedule-items.update', $item) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" title="Tandai selesai" style="color: var(--text-muted);">
                                                <i class="far fa-circle" style="font-size: 1.15rem;"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>

                                    {{-- Content --}}
                                    <div class="schedule-item-content">
                                        <div class="schedule-item-activity @if($item->status === 'completed') completed @endif">
                                            {{ $item->activity }}
                                        </div>
                                        <div class="schedule-item-meta">
                                            <span><i class="fas fa-calendar-day"></i> {{ $item->date->format('d M Y') }}</span>
                                            @if($item->time)
                                            <span><i class="fas fa-clock"></i> {{ $item->time }}</span>
                                            @endif
                                        </div>
                                        @if($item->notes)
                                        <div class="schedule-item-notes">{{ $item->notes }}</div>
                                        @endif
                                        @if($item->recommendations)
                                        <div class="schedule-item-recommendation">
                                            <i class="fas fa-lightbulb"></i>
                                            <span>{{ $item->recommendations }}</span>
                                        </div>
                                        @endif
                                        @if($item->products)
                                        <div class="schedule-item-products">
                                            @foreach(json_decode($item->products, true) as $product)
                                            <span class="schedule-product-tag">
                                                <i class="fas fa-flask"></i> {{ $product }}
                                            </span>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>

                                    {{-- Actions --}}
                                    <div class="schedule-item-actions">
                                        @if($item->status === 'pending')
                                        <form action="{{ route('schedule-items.update', $item) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="skipped">
                                            <button type="submit" title="Lewati kegiatan ini">
                                                <i class="fas fa-forward"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <form action="{{ route('schedule-items.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus kegiatan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Hapus" class="delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach

                                {{-- Add item --}}
                                <button class="schedule-add-btn" onclick="toggleAddForm({{ $stage->id }})">
                                    <i class="fas fa-plus"></i> Tambah Kegiatan
                                </button>

                                <div class="schedule-add-form" id="addForm-{{ $stage->id }}">
                                    <form action="{{ route('schedule-items.store', $stage) }}" method="POST">
                                        @csrf
                                        <input type="text" name="activity" class="schedule-form-input" required placeholder="Nama kegiatan baru...">
                                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                                            <input type="date" name="date" class="schedule-form-input" required value="{{ $stage->start_date->format('Y-m-d') }}">
                                            <input type="text" name="time" class="schedule-form-input" placeholder="Contoh: 07:00">
                                        </div>
                                        <textarea name="notes" class="schedule-form-input" rows="2" placeholder="Catatan (opsional)..."></textarea>
                                        <div style="display: flex; gap: 0.5rem;">
                                            <button type="submit" class="btn btn-primary btn-sm" style="flex: 1; padding: 0.5rem; font-size: 0.78rem;">
                                                <i class="fas fa-save"></i> Simpan
                                            </button>
                                            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleAddForm({{ $stage->id }})" style="padding: 0.5rem 1rem; font-size: 0.78rem;">
                                                Batal
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
            @endforeach
        </div>
        @endif

        {{-- ============ COMPLETED CYCLES ============ --}}
        @if($cycles->where('status', 'completed')->isNotEmpty())
        <div style="margin-bottom: 2.5rem;">
            <div class="schedule-section-title success">
                <h3>Siklus Selesai</h3>
                <span class="schedule-section-badge success">{{ $cycles->where('status', 'completed')->count() }}</span>
            </div>

            @foreach($cycles->where('status', 'completed') as $cycle)
            @php
                $cropEmoji = match($cycle->cropTemplate->slug) {
                    'padi' => '🌾', 'jagung' => '🌽', 'kedelai' => '🫘',
                    'cabe' => '🌶️', 'kangkung' => '🥬', default => '🌱'
                };
            @endphp
            <div class="glass-card" style="margin-bottom: 0.75rem; border-left: 4px solid var(--success); opacity: 0.85;">
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.75rem;">
                    <div style="min-width: 0;">
                        <div style="display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;">
                            <span style="font-size: 1.2rem; line-height: 1;">{{ $cropEmoji }}</span>
                            <h4 style="font-size: 0.95rem; font-weight: 800; color: var(--text-muted);">{{ $cycle->name }}</h4>
                            <span class="schedule-badge completed" style="font-size: 0.6rem;">
                                <i class="fas fa-check"></i> Selesai
                            </span>
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                            <span>{{ $cycle->start_date->format('d M Y') }} — {{ $cycle->actual_end_date?->format('d M Y') ?? $cycle->estimated_end_date->format('d M Y') }}</span>
                            @if($cycle->location)
                            <span><i class="fas fa-map-marker-alt"></i> {{ $cycle->location }}</span>
                            @endif
                        </div>
                    </div>
                    <form action="{{ route('farming-cycles.destroy', $cycle) }}" method="POST" onsubmit="return confirm('Hapus siklus ini dari riwayat?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="schedule-action-btn ghost">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- ============ SIMPLE SCHEDULES ============ --}}
        @if($schedules->isNotEmpty())
        <div>
            <div class="schedule-section-title warning">
                <h3>Jadwal Manual</h3>
            </div>

            <div style="display: grid; gap: 0.6rem;">
                @foreach($schedules as $schedule)
                <div class="glass-card @if($schedule->status === 'completed') opacity-70 @endif" style="padding: 1rem 1.25rem; display: grid; grid-template-columns: auto 1fr auto auto; align-items: center; gap: 1.25rem;">
                    <div style="text-align: center; min-width: 64px; padding: 0.4rem; background: var(--background); border-radius: var(--radius-sm); border: 1px solid var(--border-light);">
                        <div style="font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.3px;">{{ date('M', strtotime($schedule->date)) }}</div>
                        <div style="font-size: 1.25rem; font-weight: 900; color: var(--primary-dark); line-height: 1.2;">{{ date('d', strtotime($schedule->date)) }}</div>
                    </div>

                    <div style="min-width: 0;">
                        <h4 style="font-size: 0.9rem; font-weight: 700; @if($schedule->status === 'completed') text-decoration: line-through; color: var(--text-muted); @endif">
                            {{ $schedule->activity }}
                        </h4>
                        <p style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.1rem;">{{ $schedule->notes ?? 'Tidak ada catatan' }}</p>
                    </div>

                    <div>
                        <form action="{{ route('schedules.update', $schedule) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0.3rem 0.6rem; border-radius: 99px; transition: all 0.2s;">
                                @if($schedule->status === 'completed')
                                <span class="schedule-badge completed">
                                    <i class="fas fa-check"></i> Selesai
                                </span>
                                @else
                                <span class="schedule-badge pending" style="border: 1px solid var(--border-color);">
                                    <i class="far fa-circle"></i> Tandai Selesai
                                </span>
                                @endif
                            </button>
                        </form>
                    </div>

                    <div style="padding-left: 0.6rem; border-left: 1px solid var(--border-color);">
                        <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background: none; border: none; cursor: pointer; color: var(--text-muted); padding: 0.3rem; transition: color 0.2s;" onmouseover="this.style.color='var(--danger)'" onmouseout="this.style.color='var(--text-muted)'">
                                <i class="fas fa-trash-alt" style="font-size: 0.85rem;"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    @endif
</div>

{{-- ============ MODAL: NEW FARMING CYCLE ============ --}}
<div id="modalNewCycle" class="schedule-modal-overlay" onclick="if(event.target===this)closeModal('modalNewCycle')">
    <div class="schedule-modal-content">
        <button class="schedule-modal-close" onclick="closeModal('modalNewCycle')">&times;</button>

        <div style="margin-bottom: 2rem;">
            <h2 style="font-size: 1.4rem; font-weight: 800; display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.3rem;">
                <i class="fas fa-seedling" style="color: var(--primary);"></i> Siklus Pertanian Baru
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem; line-height: 1.5;">Pilih jenis tanaman dan tentukan tanggal mulai. Sistem akan otomatis membuat jadwal tahapan lengkap hingga panen.</p>
        </div>

        <form action="{{ route('farming-cycles.store') }}" method="POST">
            @csrf

            {{-- Crop type selector --}}
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.6rem; font-weight: 700; font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                    Jenis Tanaman <span style="color: var(--danger);">*</span>
                </label>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: 0.5rem;">
                    @foreach($templates as $template)
                    @php
                        $emoji = match($template->slug) {
                            'padi' => '🌾', 'jagung' => '🌽', 'kedelai' => '🫘',
                            'cabe' => '🌶️', 'kangkung' => '🥬', default => '🌱'
                        };
                    @endphp
                    <label class="schedule-template-card @if($loop->first) selected @endif" for="tpl-{{ $template->id }}">
                        <input type="radio" name="crop_template_id" value="{{ $template->id }}" id="tpl-{{ $template->id }}" {{ $loop->first ? 'checked' : '' }} style="display: none;" onchange="selectTemplate(this)">
                        <span class="schedule-template-emoji">{{ $emoji }}</span>
                        <div class="schedule-template-name">{{ $template->name }}</div>
                        <div class="schedule-template-duration">{{ $template->duration_days }} hari</div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Cycle name --}}
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                    Nama Siklus <span style="color: var(--danger);">*</span>
                </label>
                <input type="text" name="name" class="form-control" required placeholder="Contoh: Padi Sawah Blok A - Musim Hujan 2026">
            </div>

            {{-- Date & area --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                        Tanggal Mulai <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="date" name="start_date" class="form-control" required value="{{ date('Y-m-d') }}">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                        Luas Lahan (ha)
                    </label>
                    <input type="number" name="area_hectares" class="form-control" step="0.01" min="0" placeholder="0.5">
                </div>
            </div>

            {{-- Location --}}
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                    Lokasi Lahan
                </label>
                <input type="text" name="location" class="form-control" placeholder="Contoh: Sawah Blok A, Dusun Krajan">
            </div>

            {{-- Notes --}}
            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                    Catatan (Opsional)
                </label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Tambahan informasi tentang siklus ini..." style="resize: none;"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.9rem; font-size: 0.95rem;">
                <i class="fas fa-seedling"></i> Buat Siklus Pertanian
            </button>
        </form>
    </div>
</div>

{{-- ============ MODAL: SIMPLE SCHEDULE ============ --}}
<div id="modalSimpleSchedule" class="schedule-modal-overlay" onclick="if(event.target===this)closeModal('modalSimpleSchedule')">
    <div class="schedule-modal-content" style="max-width: 480px;">
        <button class="schedule-modal-close" onclick="closeModal('modalSimpleSchedule')">&times;</button>

        <div style="margin-bottom: 2rem;">
            <h2 style="font-size: 1.4rem; font-weight: 800; display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.3rem;">
                <i class="fas fa-calendar-plus" style="color: var(--primary);"></i> Jadwal Manual
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Tambahkan kegiatan pertanian secara manual di luar siklus.</p>
        </div>

        <form action="{{ route('schedules.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                    Nama Kegiatan <span style="color: var(--danger);">*</span>
                </label>
                <input type="text" name="activity" class="form-control" required placeholder="Contoh: Pemupukan Padi Tahap 1">
            </div>
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                    Tanggal Pelaksanaan <span style="color: var(--danger);">*</span>
                </label>
                <input type="date" name="date" class="form-control" required>
            </div>
            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                    Catatan
                </label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Sebutkan detail atau takaran..." style="resize: none;"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.9rem; font-size: 0.95rem;">
                <i class="fas fa-save"></i> Simpan Jadwal
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

function toggleAddForm(stageId) {
    const form = document.getElementById('addForm-' + stageId);
    if (form) form.classList.toggle('show');
}

function selectTemplate(radio) {
    document.querySelectorAll('.schedule-template-card').forEach(card => card.classList.remove('selected'));
    radio.closest('.schedule-template-card').classList.add('selected');
}

// Close modal on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.schedule-modal-overlay.show').forEach(modal => {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        });
    }
});
</script>
@endpush
@endsection
