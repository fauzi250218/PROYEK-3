@extends('layouts.admin')

@section('title', 'Jadwal Pelajaran')

@section('extra-css')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css" rel="stylesheet">
<style>
    #calendar {
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        min-height: 600px;
    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Kalender Jadwal Pelajaran</h4>
    <div id="calendar"></div>
</div>
@endsection

@section('extra-js')
<!-- ✅ Perbaikan utama: gunakan file global -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'id',
        height: 'auto',
        events: '{{ route("admin.jadwal.get") }}',
        dateClick: function(info) {
            window.location.href = '{{ route("admin.jadwal.create") }}?tanggal=' + info.dateStr;
        },
        eventClick: function(info) {
            const id = info.event.id;
            window.location.href = '/admin/jadwal/' + id + '/edit';
        },
    });
    calendar.render();
});
</script>
@endsection
