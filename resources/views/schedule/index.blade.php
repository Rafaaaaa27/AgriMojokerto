@extends('layouts.native')

@section('content')
<div class="sc-container">

  <div class="sc-head">
    <a href="{{ route('profile.edit') }}" onclick="event.preventDefault(); history.back();" class="sc-back" aria-label="Kembali">
      <i class="fas fa-chevron-left"></i>
    </a>
    <h1 class="sc-title">Jadwal Pertanian</h1>
    <div class="sc-head-end"></div>
  </div>

  <div class="sc-toolbar">
    <button class="btn btn-secondary btn-sm" onclick="openModal('modalSimpleSchedule')">
      <i class="fas fa-plus"></i> Jadwal Manual
    </button>
    <button class="btn btn-primary btn-sm" onclick="openModal('modalNewCycle')">
      <i class="fas fa-seedling"></i> Siklus Baru
    </button>
  </div>

  @if(session('success'))
    <div class="sc-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="sc-alert error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
  @endif

  @php
    $totalItems = 0; $completedItems = 0;
    foreach ($cycles as $c) {
      foreach ($c->stages as $s) {
        $totalItems += $s->items->count();
        $completedItems += $s->items->where('status', 'completed')->count();
      }
    }
  @endphp

  <div class="sc-stats">
    <div class="sc-stat"><span class="sc-stat-num primary">{{ $cycles->where('status', 'active')->count() }}</span><span class="sc-stat-lbl">Siklus Aktif</span></div>
    <div class="sc-stat"><span class="sc-stat-num warning">{{ $cycles->where('status', 'completed')->count() }}</span><span class="sc-stat-lbl">Selesai</span></div>
    <div class="sc-stat"><span class="sc-stat-num info">{{ $schedules->count() }}</span><span class="sc-stat-lbl">Jadwal Manual</span></div>
    <div class="sc-stat"><span class="sc-stat-num primary-dark">{{ $completedItems }}/{{ $totalItems }}</span><span class="sc-stat-lbl">Kegiatan Selesai</span></div>
  </div>

  @if($cycles->isEmpty() && $schedules->isEmpty())
    <div class="sc-empty">
      <div class="sc-empty-icon"><i class="fas fa-seedling"></i></div>
      <h3>Belum Ada Jadwal</h3>
      <p>Mulai siklus pertanian untuk jadwal otomatis dari tanam hingga panen.</p>
      <button class="btn btn-primary" onclick="openModal('modalNewCycle')">
        <i class="fas fa-seedling"></i> Mulai Siklus Baru
      </button>
    </div>
  @else

    {{-- Active cycles --}}
    @if($cycles->where('status', 'active')->isNotEmpty())
      <section class="sc-section">
        <div class="sc-section-head"><span class="sc-section-bar primary"></span><h3>Siklus Aktif</h3></div>

        @foreach($cycles->where('status', 'active') as $cycle)
          @php
            $totalStages = $cycle->stages->count();
            $completedStages = $cycle->stages->where('status', 'completed')->count();
            $progress = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;
            $cropEmoji = match($cycle->cropTemplate->slug ?? '') {
              'padi' => '🌾', default => '🌱'
            };
          @endphp

          <div class="sc-cycle">
            <div class="sc-cycle-head">
              <div>
                <div class="sc-cycle-title-row">
                  <span class="sc-crop-emoji">{{ $cropEmoji }}</span>
                  <h4 class="sc-cycle-name">{{ $cycle->name }}</h4>
                  <span class="sc-badge active"><i class="fas fa-circle"></i> Aktif</span>
                </div>
                <div class="sc-cycle-meta">
                  <span><i class="fas fa-{{ $cycle->cropTemplate->icon }}"></i> {{ $cycle->cropTemplate->name }}</span>
                  @if($cycle->location) <span><i class="fas fa-map-marker-alt"></i> {{ $cycle->location }}</span> @endif
                  @if($cycle->area_hectares) <span><i class="fas fa-expand-arrows-alt"></i> {{ $cycle->area_hectares }} ha</span> @endif
                  <span><i class="fas fa-calendar"></i> {{ $cycle->start_date->format('d M Y') }} — {{ $cycle->estimated_end_date->format('d M Y') }}</span>
                </div>
              </div>
              <div class="sc-cycle-actions">
                <form action="{{ route('farming-cycles.complete', $cycle) }}" method="POST" onsubmit="return confirm('Tandai siklus ini sebagai selesai?')">
                  @csrf @method('PATCH')
                  <button class="sc-act success" title="Selesaikan siklus"><i class="fas fa-check"></i> Selesai</button>
                </form>
                <form action="{{ route('farming-cycles.destroy', $cycle) }}" method="POST" onsubmit="return confirm('Hapus siklus ini beserta semua jadwalnya?')">
                  @csrf @method('DELETE')
                  <button class="sc-act danger" title="Hapus siklus"><i class="fas fa-trash-alt"></i></button>
                </form>
              </div>
            </div>

            <div class="sc-progress">
              <div class="sc-progress-head">
                <span class="sc-progress-lbl">Progress Siklus</span>
                <span class="sc-progress-val">{{ $progress }}%</span>
              </div>
              <div class="sc-progress-bar"><div class="sc-progress-fill" style="width: {{ $progress }}%"></div></div>
            </div>

            <div class="sc-timeline">
              @foreach($cycle->stages->sortBy('order') as $si => $stage)
                @php $stageProgress = $stage->progress @endphp
                <div class="sc-tl-item">
                  <div class="sc-tl-dot {{ $stage->status }}">
                    @if($stage->status === 'completed') <i class="fas fa-check"></i>
                    @else <span>{{ $stage->order }}</span>
                    @endif
                  </div>

                    <div class="sc-stage {{ $stage->status }}" data-stage-id="{{ $stage->id }}">
                    <div class="sc-stage-head">
                      <h5 class="sc-stage-name {{ $stage->status }}">{{ $stage->name }}</h5>
                      <span class="sc-badge {{ $stage->status }}">
                        @switch($stage->status)
                          @case('completed') <i class="fas fa-check"></i> Selesai @break
                          @case('in_progress') <i class="fas fa-spinner fa-spin"></i> Berlangsung @break
                          @default <i class="fas fa-clock"></i> Menunggu
                        @endswitch
                      </span>
                    </div>

                    <div class="sc-stage-meta">
                      <span><i class="fas fa-calendar-day"></i> {{ $stage->start_date->format('d M') }} — {{ $stage->end_date->format('d M Y') }}</span>
                      <span><i class="fas fa-clock"></i> {{ $stage->duration_days }} hari</span>
                      <span><i class="fas fa-tasks"></i> {{ $stage->items->count() }} kegiatan</span>
                    </div>

                    @if($stageProgress > 0 && $stageProgress < 100)
                      <div class="sc-sub-bar"><div class="sc-progress-fill" style="width: {{ $stageProgress }}%"></div></div>
                    @endif

                    @if($stage->status === 'pending')
                      <form action="{{ route('schedule-stages.update', $stage) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="in_progress">
                        <button class="sc-act warning"><i class="fas fa-play"></i> Mulai Tahapan</button>
                      </form>
                    @elseif($stage->status === 'in_progress')
                      <form action="{{ route('schedule-stages.update', $stage) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button class="sc-act success"><i class="fas fa-check"></i> Tandai Selesai</button>
                      </form>
                    @endif

                    @if($stage->items->isNotEmpty())
                      <div class="sc-items">
                        @foreach($stage->items as $item)
                          <div class="sc-item" draggable="true" data-item-id="{{ $item->id }}">
                            <div class="sc-item-drag"><i class="fas fa-grip-lines"></i></div>
                            <div class="sc-item-check">
                              @if($item->status === 'completed')
                                <form action="{{ route('schedule-items.update', $item) }}" method="POST">
                                  @csrf @method('PATCH')
                                  <input type="hidden" name="status" value="pending">
                                  <button title="Batalkan"><i class="fas fa-check-circle"></i></button>
                                </form>
                              @elseif($item->status === 'skipped')
                                <form action="{{ route('schedule-items.update', $item) }}" method="POST">
                                  @csrf @method('PATCH')
                                  <input type="hidden" name="status" value="pending">
                                  <button title="Batalkan"><i class="fas fa-minus-circle"></i></button>
                                </form>
                              @else
                                <form action="{{ route('schedule-items.update', $item) }}" method="POST">
                                  @csrf @method('PATCH')
                                  <input type="hidden" name="status" value="completed">
                                  <button title="Tandai selesai"><i class="far fa-circle"></i></button>
                                </form>
                              @endif
                            </div>
                            <div class="sc-item-body">
                              <div class="sc-item-act {{ $item->status === 'completed' ? 'done' : '' }}">{{ $item->activity }}</div>
                              <div class="sc-item-meta">
                                <span class="sc-item-date" data-item-id="{{ $item->id }}" data-date="{{ $item->date->format('Y-m-d') }}">
                                  <i class="fas fa-calendar-day"></i>
                                  <span class="sc-item-date-text">{{ $item->date->format('d M Y') }}</span>
                                </span>
                                @if($item->time) <span><i class="fas fa-clock"></i> {{ $item->time }}</span> @endif
                              </div>
                              @if($item->notes) <div class="sc-item-note">{{ $item->notes }}</div> @endif
                              @if($item->recommendations)
                                <div class="sc-item-rec"><i class="fas fa-lightbulb"></i> {{ $item->recommendations }}</div>
                              @endif
                              @if($item->products)
                                <div class="sc-item-tags">
                                  @foreach(json_decode($item->products, true) as $product)
                                    <span class="sc-tag"><i class="fas fa-flask"></i> {{ $product }}</span>
                                  @endforeach
                                </div>
                              @endif
                            </div>
                            <div class="sc-item-acts">
                              @if($item->status === 'pending')
                                <form action="{{ route('schedule-items.update', $item) }}" method="POST">
                                  @csrf @method('PATCH')
                                  <input type="hidden" name="status" value="skipped">
                                  <button title="Lewati"><i class="fas fa-forward"></i></button>
                                </form>
                              @endif
                              <form action="{{ route('schedule-items.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus kegiatan ini?')">
                                @csrf @method('DELETE')
                                <button class="del" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                              </form>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    @endif

                    <button class="sc-add-btn" onclick="toggleAddForm({{ $stage->id }})">
                      <i class="fas fa-plus"></i> Tambah Kegiatan
                    </button>
                    <div class="sc-add-form" id="addForm-{{ $stage->id }}">
                      <form action="{{ route('schedule-items.store', $stage) }}" method="POST">
                        @csrf
                        <input type="text" name="activity" class="sc-input" required placeholder="Nama kegiatan...">
                        <div class="sc-input-row">
                          <input type="date" name="date" class="sc-input" required value="{{ $stage->start_date->format('Y-m-d') }}">
                          <input type="text" name="time" class="sc-input" placeholder="07:00">
                        </div>
                        <textarea name="notes" class="sc-input" rows="2" placeholder="Catatan (opsional)..."></textarea>
                        <div class="sc-input-row">
                          <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Simpan</button>
                          <button type="button" class="btn btn-secondary btn-sm" onclick="toggleAddForm({{ $stage->id }})">Batal</button>
                        </div>
                      </form>
                    </div>

                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endforeach
      </section>
    @endif

    {{-- Completed cycles --}}
    @if($cycles->where('status', 'completed')->isNotEmpty())
      <section class="sc-section">
        <div class="sc-section-head"><span class="sc-section-bar success"></span><h3>Siklus Selesai</h3><span class="sc-count success">{{ $cycles->where('status', 'completed')->count() }}</span></div>
        @foreach($cycles->where('status', 'completed') as $cycle)
          @php
            $cropEmoji = match($cycle->cropTemplate->slug ?? '') {
              'padi' => '🌾', default => '🌱'
            };
          @endphp
          <div class="sc-cycle done">
            <div class="sc-cycle-head">
              <div>
                <div class="sc-cycle-title-row">
                  <span class="sc-crop-emoji">{{ $cropEmoji }}</span>
                  <h4 class="sc-cycle-name muted">{{ $cycle->name }}</h4>
                  <span class="sc-badge completed"><i class="fas fa-check"></i> Selesai</span>
                </div>
                <div class="sc-cycle-meta">
                  <span>{{ $cycle->start_date->format('d M Y') }} — {{ $cycle->actual_end_date?->format('d M Y') ?? $cycle->estimated_end_date->format('d M Y') }}</span>
                  @if($cycle->location) <span><i class="fas fa-map-marker-alt"></i> {{ $cycle->location }}</span> @endif
                </div>
              </div>
              <form action="{{ route('farming-cycles.destroy', $cycle) }}" method="POST" onsubmit="return confirm('Hapus siklus ini dari riwayat?')">
                @csrf @method('DELETE')
                <button class="sc-act ghost"><i class="fas fa-trash-alt"></i></button>
              </form>
            </div>
          </div>
        @endforeach
      </section>
    @endif

    {{-- Manual schedules --}}
    @if($schedules->isNotEmpty())
      <section class="sc-section">
        <div class="sc-section-head"><span class="sc-section-bar warning"></span><h3>Jadwal Manual</h3></div>
        <div class="sc-manual-list">
          @foreach($schedules as $schedule)
            <div class="sc-manual {{ $schedule->status === 'completed' ? 'done' : '' }}">
              <div class="sc-manual-date">
                <span class="sc-manual-month">{{ date('M', strtotime($schedule->date)) }}</span>
                <span class="sc-manual-day">{{ date('d', strtotime($schedule->date)) }}</span>
              </div>
              <div class="sc-manual-body">
                <h4 class="sc-manual-act {{ $schedule->status === 'completed' ? 'done' : '' }}">{{ $schedule->activity }}</h4>
                <p class="sc-manual-note">{{ $schedule->notes ?? 'Tidak ada catatan' }}</p>
              </div>
              <form action="{{ route('schedules.update', $schedule) }}" method="POST">
                @csrf @method('PATCH')
                <button class="sc-manual-toggle">
                  @if($schedule->status === 'completed')
                    <span class="sc-badge completed"><i class="fas fa-check"></i> Selesai</span>
                  @else
                    <span class="sc-badge pending"><i class="far fa-circle"></i> Tandai Selesai</span>
                  @endif
                </button>
              </form>
              <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                @csrf @method('DELETE')
                <button class="sc-manual-del" title="Hapus"><i class="fas fa-trash-alt"></i></button>
              </form>
            </div>
          @endforeach
        </div>
      </section>
    @endif

  @endif
