<?php

namespace App\Http\Livewire\Ujian;

use App\Models\Kelas;
use App\Models\Ulangan;
use Livewire\Component;

class Add extends Component
{
  public $kelas = [], $judul, $ulangan_id, $tgl_awal, $tgl_akhir, $waktu;
  public $data;

  public function mount($id = null)
  {
    $data[] = [
      'no' => '', 'soal' => '', 'jawaban' => '', 'jwbA' => '', 'jwbB' => '', 'jwbC' => '', 'jwbD' => ''
    ];
    $this->data = $data;

    if ($id) {
      $db = Ulangan::find($id);
      $this->ulangan_id = $db->id;
      $this->judul = $db->judul;
      $this->tgl_awal = $db->tanggal_mulai;
      $this->tgl_akhir = $db->tanggal_akhir;
      $this->waktu = $db->waktu;
      $this->data = json_decode($db->soal, true);
      $this->kelas = json_decode($db->kelas_id, true);
    }
  }

  public function tambahSoal()
  {
    $this->data[] = ['no' => '', 'soal' => '', 'jawaban' => '', 'jwbA' => '', 'jwbB' => '', 'jwbC' => '', 'jwbD' => ''];
  }

  public function hapusSoal($key)
  {
    unset($this->data[$key]);
    array_values($this->data);
  }

  public function save()
  {
    $data = [
      'tanggal_mulai' => $this->tgl_awal,
      'tanggal_akhir' => $this->tgl_akhir,
      'waktu' => $this->waktu,
      'judul' => $this->judul,
      'kelas_id' => json_encode($this->kelas),
      'soal' => json_encode($this->data)
    ];
    Ulangan::updateOrCreate(['id' => $this->ulangan_id], $data);
    $this->emit('alert', ['type' => 'success', 'message' => 'Data Berhasil Simpan', 'reload' => true]);
  }

  public function render()
  {
    $title = 'Tambah Data Ujian';
    $data = [
      'arr_kelas' => Kelas::all(),
      // 'materi' => $this->data(),
    ];
    // dd($data);
    return view('livewire.ujian.add', $data)->extends(env('APP_LAYOUT'), ['title' => $title])->section('content');
  }
}
