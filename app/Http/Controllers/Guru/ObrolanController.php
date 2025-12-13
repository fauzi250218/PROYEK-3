<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Murid;
use App\Models\ChatRoom;
use Illuminate\Support\Facades\Auth;

class ObrolanController extends Controller
{
    /**
     * ==============================
     * LIST OBROLAN (PREVIEW + UNREAD)
     * ==============================
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil guru login
        $guru = Guru::where('user_id', $user->id)->firstOrFail();

        // Ambil kelas binaan
        $kelas = $guru->kelasBinaan;

        if (!$kelas) {
            return view('guru.obrolan.index', [
                'murids' => collect()
            ]);
        }

        // Ambil murid + mapping data chat
        $murids = $kelas->murids->map(function ($murid) use ($guru) {

            // Cari room (2 arah)
            $room = ChatRoom::where(function ($q) use ($guru, $murid) {
                $q->where([
                    ['user_one_id', $guru->id],
                    ['user_one_role', 'guru'],
                    ['user_two_id', $murid->id],
                    ['user_two_role', 'murid'],
                ]);
            })->orWhere(function ($q) use ($guru, $murid) {
                $q->where([
                    ['user_one_id', $murid->id],
                    ['user_one_role', 'murid'],
                    ['user_two_id', $guru->id],
                    ['user_two_role', 'guru'],
                ]);
            })->first();

            if ($room) {
                // Pesan terakhir
                $lastMessage = $room->messages()
                    ->orderByDesc('created_at')
                    ->first();

                // Hitung unread (pesan dari murid)
                $unread = $room->messages()
                    ->where('sender_role', 'murid')
                    ->whereNull('read_at')
                    ->count();

                $murid->last_message = $lastMessage?->message;
                $murid->last_sender  = $lastMessage?->sender_role;
                $murid->last_time    = $lastMessage?->created_at;
                $murid->unread_count = $unread;
            } else {
                // Default jika belum ada chat
                $murid->last_message = null;
                $murid->last_sender  = null;
                $murid->last_time    = null;
                $murid->unread_count = 0;
            }

            return $murid;
        });

        return view('guru.obrolan.index', compact('murids'));
    }

    /**
     * ==============================
     * HALAMAN CHAT DENGAN MURID
     * ==============================
     */
    public function chat($muridId)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->firstOrFail();

        $kelas = $guru->kelasBinaan;

        if (!$kelas) {
            abort(404, 'Guru tidak memiliki kelas binaan');
        }

        // 🔒 Pastikan murid adalah murid kelas binaan
        $murid = $kelas->murids()
            ->where('id', $muridId)
            ->firstOrFail();

        return view('guru.obrolan.chat', compact('murid', 'guru'));
    }
}