</div>

{{-- Modal: New Cycle --}}
<div id="modalNewCycle" class="sc-modal" onclick="if(event.target===this)closeModal('modalNewCycle')">
  <div class="sc-modal-body">
    <button class="sc-modal-x" onclick="closeModal('modalNewCycle')">&times;</button>
    <div class="sc-modal-heading">
      <h2><i class="fas fa-seedling"></i> Siklus Pertanian Baru</h2>
      <p>Atur siklus tanam padi. Sistem akan membuat jadwal otomatis.</p>
    </div>
    <form action="{{ route('farming-cycles.store') }}" method="POST">
      @csrf
      @php
        $padiTemplate = $templates->firstWhere('slug', 'padi') ?? $templates->first();
      @endphp
      <input type="hidden" name="crop_template_id" value="{{ $padiTemplate?->id ?? '' }}">
      <div class="sc-field">
        <label>Jenis Tanaman</label>
        <div class="sc-tpl-grid" style="grid-template-columns: 1fr;">
          <label class="sc-tpl-card selected" style="cursor: default; display: flex; align-items: center; gap: 0.75rem; text-align: left; padding: 0.9rem 1.25rem;">
            <span class="sc-tpl-emoji" style="margin-bottom: 0; font-size: 1.5rem;">🌾</span>
            <div>
              <span class="sc-tpl-name" style="font-size: 0.85rem;">Padi</span>
              <span class="sc-tpl-dur" style="font-size: 0.7rem; margin-top: 0.05rem;">{{ $padiTemplate->duration_days }} hari — siklus otomatis dari tanam hingga panen</span>
            </div>
          </label>
        </div>
      </div>
      <div class="sc-field">
        <label>Nama Siklus <span>*</span></label>
        <input type="text" name="name" class="sc-field-input" required placeholder="Contoh: Padi Sawah Blok A">
      </div>
      <div class="sc-field-row">
        <div class="sc-field">
          <label>Tanggal Mulai <span>*</span></label>
          <input type="date" name="start_date" class="sc-field-input" required value="{{ date('Y-m-d') }}">
        </div>
        <div class="sc-field">
          <label>Luas Lahan (ha)</label>
          <input type="number" name="area_hectares" class="sc-field-input" step="0.01" min="0" placeholder="0.5">
        </div>
      </div>
      <div class="sc-field">
        <label>Lokasi Lahan</label>
        <input type="text" name="location" class="sc-field-input" placeholder="Contoh: Sawah Blok A, Dusun Krajan">
      </div>
      <div class="sc-field">
        <label>Catatan</label>
        <textarea name="notes" class="sc-field-input" rows="2" placeholder="Informasi tambahan..."></textarea>
      </div>
      <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-seedling"></i> Buat Siklus Pertanian</button>
    </form>
  </div>
