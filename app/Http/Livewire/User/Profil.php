<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use Auth;
use Hash;
use Validator;
use Storage;
use Str;

class Profil extends Component
{
	use WithFileUploads;
	public $name, $new_password, $old_password, $nisn, $hp;
	public $photo, $old_photo;
	
	public function mount()
	{
		$user = User::findOrFail(Auth::user()->id);
		$this->name = $user->name;
		$this->nisn = $user->nisn;
		$this->hp = $user->hp;
		$this->old_photo = $user->photo;
	}

	public function resetFields()
	{
		$this->new_password = null;
		$this->old_password = null;
		$this->photo = null;
		$this->hp = null;
		$this->old_photo = null;
		$this->resetValidation();
	}
	
	public function save()
	{		
		$req = ['name' => $this->name,'new_password' => $this->new_password, 'old_password' => $this->old_password, 'photo' => $this->photo, 'hp' => $this->hp];
		$valid = [
			'name' => 'required|string',
			'new_password' => 'required',
			'hp' => 'required',
			'old_password' => 'required',
			'photo' => 'image|max:1024',
		];

		if (!$this->photo) {
			unset($req['photo']);unset($valid['photo']);
		}
		if (!$this->new_password) {
			unset($req['new_password']);unset($valid['new_password']);
		}
		$validator = Validator::make($req,$valid);
		if ($validator->fails()) {
			$this->emit('alert', ['type' => 'error', 'message' => 'Mohon isi data dengan benar']);
		} 
		$validator->validate();
		
		$user = User::findOrFail(Auth::user()->id);
		if (Hash::check($this->old_password, $user->password)) {
			$filename = $this->old_photo;
			if ($this->photo) {
				$ext = $this->photo->extension();
				$filename = Auth::user()->id . '_' . Str::slug($this->name) . '.' . $ext;
				Storage::delete(['public/user/' . $this->old_photo]);
				$this->photo->storeAs('public/user', $filename);
			}
			$user->name = $this->name;
			$user->hp = $this->hp;
			if ($this->new_password){
				$user->password = Hash::make($this->new_password);
			}
			$user->photo = $filename;
			$user->save();
			$this->emit('alert', ['type' => 'success', 'message' => 'Data Berhasil Diupdate', 'reload' => true]);
			$this->resetFields();
		}
		else {
			$this->emit('alert', ['type' => 'error', 'message' => 'Password tidak sama']);
		}
	}
	 
	public function render()
	{
		$title = 'Profil User';
		$data = [];
		// dd($data);
		return view('livewire.user.profil', $data)->extends('layouts.admin', ['title' => $title])->section('content');
	}
}
