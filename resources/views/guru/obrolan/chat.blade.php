@extends('layouts.guru')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('guru.obrolan.index') }}"
               class="btn btn-sm btn-outline-success btn-back">
                <i class="bi bi-arrow-left"></i>
            </a>

            <h5 class="mb-0 fw-semibold text-success">
                Chat dengan {{ $murid->nama }}
            </h5>
        </div>
    </div>

    {{-- CHAT BOX --}}
    <div id="chat-box" class="chat-box mb-3 bg-white rounded shadow-sm">
        <!-- pesan dimuat via JS -->
    </div>

    {{-- INPUT --}}
    <form id="chat-form">
        @csrf
        <div class="input-group">
            <input type="text"
                   id="message"
                   class="form-control"
                   placeholder="Ketik pesan..."
                   autocomplete="off"
                   required>
            <button class="btn btn-success px-4">Kirim</button>
        </div>
    </form>

</div>

{{-- ================= SCRIPT ================= --}}
<script>
const guruId  = {{ $guru->id }};
const muridId = {{ $murid->id }};
let roomId    = null;

// ================= OPEN ROOM =================
fetch("{{ url('/api/chat/open-room') }}", {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
    },
    body: new URLSearchParams({
        user_id: guruId,
        user_role: 'guru',
        peer_id: muridId,
        peer_role: 'murid'
    })
})
.then(res => res.json())
.then(data => {
    roomId = data.room.id;
    loadMessages();
    markAsRead();
});

// ================= LOAD MESSAGES =================
function loadMessages() {
    fetch(`/api/chat/messages/${roomId}?user_id=${guruId}&user_role=guru`)
        .then(res => res.json())
        .then(data => {
            const box = document.getElementById('chat-box');
            box.innerHTML = '';

            data.messages.forEach(m => {
                const wrap = document.createElement('div');
                const isGuru = m.sender_role === 'guru';
                const isRead = isGuru && m.read_at !== null;

                wrap.className = isGuru
                    ? 'chat-row chat-right'
                    : 'chat-row chat-left';

                wrap.innerHTML = `
                    <div class="chat-bubble ${isGuru ? 'bubble-guru' : 'bubble-murid'}">
                        ${m.message}
                        <div class="chat-meta">
                            <span class="chat-time">
                                ${new Date(m.created_at).toLocaleTimeString([], {
                                    hour: '2-digit',
                                    minute:'2-digit'
                                })}
                            </span>
                            ${isGuru ? `
                                <span class="chat-read ${isRead ? 'readed' : ''}">
                                    ✓✓
                                </span>
                            ` : ''}
                        </div>
                    </div>
                `;
                box.appendChild(wrap);
            });

            box.scrollTop = box.scrollHeight;
            markAsRead(); // 🔥 realtime read
        });
}

// ================= SEND MESSAGE =================
document.getElementById('chat-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const input = document.getElementById('message');
    const msg   = input.value.trim();
    if (!msg) return;

    fetch("{{ url('/api/chat/send') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: new URLSearchParams({
            room_id: roomId,
            sender_id: guruId,
            sender_role: 'guru',
            message: msg
        })
    }).then(() => {
        input.value = '';
        loadMessages();
    });
});

// ================= MARK AS READ =================
function markAsRead() {
    fetch("{{ url('/api/chat/mark-read') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: new URLSearchParams({
            room_id: roomId,
            user_role: 'guru'
        })
    });
}
</script>

{{-- ================= STYLE ================= --}}
<style>
.chat-box {
    height: 420px;
    overflow-y: auto;
    padding: 16px;
    border: 1px solid #e3e6ea;
}

.chat-row {
    display: flex;
    margin-bottom: 10px;
}

.chat-left {
    justify-content: flex-start;
}

.chat-right {
    justify-content: flex-end;
}

.chat-bubble {
    max-width: 65%;
    padding: 10px 14px;
    border-radius: 14px;
    font-size: 14px;
    line-height: 1.4;
}

.bubble-guru {
    background-color: #198754;
    color: #fff;
    border-bottom-right-radius: 4px;
}

.bubble-murid {
    background-color: #f1f3f5;
    color: #333;
    border-bottom-left-radius: 4px;
}

/* ===== META ===== */
.chat-meta {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
    margin-top: 4px;
}

.chat-time {
    font-size: 10px;
    opacity: .7;
}

/* ===== READ INDICATOR ===== */
.chat-read {
    font-size: 11px;
    color: rgba(255,255,255,0.6); /* belum dibaca */
}

.chat-read.readed {
    color: #faf7f7; /* ✓✓ biru */
}

/* ===== BUTTON BACK ===== */
.btn-back {
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-back:hover {
    background-color: #198754;
    color: #fff;
}
</style>
@endsection
