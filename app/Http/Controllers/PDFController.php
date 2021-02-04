<?php

namespace App\Http\Controllers;

use Auth;
use Request;
use PDF;
use App\Models\User;
use App\Models\Materi;

class PDFController extends Controller
{
  public function index($id)
  {
    $user = User::findOrFail(Auth::user()->id);
    $materi = Materi::where('id', $id)->whereJsonContains('kelas_id', Auth::user()->kelas_id)->first();
    // dd($materi);
    if ($materi) {
      $data = [
        'data' => $materi,
        'nama' => $user->name,
        'kelas' => $user->nama_kelas,
        'title' => $materi->judul,
      ];
      $pdf = PDF::loadview('livewire.materi.baca', $data)->setPaper('polio', 'potrait');
      return $pdf->stream();
    } else {
      return redirect('/');
    }
  }
}
