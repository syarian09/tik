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
    $balasan = 'Assalamualaikum ' . $this->sender . ' Silahkan kirim NISN anda sebelum mengerjakan tugas Bimbingan TIK dengan format NISN:,
    contoh (NISN:123456789)';

    $reply['data'][] = [
      'message' => $balasan,
    ];
    return $reply;
  }

  public function terimaNISN()
  {
    $nisn = Str::of($this->pesan)->after(':')->trim();
    $user = User::where('nisn', $nisn)->first();
    if ($user) {
      $cekUjian = Ulangan::whereJsonContains('kelas_id', $user->kelas_id)->where('aktif', 1)->first();
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

      $token = $this->generateToken();
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
      $balasan .= 'Simpan TOKEN sebaik mungkin, ketik MULAI:TOKEN bila sudah siap mengerjakan tugas contoh (MULAI:123456)';
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
    $token = Str::of($this->pesan)->after(':')->trim();
    $cekToken = Jawaban::where('token', $token)->with('user')->with('ulangan')->first();
    if ($cekToken) {
      if ($cekToken->ulangan->aktif == 0) {
        $reply['data'][] = [
          'message' => 'Maaf waktu pengerjaan tugas dengan token ' . $cekToken->token . ', sudah selesai, terima kasih',
        ];
        return $reply;
      }
      if ($cekToken->jawaban) {
        $reply['data'][] = [
          'message' => 'Anda sudah mengerjakan tugas dengan token ' . $cekToken->token . ', terima kasih',
        ];
        return $reply;
      }
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
      $selesai = 'ketik SELESAI:TOKEN bila sudah selesai mengerjakan, contoh (SELESAI:123456)';
      $reply['data'][] = [
        'message' => $arr . $selesai,
      ];
      return $reply;
    } else {
      $reply['data'][] = [
        'message' => "Maaf TOKEN tidak ditemukan atau format penulisan salah",
      ];
      return $reply;
    }
  }

  public function terimaSelesai()
  {
    $token = Str::of($this->pesan)->after(':')->trim();
    $cekToken = Jawaban::where('token', $token)->with('user')->with('ulangan')->first();

    if ($cekToken) {
      if ($cekToken->ulangan->aktif == 0) {
        $reply['data'][] = [
          'message' => 'Maaf waktu pengerjaan tugas dengan token ' . $cekToken->token . ', sudah selesai, terima kasih',
        ];
        return $reply;
      }
      if ($cekToken->jawaban) {
        $reply['data'][] = [
          'message' => 'Anda sudah mengerjakan tugas dengan token ' . $cekToken->token . ', terima kasih',
        ];
        return $reply;
      }

      $n = "\n";
      $balasan = 'Silahkan kirim jawaban anda dengan format berikut : ' . $n;
      $balasan .= 'JAWABAN:TOKEN contoh (JAWABAN:123456)' . $n;
      $balasan .= '1:Jawaban Anda contoh (1:A)' . $n;
      $balasan .= '2:Jawaban Anda' . $n;
      $balasan .= '3:Jawaban Anda' . $n;
      $balasan .= '4:Jawaban Anda' . $n;
      $balasan .= '5:Jawaban Anda' . $n;

      $reply['data'][] = [
        'message' => $balasan,
      ];
      return $reply;
    } else {
      $reply['data'][] = [
        'message' => "Maaf TOKEN tidak ditemukan atau format penulisan salah",
      ];
      return $reply;
    }
  }

  public function terimaJawaban()
  {
    $n = "\n";
    $pesan = Str::of($this->pesan)->explode($n);
    $token = collect($pesan)->filter(function ($item, $key) {
      return $key == 0 ? $item : false;
    })->map(function ($item) {
      $i = Str::of($item)->after(':')->trim();
      return $i;
    })->implode('');

    $cekToken = Jawaban::where('token', $token)->with('user')->with('ulangan')->first();

    if ($cekToken) {
      if ($cekToken->ulangan->aktif == 0) {
        $reply['data'][] = [
          'message' => 'Maaf waktu pengerjaan tugas dengan token ' . $cekToken->token . ', sudah selesai, terima kasih',
        ];
        return $reply;
      }
      if ($cekToken->jawaban) {
        $reply['data'][] = [
          'message' => 'Anda sudah mengerjakan tugas dengan token ' . $cekToken->token . ', terima kasih',
        ];
        return $reply;
      }
      $ulangan = collect(json_decode($cekToken->ulangan->soal))->count();
      $jawab = collect($pesan)->filter(function ($item, $key) {
        return $key != 0 ? $item : false;
      })->filter();

      $arr = [];
      foreach ($jawab as $row) {
        $val = Str::of($row)->explode(':');
        $no = trim($val[0]);
        $jwb = trim($val[1]);
        $arr[$no] = $jwb;
      }

      $jml = count($arr);
      $ulangan = collect(json_decode($ulangan, true))->count();
      if ($jml != $ulangan) {
        $reply['data'][] = [
          'message' => 'Jawaban anda tidak lengkap, silahkan periksa kembali',
        ];
        return $reply;
      }
      $arr = collect($arr)->toJson();
      $cekToken->jawaban = $arr;
      $cekToken->save();

      $balasan = 'Terima Kasih ' . Str::upper($cekToken->user->name) . ' sudah mengerjakan tugas Bimbingan TIK, jawaban anda sudah disimpan' . $n;
      $balasan .= 'ketik NILAI:TOKEN contoh (NILAI:123456)untuk melihat nilai';

      $reply['data'][] = [
        'message' => $balasan,
      ];
      return $reply;
    } else {
      $reply['data'][] = [
        'message' => "Maaf TOKEN tidak ditemukan atau format penulisan salah",
      ];
      return $reply;
    }
  }
  public function terimaNilai()
  {
    $token = Str::of($this->pesan)->after(':')->trim();
    $cekToken = Jawaban::where('token', $token)->with('user')->with('ulangan')->first();
    if ($cekToken) {
      $jawab = json_decode($cekToken->jawaban ?? [], true);
      $total = 0;
      $benar = [];
      foreach ($jawab as $no => $jwb) {
        $ulangan = $cekToken->ulangan->soal;
        $ulangan = json_decode($ulangan, true);
        $total = count($ulangan);
        $nilai = [];
        foreach ($ulangan as $k => $v) {
          if ($no == $v['no'] && $jwb == $v['jawaban']) {
            $nilai[$k] = 1;
          } else {
            $nilai[$k] = 0;
          }
        }
        $benar[] = $nilai;
      }
      $benar = collect($benar)->flatten()->sum();
      $nilai = ($benar / $total) * 100;

      $cekToken->nilai = $nilai;
      $cekToken->save();

      $n = "\n";
      $balasan = 'Jumlah Benar = ' . $benar . $n;
      $balasan .= 'Jumlah Nilai = ' . $nilai . $n . $n;
      $selesai = "Jaga kesehatan ya..., Wassalamu'alaikum wr. wb";

      $reply['data'][] = [
        'message' => $balasan . $selesai,
      ];
      return $reply;
    } else {
      $reply['data'][] = [
        'message' => "Maaf TOKEN tidak ditemukan atau format penulisan salah",
      ];
      return $reply;
    }
  }

  function generateToken()
  {
    $number = mt_rand(100000, 999999);
    if ($this->tokenExits($number)) {
      return $this->generateToken();
    }
    return $number;
  }

  function tokenExits($number)
  {
    return Jawaban::whereToken($number)->exists();
  }
}
