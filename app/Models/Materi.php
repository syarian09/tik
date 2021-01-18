<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Materi extends Model
{
	use HasFactory;
	protected $fillable = ['judul','isi_materi','kelas_id','photo'];
	protected $appends = ['nama_kelas', 'photo_url', 'photo_ori'];
	
	public function getNamaKelasAttribute()
	{ 
		$db_kelas = Kelas::pluck('kelas', 'id')->toArray();
		$tkelas = json_decode($this->kelas_id);
		$_kelas = '';
		foreach ($tkelas as $row) {
			if (array_key_exists($row,$db_kelas))
			{
				$_kelas .= $db_kelas[$row] . ', ';
			}
		}
		return $_kelas;
	}

	public function getPhotoUrlAttribute()
	{ 
		return $this->photo ? asset(Storage::url('materi/'.$this->photo)) : asset('/assets/img/noimage.jpg');
	}
	
	public function getPhotoOriAttribute()
	{ 
		return $this->photo ? 'public/materi/'.$this->photo : '';
	}
}
