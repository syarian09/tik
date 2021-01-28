<?php

namespace App\Http\Livewire\Materi;

use Livewire\Component;
use Livewire\WithPagination;
use Auth;
use Storage;
use App\Models\User;
use App\Models\Materi;
use App\Models\Kelas;

class Index extends Component
{
  use WithPagination;
  public $nama_kelas, $name, $judul, $kelas_id;
  public $chmateri_id = [], $selectAll = false;

  protected $listeners = ['baca' => 'baca'];

  public function mount()
  {
    $user = User::where('id', Auth::user()->id)->first();
    $this->nama_kelas = $user->nama_kelas;
    $this->name = $user->name;
    $this->kelas_id = $user->kelas_id;
  }

  public function delete()
  {
    if (!empty($this->chmateri_id)) {
      $_photo = Materi::whereIn('id', $this->chmateri_id)->get()->pluck('photo_ori')->filter()->all();
      Storage::delete($_photo);
      Materi::destroy($this->chmateri_id);
      $this->emit('alert', ['type' => 'success', 'message' => 'Data Berhasil Dihapus']);
      $this->chmateri_id = [];
      $this->selectAll = false;
    } else {
      $this->emit('alert', ['type' => 'error', 'message' => 'Data Tidak Ditemukan']);
    }
  }

  public function updatedSelectAll($value)
  {
    if ($value) {
      $this->chmateri_id = $this->data()->pluck('id');
    } else {
      $this->chmateri_id = [];
    }
  }

  public function baca($id)
  {
    $materi = Materi::find($id);
    $arr_id = $materi->baca ? json_decode($materi->baca, true) : [];
    if (!in_array(Auth::user()->id, $arr_id)) {
      array_push($arr_id, Auth::user()->id);
      $materi->baca = json_encode($arr_id);
      $materi->save();
    }
  }

  public function data()
  {
    $perPage = 10;
    $materi = Materi::orderBy('judul');
    if ($this->judul) $materi = $materi->where('judul', 'like', '%' . $this->judul . '%');
    if ($this->kelas_id) $materi = $materi->whereJsonContains('kelas_id', $this->kelas_id);
    return $materi->paginate($perPage);
  }

  public function render()
  {
    $title = 'Materi Pelajaran ' . $this->nama_kelas;
    $data = [
      'arr_kelas' => Kelas::pluck('kelas', 'id'),
      'materi' => $this->data(),
    ];
    // dd($data);
    $view = 'livewire.materi.siswa';
    if (Auth::user()->level == 9) {
      $view = 'livewire.materi.index';
    }
    return view($view, $data)->extends(env('APP_LAYOUT'), ['title' => $title])->section('content');
  }
}
