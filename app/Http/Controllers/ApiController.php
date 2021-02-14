<?php

namespace App\Http\Controllers;

use App\Models\Jawaban;
use App\Models\Ulangan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiController extends Controller
{
  public function __construct(Request $request)
  {
    $this->sender = $request->input('senderName');
    $this->pesan = $request->input('senderMessage');
  }

  public function ulangan()
  {
    $balasan = 'Assalamualaikum ' . $this->sender . ' Silahkan kirim NISN anda sebelum mengerjakan tugas dengan format NISN:,
    contoh (NISN:123456789)';

    $reply['data'][] = [
      'message' => $balasan,
    ];
    return $reply;
  }

  public function terimaNISN()
  {
    $nisn = Str::of($this->pesan)->after(':')->ltrim()->rtrim();
    $db = User::where('nisn', $nisn)->first();
    if ($db) {
      $cekJwb = Jawaban::where('user_id', $db->id)->first();
      $cekUjian = Ulangan::where('aktif', 1)->first();
      $cekJwb->token = $token;
      $cekJwb->ulangan_id = $cekUjian->id;
      $cekJwb->save();

      if ($cekJwb && $cekJwb->jawaban) {
        $reply['data'][] = [
          'message' => 'Anda sudah mengerjakan tugas dengan token ' . $cekJwb->token . ', terima kasih',
        ];
        return $reply;
      }

      if ($cekJwb && $cekJwb->token) {
        $token = $cekJwb->token;
      }
      $n = "\n";
      $balasan = 'Terima Kasih ' . $db->nama . ' Sudah berpatisipasi, ini detail data anda : ' . $n;
      $balasan .= 'Nama : ' . $db->nama . $n . 'NISN : ' . $db->nisn . $n . 'Kelas : ' . $db->nama_kelas . $n . 'Token : ' . $token;
      $balasan .= 'Simpan TOKEN sebaik mungkin, ketik MULAI bila anda sudah siap mengerjakan tugas';
      $reply['data'][] = [
        'message' => $balasan,
      ];
      return $reply;
    } else {
      $reply['data'][] = [
        'message' => "Maaf data anda tidak ditemukan atau format penulisan salah",
      ];
      return $reply;
    }
  }
  public function terimaMulai()
  {
    # code...
  }
}
