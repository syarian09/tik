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

  protected $fillable = ['name', 'nisn', 'password', 'level', 'photo', 'kelas_id', 'hp'];
  protected $appends = ['str_level', 'photo_url', 'nama_kelas'];

  public function kelas()
  {
    return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
  }

  public function materis()
  {
    $materis = Materi::whereJsonContains('kelas_id', strval($this->kelas_id))->get();
    $arr = [];
    foreach ($materis as $row) {
      $arr_id = $row->baca ? json_decode($row->baca, true) : [];
      if (in_array($this->id, $arr_id)) {
        $baca = 'Sudah dibaca';
      } else {
        $baca = 'Belum dibaca';
      }
      $arr[] = [
        'judul' => $row->judul,
        'baca' => $baca,
      ];
    }
    return collect($arr);
  }

  public function getNamaKelasAttribute()
  {
    return isset($this->kelas->kelas) ? Str::title($this->kelas->kelas) : '';
  }

  public function getPhotoUrlAttribute()
  {
    return $this->photo ? asset(Storage::url('user/' . $this->photo)) : asset('/assets/img/no_image.jpg');
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

  public function jawaban()
  {
    return Jawaban::where('user_id', $this->id)->first();
  }

  public function ulangan()
  {
    return Ulangan::where('id', $this->jawaban()->ulangan_id)->first();
  }
}
