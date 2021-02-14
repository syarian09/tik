<?php

namespace App\Http\Controllers;

use App\Models\Jawaban;
use App\Models\Ulangan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
    $user = User::where('nisn', $nisn)->first();
    if ($user) {
      $cekUjian = $user->ulangan()->whereJsonContains('kelas_id', $user->kelas_id)->where('aktif', 1)->first();
      $cekJwb = $user->jawaban();
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

      $token = $this->cekToken();
      $data = [
        'ulangan_id' => $cekUjian->id,
        'user_id' => $user->id,
        'token' => $cekJwb->token ?? $token,
      ];
      Jawaban::updateOrCreate(['id' => $cekJwb->id ?? null], $data);
      if ($cekJwb && $cekJwb->token) {
        $token = $cekJwb->token;
      }
      $n = "\n";
      $balasan = 'Terima Kasih ' . Str::upper($user->name) . ' Sudah berpatisipasi, ini detail data anda : ' . $n;
      $balasan .= 'Nama : ' . $user->name . $n . 'NISN : ' . $user->nisn . $n . 'Kelas : ' . $user->nama_kelas . $n . 'Token : ' . $token . $n;
      $balasan .= 'Simpan TOKEN sebaik mungkin, ketik TOKEN bila sudah siap mengerjakan tugas contoh (token:1234)';
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
  public function terimaToken()
  {
    $token = Str::of($this->pesan)->after(':')->ltrim()->rtrim();
    $cekToken = Jawaban::where('token', $token)->with('user')->with('ulangan')->first();
    if ($cekToken) {
      $soal = $cekToken->ulangan->soal;
      $soal = collect(json_decode($soal, true));
      $arr = '';
      $n = "\n";
      foreach ($soal as $row) {
        $soal = '[Nomor ' . $row['no'] . ']. ' . $row['soal'] . $n;
        $a = '[A] : ' . $row['jwbA'] . $n;
        $b = '[B] : ' . $row['jwbB'] . $n;
        $c = '[C] : ' . $row['jwbC'] . $n;
        $d = '[D] : ' . $row['jwbD'] . $n;
        $arr .= $soal . $a . $b . $c . $d . $n;
      }
      $reply['data'][] = [
        'message' => $arr,
      ];
      return $reply;
    } else {
      $reply['data'][] = [
        'message' => "Maaf TOKEN tidak ditemukan atau format penulisan salah",
      ];
      return $reply;
    }
  }

  public function cekToken()
  {
    $token = collect(range(1, 9))->random(4);
    $token = collect($token)->implode('');
    $cekToken = Jawaban::where('token', $token)->count();
    if ($cekToken > 0) {
      return new $token;
    } else {
      return $token;
    }
  }
}
