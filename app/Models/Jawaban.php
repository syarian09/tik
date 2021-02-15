<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
  use HasFactory;
  protected $fillable = ['user_id', 'ulangan_id', 'token', 'jawaban', 'hp'];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function ulangan()
  {
    return $this->belongsTo(Ulangan::class, 'ulangan_id', 'id');
  }
}
