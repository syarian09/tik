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
      $cekUjian = Ulangan::whereJsonContains('kelas_id', $db->kelas_id)->where('aktif', 1)->first();
      $cekJwb = Jawaban::where('ulangan_id', $cekUjian->id)->where('user_id', $db->id)->first();
      if ($cekUjian ==  null) {
        $reply['data'][] = [
          'message' => 'Maaf tidak ada Tugas / Ujian yang aktif hari ini',
        ];
        return $reply;
      }
      if ($cekJwb && $cekJwb->jawaban) {
        $reply['data'][] = [
          'message' => 'Anda sudah mengerjakan tugas dengan token ' . $cekJwb->token . ', terima kasih',
        ];
        return $reply;
      }

      $token = $cekJwb->token ? $cekJwb->token : $token;
      $data = [
        'ulangan_id' => $cekUjian->id,
        'user_id' => $db->user_id,
        'token' => $token,
      ];
      Jawaban::updateOrCreate(['id' => $cekJwb->id], $data);
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
