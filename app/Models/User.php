<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Storage;
use Str;

class User extends Authenticatable
{
	use HasFactory;
	use Notifiable;

	protected $fillable = ['name','nisn','password','level','photo','kelas_id','hp'];
	protected $appends = ['str_level', 'photo_url', 'nama_kelas'];
	
	public function kelas()
	{
		return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
	}

	public function getNamaKelasAttribute()
	{
		return isset($this->kelas->kelas) ? Str::title($this->kelas->kelas) : '';
    }
    
	public function getPhotoUrlAttribute()
	{ 
		return $this->photo ? asset(Storage::url('user/'.$this->photo)) : asset('/assets/img/no_image.jpg');
	}

	public function getStrLevelAttribute()
	{ 
		$level = ['' => 'Siswa', 1 => 'Siswa', 9 => 'Administrator'];
		return $level[$this->level];
	}

	public function setNameAttribute($value)
	{ 
		$this->attributes['name'] = Str::title($value);
	}
	
	public function getNameAttribute($value)
	{ 
		return Str::title($value);
	}
}
