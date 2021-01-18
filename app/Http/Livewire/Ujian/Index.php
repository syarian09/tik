<?php

namespace App\Http\Livewire\Ujian;

use Livewire\Component;

class Index extends Component
{
    public function render()
	{
		$title = 'Data Ujian';
		$data = [
			
		];
		// dd($data);
		return view('livewire.ujian.index', $data)->extends(env('APP_LAYOUT'), ['title' => $title])->section('content');
	}
}
