<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function contactPost(Request $request) {

       



        $this->validate($request,
        [
        'name'  => 'required|max:100',
        'email' => 'required|email',
        'message' => 'required|max:800',
        'g-recaptcha-response' => 'required|captcha',
       ]);

    Contact::create($request->all());

    //   Mail::send('email',
      
    //       array(
    //             'name' => $request->get('name'),
    //             'email' => $request->get('email'),
    //             'bodyMessage' => $request->get('message')
    //            ), function($message) {

    //                $message->from('');
    //                $message->to('')->subject('OpuPower Contact Form');

                   

    //            });

       return back()->with('success','Thank you for contacting us!');
    }
}
