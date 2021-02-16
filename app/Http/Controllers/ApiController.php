<?php

namespace App\Http\Controllers;

use App\Models\Jawaban;
use App\Models\Kelas;
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
    $balasan = 'Assalamualaikum ' . $this->sender . ' Silahkan kirim NISN anda sebelum mengerjakan tugas Bimbingan TIK dengan format NISN:, contoh NISN:123456789';

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
      $balasan .= 'Simpan TOKEN sebaik mungkin, ketik MULAI:TOKEN bila sudah siap mengerjakan tugas contoh MULAI:123456';
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
      $atas = 'Pilihlah salah satu jawaban a, b, c atau d yang paling benar !' . $n . $n;
      $selesai = 'ketik SELESAI:TOKEN bila sudah selesai mengerjakan, contoh SELESAI:123456';
      $reply['data'][] = [
        'message' => $atas . $arr . $selesai,
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
      $balasan = 'Silahkan kirim jawaban, dengan format : ' . $n;
      $balasan .= 'JAWABAN:TOKEN' . $n;
      $balasan .= '1: contoh 1:A' . $n;
      $balasan .= '2: ' . $n;
      $balasan .= '3: ' . $n;
      $balasan .= '4: ' . $n;
      $balasan .= '5: ' . $n;

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
      $jawab = collect($pesan)->filter(function ($item, $key) {
        return $key != 0 ? $item : false;
      })->filter();

      $cek = $jawab->map(function ($item) {
        $res = Str::contains($item, ':');
        return $res;
      })->toArray();

      if (in_array(false, $cek)) {
        $reply['data'][] = [
          'message' => 'Format penulisan SALAH, wajib menggunakan TITIK DUA (:)',
        ];
        return $reply;
      }

      $arr = [];
      foreach ($jawab as $row) {
        $val = Str::of($row)->explode(':');
        $no = trim($val[0]);
        $jwb = strtoupper(trim($val[1]));
        $arr[$no] = $jwb;
      }
      $jml = count($arr);
      $ulangan = collect(json_decode($cekToken->ulangan->soal))->count();
      if ($jml != $ulangan) {
        $reply['data'][] = [
          'message' => 'Jawaban anda tidak lengkap, silahkan periksa kembali jawaban anda',
        ];
        return $reply;
      }
      $arr = collect($arr)->toJson();
      $cekToken->jawaban = $arr;
      $cekToken->hp = $this->sender;
      $cekToken->save();

      $balasan = 'Terima Kasih ' . Str::upper($cekToken->user->name) . ' sudah mengerjakan tugas Bimbingan TIK, jawaban anda sudah disimpan' . $n;
      $balasan .= 'ketik NILAI:TOKEN contoh NILAI:123456 untuk melihat nilai';

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

  public function laporan()
  {
    $n = "\n";
    $kelas = [
      1 => Str::contains($this->pesan, ['7.1', '71', 'VII.1']),
      2 => Str::contains($this->pesan, ['7.2', '72', 'VII.2']),
      3 => Str::contains($this->pesan, ['7.3', '73', 'VII.3']),
      4 => Str::contains($this->pesan, ['8.1', '81', 'VIII.1']),
      5 => Str::contains($this->pesan, ['8.2', '82', 'VIII.2']),
      6 => Str::contains($this->pesan, ['9.1', '91', 'IX.1']),
      7 => Str::contains($this->pesan, ['9.2', '92', 'IX.2']),
    ];

    $kelas = collect($kelas)->filter(function ($item, $key) {
      return $item == true;
    })->keys()->implode('');

    if ($kelas == '') {
      $reply['data'][] = [
        'message' => "Maaf Kelas tidak ditemukan",
      ];
      return $reply;
    }
    $user_sudah = User::where('kelas_id', $kelas)->whereHas('userjawaban')->get();
    $user_belum = User::where('kelas_id', $kelas)->whereDoesntHave('userjawaban')->get();

    $sudah = '';
    foreach ($user_sudah as $key => $value) {
      $sudah .= $key + 1 . '. ' . $value['name'] . $n;
    }

    $belum = '';
    foreach ($user_belum as $key => $value) {
      $belum .= $key + 1 . '. ' . $value['name'] . $n;
    }

    $head_s = 'Yang SUDAH mengerjakan tugas minggu ini';
    $head_b = 'Yang BELUM mengerjakan tugas minggu ini';

    $reply['data'][] = [
      'message' => $head_s . $n . $n . $sudah . $n . $head_b . $belum,
    ];
    return $reply;
  }
}