</div>

{{-- Modal: Manual Schedule --}}
<div id="modalSimpleSchedule" class="sc-modal @if($errors->any() && old('_form') === 'simple_schedule') show @endif" onclick="if(event.target===this)closeModal('modalSimpleSchedule')">
  <div class="sc-modal-body compact">
    <button class="sc-modal-x" onclick="closeModal('modalSimpleSchedule')">&times;</button>
    <div class="sc-modal-heading">
      <h2><i class="fas fa-calendar-plus"></i> Jadwal Manual</h2>
      <p>Tambahkan kegiatan pertanian di luar siklus.</p>
    </div>
    <form action="{{ route('schedules.store') }}" method="POST" id="formSimpleSchedule">
      @csrf
      <input type="hidden" name="_form" value="simple_schedule">
      <div class="sc-field">
        <label for="activity">Nama Kegiatan <span>*</span></label>
        <input type="text" name="activity" id="activity" class="sc-field-input @error('activity', 'simple_schedule') is-invalid @enderror" required maxlength="255" placeholder="Contoh: Pemupukan Padi Tahap 1" value="{{ old('activity') }}">
        @error('activity', 'simple_schedule') <div class="sc-err"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div> @enderror
      </div>
      <div class="sc-field">
        <label for="date">Tanggal Pelaksanaan <span>*</span></label>
        <input type="date" name="date" id="date" class="sc-field-input @error('date', 'simple_schedule') is-invalid @enderror" required value="{{ old('date', date('Y-m-d')) }}">
        @error('date', 'simple_schedule') <div class="sc-err"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div> @enderror
      </div>
      <div class="sc-field">
        <label for="notes">Catatan</label>
        <textarea name="notes" id="notes" class="sc-field-input @error('notes', 'simple_schedule') is-invalid @enderror" rows="3" maxlength="1000" placeholder="Detail atau takaran...">{{ old('notes') }}</textarea>
        @error('notes', 'simple_schedule') <div class="sc-err"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div> @enderror
      </div>
      <button type="submit" class="btn btn-primary btn-block" id="btnSimpleScheduleSubmit"><i class="fas fa-save"></i> Simpan Jadwal</button>
    </form>
  </div>
