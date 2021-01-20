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
		$users = User::orderBy('kelas_id')->orderBy('name')->where('level','!=',9)->where(function($q){
			$q->where('photo', null)->orWhere('hp', null);
		});
		if (Auth::user()->level == 9) {
			if ($this->kelas_id) $users = $users->where('kelas_id', $this->kelas_id);
		}else{
			$users = $users->where('kelas_id', Auth::user()->kelas_id);
		}
		$users = $users->Paginate(10);

		return $users;
	}

	public function belumbacamateri()
	{
		// $materis = Materi::orderBy('judul');
		// $users = User::orderBy('kelas_id')->orderBy('name')->where('level','!=',9);
		// if (Auth::user()->level == 9) {
		// 	$users = $users->where('kelas_id', $this->kelas_id);
		// 	$materis = $materis->whereJsonContains('kelas_id', $this->kelas_id);
		// }else{
		// 	$users = $users->where('kelas_id', Auth::user()->kelas_id);
		// 	$materis = $materis->whereJsonContains('kelas_id', strval(Auth::user()->kelas_id));
		// }
		
		// $arr = [];
		// foreach ($users->get() as $row) {
		// 	$arr[] = $materis->whereJsonContains('baca', $row->id)->get();
		// }
		// return $arr;
	}

	public function render()
	{
		$title = 'Beranda';
		$data = [
			'belumlogin' => $this->belumlogin(),
			'arr_kelas' => Kelas::pluck('kelas', 'id'),
			// 'belumbaca' => $this->belumbacamateri(),
		];
		// dd($data);
		return view('livewire.beranda.index', $data)->extends(env('APP_LAYOUT'), ['title' => $title])->section('content');
	}
}
