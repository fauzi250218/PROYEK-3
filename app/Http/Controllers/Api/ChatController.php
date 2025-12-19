<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\Murid;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ChatController extends Controller
{
    // ==================================================
    // OPEN / CREATE CHAT ROOM
    // ==================================================
    public function openRoom(Request $request)
    {
        $request->validate([
            'user_id'   => 'required|integer',
            'user_role' => 'required|in:murid,guru',
            'peer_id'   => 'required|integer',
            'peer_role' => 'required|in:murid,guru',
        ]);

        $userId   = (int) $request->user_id;
        $userRole = $request->user_role;
        $peerId   = (int) $request->peer_id;
        $peerRole = $request->peer_role;

        // 🔒 VALIDASI: murid ↔ wali kelas
        if ($userRole === 'murid' && $peerRole === 'guru') {
            $murid = Murid::with('kelas')->find($userId);
            $guru  = Guru::find($peerId);

            if (!$murid || !$murid->kelas || !$guru) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data murid / guru tidak valid'
                ], 403);
            }

            if ($murid->kelas->guru_id !== $guru->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Guru bukan wali kelas murid ini'
                ], 403);
            }
        }

        // 🔍 Cari room dua arah
        $room = ChatRoom::where(function ($q) use ($userId, $userRole, $peerId, $peerRole) {
            $q->where([
                ['user_one_id', $userId],
                ['user_one_role', $userRole],
                ['user_two_id', $peerId],
                ['user_two_role', $peerRole],
            ]);
        })->orWhere(function ($q) use ($userId, $userRole, $peerId, $peerRole) {
            $q->where([
                ['user_one_id', $peerId],
                ['user_one_role', $peerRole],
                ['user_two_id', $userId],
                ['user_two_role', $userRole],
            ]);
        })->first();

        // ➕ Buat room jika belum ada
        if (!$room) {
            $room = ChatRoom::create([
                'user_one_id'   => $userId,
                'user_one_role' => $userRole,
                'user_two_id'   => $peerId,
                'user_two_role' => $peerRole,
            ]);
        }

        return response()->json([
            'status' => true,
            'room'   => $room
        ]);
    }

    // ==================================================
    // GET CHAT ROOMS + UNREAD COUNT
    // ==================================================
    public function getRooms(Request $request)
    {
        $request->validate([
            'user_id'   => 'required|integer',
            'user_role' => 'required|in:murid,guru'
        ]);

        $userId   = (int) $request->user_id;
        $userRole = $request->user_role;

        $rooms = ChatRoom::where(function ($q) use ($userId, $userRole) {
            $q->where('user_one_id', $userId)
              ->where('user_one_role', $userRole);
        })->orWhere(function ($q) use ($userId, $userRole) {
            $q->where('user_two_id', $userId)
              ->where('user_two_role', $userRole);
        })->get();

        $result = $rooms->map(function ($room) use ($userId, $userRole) {

            // Tentukan lawan chat
            if ($room->user_one_id == $userId && $room->user_one_role == $userRole) {
                $peerId   = $room->user_two_id;
                $peerRole = $room->user_two_role;
            } else {
                $peerId   = $room->user_one_id;
                $peerRole = $room->user_one_role;
            }

            // Nama & foto
            $peerName = '-';
            $peerPhoto = null;

            if ($peerRole === 'murid') {
                $m = Murid::find($peerId);
                if ($m) {
                    $peerName = $m->nama;
                    $peerPhoto = $this->formatFoto($m->foto_profil);
                }
            } else {
                $g = Guru::find($peerId);
                if ($g && $g->user) {
                    $peerName = $g->user->name;
                    $peerPhoto = $this->formatFoto(
                        $g->foto_profil ?? $g->user->foto_profil
                    );
                }
            }

            // Pesan terakhir
            $lastMessage = ChatMessage::where('chat_room_id', $room->id)
                ->latest()
                ->first();

            // 🔥 HITUNG UNREAD (INI KUNCI BADGE)
            $unreadCount = ChatMessage::where('chat_room_id', $room->id)
                ->where('sender_role', '!=', $userRole)
                ->whereNull('read_at')
                ->count();

            return [
                'id'           => $room->id,
                'peerId'       => $peerId,
                'peerRole'     => $peerRole,
                'peerName'     => $peerName,
                'peerPhoto'    => $peerPhoto,
                'lastMessage'  => $lastMessage?->message ?? '',
                'unreadCount'  => $unreadCount,
            ];
        });

        return response()->json([
            'status' => true,
            'rooms'  => $result
        ]);
    }

    // ==================================================
    // GET MESSAGES + AUTO MARK READ
    // ==================================================
    public function getMessages($roomId, Request $request)
    {
        $request->validate([
            'user_id'   => 'required|integer',
            'user_role' => 'required|in:murid,guru'
        ]);

        // 🔥 AUTO MARK AS READ
        ChatMessage::where('chat_room_id', $roomId)
            ->where('sender_role', '!=', $request->user_role)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = ChatMessage::where('chat_room_id', $roomId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status'   => true,
            'messages' => $messages
        ]);
    }

    // ==================================================
    // SEND MESSAGE
    // ==================================================
    public function sendMessage(Request $request)
    {
        $request->validate([
            'room_id'     => 'required|integer',
            'sender_id'   => 'required|integer',
            'sender_role' => 'required|in:murid,guru',
            'message'     => 'required|string',
        ]);

        $room = ChatRoom::find($request->room_id);
        if (!$room) {
            return response()->json([
                'status' => false,
                'message' => 'Room tidak ditemukan'
            ], 404);
        }

        $msg = ChatMessage::create([
            'chat_room_id' => $request->room_id,
            'sender_id'    => $request->sender_id,
            'sender_role'  => $request->sender_role,
            'message'      => $request->message,
            'created_at'   => Carbon::now(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => $msg
        ]);
    }

    // ==================================================
    // FORMAT FOTO
    // ==================================================
    private function formatFoto($foto)
    {
        if (!$foto) return null;
        if (str_starts_with($foto, 'http')) return $foto;
        return asset("storage/" . $foto);
    }
}
