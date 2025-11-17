@extends('layouts.admin')

@section('title', 'Kalender Jadwal')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin-jadwal.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
<div class="calendar-page-container container py-4">

    <div class="row mb-3 align-items-center">
        <div class="col">
            <h4 class="fw-semibold mb-0">Kalender Akademik</h4>
        </div>
        <div class="col-auto">
            <button id="addScheduleBtn" class="btn btn-primary btn-sm">Tambah Jadwal</button>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="calendar-container position-relative">
                <div class="d-flex align-items-center mb-2">
                    <button id="prev" class="btn btn-light btn-sm me-2"><i class="bi bi-chevron-left"></i></button>
                    <h3 id="monthYear" class="month-year mb-0 mx-2"></h3>
                    <button id="next" class="btn btn-light btn-sm ms-2"><i class="bi bi-chevron-right"></i></button>
                </div>

                <div id="calendar" class="mini-calendar mt-3" aria-hidden="false"></div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="agenda-section">
                <h5 class="mb-3"><i class="bi bi-calendar-check"></i> Jadwal</h5>
                <ul id="agendaList" class="agenda-list">
                    <li class="agenda-empty">Pilih tanggal untuk melihat jadwal.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL TAMBAH ================= --}}
<div class="modal fade" id="addEventModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Tambah Jadwal</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form id="eventForm">
          @csrf

          <div class="mb-3">
            <label class="form-label">Nama Guru</label>
            <select id="guruSelect" name="guru" class="form-select" required>
                <option value="">-- Pilih Guru --</option>
                @foreach($guru as $g)
                <option value="{{ $g->user->name }}" data-mapel="{{ $g->mata_pelajaran }}">
                    {{ $g->user->name }}
                </option>
                @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Mata Pelajaran</label>
            <input id="mapelInput" type="text" name="mata_pelajaran" class="form-control" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label">Kelas</label>
            <select name="kelas_id" class="form-select" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelas as $k)
                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Jam Mulai</label>
              <input type="time" name="jam_mulai" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Jam Selesai</label>
              <input type="time" name="jam_selesai" class="form-control" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Ulang</label>
            <select name="ulang" class="form-select">
                <option value="sekali">Sekali</option>
                <option value="semester">Semester</option>
            </select>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div class="modal fade" id="editEventModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title">Edit Jadwal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form id="editForm">
          @csrf
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" id="edit_id">
          <input type="hidden" id="edit_group_id"> {{-- ★ NEW --}}

          <div class="mb-3">
            <label class="form-label">Nama Guru</label>
            <select id="edit_guru" name="guru" class="form-select" required>
                @foreach($guru as $g)
                <option value="{{ $g->user->name }}" data-mapel="{{ $g->mata_pelajaran }}">
                    {{ $g->user->name }}
                </option>
                @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Mata Pelajaran</label>
            <input type="text" id="edit_mata_pelajaran" name="mata_pelajaran" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Kelas</label>
            <select id="edit_kelas" name="kelas_id" class="form-select" required>
                @foreach($kelas as $k)
                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Jam Mulai</label>
              <input type="time" id="edit_jam_mulai" name="jam_mulai" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Jam Selesai</label>
              <input type="time" id="edit_jam_selesai" name="jam_selesai" class="form-control">
            </div>
          </div>

          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-danger" id="deleteOneBtn">Hapus Hari Ini</button>
            <button type="button" class="btn btn-outline-danger" id="deleteSemesterBtn">Hapus Semester</button>
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('extra-js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {

    /* -------------------------
       ELEMENT SELECTORS SAFE
    ------------------------- */
    const calendar = document.getElementById("calendar");
    const monthYear = document.getElementById("monthYear");
    const agendaList = document.getElementById("agendaList");

    const guruSelect = document.getElementById("guruSelect");
    const mapelInput = document.getElementById("mapelInput");

    const editGuru = document.getElementById("edit_guru");
    const editMapel = document.getElementById("edit_mata_pelajaran");
    const editGroupId = document.getElementById("edit_group_id");

    const eventForm = document.getElementById("eventForm");
    const editForm = document.getElementById("editForm");

    const addScheduleBtn = document.getElementById("addScheduleBtn");
    const deleteOneBtn = document.getElementById("deleteOneBtn");
    const deleteSemesterBtn = document.getElementById("deleteSemesterBtn");

    const addModalEl = document.getElementById("addEventModal");
    const editModalEl = document.getElementById("editEventModal");

    const addModal = addModalEl ? new bootstrap.Modal(addModalEl) : null;
    const editModal = editModalEl ? new bootstrap.Modal(editModalEl) : null;

    /* ----- prev/next must be defined before used ----- */
    const prevBtn = document.getElementById("prev");
    const nextBtn = document.getElementById("next");

    /* -------------------------
       STATE
    ------------------------- */
    const today = new Date();
    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();
    let selectedDate = null;
    let eventsByDate = new Map();

    const months = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];

    /* -------------------------
       SAFELY ATTACH EVENT (if exists)
    ------------------------- */
    if (guruSelect) {
        guruSelect.addEventListener("change", function () {
            const opt = this.options[this.selectedIndex];
            mapelInput.value = opt ? opt.dataset.mapel || "" : "";
        });
    }

    if (editGuru) {
        editGuru.addEventListener("change", function () {
            const opt = this.options[this.selectedIndex];
            editMapel.value = opt ? opt.dataset.mapel || "" : "";
        });
    }

    function formatDate(y, m, d) {
        return `${y}-${String(m+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
    }

    /* ===========================
       FETCH EVENTS (server)
    ============================ */
    async function fetchEvents() {
        try {
            const res = await fetch("{{ route('admin.jadwal.get') }}", { cache: "no-store" });
            if (!res.ok) return [];
            const data = await res.json();
            return data;
        } catch (err) {
            console.error("fetchEvents error:", err);
            return [];
        }
    }

    async function loadEventsIntoMap() {
        const data = await fetchEvents();
        eventsByDate.clear();
        data.forEach(ev => {
            const raw = ev.start || ev.tanggal || "";
            const key = raw.split("T")[0] || raw;
            if (!eventsByDate.has(key)) eventsByDate.set(key, []);
            eventsByDate.get(key).push(ev);
        });
    }

    /* ===========================
       RENDER CALENDAR
    ============================ */
    async function renderCalendar() {
        if (!calendar) return;

        calendar.innerHTML = "";
        await loadEventsIntoMap();

        monthYear.textContent = `${months[currentMonth]} ${currentYear}`;

        // headers
        ["Sen","Sel","Rab","Kam","Jum","Sab","Min"].forEach(h => {
            const el = document.createElement("div");
            el.className = "day-header";
            el.textContent = h;
            calendar.appendChild(el);
        });

        const first = new Date(currentYear, currentMonth, 1);
        const lastDay = new Date(currentYear, currentMonth+1, 0).getDate();
        let start = (first.getDay() + 6) % 7; // make Monday index 0

        // empty cells
        for (let i = 0; i < start; i++) {
            const c = document.createElement("div");
            c.className = "day-cell empty";
            calendar.appendChild(c);
        }

        for (let d = 1; d <= lastDay; d++) {
            const key = formatDate(currentYear, currentMonth, d);
            const c = document.createElement("div");
            c.className = "day-cell";
            c.innerHTML = `<span class="date-number">${d}</span>`;

            if (key === formatDate(today.getFullYear(), today.getMonth(), today.getDate())) {
                c.classList.add("today");
            }

            if (eventsByDate.has(key)) c.classList.add("has-event");

            c.addEventListener("click", () => {
                document.querySelectorAll(".day-cell").forEach(el => el.classList.remove("selected"));
                c.classList.add("selected");
                selectedDate = key;
                loadAgenda(key);
            });

            calendar.appendChild(c);
        }
    }

    /* ===========================
       LOAD AGENDA (per tanggal)
    ============================ */
    async function loadAgenda(date) {
        try {
            const res = await fetch(`/admin/jadwal/hari/${date}`);
            if (!res.ok) {
                agendaList.innerHTML = `<li class="agenda-empty">Gagal memuat data.</li>`;
                return;
            }
            const data = await res.json();
            agendaList.innerHTML = "";

            if (!data || data.length === 0) {
                agendaList.innerHTML = `<li class="agenda-empty">Tidak ada jadwal.</li>`;
                return;
            }

            data.forEach(j => {
                const li = document.createElement("li");
                li.className = "agenda-item";

                const kelasNama = j.kelas_nama || (j.kelas?.nama_kelas ?? "-");

                li.innerHTML = `
                    <strong>${j.mata_pelajaran}</strong><br>
                    <small>Guru: ${j.guru}</small><br>
                    <small>Kelas: ${kelasNama}</small><br>
                    <small>${(j.jam_mulai || '').substring(0,5)} - ${(j.jam_selesai || '').substring(0,5)}</small>
                `;

                li.addEventListener("click", () => openEdit(j));
                agendaList.appendChild(li);
            });
        } catch (err) {
            console.error("loadAgenda error:", err);
            agendaList.innerHTML = `<li class="agenda-empty">Error memuat agenda.</li>`;
        }
    }

    /* ===========================
       OPEN EDIT MODAL (isi data)
    ============================ */
    function openEdit(j) {
        if (!editForm) return;

        document.getElementById("edit_id").value = j.id ?? "";
        if (editGuru) editGuru.value = j.guru ?? "";
        if (editMapel) editMapel.value = j.mata_pelajaran ?? "";
        if (document.getElementById("edit_jam_mulai")) document.getElementById("edit_jam_mulai").value = j.jam_mulai ?? "";
        if (document.getElementById("edit_jam_selesai")) document.getElementById("edit_jam_selesai").value = j.jam_selesai ?? "";
        editGroupId.value = j.semester_group_id ?? "";

        const editKelas = document.getElementById("edit_kelas");
        if (editKelas && j.kelas_id) {
            [...editKelas.options].forEach(o => {
                o.selected = String(o.value) === String(j.kelas_id);
            });
        }

        if (editModal) editModal.show();
    }

    /* ===========================
       HANDLERS: tombol add schedule
    ============================ */
    if (addScheduleBtn) {
        addScheduleBtn.addEventListener("click", () => {
            if (!selectedDate) return Swal.fire("Pilih tanggal dahulu!", "", "info");
            if (addModal) addModal.show();
        });
    }

    /* ===========================
       SUBMIT TAMBAH
    ============================ */
    if (eventForm) {
        eventForm.addEventListener("submit", async e => {
            e.preventDefault();
            if (!selectedDate) return Swal.fire("Pilih tanggal dahulu!", "", "info");

            try {
                const fd = new FormData(eventForm);
                fd.append("tanggal", selectedDate);

                const res = await fetch("{{ route('admin.jadwal.store') }}", {
                    method: "POST",
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: fd
                });

                const json = await res.json();

                if (json.success) {
                    Swal.fire("Berhasil!", json.message, "success");
                    eventForm.reset();
                    if (addModal) addModal.hide();
                    await renderCalendar();
                    loadAgenda(selectedDate);
                } else {
                    const msg = json.message || (json.errors ? Object.values(json.errors).flat().join(', ') : 'Gagal');
                    Swal.fire("Gagal!", msg, "warning");
                }
            } catch (err) {
                console.error("submit add error:", err);
                Swal.fire("Gagal!", "Kesalahan server.", "error");
            }
        });
    }

    /* ===========================
       SUBMIT EDIT
    ============================ */
    if (editForm) {
        editForm.addEventListener("submit", async e => {
            e.preventDefault();
            const id = document.getElementById("edit_id").value;
            if (!id) return Swal.fire("Tidak ditemukan id jadwal.", "", "warning");

            try {
                const fd = new FormData(editForm);

                const res = await fetch(`/admin/jadwal/${id}`, {
                    method: "POST",
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: fd
                });

                const json = await res.json();

                if (json.success) {
                    Swal.fire("Berhasil!", "Jadwal diperbarui!", "success");
                    if (editModal) editModal.hide();
                    await renderCalendar();
                    if (selectedDate) loadAgenda(selectedDate);
                } else {
                    const msg = json.message || 'Gagal memperbarui';
                    Swal.fire("Gagal!", msg, "warning");
                }
            } catch (err) {
                console.error("edit submit error:", err);
                Swal.fire("Gagal!", "Kesalahan server.", "error");
            }
        });
    }

    /* ===========================
       DELETE SATU
    ============================ */
    if (deleteOneBtn) {
        deleteOneBtn.addEventListener("click", async () => {
            const id = document.getElementById("edit_id").value;
            if (!id) return Swal.fire("ID tidak ditemukan.", "", "warning");

            try {
                const res = await fetch(`/admin/jadwal/${id}`, {
                    method: "DELETE",
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
                });

                const json = await res.json();

                if (json.success) {
                    Swal.fire("Dihapus!", json.message, "success");
                    if (editModal) editModal.hide();
                    await renderCalendar();
                    if (selectedDate) loadAgenda(selectedDate);
                } else {
                    Swal.fire("Gagal!", json.message || "Gagal menghapus.", "warning");
                }
            } catch (err) {
                console.error("delete one error:", err);
                Swal.fire("Gagal!", "Kesalahan server.", "error");
            }
        });
    }

    /* ===========================
       DELETE SEMESTER (group)
    ============================ */
    if (deleteSemesterBtn) {
        deleteSemesterBtn.addEventListener("click", async () => {
            const groupId = editGroupId.value;
            if (!groupId) return Swal.fire("Tidak bisa!", "Jadwal ini bukan bagian jadwal semester.", "warning");

            try {
                const res = await fetch(`/admin/jadwal/hapus-semester/${encodeURIComponent(groupId)}`, {
                    method: "DELETE",
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
                });

                const json = await res.json();

                if (json.success) {
                    Swal.fire("Dihapus!", json.message, "success");
                    if (editModal) editModal.hide();
                    await renderCalendar();
                    if (selectedDate) loadAgenda(selectedDate);
                } else {
                    Swal.fire("Gagal!", json.message || "Gagal menghapus semester.", "warning");
                }
            } catch (err) {
                console.error("delete semester error:", err);
                Swal.fire("Gagal!", "Kesalahan server.", "error");
            }
        });
    }

    /* ===========================
       NAVIGATION
    ============================ */
    if (prevBtn) {
        prevBtn.addEventListener("click", () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener("click", () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar();
        });
    }

    /* ===========================
       INITIAL LOAD
    ============================ */
    renderCalendar();

}); // DOMContentLoaded
</script>
@endsection
