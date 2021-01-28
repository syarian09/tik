<?php

namespace App\Http\Livewire\Beranda;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;
use Storage;
use Hash;
use App\Models\User;
use App\Models\Materi;
use App\Models\Kelas;

class Index extends Component
{
  use WithPagination;
  public $kelas_id, $tbl;

  public function updatingKelasId()
  {
    $this->resetPage();
  }

  public function paginationView()
  {
    return 'layouts.page';
  }

  public function tbl_name($tbl)
  {
    $this->tbl = $tbl;
  }

  public function belumlogin()
  {
    if (Auth::user()->level == 9) {
      $users = User::orderBy('kelas_id')->orderBy('name')->where('level', '!=', 9)->where(function ($q) {
        $q->where('photo', null)->orWhere('hp', null);
      });
      if ($this->kelas_id) $users = $users->where('kelas_id', $this->kelas_id);
      $users = $users->Paginate(10);
    } else {
      $users = User::where('id', Auth::user()->id)->where(function ($q) {
        $q->where('photo', null)->orWhere('hp', null);
      });
      $users = $users->count();
    }
    return $users;
  }

  public function updateProfil()
  {
    # code...
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

  public function materi()
  {
    $materis = Materi::orderBy('id', 'desc')->orderBy('judul')
      ->whereJsonContains('kelas_id', strval(Auth::user()->kelas_id))->take(10);

    return $materis->get();
  }

  public function render()
  {
    $title = 'Beranda';
    $data = [
      'belumlogin' => $this->belumlogin(),
      'arr_kelas' => Kelas::pluck('kelas', 'id'),
      'materi' => $this->materi(),
    ];

    $view = 'livewire.beranda.index';
    if (Auth::user()->level == 9) {
      $view = 'livewire.beranda.admin';
    }
    return view($view, $data)->extends(env('APP_LAYOUT'), ['title' => $title])->section('content');
  }
}
