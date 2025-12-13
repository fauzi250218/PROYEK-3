@extends('layouts.guru')

@section('content')
<div class="container-fluid">

    <h5 class="mb-4 fw-semibold text-success">
        <i class="bi bi-chat-dots me-2"></i>Obrolan
    </h5>

    @if($murids->isEmpty())
        <div class="alert alert-info">
            Belum ada murid di kelas binaan Anda.
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="list-group list-group-flush">

                @foreach ($murids as $murid)
                    <a href="{{ route('guru.obrolan.chat', $murid->id) }}"
                       class="list-group-item list-group-item-action obrolan-item">

                        <div class="d-flex justify-content-between align-items-center">

                            {{-- KIRI --}}
                            <div class="d-flex align-items-start gap-3">

                                {{-- Avatar --}}
                                <div class="avatar">
                                    <span class="avatar-circle">
                                        {{ strtoupper(substr($murid->nama, 0, 1)) }}
                                    </span>
                                </div>

                                {{-- Info --}}
                                <div>
                                    <div class="fw-semibold text-dark">
                                        {{ $murid->nama }}
                                    </div>

                                    {{-- Preview pesan --}}
                                    <div class="text-muted small text-truncate" style="max-width: 400px;">
                                        @if($murid->last_message)
                                            @if($murid->last_sender === 'guru')
                                                <span class="badge bg-success-subtle text-success me-1">
                                                    Anda
                                                </span>
                                            @endif
                                            {{ \Illuminate\Support\Str::limit($murid->last_message, 40) }}
                                        @else
                                            <em>Belum ada percakapan</em>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- KANAN --}}
                            <div class="text-end d-flex flex-column align-items-end gap-1">

                                {{-- 🔥 BADGE UNREAD REAL --}}
                                @if(($murid->unread_count ?? 0) > 0)
                                    <span class="badge-unread">
                                        {{ $murid->unread_count }}
                                    </span>
                                @endif

                                {{-- WAKTU PESAN --}}
                                @if($murid->last_time)
                                    <div class="small text-muted">
                                        {{ \Carbon\Carbon::parse($murid->last_time)->diffForHumans() }}
                                    </div>
                                @endif
                            </div>

                        </div>
                    </a>
                @endforeach

            </div>
        </div>
    @endif
</div>

{{-- ================= STYLE KHUSUS ================= --}}
<style>
.obrolan-item {
    transition: background-color 0.2s ease, transform 0.1s ease;
}

.obrolan-item:hover {
    background-color: #f1f8f5;
    transform: translateX(4px);
}

.avatar-circle {
    width: 42px;
    height: 42px;
    background-color: #198754;
    color: #fff;
    font-weight: 600;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* 🔥 BADGE UNREAD */
.badge-unread {
    background-color: #dc3545;
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 999px;
    min-width: 22px;
    text-align: center;
}
</style>
@endsection
