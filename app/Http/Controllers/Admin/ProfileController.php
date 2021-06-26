<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEMailAddress;
use App\Models\Admin;
use App\Models\Role;
use App\Rules\NameValidate;
use App\Rules\WordCountRule;
use App\User;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{

    /*public function __construct()
    {
        $this->middleware('auth:admin');
    }*/

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $User = User::findOrFail(auth()->id());
        $Roles = Role::get();
        $AdminCount = count(Admin::where('is_super', 1)->get());

        return view('admin.profile', compact('User', 'Roles', 'AdminCount'));
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function validation($request){

        Session::flash('formErrorUpdate', 'Error.');
        Validator::extend('olderThan', function($attribute, $value, $parameters)
        {
            $minAge = ( ! empty($parameters)) ? (int) $parameters[0] : 18;
            return (new DateTime)->diff(new DateTime($value))->y >= $minAge;
        });

        /*$dt = new Carbon();
        $before = $dt->subYears(18)->format('Y-m-d');*/

        $request->validate([
            'first_name' => ['required','string', new WordCountRule('First Name', 1, 2), new NameValidate()],
            'last_name' => ['required','string', new WordCountRule('Last Name', 1, 2), new NameValidate()],
            'email' => ['required','string', 'email', 'unique:users,email,'.$this->decryptID($request->id)],
            'user_type' => ['required'],
            'dob' => ['required','date', 'olderThan:18', 'before:-18 years'],
            'phone' => ['required', 'string', 'min:11', 'max:11', 'unique:users,phone,'.$this->decryptID($request->id)],
            'job_title' => ['required', 'string', 'min:2', 'max:30'],
        ], [
            'first_name.required' => 'First name is required.',
            'first_name.string' => 'First name must be a string.',

            'last_name.required' => 'Last name is required.',
            'last_name.string' => 'Last name must be a string.',

            'email.required' => 'Email is required.',
            'email.string' => 'Email must be a string.',
            'email.email' => 'Email must be a email.',
            'email.unique' => 'Email must be unique. The email you entered is already used.',

            'user_type.required' => 'You must select a user type.',

            'dob.required' => 'Date of birth is required.',
            'dob.date' => 'Date of birth must be a date.',
            'dob.olderThan' => 'Your date of birth must be at-least 18 years.',
            'dob.before' => 'Your date of birth must be at-least 18 years.',

            'phone.required' => 'Phone number is required.',
            'phone.min' => 'Phone number must be at-least 11 numbers.',
            'phone.max' => 'Phone number must should contain at max 11 numbers.',
            'phone.unique' => 'Phone number must be unique. The Phone number you entered is already used.',

            'job_title.required' => 'Job title is required.',
            'job_title.string' => 'Job title must be a string.',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSuper(Request $request)
    {
        $this->validation($request);
        $User = User::findOrFail($this->decryptID($request->id));

        if($request->user_type == '1' || $request->user_type == 1){
            if(count(Admin::where('is_super', '=', 1)->get()) >= 2){
                return back()->with('Error', 'Only two users can be super admin.')->withInput();
            }
        }else{
            if($User->roles->pluck('name')->first() == 'Super Admin') {
                if (count(Admin::where('is_super', 1)->get()) <= 1) {
                    return back()->with('Error', 'Error updating profile. At-least one super admin is required. Please create a super admin first.')->withInput();
                }
            }
        }

        $is_super = false;

        $User->first_name = ucwords($request->first_name);
        $User->last_name = ucwords($request->last_name);
        $User->phone = $request->phone;
        $User->dob = Carbon::parse($request->dob)->format('Y-m-d');

        $User->roles()->sync($request->user_type);
        if($User->roles->pluck('name')->first() == 'Super Admin'){
            $is_super = true;
        }

        if($User->email != $request->email){
            $token = str_shuffle(Str::random(32));
            $data = [
                'id' => encrypt($User->id),
                'name'      => $request->first_name .' '. $request->last_name,
                'message'   => '<p>Your account was transferred from "'.$User->email.'" to "'.$request->email.'"</p>',
                'subject'   => 'Account Transferred',
                'from'      => 'RS-School@gmail.com',
                'from_name' => 'Rising Sun Preparatory School',
                'logo' => '/storage/image/web_layout/logo.png',
                'token' => $token,
            ];

            $User->email = $request->email;
            $User->email_verified_at = null;

            Admin::where('user_id', '=', $User->id)
                ->update([
                    'job_title' => ucfirst($request->job_title),
                    'token' => $token,
                    'is_super' => $is_super,
                ]);

            Mail::to($User->email)->send(new VerifyEMailAddress($data));
            if (Mail::failures()) {
                return response()->Fail('Sorry! Please try again latter');
            }
            $User->save();
            Session::forget('formErrorUpdate');
            Auth::logout();
            return redirect()->route('admin.login')->with('Info', 'Please verify your email first. Then login with your new email.');
        }else{
            Admin::where('user_id', '=', $User->id)
                ->update([
                    'job_title' => ucfirst($request->job_title),
                    'is_super' => $is_super,
                ]);
            $User->save();
        }
        Session::forget('formErrorUpdate');
        return back()->with('Success', 'Profile successfully updated.');
    }

    public function updateAdmin(Request $request)
    {
        Session::flash('formErrorUpdate', 'Error.');
        Validator::extend('olderThan', function($attribute, $value, $parameters)
        {
            $minAge = ( ! empty($parameters)) ? (int) $parameters[0] : 18;
            return (new DateTime)->diff(new DateTime($value))->y >= $minAge;
        });

        $request->validate([
            'first_name' => ['required','string', new WordCountRule('First Name', 1, 2), new NameValidate()],
            'last_name' => ['required','string', new WordCountRule('Last Name', 1, 2), new NameValidate()],
            'dob' => ['required','date', 'olderThan:18', 'before:-18 years'],
            'phone' => ['required', 'string', 'min:11', 'max:11', 'unique:users,phone,'.$this->decryptID($request->id)],
        ], [
            'first_name.required' => 'First name is required.',
            'first_name.string' => 'First name must be a string.',

            'last_name.required' => 'Last name is required.',
            'last_name.string' => 'Last name must be a string.',

            'dob.required' => 'Date of birth is required.',
            'dob.date' => 'Date of birth must be a date.',
            'dob.olderThan' => 'Your date of birth must be at-least 18 years.',
            'dob.before' => 'Your date of birth must be at-least 18 years.',

            'phone.required' => 'Phone number is required.',
            'phone.min' => 'Phone number must be at-least 11 numbers.',
            'phone.max' => 'Phone number must should contain at max 11 numbers.',
            'phone.unique' => 'Phone number must be unique. The Phone number you entered is already used.',
        ]);

        $User = User::findOrFail($this->decryptID($request->id));

        $User->first_name = ucwords($request->first_name);
        $User->last_name = ucwords($request->last_name);
        $User->phone = $request->phone;
        $User->dob = Carbon::parse($request->dob)->format('Y-m-d');
        $User->save();
        Session::forget('formErrorUpdate');
        return back()->with('Success', 'Profile successfully updated.');
    }


    public function updatePassword(Request $request)
    {
        Session::flash('formError', 'Error');
        $request->validate([
            'old_password' => 'required|string|min:8|max:60',
            'password' => 'required|string|min:8|max:60|confirmed',
        ]);

        $User_password = User::findOrFail(Auth::guard('admin')->user()->id)->password;

        if (Hash::check($request->old_password, $User_password)) {
            if($request->old_password == $request->password){
                return back()->withErrors(['password' => 'New password cannot be same as Old password.']);
            }
            if (Hash::check($request->password, $User_password)) {
                return back()->withErrors(['password' => 'New password cannot be same as Old password.']);
            }
            if(User::findOrFail(Auth::guard('admin')->user()->id)->update(['password' => Hash::Make($request->password)])){
                Session::forget('formError');
                return back()->with('Success', 'Password successfully changed.');
            }
            return back()->withErrors(['password' => 'Something went wrong. Please try again later.']);
        }
        return back()->withErrors(['old_password' => 'Old password is wrong.']);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'uid' => 'required',
            'avatar' => 'required|image|mimes:jpg,jpeg,gif,png',
        ], [
            'avatar.required' => 'Profile picture is required.',
            'avatar.image' => 'Profile picture must be a image file.',
            'avatar.mimes' => 'Profile picture can only contain jpg, jpeg, png and gif file.',
        ]);

        $User = User::findOrFail($request->uid);

        $Full_name = strtolower($User->first_name) . '_' . strtolower($User->last_name);
        $StorageLink = '';

        try{
            // Image Upload
            if($request->hasFile('avatar')) {
                $get_image = $request->file('avatar'); // get the image form post method...
                $fileNameWithExt = $get_image->getClientOriginalName(); // get full file name ...
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME); // get only the file name without extension ....
                $extension = $get_image->getClientOriginalExtension();// get the file extension

                $allowed_Ext = array('jpg', 'jpeg', 'gif', 'png');
                if (in_array(strtolower($extension), $allowed_Ext, true) == false) {
                    return back()->with('Error', 'Profile picture can only contain jpg, jpeg, png and gif file.');
                }
                $newFileName = $Full_name . '-' . time() . '.' . $extension; // Ste the file name to store in the database ....

                $Thumbnail = 'storage/user_data/admin/' . $newFileName;
                $BigImage = 'storage/user_data/admin/big_image/' . $newFileName;

                Image::make($get_image)->fit(200, 200)->save(public_path($Thumbnail));
                // fit :- Crop the image in the given dimension .....
                Image::make($get_image)->save(public_path($BigImage));
            }

             User::where('id',$User->id)->update([
                'avatar' => $Thumbnail,
                'updated_at' => Carbon::now(),
            ]);
        }catch (Exception $exception){
            return back()->withErrors($exception->getMessage());
        }
        //App::abort(500, 'Error');
        return back()->with('Success', 'Profile picture successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function chkPassword(Request $request){
        if($request->ajax()){
            $user = User::find(Auth::guard('admin')->id());
            $status = '';
            if (Hash::check($request->password, $user->password)) {
                $status = 'Success';
            }else{
                $status = 'Error';
            }
            return response()->json(array('status' => $status), 200);
        }
        abort('404');
    }


    public function delete(Request $request){
        $User = User::findOrFail($this->decryptID($request->id));
        if($User->roles->pluck('name')->first() == 'Super Admin') {
            if (count(Admin::where('is_super', 1)->get()) <= 1) {
                return back()->with('Error', 'You cannot Delete your account. Please create a super admin first.');
            }
        }
        $is_super = false;
        $User->admin->update(['is_super'=> $is_super]);
        Admin::where('user_id', '=', $User->id)
            ->update([
                'is_super' => $is_super,
            ]);
        $User->roles()->detach($User->id);
        $User->delete();

        if(Auth::id() == $User->id){
            Auth::logout();
            return redirect()->route('admin.login')->with('Info', 'Your account was successfully deleted.');
        }
        return redirect()->route('admin.users.trash')->with('Success', 'User account was successfully deleted.');


    }
}
