<?php

namespace App\Http\Controllers;

use App\Models\Jawaban;
use App\Models\Ulangan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
    $n = "\n";
    $balasan = 'Assalamualaikum, Silahkan kirim NISN-nya dengan format NISN:NOMOR NISN, ' . $n . ' Ketik => NISN:123456789';

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
      $balasan = 'Terima Kasih ' . Str::upper($user->name) . ' Sudah berpatisipasi, untuk mengerjakan tugas' . $n;
      $balasan .= 'Ketik => MULAI:' . $token . '';

      $reply['data'][] = [
        'message' => $balasan,
      ];
      return $reply;
    } else {
      $reply['data'][] = [
        'message' => "NISN Tidak ditemukan atau format penulisan salah",
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
      $selesai = 'Kirim jawaban dengan format :, ' . $n;
      $jawaban = 'JAWABAN:' . $token . '' . $n;
      $jawaban .= '1: ' . $n;
      $jawaban .= '2: ' . $n;
      $jawaban .= '3: ' . $n;
      $jawaban .= '4: ' . $n;
      $jawaban .= '5: ' . $n;
      $reply['data'][] = [
        'message' => $atas . $arr . $selesai . $jawaban,
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

      $cek2 = $jawab->map(function ($item) {
        $res = Str::of($item)->after(':')->trim();
        $res = Str::length($res);
        return $res;
      })->filter(function ($item, $key) {
        return $item > 1;
      })->count();

      if ($cek2) {
        $reply['data'][] = [
          'message' => 'Format Jawaban Salah, Hanya Opsi (A / B / C / D) yang diperbolehkan ' . $n . 'contoh => 1:A',
        ];
        return $reply;
      }

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

      $jawab = json_decode($cekToken->jawaban ?? [], true);
      $total = 0;
      $benar = [];

      foreach ($jawab as $no => $jwb) {
        $ujian = $cekToken->ulangan->soal;
        $ujian = json_decode($ujian, true);
        $total = count($ujian);
        $nilai = [];
        foreach ($ujian as $k => $v) {
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
      $simpan_nilai = ['benar' => $benar, 'nilai' => $nilai];
      $cekToken->nilai = json_encode($simpan_nilai);
      $cekToken->save();

      $balasan = 'Terima Kasih ' . Str::upper($cekToken->user->name) . ' sudah mengerjakan tugas Bimbingan TIK, jawaban anda sudah disimpan' . $n;
      $balasan .= 'ketik => NILAI:' . $token . ' untuk melihat nilai';

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
      $n = "\n";
      $nilai = json_decode($cekToken->nilai, true);
      $balasan = 'Jumlah Benar = ' . $nilai['benar'] . $n;
      $balasan .= 'Total Nilai = ' . $nilai['nilai'] . $n . $n;
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
    $user_sudah = User::where('kelas_id', $kelas)->whereNotIn('nisn', [7, 8, 9])->whereHas('userjawaban', function ($q) {
      $q->whereNotNull('jawaban');
    });

    $id_sudah = collect($user_sudah->get('id')->toArray())->map(function ($item, $key) {
      return $item['id'];
    })->toArray();

    $user_belum = User::where('kelas_id', $kelas)->whereNotIn('nisn', [7, 8, 9])->whereNotIn('id', $id_sudah)->get();

    $sudah = $user_sudah->count() > 0 ? '' : '-- KOSONG --' . $n;
    foreach ($user_sudah->get() as $key => $value) {
      $sudah .= $key + 1 . '. ' . $value['name'] . $n;
    }

    $belum = $user_belum->count() > 0 ? '' : '-- KOSONG --' . $n;
    foreach ($user_belum as $key => $value) {
      $belum .= $key + 1 . '. ' . $value['name'] . $n;
    }

    $head_s = 'Yang SUDAH mengerjakan tugas minggu ini';
    $head_b = 'Yang BELUM mengerjakan tugas minggu ini';

    $reply['data'][] = [
      'message' => $head_s . $n . $sudah . $n . $head_b . $n . $belum,
    ];
    return $reply;
  }
}
