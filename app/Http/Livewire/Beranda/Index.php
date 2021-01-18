<?php

namespace App\Http\Livewire\Beranda;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $title = 'Beranda';
        $data = [];
        return view('livewire.beranda.index', $data)->extends(env('APP_LAYOUT'), ['title' => $title])->section('content');
    }
}
