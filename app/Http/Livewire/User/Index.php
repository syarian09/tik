<?php

namespace App\Http\Livewire\User;

use Auth;
use Validator;
use Hash;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Kelas;


class Index extends Component
{
	use WithPagination;
	public $name, $nisn, $user_id, $kelas_id;
	public $chuser_id = [], $edit_data = false, $selectAll = false;

	public function updatingKelasId()
	{
		$this->resetPage();
	}

	public function paginationView()
   {
      return 'layouts.page';
	}
	
	public function resetFields()
	{
		$this->user_id = null;
		$this->name = null;
		$this->nisn = null;
		$this->kelas_id = null;
		$this->resetValidation();
	}

	public function store()
	{
		$validator = Validator::make(
			['name'  => $this->name, 'kelas_id'  => $this->kelas_id, 'nisn' => $this->nisn],
			[
				'name' => 'required|string',
				'kelas_id' => 'required|numeric',
				'nisn' => 'required|numeric',
			]
		);
		if ($validator->fails()) {
			$this->emit('alert', ['type' => 'error', 'message' => 'Gagal Disimpan']);
		} 
		$validator->validate();

		$data = [
			'name' => $this->name,
			'nisn' => $this->nisn,
			'kelas_id' => $this->kelas_id,
			'level' => 1,
			'password' => Hash::make($this->nisn),
		];
		
		$message = 'Data Berhasil Simpan dengan Password '. $this->nisn;
		if ($this->edit_data) {
			unset($data['password']);
			$message = 'Data Berhasil Diupdate';
		}

		User::updateOrCreate(['id' => $this->user_id], $data);
		$this->emit('alert', ['type' => 'success', 'message' => $message ]);
		$this->resetFields();
		$this->edit_data = false;
	}

	public function edit($id)
	{
		$user = User::find($id); 
		$this->user_id = $user->id;
		$this->name = $user->name;
		$this->nisn = $user->nisn;
		$this->kelas_id = $user->kelas_id;
		$this->edit_data = true;
	}

	public function delete()
	{
		if (!empty($this->chuser_id)) {
			User::destroy($this->chuser_id);
			$this->emit('alert', ['type' => 'success', 'message' => 'Data Berhasil Dihapus']);
			$this->chuser_id = [];
			$this->selectAll = false;
		} else {
			$this->emit('alert', ['type' => 'error', 'message' => 'Data Tidak Ditemukan']);
		}
	}

	public function resetPass()
	{
		if (!empty($this->chuser_id)) {
			foreach ($this->chuser_id as $id) {
				$user = User::find($id);
				$user->photo = null;
				$user->hp = null;
				$user->password = Hash::make($user->nisn);
				$user->save();
			}
			$this->chuser_id = [];
			$this->selectAll = false;
			$this->emit('alert', ['type' => 'success', 'message' => 'Password Berhasil Direset dengan password '.$user->nisn]);
		} else {
			$this->emit('alert', ['type' => 'error', 'message' => 'Data Tidak Ditemukan']);
		}
	}

	public function updatedSelectAll($value)
	{
		if ($value) {
			$this->chuser_id = $this->data()->pluck('id');
		}
		else{
			$this->chuser_id = [];
		}
	}
	
	public function data()
	{
		$perPage = 10;
		$users = User::orderBy('kelas_id')->orderBy('name')->where('level','!=',9);
		if ($this->name) $users = $users->where('name', 'like', '%'.$this->name.'%');
		if ($this->nisn) $users = $users->where('nisn', 'like', '%'.$this->nisn.'%');
		if ($this->kelas_id) $users = $users->where('kelas_id', $this->kelas_id);
		return $users->paginate($perPage);
	}

	public function render()
	{
		$title = 'Data User';
		$data = [
			'arr_kelas' => Kelas::pluck('kelas', 'id'),
			'users' => $this->data(),
		];
		// dd($data);
		return view('livewire.user.index', $data)->extends(env('APP_LAYOUT'), ['title' => $title])->section('content');
	}
}
