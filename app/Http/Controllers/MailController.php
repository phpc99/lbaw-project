<?php

namespace App\Http\Controllers;

use Mail;
use Illuminate\Http\Request;
use App\Mail\MailModel;
use TransportException;
use Exception;

class MailController extends Controller
{
    function send(Request $request) {

        $base_url = route('password.new.form', ['email' => $request->email]);

        $mailData = [
            'email' => $request->email,
            'recover-link' => $base_url
        ];

        Mail::to($request->email)->send(new MailModel($mailData));

        return redirect()->route('login')->with('success', 'Email sent.');
    }
}
