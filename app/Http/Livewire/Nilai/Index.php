<?php

namespace App\Http\Livewire\Nilai;

use App\Models\User;
use Livewire\Component;

class Index extends Component
{
  public function data()
  {
    $db = User::all();
    dd($db);
  }

  public function render()
  {
    $title = 'Data Nilai';
    $data = [
      'data' => $this->data(),
    ];
    // dd($data);
    return view('livewire.nilai.index', $data)->extends(env('APP_LAYOUT'), ['title' => $title])->section('content');
  }
}