</div>

@push('scripts')
<script>
const CSRF = '{{ csrf_token() }}';

// ========== MODAL HELPERS ==========
function openModal(id) {
  const m = document.getElementById(id);
  if (!m) return;
  m.classList.add('show');
  document.body.style.overflow = 'hidden';
  const f = m.querySelector('input:not([type=hidden]), select, textarea');
  if (f) setTimeout(() => f.focus(), 50);
}
function closeModal(id) {
  const m = document.getElementById(id);
  if (!m) return;
  m.classList.remove('show');
  document.body.style.overflow = '';
}
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') document.querySelectorAll('.sc-modal.show').forEach(m => { m.classList.remove('show'); document.body.style.overflow = ''; });
});

(function() {
  const form = document.getElementById('formSimpleSchedule');
  if (form) {
    form.addEventListener('submit', function() {
      const btn = document.getElementById('btnSimpleScheduleSubmit');
      if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...'; }
    });
  }
})();

document.addEventListener('DOMContentLoaded', function() {
  const openOnLoad = document.querySelector('.sc-modal.show');
  if (openOnLoad) {
    document.body.style.overflow = 'hidden';
    const fi = openOnLoad.querySelector('.is-invalid') || openOnLoad.querySelector('input:not([type=hidden]), select, textarea');
    if (fi) setTimeout(() => fi.focus(), 50);
  }

  initDragDrop();
  initDateEdit();
});

