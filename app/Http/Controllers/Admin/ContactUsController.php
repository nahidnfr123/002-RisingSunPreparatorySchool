<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SendEmail;
use App\Models\ContactDetails;
use App\Models\ContactUs;
use App\Rules\WordCountRule;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class ContactUsController extends Controller
{
    public function inbox()
    {
        $Msgs = ContactUs::orderBy('created_at', 'DESC')->where('sender', '=', 0)->get();
        $subPageName = 'Inbox';
        return view('admin.contact_us.inbox', compact('Msgs', 'subPageName'));
    }

    public function compose()
    {
        return view('admin.contact_us.compose');
    }

    public function reply($id){
        $id = $this->decryptID($id);

        $Msg = ContactUs::findOrFail($id);
        if ($Msg->seen == 0){
            $Msg->update([
                'seen' => 1,
                'updated_at' => Carbon::now(),
            ]);
        }
        return view('admin.contact_us.compose', compact('Msg'));
    }

    public function sendMail(Request $request){
        $request->validate([
            'email' => 'required|email',
            'message_subject' => ['required', new WordCountRule('Message body', 1, 50)],
            'message_body' => ['required', new WordCountRule('Message body', 20, 200)],
        ],[
            'email.required' => 'Recipient email is required.',
            'email.email' => 'Recipient email must be a email.',

            'message_subject.required' => 'Email subject is required.',

            'message_body.required' => 'Message body is required.',
        ]);

        $request->request->add(['name'=> 'there']);

        $data = array(
            'email' => $request->email,
            'message_subject' => ucfirst($request->message_subject),
            'message_body' => ucfirst($request->message_body),
            'name' => ucfirst($request->name),
        );
        $Ins = new ContactUs();
        $Ins->name = Auth::user()->first_name . ' ' . Auth::user()->last_name;
        $Ins->email = $request->email;
        $Ins->subject = ucfirst($request->message_subject);
        $Ins->message = ucfirst($request->message_body);
        $Ins->replied = 0;
        $Ins->sender = auth()->id();
        $Ins->seen = true;
        $Ins->save();

        Mail::to($request->email)->send(new SendEmail($data));

        return redirect()->route('admin.contact-us.sent')->with('Success', 'Email successfully sent.');
    }


    public function replyMail(Request $request){
        $request->validate([
            'email' => 'required|email',
            'message_subject' => ['required', new WordCountRule('Message body', 1, 50)],
            'message_body' => ['required', new WordCountRule('Message body', 20, 200)],
            'name' => 'required|string',
        ],[
            'email.required' => 'Recipient email is required.',
            'email.email' => 'Recipient email must be a email.',

            'message_subject.required' => 'Email subject is required.',

            'message_body.required' => 'Message body is required.',
        ]);

        $data = array(
            'email' => $request->email,
            'message_subject' => ucfirst($request->message_subject),
            'message_body' => ucfirst($request->message_body),
            'name' => ucfirst($request->name),
        );

        $Ins = new ContactUs();
        $Ins->name = Auth::user()->first_name . ' ' . Auth::user()->last_name;
        $Ins->email = $request->email;
        $Ins->subject = ucfirst($request->message_subject);
        $Ins->message = ucfirst($request->message_body);
        $Ins->replied = $request->id;
        $Ins->sender = auth()->id();
        $Ins->seen = true;
        $Ins->save();

        Mail::to($request->email)->send(new SendEmail($data));

        return redirect()->route('admin.contact-us.sent')->with('Success', 'Email successfully sent.');
    }

    public function sentMsg()
    {
        $Msgs = ContactUs::orderBy('created_at', 'DESC')->where('sender', '!=', 0)->get();
        $subPageName = 'Sent message';
        return view('admin.contact_us.inbox', compact('Msgs', 'subPageName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $id = $this->decryptID($id); // Perform decryption If not successful then redirect to 404
        $ContactDetails = ContactUs::findOrFail($id);
        $ContactDetails->update([
            'seen' => 1,
            'updated_at' => Carbon::now(),
        ]);

        return view('admin.contact_us.read_message', compact('ContactDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }//

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function delete($id){ //Done
        try {
            $id = $this->decryptID($id);
            ContactUs::withoutTrashed()->findOrFail($id)->delete();
            return redirect()->route('admin.contact-us.inbox')->with('Info', 'Message successfully moved to trash.');
        }catch (Exception $e) {
            return back()->with('Error', $e->getMessage())->withInput();
        }
    }
    public function multipleDelete(Request $request){ //Done
        $Id_array = $request->input('id');
        $Message = ContactUs::whereIn('id', $Id_array);
        if($Message->delete()){
            echo 'Message successfully moved to trash.';
        }
    }


    public function trash(){
        $Deleted_Messages = ContactUs::orderBy('id', 'DESC')->onlyTrashed()->get();
        $subPageName = 'Trash';
        return view('admin.contact_us.trash', compact('Deleted_Messages', 'subPageName'));
    }

    public function restore(Request $request){
        $Id_array = $request->input('id');
        $Message = ContactUs::onlyTrashed()->whereIn('id', $Id_array);
        if($Message->restore()){
            echo 'Message successfully restored.';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id){
        try {
            $id = $this->decryptID($id);
            ContactUs::withTrashed()->findOrFail($id)->forceDelete();
            return redirect()->route('admin.contact-us.inbox')->with('Info', 'Message deleted successfully.');
        }catch (Exception $e) {
            return back()->with('Error', $e->getMessage())->withInput();
        }
    }
    public function multipleDestroy(Request $request){
        $Id_array = $request->input('id');
        $Message = ContactUs::whereIn('id', $Id_array);
        if($Message->forceDelete()){
            echo 'Message successfully deleted.';
        }
    }














    public function details(){
        $CD = ContactDetails::first();
        return view('admin.contact_us.contact_details', compact('CD'));
    }
    public function details_add(Request $request){
        $request->validate([
            'email' => 'required|email',
            'phone' => ['required','numeric', 'regex:/(01)[0-9]{9}/', ],
            'city' => ['required', 'string', new WordCountRule('City', 1, 2)],
            'address' =>  ['required', 'string', new WordCountRule('Address', 1, 6)],
        ],[
            'email.required' => 'Please enter the email address.',
        ]);

        try {
            $CD = new ContactDetails();
            $CD->user_id = auth()->id();
            $CD->email = $request->email;
            $CD->phone = $request->phone;
            $CD->city = $request->city;
            $CD->address = $request->address;
            $CD->created_at = Carbon::now();
            $CD->save();

        }catch (Exception $e) {
            return back()->with('Error', $e->getMessage())->withInput();
        }
        return back()->with('Success', 'Contact details successfully added.');
    }

    public function details_update(Request $request){
        $request->validate([
            'email' => 'required|email',
            'phone' => ['required','numeric', 'regex:/(01)[0-9]{9}/', ],
            'city' => ['required', 'string', new WordCountRule('City', 1, 2)],
            'address' =>  ['required', 'string', new WordCountRule('Address', 1, 6)],
        ],[
            'email.required' => 'Please enter the email address.',
        ]);

        try {
            $CD = ContactDetails::findOrFail($request->id);
            $CD->user_id = auth()->id();
            $CD->email = $request->email;
            $CD->phone = $request->phone;
            $CD->city = $request->city;
            $CD->address = $request->address;
            $CD->created_at = Carbon::now();
            $CD->save();

        }catch (Exception $e) {
            return back()->with('Error', $e->getMessage())->withInput();
        }
        return back()->with('Success', 'Contact details successfully updated.');
    }
}
