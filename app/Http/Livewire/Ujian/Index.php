<?php

namespace App\Http\Livewire\Ujian;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kelas;
use App\Models\Ulangan;

class Index extends Component
{
  use WithPagination;
  public $nama_kelas, $judul, $kelas_id;
  public $ch_id = [], $selectAll = false;

  public function updatedSelectAll($value)
  {
    if ($value) {
      $this->ch_id = $this->data()->pluck('id');
    } else {
      $this->ch_id = [];
    }
  }

  public function delete()
  {
    if (!empty($this->ch_id)) {
      Ulangan::destroy($this->ch_id);
      $this->emit('alert', ['type' => 'success', 'message' => 'Data Berhasil Dihapus']);
      $this->ch_id = [];
      $this->selectAll = false;
    } else {
      $this->emit('alert', ['type' => 'error', 'message' => 'Data Tidak Ditemukan']);
    }
  }

  public function data()
  {
    $perPage = 10;
    $db = Ulangan::orderBy('judul');
    if ($this->judul) $db = $db->where('judul', 'like', '%' . $this->judul . '%');
    if ($this->kelas_id) $db = $db->whereJsonContains('kelas_id', $this->kelas_id);
    return $db->paginate($perPage);
  }

  public function render()
  {
    $title = 'Data Ujian';
    $data = [
      'arr_kelas' => Kelas::pluck('kelas', 'id'),
      'data' => $this->data(),
    ];
    // dd($data);
    return view('livewire.ujian.index', $data)->extends(env('APP_LAYOUT'), ['title' => $title])->section('content');
  }
}
