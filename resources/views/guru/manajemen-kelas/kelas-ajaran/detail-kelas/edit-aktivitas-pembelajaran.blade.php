@extends('layouts.guru')

@section('title', 'Edit Aktivitas Pembelajaran')

@section('content')

<div class="container py-3">

    <div class="card shadow-sm p-4 rounded-4">

        <h4 class="fw-bold mb-3">Edit Aktivitas Pembelajaran</h4>

        <form action="{{ route('guru.kelas.ajaran.sesi.update', $sesi->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">

            <label class="fw-semibold small">Judul Sesi</label>
            <input 
                type="text"
                name="judul_sesi"
                class="form-control mb-3"
                value="{{ $sesi->judul_sesi }}"
                required
            >

            <label class="fw-semibold small">Topik</label>
            <input 
                type="text"
                name="topik"
                class="form-control mb-3"
                value="{{ $sesi->topik }}"
            >

            <label class="fw-semibold small">Tujuan Pembelajaran</label>

            <!-- TEXTAREA disembunyikan supaya tidak muncul dulu -->
            <textarea 
                id="editorDeskripsi"
                name="deskripsi"
                class="hidden-editor"
            >{!! $sesi->deskripsi !!}</textarea>

            <label class="fw-semibold small mt-3">Tanggal Sesi</label>
            <input 
                type="date" 
                name="tanggal"
                class="form-control mb-3"
                value="{{ $sesi->tanggal }}"
                required
            >

            <div class="row">
                <div class="col-6 mb-3">
                    <label class="fw-semibold small">Jam Mulai</label>
                    <input 
                        type="time"
                        name="jam_mulai"
                        class="form-control"
                        value="{{ $sesi->jam_mulai }}"
                        required
                    >
                </div>

                <div class="col-6 mb-3">
                    <label class="fw-semibold small">Jam Selesai</label>
                    <input 
                        type="time"
                        name="jam_selesai"
                        class="form-control"
                        value="{{ $sesi->jam_selesai }}"
                        required
                    >
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('guru.kelas.ajaran.detail', $kelas_id) }}" class="btn btn-light">
                    Batal
                </a>
                <button class="btn btn-success">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>

</div>

@endsection


@section('extra-css')
<style>
    /* textarea wajib disembunyikan agar tidak muncul <p> sebelum TinyMCE muncul */
    .hidden-editor {
        display: none !important;
    }

    .tox-tinymce {
        border-radius: 8px !important;
        width: 100% !important;
    }

    @media (max-width: 576px) {
        .tox-tinymce {
            min-height: 250px !important;
        }
    }
</style>
@endsection


@section('extra-js')

<!-- LOAD TinyMCE -->
<script src="https://cdn.tiny.cloud/1/f07j73qf5orh08pbdf2z8lltgc4x3q0y4cg5gxnw50hca6i9/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<script>
tinymce.init({
    selector: '#editorDeskripsi',
    height: 320,
    menubar: false,

    plugins: 'lists link image table autoresize code',

    toolbar: `
        undo redo |
        bold italic underline |
        bullist numlist |
        link image table |
        code removeformat
    `,

    elementpath: false,
    statusbar: false,

    content_style: `
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
    `,

    /* HILANGKAN BUG KEDIP */
    setup: function(editor) {
        editor.on('init', function() {
            document.querySelector('#editorDeskripsi').style.display = 'block';
        });
    }
});
</script>

@endsection