function toggleAddForm(id) {
  document.getElementById('addForm-' + id)?.classList.toggle('show');
}

// ========== DRAG & DROP ==========
let draggedItem = null;

function initDragDrop() {
  document.querySelectorAll('.sc-item[draggable]').forEach(el => {
    el.addEventListener('dragstart', onDragStart);
    el.addEventListener('dragend', onDragEnd);
  });

  document.querySelectorAll('.sc-stage[data-stage-id]').forEach(el => {
    el.addEventListener('dragover', onDragOver);
    el.addEventListener('dragleave', onDragLeave);
    el.addEventListener('drop', onDrop);
  });
}

function onDragStart(e) {
  draggedItem = this;
  this.classList.add('dragging');
  e.dataTransfer.effectAllowed = 'move';
  e.dataTransfer.setData('text/plain', this.dataset.itemId);

  const ghost = document.createElement('div');
  ghost.className = 'sc-drag-ghost';
  ghost.textContent = this.querySelector('.sc-item-act')?.textContent?.trim() || 'Kegiatan';
  document.body.appendChild(ghost);
  e.dataTransfer.setDragImage(ghost, 10, 10);
  setTimeout(() => ghost.remove(), 0);
}

function onDragEnd() {
  this.classList.remove('dragging');
  document.querySelectorAll('.sc-stage.drag-over').forEach(el => el.classList.remove('drag-over'));
  draggedItem = null;
}

