document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const modalEl = document.getElementById('aksiTanggalModal');
    const modal = new bootstrap.Modal(modalEl);
    let tanggalKlik = '';

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'id',
        height: 'auto',
        events: '/admin/jadwal/get',

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },

        dateClick: function (info) {
            tanggalKlik = info.dateStr;
            const tanggalFormatted = new Date(info.dateStr).toLocaleDateString('id-ID', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            });

            document.getElementById('tanggalTerpilih').innerText = tanggalFormatted;
            document.getElementById('jadwalContainer').innerHTML =
                `<p class="text-muted">Klik tombol "Lihat Semua Jadwal Hari Ini" untuk menampilkan data.</p>`;

            modal.show();
        },

        eventClick: function (info) {
            const id = info.event.id;
            window.location.href = `/admin/jadwal/${id}/edit`;
        },

        eventDidMount: function (info) {
            // Tooltip ringan pada hover event
            const tooltip = new bootstrap.Tooltip(info.el, {
                title: `${info.event.title}`,
                placement: 'top',
                trigger: 'hover'
            });
        }
    });

    calendar.render();

    // Tombol: Tambah Mapel Baru
    document.getElementById('btnTambahMapel').addEventListener('click', function () {
        window.location.href = `/admin/jadwal/create?tanggal=${tanggalKlik}`;
    });

    // Tombol: Lihat Semua Jadwal Hari Ini
    document.getElementById('btnLihatJadwal').addEventListener('click', function () {
        fetch(`/admin/jadwal/hari/${tanggalKlik}`)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('jadwalContainer');
                if (data.length === 0) {
                    container.innerHTML = `<p class="text-danger">Tidak ada jadwal pada tanggal ini.</p>`;
                } else {
                    let html = '<ul class="jadwal-list">';
                    data.forEach(item => {
                        html += `
                            <li>
                                <strong>${item.mata_pelajaran}</strong> (${item.jam_mulai} - ${item.jam_selesai})<br>
                                Guru: ${item.guru} <br>
                                Kelas: ${item.kelas_nama}
                                <a href="/admin/jadwal/${item.id}/edit" class="btn btn-sm btn-outline-primary">Edit</a>
                            </li>
                        `;
                    });
                    html += '</ul>';
                    container.innerHTML = html;
                }
            })
            .catch(err => {
                console.error(err);
                alert('Gagal memuat jadwal hari ini.');
            });
    });
});
