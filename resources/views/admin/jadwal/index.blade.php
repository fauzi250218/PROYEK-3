@extends('layouts.admin')

@section('title', 'Kalender Jadwal')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin-jadwal.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
<div class="calendar-page-container">

    <!-- 🔵 Filter Angkatan -->
    <div class="filter-section">
        <h4 class="fw-semibold text-dark mb-0">Kalender Akademik</h4>
        <div>
            <label class="form-label me-2 mb-0">Filter Angkatan</label>
            <select id="filterAngkatan" class="form-select form-select-sm d-inline-block w-auto">
                <option value="">Semua Angkatan</option>
                <option value="7">Kelas 7</option>
                <option value="8">Kelas 8</option>
                <option value="9">Kelas 9</option>
            </select>
        </div>
    </div>

    <!-- ===== Kalender ===== -->
    <div class="calendar-container position-relative">
        <div class="calendar-header">
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

    <!-- ===== Jadwal Hari Ini / Tanggal Terpilih ===== -->
    <div class="agenda-section mt-4">
        <h4 class="agenda-title mb-3"><i class="bi bi-calendar-check"></i> Jadwal</h4>
        <ul id="agendaList" class="agenda-list">
            <li class="agenda-empty">Pilih tanggal untuk melihat jadwal.</li>
        </ul>
    </div>
</div>

