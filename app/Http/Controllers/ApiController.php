<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
  public function ulangan(Request $request)
  {
    $senderMessage = $request->input('senderMessage');
    $senderName = $request->input('senderName');

    $reply['data'][] = [
      'message' => 'Hai senderName = ' . $senderName . ' senderMessage = ' . $senderMessage,
    ];
    return $reply;
  }
}
