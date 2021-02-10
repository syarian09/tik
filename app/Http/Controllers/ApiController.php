<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
  public function ulangan(Request $request)
  {
    $app = $request->input('app');
    $sender = $request->input('sender');
    $message = $request->input('message');

    $reply['reply'] = 'Hai App = ' . $app . ' Sender = ' . $sender . ' Message = ' . $message;
    return $reply;
  }
}
