<?php

namespace App\Http\Livewire\Materi;

use Livewire\Component;
use Livewire\WithFileUploads;
use Validator;
use Str;
use Storage;
use App\Models\Materi;
use App\Models\Kelas;

class Add extends Component
{
	use WithFileUploads;
	public $judul, $isi, $kelas_id = [], $materi_id;
	public $photo, $old_photo, $edit_data = false;

	public function mount($id = null)
	{
		if ($id) {
			$materi = Materi::findOrFail($id);
			$this->judul = $materi->judul;
			$this->isi = $materi->isi_materi;
			$this->kelas_id = json_decode($materi->kelas_id, true);
			$this->materi_id = $materi->id;
			$this->old_photo = $materi->photo;
		}	
	}

	public function resetFields()
	{
		$this->judul = null;
		$this->isi = null;
		$this->kelas_id = null;
		$this->materi_id = null;
		$this->photo = null;
		$this->old_photo = null;
		$this->resetValidation();
	}
	
	public function save()
	{
		$req = [
			'judul' => $this->judul,
			'isi' => $this->isi,
			'kelas_id' => $this->kelas_id,
			'photo' => $this->photo,
		];
		$valid = [
			'judul' => 'required|string',
			'isi' => 'required',
			'kelas_id' => 'required',
			'photo' => 'image|max:1024',
		];
		if (!$this->photo) {
			unset($req['photo']);unset($valid['photo']);
		}
		$validator = Validator::make($req,$valid);
		if ($validator->fails()) {
			$this->emit('alert', ['type' => 'error', 'message' => 'Mohon isi data dengan benar']);
		} 
		$validator->validate();

		$filename = $this->old_photo;
		if ($this->photo) {
			$ext = $this->photo->extension();
			$filename = Str::random(20) . '.' . $ext;
			Storage::delete(['public/materi/' . $this->old_photo]);
			$this->photo->storeAs('public/materi', $filename);
		}

		$data = [
			'judul' => $this->judul,
			'isi_materi' => $this->isi,
			'kelas_id' => json_encode($this->kelas_id),
			'photo' => $filename,
		];

		Materi::updateOrCreate(['id' => $this->materi_id], $data);
		$this->emit('alert', ['type' => 'success', 'message' => 'Data Berhasil Simpan', 'reload' => true]);
		$this->edit_data = false;
	}
	
	public function render()
	{
		$title = 'Tambah Materi Pelajaran';
		$data = [
			'arr_kelas' => Kelas::all(),
		];
		// dd($data);
		return view('livewire.materi.add', $data)->extends(env('APP_LAYOUT'), ['title' => $title])->section('content');
	}
}
