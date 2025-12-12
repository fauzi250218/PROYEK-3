<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\Murid;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ChatController extends Controller
{
    // Open atau buat room private — cari pair (id+role) tanpa tergantung urutan
    public function openRoom(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'user_role' => 'required|in:murid,guru',
            'peer_id' => 'required|integer',
            'peer_role' => 'required|in:murid,guru'
        ]);

        $userId = (int)$request->user_id;
        $userRole = $request->user_role;
        $peerId = (int)$request->peer_id;
        $peerRole = $request->peer_role;

        // Cari room baik (user,peer) atau (peer,user)
        $room = ChatRoom::where(function($q) use ($userId, $userRole, $peerId, $peerRole) {
            $q->where([
                ['user_one_id', $userId],
                ['user_one_role', $userRole],
                ['user_two_id', $peerId],
                ['user_two_role', $peerRole],
            ]);
        })->orWhere(function($q) use ($userId, $userRole, $peerId, $peerRole) {
            $q->where([
                ['user_one_id', $peerId],
                ['user_one_role', $peerRole],
                ['user_two_id', $userId],
                ['user_two_role', $userRole],
            ]);
        })->first();

        if (!$room) {
            // Buat dengan urutan natural (user sebagai user_one jika tidak ada pasangan)
            // Tapi kita tidak memaksa urutan numeric — simpan sesuai permintaan untuk memudahkan pengecekan
            $room = ChatRoom::create([
                'user_one_id' => $userId,
                'user_one_role' => $userRole,
                'user_two_id' => $peerId,
                'user_two_role' => $peerRole,
            ]);
        }

        return response()->json([
            'status' => true,
            'room' => $room
        ]);
    }

    // Ambil semua chat rooms untuk user tertentu
    public function getRooms(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'user_role' => 'required|in:murid,guru'
        ]);

        $userId = (int)$request->user_id;
        $userRole = $request->user_role;

        // Pastikan hanya rooms di mana (id+role) cocok
        $rooms = ChatRoom::where(function($q) use ($userId, $userRole) {
            $q->where('user_one_id', $userId)->where('user_one_role', $userRole)
              ->orWhere(function($q2) use ($userId, $userRole) {
                  $q2->where('user_two_id', $userId)->where('user_two_role', $userRole);
              });
        })->with(['messages' => function($q){
            $q->latest();
        }])->get();

        $result = $rooms->map(function($room) use ($userId, $userRole) {
            // Tentukan sisi mana current user, dan siapa peer (dengan role)
            if ($room->user_one_id == $userId && $room->user_one_role == $userRole) {
                $peerId = $room->user_two_id;
                $peerRole = $room->user_two_role;
            } elseif ($room->user_two_id == $userId && $room->user_two_role == $userRole) {
                $peerId = $room->user_one_id;
                $peerRole = $room->user_one_role;
            } else {
                // safety - seharusnya tidak terjadi karena query di atas
                return null;
            }

            $peerName = 'No Name';
            $peerPhoto = null;

            if ($peerRole === 'murid') {
                $peer = Murid::find($peerId);
                if ($peer) {
                    $peerName = $peer->nama ?? $peerName;
                    $peerPhoto = $peer->foto_profil ? $this->formatFoto($peer->foto_profil) : null;
                }
            } else { // guru
                $guru = Guru::find($peerId);
                if ($guru) {
                    $user = $guru->user_id ? User::find($guru->user_id) : null;
                    // prefer nama dari user (jika tersedia), fallback nama guru jika ada
                    $peerName = $user ? ($user->name ?? $peerName) : ($guru->nama ?? $peerName);
                    $peerPhoto = $guru->foto_profil ? $this->formatFoto($guru->foto_profil) : ($user && $user->foto_profil ? $this->formatFoto($user->foto_profil) : null);
                }
            }

            $lastMessage = $room->messages->first();

            // unread: pesan yang read_at = null dan bukan dikirim oleh current (cek id+role)
            $unreadCount = $room->messages
                ->whereNull('read_at')
                ->filter(function($m) use ($userId, $userRole) {
                    return !($m->sender_id == $userId && $m->sender_role == $userRole);
                })->count();

            return [
                'id' => $room->id,
                'peerId' => $peerId,
                'peerRole' => $peerRole,
                'peerName' => $peerName,
                'peerPhoto' => $peerPhoto,
                'lastMessage' => $lastMessage ? $lastMessage->message : '',
                'lastMessageSenderId' => $lastMessage ? $lastMessage->sender_id : null,
                'lastMessageSenderRole' => $lastMessage ? $lastMessage->sender_role : null,
                'unreadCount' => $unreadCount,
            ];
        })->filter()->values(); // hilangkan null jika ada

        return response()->json([
            'status' => true,
            'rooms' => $result
        ]);
    }

    // Ambil pesan di room tertentu (mark as read untuk penerima dan sertakan sender info)
    public function getMessages($room_id, Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'user_role' => 'required|in:murid,guru'
        ]);

        $userId = (int)$request->user_id;
        $userRole = $request->user_role;

        $room = ChatRoom::where('id', $room_id)
            ->where(function($q) use ($userId, $userRole) {
                $q->where(function($qq) use ($userId, $userRole) {
                    $qq->where('user_one_id', $userId)->where('user_one_role', $userRole);
                })->orWhere(function($qq) use ($userId, $userRole) {
                    $qq->where('user_two_id', $userId)->where('user_two_role', $userRole);
                });
            })->first();

        if (!$room) {
            return response()->json([
                'status' => false,
                'messages' => [],
                'message' => 'Room tidak ditemukan untuk user ini'
            ], 403);
        }

        // Mark as read: hanya pesan yang belum terbaca dan bukan dikirim oleh current user
        ChatMessage::where('chat_room_id', $room_id)
            ->whereNull('read_at')
            ->where(function($q) use ($userId, $userRole) {
                $q->where('sender_id', '!=', $userId)
                  ->orWhere('sender_role', '!=', $userRole);
            })
            ->update(['read_at' => Carbon::now()]);

        $messages = ChatMessage::where('chat_room_id', $room_id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($m) {
                $senderPhoto = null;
                if ($m->sender_role == 'murid') {
                    $s = Murid::find($m->sender_id);
                    if ($s && $s->foto_profil) $senderPhoto = $this->formatFoto($s->foto_profil);
                } else {
                    $g = Guru::find($m->sender_id);
                    if ($g) {
                        if ($g->foto_profil) $senderPhoto = $this->formatFoto($g->foto_profil);
                        else if ($g->user_id) {
                            $u = User::find($g->user_id);
                            if ($u && $u->foto_profil) $senderPhoto = $this->formatFoto($u->foto_profil);
                        }
                    }
                }

                return [
                    'id' => $m->id,
                    'chat_room_id' => $m->chat_room_id,
                    'message' => $m->message,
                    'sender_id' => $m->sender_id,
                    'sender_role' => $m->sender_role,
                    'sender_photo' => $senderPhoto,
                    'read_at' => $m->read_at,
                    'created_at' => $m->created_at,
                ];
            });

        return response()->json([
            'status' => true,
            'messages' => $messages
        ]);
    }

    // Kirim pesan (cek dulu apakah sender participant room)
    public function sendMessage(Request $request)
    {
        $request->validate([
            'room_id' => 'required|integer',
            'sender_id' => 'required|integer',
            'sender_role' => 'required|in:murid,guru',
            'message' => 'required|string'
        ]);

        $room = ChatRoom::find($request->room_id);
        if (!$room) {
            return response()->json(['status' => false, 'message' => 'Room tidak ditemukan'], 404);
        }

        // Pastikan sender memang salah satu partisipan (cek id+role cocok)
        $isParticipant = (
            ($room->user_one_id == $request->sender_id && $room->user_one_role == $request->sender_role) ||
            ($room->user_two_id == $request->sender_id && $room->user_two_role == $request->sender_role)
        );

        if (!$isParticipant) {
            return response()->json(['status' => false, 'message' => 'Sender bukan partisipan room'], 403);
        }

        $msg = ChatMessage::create([
            'chat_room_id' => $request->room_id,
            'sender_id'    => $request->sender_id,
            'sender_role'  => $request->sender_role,
            'message'      => $request->message
        ]);

        // Kembalikan info message lengkap (termasuk sender info)
        $senderPhoto = null;
        if ($msg->sender_role == 'murid') {
            $s = Murid::find($msg->sender_id);
            if ($s && $s->foto_profil) $senderPhoto = $this->formatFoto($s->foto_profil);
        } else {
            $g = Guru::find($msg->sender_id);
            if ($g) {
                if ($g->foto_profil) $senderPhoto = $this->formatFoto($g->foto_profil);
                else if ($g->user_id) {
                    $u = User::find($g->user_id);
                    if ($u && $u->foto_profil) $senderPhoto = $this->formatFoto($u->foto_profil);
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => [
                'id' => $msg->id,
                'chat_room_id' => $msg->chat_room_id,
                'message' => $msg->message,
                'sender_id' => $msg->sender_id,
                'sender_role' => $msg->sender_role,
                'sender_photo' => $senderPhoto,
                'created_at' => $msg->created_at,
            ]
        ]);
    }

    // helper format foto -> return full URL bila perlu
    private function formatFoto($foto)
    {
        if (!$foto) return null;
        if (str_starts_with($foto, 'http')) return $foto;
        return asset("storage/{$foto}");
    }
}
