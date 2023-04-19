<?php

namespace App\Http\Controllers;

use App\Mail\TestEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function bar()
    {
        $userv = ['email' => "", 'nom' => "", 'role' => ""];

        Mail::to('aya@mail.test')->send(new TestEmail($userv));
        return view('welcome');
    }
}
