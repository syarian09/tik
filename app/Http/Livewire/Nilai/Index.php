<?php

namespace App\Http\Livewire\Nilai;

use Livewire\Component;

class Index extends Component
{
    public function render()
	{
		$title = 'Data Nilai';
		$data = [
			
		];
		// dd($data);
		return view('livewire.nilai.index', $data)->extends(env('APP_LAYOUT'), ['title' => $title])->section('content');
	}
}