function onDragOver(e) {
  e.preventDefault();
  e.dataTransfer.dropEffect = 'move';
  const stage = this.closest('.sc-stage[data-stage-id]');
  if (stage && !stage.classList.contains('drag-over')) {
    document.querySelectorAll('.sc-stage.drag-over').forEach(el => el !== stage && el.classList.remove('drag-over'));
    stage.classList.add('drag-over');
  }
}

function onDragLeave(e) {
  const stage = this.closest('.sc-stage[data-stage-id]');
  if (stage && !stage.contains(e.relatedTarget)) {
    stage.classList.remove('drag-over');
  }
}

function onDrop(e) {
  e.preventDefault();
  this.classList.remove('drag-over');

  const targetStage = this.closest('.sc-stage[data-stage-id]');
  if (!targetStage || !draggedItem) return;

  const targetStageId = targetStage.dataset.stageId;
  const itemId = draggedItem.dataset.itemId;

  if (!itemId || !targetStageId) return;

  const itemsContainer = targetStage.querySelector('.sc-items');
  if (!itemsContainer) return;

  // Optimistic move: append item to target stage
  draggedItem.classList.remove('dragging');
  itemsContainer.appendChild(draggedItem);

  // Re-init drag events on moved item
  draggedItem.removeEventListener('dragstart', onDragStart);
  draggedItem.removeEventListener('dragend', onDragEnd);
  draggedItem.addEventListener('dragstart', onDragStart);
  draggedItem.addEventListener('dragend', onDragEnd);

  // Re-init date edit on moved item
  const dateEl = draggedItem.querySelector('.sc-item-date');
  if (dateEl) {
    dateEl.replaceWith?.(dateEl.cloneNode(true));
  }

  // AJAX save
  fetch(`/schedule-items/${itemId}/stage`, {
    method: 'PATCH',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    body: JSON.stringify({ schedule_stage_id: targetStageId })
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      showToast('Berhasil!', 'Kegiatan dipindahkan', 'success');
    }
  })
  .catch(() => {
    showToast('Gagal', 'Gagal memindahkan kegiatan', 'danger');
    location.reload();
  });

  draggedItem = null;
}

// ========== INLINE DATE EDIT ==========
function initDateEdit() {
  document.querySelectorAll('.sc-item-date').forEach(el => {
    el.addEventListener('click', function(e) {
      if (e.target.closest('.sc-item-date-input')) return;
      const current = this.dataset.date;
      const itemId = this.dataset.itemId;
      if (!itemId) return;

      const textSpan = this.querySelector('.sc-item-date-text');
      if (!textSpan) return;

      const input = document.createElement('input');
      input.type = 'date';
      input.className = 'sc-item-date-input';
      input.value = current;

      textSpan.replaceWith(input);
      input.focus();
      input.select();

      const save = () => {
        const newDate = input.value;
        if (newDate && newDate !== current) {
          this.classList.add('sc-item-date-saving');
          fetch(`/schedule-items/${itemId}/date`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ date: newDate })
          })
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              const newSpan = document.createElement('span');
              newSpan.className = 'sc-item-date-text';
              const d = new Date(newDate + 'T00:00:00');
              const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
              newSpan.textContent = `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
              input.replaceWith(newSpan);
              this.dataset.date = newDate;
              this.classList.remove('sc-item-date-saving');
              showToast('Berhasil!', 'Tanggal diperbarui', 'success');
            }
          })
          .catch(() => {
            showToast('Gagal', 'Gagal memperbarui tanggal', 'danger');
            location.reload();
          });
        } else {
          const newSpan = document.createElement('span');
          newSpan.className = 'sc-item-date-text';
          newSpan.textContent = textSpan.textContent;
          input.replaceWith(newSpan);
        }
      };

      input.addEventListener('blur', save);
      input.addEventListener('keydown', function(ev) {
        if (ev.key === 'Enter') { ev.preventDefault(); save(); }
        if (ev.key === 'Escape') {
          const newSpan = document.createElement('span');
          newSpan.className = 'sc-item-date-text';
          newSpan.textContent = textSpan.textContent;
          input.replaceWith(newSpan);
        }
      });
    });
  });
}
</script>
@endpush
@endsection
