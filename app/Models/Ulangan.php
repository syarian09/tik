<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulangan extends Model
{
  use HasFactory;
  protected $fillable = [
    'tanggal_mulai', 'tanggal_akhir', 'waktu', 'judul', 'soal', 'kelas_id', 'aktif'
  ];
  protected $appends = ['nama_kelas'];

  public function getNamaKelasAttribute()
  {
    $db_kelas = Kelas::pluck('kelas', 'id')->toArray();
    $tkelas = json_decode($this->kelas_id);
    $_kelas = '';
    foreach ($tkelas as $row) {
      if (array_key_exists($row, $db_kelas)) {
        $_kelas .= $db_kelas[$row] . ', ';
      }
    }
    return $_kelas;
  }
}
