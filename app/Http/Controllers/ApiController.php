<?php

namespace App\Http\Controllers;

use App\Models\Jawaban;
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
    $token = collect(range(0, 9))->random(4);
    $token =  collect($token)->implode('');
    if ($db) {
      $cekJwb = Jawaban::where('user_id', $db->id)->first();
      if ($cekJwb && $cekJwb->jawaban) {
        $reply['data'][] = [
          'message' => 'Anda sudah mengerjakan tugas, terima kasih',
        ];
        return $reply;
      }
      $balasan = 'Terima Kasih ' . $db->nama . ' Sudah berpatisipasi, ini detail data anda : "\n" Hai';
      $reply['data'][] = [
        'message' => $balasan,
      ];
      return $reply;

      // $nama = $db->nama;
      // $nisn = $db->nisn;
      // $kelas = $db->nama_kelas;
      // $token = Str::random(4);
    } else {
      $balasan = 'Terima Kasih tidak ada Sudah berpatisipasi, ini detail data anda : "\n" Hai';
      $reply['data'][] = [
        'message' => $balasan,
      ];
      return $reply;
    }
  }
}
