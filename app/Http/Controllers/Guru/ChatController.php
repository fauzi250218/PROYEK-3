<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\Guru;
use App\Models\Murid;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Guru melihat daftar murid berdasarkan kelas binaan.
     */
    public function listMurid($guru_id)
    {
        $guru = Guru::with('kelasBinaan.murids')->find($guru_id);

        if (! $guru || ! $guru->kelasBinaan) {
            return response()->json([
                'status' => false,
                'message' => 'Guru tidak memiliki kelas binaan'
            ]);
        }

        return response()->json([
            'status' => true,
            'murid' => $guru->kelasBinaan->murids
        ]);
    }

    /**
     * Guru membuka / membuat room chat dengan murid.
     */
    public function openRoom(Request $request)
    {
        $request->validate([
            'guru_id' => 'required',
            'murid_id' => 'required'
        ]);

        // cari room yg sudah ada
        $room = ChatRoom::where(function($q) use ($request) {
            $q->where('user_one_id', $request->guru_id)
              ->where('user_one_role', 'guru')
              ->where('user_two_id', $request->murid_id)
              ->where('user_two_role', 'murid');
        })->orWhere(function($q) use ($request) {
            $q->where('user_one_id', $request->murid_id)
              ->where('user_one_role', 'murid')
              ->where('user_two_id', $request->guru_id)
              ->where('user_two_role', 'guru');
        })->first();

        if (! $room) {
            $room = ChatRoom::create([
                'user_one_id'   => $request->guru_id,
                'user_one_role' => 'guru',
                'user_two_id'   => $request->murid_id,
                'user_two_role' => 'murid'
            ]);
        }

        return response()->json([
            'status' => true,
            'room' => $room
        ]);
    }

    /**
     * Guru mengambil pesan dari sebuah room.
     */
    public function getMessages($room_id)
    {
        $messages = ChatMessage::where('chat_room_id', $room_id)->get();

        return response()->json([
            'status' => true,
            'messages' => $messages
        ]);
    }

    /**
     * Guru mengirim pesan.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'room_id' => 'required',
            'guru_id' => 'required',
            'message' => 'required'
        ]);

        $msg = ChatMessage::create([
            'chat_room_id' => $request->room_id,
            'sender_id'    => $request->guru_id,
            'sender_role'  => 'guru',
            'message'      => $request->message,
        ]);

        return response()->json([
            'status' => true,
            'message' => $msg
        ]);
    }
}
