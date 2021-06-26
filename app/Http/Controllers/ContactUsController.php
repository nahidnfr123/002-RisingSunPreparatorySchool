<?php

namespace App\Http\Controllers;

use App\Models\ContactDetails;
use App\Models\ContactUs;
use App\Models\Images;
use App\Rules\NameValidate;
use App\Rules\WordCountRule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function index()
    {
        $ContactDetails = ContactDetails::first();

        $Banner = Images::orderBy('id', 'DESC')->where('contact', 1)->first();
        return view('visitor.contact', compact('ContactDetails', 'Banner'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => ['required', new NameValidate, 'min:3'],
            'email' => 'required|email',
            'subject' => ['required', 'string', new WordCountRule('Subject', 1, 20)],
            'message' => ['required', 'string', new WordCountRule('Message body', 6, 160)],
        ], [
            'name.required' => 'Name is required.',
            'name.min' => 'Name must be at-least 3 letters.',

            'email.required' => 'Email is required.',
            'email.email' => 'Email must be an email.',

            'subject.required' => 'subject is required.',
            'subject.numeric' => 'subject must be string.',

            'message.required' => 'Message is required.',
        ]);

        if(str_word_count($request->message) > 200){
            return back()->withErrors('Error', 'Contact message must be under 200 words.');
        }
        else{
            ContactUs::Insert([
                'name' => ucwords($request->name),
                'email' => $request->email,
                'subject' => ucfirst($request->subject),
                'message' => ucfirst($request->message),
                'created_at' => Carbon::now(),
            ]);
            return back()->with('Success', 'Your message was received. We will reply soon in your email.');
        }
    }

}
