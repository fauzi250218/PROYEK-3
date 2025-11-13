@extends('layouts.admin')

@section('title', 'Kalender Jadwal')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin-jadwal.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
<div class="calendar-page-container">

    <!-- Filter Angkatan -->
    <div class="filter-section d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold text-dark mb-0">Kalender Akademik</h4>

        <div class="d-flex align-items-center">
            <label class="form-label me-2 mb-0">Filter Angkatan</label>
            <select id="filterAngkatan" class="form-select form-select-sm d-inline-block w-auto">
                <option value="">Semua Angkatan</option>
                <option value="7">Kelas 7</option>
                <option value="8">Kelas 8</option>
                <option value="9">Kelas 9</option>
            </select>
        </div>
    </div>

    <!-- Kalender -->
    <div class="calendar-container position-relative">
        <div class="calendar-header mb-2">
            <div class="left-section d-flex align-items-center">
                <button id="prev" class="nav-btn btn btn-light btn-sm"><i class="bi bi-chevron-left"></i></button>
                <h3 id="monthYear" class="month-year mb-0 mx-3"></h3>
                <button id="next" class="nav-btn btn btn-light btn-sm"><i class="bi bi-chevron-right"></i></button>
            </div>

            <div class="right-section">
                <button id="addScheduleBtn" class="btn btn-primary btn-sm">Tambah Jadwal</button>
            </div>
        </div>

        <div id="calendar" class="mini-calendar mt-3"></div>
    </div>

    <!-- Agenda -->
    <div class="agenda-section mt-4">
        <h4 class="agenda-title mb-3"><i class="bi bi-calendar-check"></i> Jadwal</h4>
        <ul id="agendaList" class="agenda-list">
            <li class="agenda-empty">Pilih tanggal untuk melihat jadwal.</li>
        </ul>
    </div>
</div>

