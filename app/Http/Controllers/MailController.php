<?php

namespace App\Http\Controllers;

use App\Mail\CSCFuto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendMail(Request $request) {
        $name = 'Bright';
        Mail::to('bce.algorithm@gmail.com')->send(new CSCFuto($name));
    }
}