<!-- ===== Modal Tambah Jadwal ===== -->
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
            <label class="form-label">Mata Pelajaran</label>
            <input type="text" name="mata_pelajaran" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nama Guru</label>
            <select name="guru" class="form-select" required>
              <option value="">-- Pilih Guru --</option>
              @foreach($guru as $g)
                <option value="{{ $g->user->name }}">{{ $g->user->name }}</option>
              @endforeach
            </select>
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
          <button type="submit" class="btn btn-primary w-100">Simpan Jadwal</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ===== Modal Edit Jadwal ===== -->
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
          <input type="hidden" name="id" id="edit_id">

          <div class="mb-3">
            <label class="form-label">Mata Pelajaran</label>
            <input type="text" name="mata_pelajaran" id="edit_mata_pelajaran" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Nama Guru</label>
            <select name="guru" id="edit_guru" class="form-select" required>
              <option value="">-- Pilih Guru --</option>
              @foreach($guru as $g)
                <option value="{{ $g->user->name }}">{{ $g->user->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Kelas</label>
            <select name="kelas_id" id="edit_kelas" class="form-select" required>
              <option value="">-- Pilih Kelas --</option>
              @foreach($kelas as $k)
                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
              @endforeach
            </select>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Jam Mulai</label>
              <input type="time" name="jam_mulai" id="edit_jam_mulai" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Jam Selesai</label>
              <input type="time" name="jam_selesai" id="edit_jam_selesai" class="form-control" required>
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
  const calendar = document.getElementById("calendar");
  const monthYear = document.getElementById("monthYear");
  const agendaList = document.getElementById("agendaList");
  const eventModal = new bootstrap.Modal(document.getElementById("addEventModal"));
  const editModal = new bootstrap.Modal(document.getElementById("editEventModal"));
  const eventForm = document.getElementById("eventForm");
  const editForm = document.getElementById("editForm");
  const addScheduleBtn = document.getElementById("addScheduleBtn");
  const filterAngkatan = document.getElementById("filterAngkatan");

  let selectedAngkatan = "";
  let selectedDate = null;
  let now = new Date();
  let currentMonth = now.getMonth();
  let currentYear = now.getFullYear();
  const months = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
  let eventsByDate = new Map();

  /* =======================
     HELPER: Tanggal Lokal
     ======================= */
  // Buat "YYYY-MM-DD" dari komponen tanggal LOKAL (aman dari UTC)
  function localISO(y, mIndex, d) {
    const dt = new Date(y, mIndex, d); // local time
    const yy = dt.getFullYear();
    const mm = String(dt.getMonth() + 1).padStart(2, "0");
    const dd = String(dt.getDate()).padStart(2, "0");
    return `${yy}-${mm}-${dd}`;
  }

  // Parse "YYYY-MM-DD" -> Date lokal
  function parseYMD(ymd) {
    const [y, m, d] = ymd.split("-").map(n => parseInt(n, 10));
    return new Date(y, m - 1, d); // local time
  }

  /* =======================
     FETCH EVENTS (per bulan)
     ======================= */
  async function fetchEventsForMonth(year, month) {
      const url = selectedAngkatan
          ? `{{ route('admin.jadwal.get') }}?angkatan=${selectedAngkatan}`
          : `{{ route('admin.jadwal.get') }}`;

      const res = await fetch(url);
      const data = await res.json();

      eventsByDate.clear();

      data.forEach(ev => {
          // Normalisasi key tanggal ke LOKAL
          // ev.start bisa "YYYY-MM-DDTHH:MM:SS"
          const raw = ev.start.split("T")[0];
          const dt = parseYMD(raw);
          const key = localISO(dt.getFullYear(), dt.getMonth(), dt.getDate());

          if (dt.getFullYear() === year && dt.getMonth() === month) {
              if (!eventsByDate.has(key)) eventsByDate.set(key, []);
              eventsByDate.get(key).push(ev);
          }
      });
  }

  /* =======================
     RENDER KALENDER
     ======================= */
  async function renderCalendar() {
      calendar.innerHTML = "";
      await fetchEventsForMonth(currentYear, currentMonth);

      const firstDay = new Date(currentYear, currentMonth, 1);
      const lastDay  = new Date(currentYear, currentMonth + 1, 0);
      const startDay = firstDay.getDay(); // 0=Min ... 6=Sab
      const totalDays = lastDay.getDate();

      monthYear.textContent = `${months[currentMonth]} ${currentYear}`;

      const daysOfWeek = ["Min","Sen","Sel","Rab","Kam","Jum","Sab"];
      daysOfWeek.forEach(day => {
          const header = document.createElement("div");
          header.classList.add("day-header");
          header.textContent = day;
          calendar.appendChild(header);
      });

      for (let i = 0; i < startDay; i++) {
          const empty = document.createElement("div");
          empty.classList.add("day-cell", "empty");
          calendar.appendChild(empty);
      }

      const todayKey = localISO(now.getFullYear(), now.getMonth(), now.getDate());

      for (let day = 1; day <= totalDays; day++) {
          const cell = document.createElement("div");
          cell.classList.add("day-cell");

          // Gunakan tanggal lokal sebagai key & value
          const dateKey = localISO(currentYear, currentMonth, day);

          cell.innerHTML = `<span class="date-number">${day}</span>`;

          if (dateKey === todayKey) cell.classList.add("today");
          if (eventsByDate.has(dateKey)) cell.classList.add("has-event");

          cell.addEventListener("click", () => {
              document.querySelectorAll(".day-cell.selected").forEach(el => el.classList.remove("selected"));
              cell.classList.add("selected");
              selectedDate = dateKey;              // <-- simpan tanggal lokal
              loadAgenda(dateKey);                 // <-- tampilkan agenda tanggal tersebut
          });

          calendar.appendChild(cell);
      }
  }

  /* =======================
     NAVIGASI KALENDER
     ======================= */
  document.getElementById("prev").addEventListener("click", async () => {
      currentMonth--;
      if (currentMonth < 0) { currentMonth = 11; currentYear--; }
      await renderCalendar();
      agendaList.innerHTML = `<li class="agenda-empty">Pilih tanggal untuk melihat jadwal.</li>`;
  });

  document.getElementById("next").addEventListener("click", async () => {
      currentMonth++;
      if (currentMonth > 11) { currentMonth = 0; currentYear++; }
      await renderCalendar();
      agendaList.innerHTML = `<li class="agenda-empty">Pilih tanggal untuk melihat jadwal.</li>`;
  });

  filterAngkatan.addEventListener("change", async (e) => {
      selectedAngkatan = e.target.value;
      await renderCalendar();
      agendaList.innerHTML = `<li class="agenda-empty">Pilih tanggal untuk melihat jadwal.</li>`;
  });

  /* =======================
     AGENDA LIST (kartu jadwal)
     ======================= */
  async function loadAgenda(date = null) {
      const targetDate = date ?? localISO(now.getFullYear(), now.getMonth(), now.getDate());
      const url = selectedAngkatan
          ? `/admin/jadwal/hari/${targetDate}?angkatan=${selectedAngkatan}`
          : `/admin/jadwal/hari/${targetDate}`;

      const res = await fetch(url);
      const data = await res.json();

      agendaList.innerHTML = "";
      if (!Array.isArray(data) || data.length === 0) {
          agendaList.innerHTML = `<li class="agenda-empty">Belum ada jadwal pada tanggal ${targetDate}.</li>`;
          return;
      }

      data.forEach(j => {
          // BACA TANGGAL SECARA LOKAL (fix bug "jadi Minggu")
          const tanggalObj = parseYMD(j.tanggal);
          const hariNama = tanggalObj.toLocaleDateString('id-ID', { weekday: 'long' });
          const tanggalLengkap = tanggalObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

          const jamMulai   = (j.jam_mulai || '').substring(0,5);
          const jamSelesai = (j.jam_selesai || '').substring(0,5);

          const li = document.createElement("li");
          li.classList.add("agenda-item");
          li.innerHTML = `
              <strong>${j.mata_pelajaran}</strong><br>
              <small>Guru: ${j.guru}</small><br>
              <small>Kelas: ${j.kelas_nama}</small><br>
              <small>Jam: ${jamMulai} - ${jamSelesai}</small><br>
              <small class="text-muted">${hariNama}, ${tanggalLengkap}</small>`;
          li.addEventListener("click", () => openEditModal(j));
          agendaList.appendChild(li);
      });
  }

  /* =======================
     MODAL EDIT
     ======================= */
  function openEditModal(j) {
      document.getElementById("edit_id").value = j.id;
      document.getElementById("edit_mata_pelajaran").value = j.mata_pelajaran;

      const guruSelect = document.getElementById("edit_guru");
      [...guruSelect.options].forEach(opt => opt.selected = (opt.value === j.guru));

      const kelasSelect = document.getElementById("edit_kelas");
      [...kelasSelect.options].forEach(opt => opt.text === j.kelas_nama ? (opt.selected = true) : null);

      document.getElementById("edit_jam_mulai").value = j.jam_mulai;
      document.getElementById("edit_jam_selesai").value = j.jam_selesai;

      editModal.show();
  }

  /* =======================
     TAMBAH JADWAL
     ======================= */
  addScheduleBtn.addEventListener("click", () => {
      if (!selectedDate) return Swal.fire("Pilih tanggal terlebih dahulu!", "", "info");
      eventModal.show();
  });

  eventForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      if (!selectedDate) return Swal.fire("Pilih tanggal terlebih dahulu!", "", "warning");

      const formData = new FormData(eventForm);
      formData.append("tanggal", selectedDate); // kirim YYYY-MM-DD lokal

      const res = await fetch("{{ route('admin.jadwal.store') }}", {
          method: "POST",
          headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
          body: formData
      });

      const json = await res.json().catch(()=>null);
      if (json && json.success) {
          Swal.fire("Berhasil!", "Jadwal disimpan!", "success");
          eventModal.hide();
          eventForm.reset();
          await renderCalendar();
          loadAgenda(selectedDate);
      } else {
          Swal.fire("Gagal", json?.message || "Terjadi kesalahan.", "error");
      }
  });

  /* =======================
     EDIT & HAPUS
     ======================= */
  editForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const id = document.getElementById("edit_id").value;
      const formData = new FormData(editForm);

      const res = await fetch(`/admin/jadwal/${id}`, {
          method: "POST",
          headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
          body: formData
      });

      const json = await res.json().catch(()=>null);
      if (json && json.success) {
          Swal.fire("Berhasil!", "Jadwal diperbarui!", "success");
          editModal.hide();
          await renderCalendar();
          loadAgenda(selectedDate);
      } else {
          Swal.fire("Gagal", json?.message || "Terjadi kesalahan saat update!", "error");
      }
  });

  document.getElementById("deleteOneBtn").addEventListener("click", async () => {
      const id = document.getElementById("edit_id").value;
      if (!confirm("Yakin ingin menghapus jadwal ini?")) return;

      await fetch(`/admin/jadwal/${id}`, {
          method: "DELETE",
          headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"}
      });

      Swal.fire("Dihapus!", "Jadwal hari ini dihapus.", "success");
      editModal.hide();
      renderCalendar();
      loadAgenda(selectedDate);
  });

  document.getElementById("deleteSemesterBtn").addEventListener("click", async () => {
      const mapel = document.getElementById("edit_mata_pelajaran").value;
      if (!confirm(`Yakin ingin hapus semua jadwal ${mapel}?`)) return;

      await fetch(`/admin/jadwal/hapus-semester/${mapel}`, {
          method: "DELETE",
          headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"}
      });

      Swal.fire("Dihapus!", `Semua jadwal ${mapel} di semester ini dihapus.`, "success");
      editModal.hide();
      renderCalendar();
      loadAgenda(selectedDate);
  });

  /* =======================
     INIT
     ======================= */
  (async () => { await renderCalendar(); })();
});
</script>
@endsection