<!-- Modal Tambah -->
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

          <!-- Urutan BENAR: guru → mapel -->
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
            <input id="mapelInput" type="text" name="mata_pelajaran" class="form-control" readonly required>
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
                <option value="semester">Semester (setiap minggu 6 bulan)</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary w-100">Simpan Jadwal</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit -->
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

          <div class="mb-3">
            <label class="form-label">Nama Guru</label>
            <select id="edit_guru" name="guru" class="form-select" required>
                @foreach($guru as $g)
                <option value="{{ $g->user->name }}">{{ $g->user->name }}</option>
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

  /* ==============================
     AUTO isi mata pelajaran (Tambah)
  ===============================*/
  const guruSelect = document.getElementById("guruSelect");
  const mapelInput = document.getElementById("mapelInput");

  if (guruSelect) {
    guruSelect.addEventListener("change", function () {
      let mapel = this.options[this.selectedIndex].dataset.mapel || "";
      mapelInput.value = mapel;
    });
  }

  /* ==============================
     VARIABEL KALENDER
  ===============================*/
  const calendar = document.getElementById("calendar");
  const monthYear = document.getElementById("monthYear");
  const agendaList = document.getElementById("agendaList");
  const filterAngkatan = document.getElementById("filterAngkatan");
  const eventModal = new bootstrap.Modal(document.getElementById("addEventModal"));
  const editModal  = new bootstrap.Modal(document.getElementById("editEventModal"));

  let selectedAngkatan = "";
  let selectedDate = null;

  const today = new Date();
  let currentYear = today.getFullYear();
  let currentMonth = today.getMonth();

  const months = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
  const DAY_OFFSET = 1;

  let eventsByDate = new Map();

  function convertDate(y,m,d){
    const dt = new Date(y,m,d);
    dt.setHours(0,0,0,0);
    return dt.toISOString().split("T")[0];
  }

  /* ==============================
     FETCH EVENT
  ===============================*/
  async function fetchEvents(){
    const res = await fetch(
        selectedAngkatan
          ? `{{ route('admin.jadwal.get') }}?angkatan=${selectedAngkatan}`
          : `{{ route('admin.jadwal.get') }}`
    );

    const data = await res.json();
    eventsByDate.clear();

    data.forEach(ev => {
      const key = ev.start.split("T")[0];
      if (!eventsByDate.has(key)) eventsByDate.set(key, []);
      eventsByDate.get(key).push(ev);
    });
  }


  /* ==============================
     RENDER KALENDER
  ===============================*/
  async function renderCalendar(){
    calendar.innerHTML = "";

    await fetchEvents();

    monthYear.textContent = `${months[currentMonth]} ${currentYear}`;

    const firstDate = new Date(currentYear,currentMonth,1);
    const lastDay   = new Date(currentYear,currentMonth+1,0).getDate();

    let startDay = (firstDate.getDay() - DAY_OFFSET + 7) % 7;

    ["Sen","Sel","Rab","Kam","Jum","Sab","Min"].forEach(day => {
      const div = document.createElement("div");
      div.classList.add("day-header");
      div.textContent = day;
      calendar.appendChild(div);
    });

    for (let i=0; i<startDay; i++){
      const empty = document.createElement("div");
      empty.classList.add("day-cell","empty");
      calendar.appendChild(empty);
    }

    for (let d=1; d<=lastDay; d++){
      const key = convertDate(currentYear,currentMonth,d);

      const cell = document.createElement("div");
      cell.classList.add("day-cell");
      cell.innerHTML = `<span class="date-number">${d}</span>`;

      if (eventsByDate.has(key)) cell.classList.add("has-event");

      if (key === convertDate(today.getFullYear(),today.getMonth(),today.getDate()))
        cell.classList.add("today");

      cell.addEventListener("click", () => {
        document.querySelectorAll(".day-cell.selected").forEach(c => c.classList.remove("selected"));
        cell.classList.add("selected");

        selectedDate = key;
        loadAgenda(key);
      });

      calendar.appendChild(cell);
    }
  }


  /* ==============================
     LOAD AGENDA
  ===============================*/
  async function loadAgenda(date){
    const res = await fetch(
      selectedAngkatan
        ? `/admin/jadwal/hari/${date}?angkatan=${selectedAngkatan}`
        : `/admin/jadwal/hari/${date}`
    );

    const data = await res.json();
    agendaList.innerHTML = "";

    if (data.length === 0){
      agendaList.innerHTML = `<li class="agenda-empty">Tidak ada jadwal.</li>`;
      return;
    }

    data.forEach(j => {
      const li = document.createElement("li");
      li.classList.add("agenda-item");

      li.innerHTML = `
        <strong>${j.mata_pelajaran}</strong><br>
        <small>Guru: ${j.guru}</small><br>
        <small>Kelas: ${j.kelas_nama}</small><br>
        <small>Jam: ${j.jam_mulai.substring(0,5)} - ${j.jam_selesai.substring(0,5)}</small>
      `;

      li.addEventListener("click", () => openEdit(j));
      agendaList.appendChild(li);
    });
  }


  /* ==============================
     OPEN EDIT
  ===============================*/
  function openEdit(j){
    document.getElementById("edit_id").value = j.id;
    document.getElementById("edit_guru").value = j.guru;
    document.getElementById("edit_mata_pelajaran").value = j.mata_pelajaran;
    document.getElementById("edit_jam_mulai").value = j.jam_mulai;
    document.getElementById("edit_jam_selesai").value = j.jam_selesai;

    [...document.getElementById("edit_kelas").options].forEach(o => {
      o.selected = (o.text === j.kelas_nama);
    });

    editModal.show();
  }


  /* ==============================
     TAMBAH JADWAL
  ===============================*/
  document.getElementById("addScheduleBtn").addEventListener("click", () => {
    if (!selectedDate) return Swal.fire("Pilih tanggal dulu!", "", "info");
    eventModal.show();
  });

  eventForm.addEventListener("submit", async e => {
    e.preventDefault();

    let fd = new FormData(eventForm);
    fd.append("tanggal", selectedDate);

    const res = await fetch("{{ route('admin.jadwal.store') }}", {
      method: "POST",
      headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
      body: fd
    });

    const json = await res.json();

    if (json.success){
      Swal.fire("Berhasil!", json.message, "success");
      eventModal.hide();
      eventForm.reset();
      renderCalendar();
      loadAgenda(selectedDate);
    } else {
      Swal.fire("Gagal!", json.message, "warning");
    }
  });


  /* ==============================
     EDIT JADWAL
  ===============================*/
  editForm.addEventListener("submit", async e => {
    e.preventDefault();

    const id = document.getElementById("edit_id").value;
    let fd = new FormData(editForm);

    const res = await fetch(`/admin/jadwal/${id}`, {
      method: "POST",
      headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
      body: fd
    });

    const json = await res.json();

    if (json.success){
      Swal.fire("Berhasil!", "Jadwal diperbarui!", "success");
      editModal.hide();
      renderCalendar();
      loadAgenda(selectedDate);
    }
  });


  /* ==============================
     HAPUS HARI INI
  ===============================*/
  deleteOneBtn.addEventListener("click", async () => {
    const id = document.getElementById("edit_id").value;
    if (!confirm("Hapus jadwal ini?")) return;

    const res = await fetch(`/admin/jadwal/${id}`, {
      method: "DELETE",
      headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
    });

    const json = await res.json();

    if (json.success){
      Swal.fire("Dihapus!", "Jadwal berhasil dihapus", "success");
      editModal.hide();
      renderCalendar();
      loadAgenda(selectedDate);
    }
  });


  /* ==============================
     HAPUS SEMESTER
  ===============================*/
  deleteSemesterBtn.addEventListener("click", async () => {
    const mapel = document.getElementById("edit_mata_pelajaran").value;

    if (!confirm(`Hapus semua jadwal '${mapel}' selama semester?`)) return;

    const res = await fetch(`/admin/jadwal/hapus-semester/${mapel}`, {
      method: "DELETE",
      headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
    });

    const json = await res.json();

    if (json.success){
      Swal.fire("Dihapus!", json.message, "success");
      editModal.hide();
      renderCalendar();
      loadAgenda(selectedDate);
    }
  });

  /* INIT */
  renderCalendar();

});
</script>
@endsection
