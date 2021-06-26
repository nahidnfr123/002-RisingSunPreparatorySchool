<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckSuperAdmin;
use App\Mail\resetPassAdmin;
use App\Mail\UserMailNotify;
use App\Mail\UserNotification;
use App\Mail\VerifyEMailAddress;
use App\Models\Admin;
use App\Models\Role;
use App\Rules\NameValidate;
use App\Rules\WordCountRule;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Symfony\Component\CssSelector\Parser\Token;

class ManageUsersController extends Controller
{
    public function __construct()
    {
        // Check if the user is a super admin ...... // if user is not a super admin then prevent access to users management pages .....
        $this->middleware(CheckSuperAdmin::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $Users = User::paginate(9);
        $Roles = Role::get();
        return view('admin.manageUser.users', compact('Users', 'Roles'));
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Session::flash('formError', 'Error.');
        $request->validate([
            'first_name' => ['required','string', new WordCountRule('First Name', 1, 2), new NameValidate()],
            'last_name' => ['required','string', new WordCountRule('Last Name', 1, 2), new NameValidate()],
            'email' => ['required','string', 'email', 'unique:users'],
            'user_type' => ['required'],
            'gender' => ['required','string'],
            'password' => ['required', 'string', 'min:8', 'max:60'],
            'phone' => ['required', 'string', 'min:11', 'max:11', 'unique:users'],
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

            'gender.required' => 'Gender is required.',

            'password.required' => 'Password is required.',
            'password.string' => 'Password is must be a string.',

            'phone.required' => 'Phone number is required.',
            'phone.min' => 'Phone number must be at-least 11 numbers.',
            'phone.max' => 'Phone number must should contain at max 11 numbers.',
            'phone.unique' => 'Phone number must be unique. The Phone number you entered is already used.',

            'job_title.required' => 'Job title is required.',
            'job_title.string' => 'Job title must be a string.',
        ]);
        //dd($request->all());

        if($request->gender == 'Male'){$Avatar = 'storage/user_data/admin/male_user.png';}
        elseif($request->gender == 'Female'){$Avatar = 'storage/user_data/admin/female_user.png';}
        else{$Avatar = 'storage/user_data/admin/user.png';}

        if($request->user_type == '1' || $request->user_type == 1){
            if(count(Admin::where('is_super', '=', 1)->get()) >= 2){
                return back()->with('Error', 'Only two users can be super admin.')->withInput();
            }
        }

        // Create new User ....
        $User = new User;
        $User->first_name = ucwords($request->first_name);
        $User->last_name = ucwords($request->last_name);
        $User->email = $request->email;
        $User->password = Hash::make($request->password);
        $User->phone = $request->phone;
        $User->gender = ucfirst($request->gender);
        $User->avatar = $Avatar;
        $User->created_at = Carbon::now();

        if($User->save()){
            // assign user to a role ....
            $User->roles()->sync($request->user_type);
            $is_super = false;
            if($User->roles->pluck('name')->first() == 'Super Admin'){
                $is_super = true;
            }
            $token = str_shuffle(Str::random(32));

            // Insert Admin data ....
            $AdminData = new Admin;
            $AdminData->user_id = $User->id;
            $AdminData->job_title = ucfirst($request->job_title);
            $AdminData->token = $token;
            $AdminData->is_super = $is_super;
            $AdminData->created_at = Carbon::now();
            $AdminData->save();

            $data = [
                'id' => encrypt($User->id),
                'name'      => $User->first_name .' '. $User->last_name,
                'message'   => '<p>An account was created with this email address. Please verify your email address to use the system.</p>',
                'subject'   => 'Account Created',
                'from'      => 'RS-School@gmail.com',
                'from_name' => 'Rising Sun Preparatory School',
                'logo' => '/storage/image/web_layout/logo.png',
                'token' => $token,
            ];
            Mail::to($User->email)->send(new VerifyEMailAddress($data));
            if (Mail::failures()) {
                return response()->Fail('Sorry! Please try again latter');
            }

            Session::forget('formError');
            return redirect()->route('admin.users')->with('Success', 'User account successfully created.');
        }

        return back()->with('Error', 'Something went wrong. Unexpected error!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $User = User::findOrFail($this->decryptID($id));
        $Roles = Role::get();
        $AdminCount = count(Admin::where('is_super', 1)->get());
        return view('admin.manageUser.userProfile', compact('User', 'Roles', 'AdminCount'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $id = $this->decryptID($id);
        $User = User::findOrFail($id);
        $Roles = Role::get();
        $subPageName = 'Edit';
        return view('admin.manageUser.userEdit', compact('User','Roles', 'subPageName'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'first_name' => ['required','string', new WordCountRule('First Name', 1, 2), new NameValidate()],
            'last_name' => ['required','string', new WordCountRule('Last Name', 1, 2), new NameValidate()],
            'email' => ['required','string', 'email', 'unique:users,email,'.$this->decryptID($request->id)],
            'user_type' => ['required'],
            'phone' => ['required', 'string', 'min:11', 'max:11', 'unique:users,phone,'.$this->decryptID($request->id)],
            'job_title' => ['required', 'string', 'min:2', 'max:250'],
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

            'phone.required' => 'Phone number is required.',
            'phone.min' => 'Phone number must be at-least 11 numbers.',
            'phone.max' => 'Phone number must should contain at max 11 numbers.',
            'phone.unique' => 'Phone number must be unique. The Phone number you entered is already used.',

            'job_title.required' => 'Job title is required.',
            'job_title.string' => 'Job title must be a string.',
        ]);

        if($request->user_type == 1){
            if(count(Admin::where('is_super', '=', 1)->get()) >= 3){
                return back()->with('Error', 'Only two users can be super admin.')->withInput();
            }
        }
        $User = User::findOrFail($this->decryptID($request->id));
        $is_super = false;

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

            $User->first_name = ucwords($request->first_name);
            $User->last_name = ucwords($request->last_name);
            $User->email = $request->email;
            $User->email_verified_at = null;
            $User->phone = $request->phone;

            $User->roles()->sync($request->user_type);
            if($User->roles->pluck('name')->first() == 'Super Admin'){
                $is_super = true;
            }

            $Admin = Admin::where('user_id', '=', $User->id)
                ->update([
                    'job_title' => ucfirst($request->job_title),
                    'token' => $token,
                    'is_super' => $is_super,
                ]);

            Mail::to($User->email)->send(new VerifyEMailAddress($data));
            if (Mail::failures()) {
                return response()->Fail('Sorry! Please try again latter');
            }
        }else{
            $User->first_name = ucwords($request->first_name);
            $User->last_name = ucwords($request->last_name);
            $User->phone = $request->phone;

            $User->roles()->sync($request->user_type);
            if($User->roles->pluck('name')->first() == 'Super Admin'){
                $is_super = true;
            }

            $Admin = Admin::where('user_id', '=', $User->id)
                ->update([
                    'job_title' => ucfirst($request->job_title),
                    'is_super' => $is_super,
                ]);
        }
        $User->save();
        return redirect()->route('admin.users')->with('Success', 'User account successfully updated');
    }


    public function block($id)
    {
        $id = $this->decryptID($id);
        $User = User::findOrFail($id);
        $User->update(['status' => 'Blocked',]);

        $data = [
            'name'      => $User->first_name .' '. $User->last_name,
            'message'   => '<p>Your account is blocked by us. You will no longer be able to login to your account. For more details you may wish to contact with us.</p>',
            'subject'   => 'Account Blocked',
            'from'      => 'RS-School@gmail.com',
            'from_name' => 'Rising Sun Preparatory School',
            'logo' => '/storage/image/web_layout/logo.png',
        ];

        Mail::to($User->email)->send(new UserMailNotify($data));
        if (Mail::failures()) {
            return response()->Fail('Sorry! Please try again latter');
        }
        return redirect()->route('admin.users')->with('Success', 'User successfully blocked.');
    }

    public function unblock($id)
    {
        $id = $this->decryptID($id);
        $User = User::findOrFail($id);
        $User->update(['status' => 'Active',]);

        $data = [
            'name'      => $User->first_name .' '. $User->last_name,
            'message'   => '<p>Congratulation! Your account is unblocked and is now active. You can login to your account.</p>',
            'subject'   => 'Account Unblocked',
            'from'      => 'RS-School@gmail.com',
            'from_name' => 'Rising Sun Preparatory School',
            'logo' => '/storage/image/web_layout/logo.png',
        ];

        Mail::to($User->email)->send(new UserMailNotify($data));
        if (Mail::failures()) {
            return response()->Fail('Sorry! Please try again latter');
        }
        return redirect()->route('admin.users')->with('Success', 'User successfully unblocked.');
    }

    public function delete($id)
    {
        $id = $this->decryptID($id);
        $User = User::withoutTrashed()->findOrFail($id);
        $data = [
            'name'      => $User->first_name .' '. $User->last_name,
            'message'   => '<p>Sorry your account was deleted by an Admin. You will no longer be able to login to your account and use the system.</p>',
            'subject'   => 'Account Deleted',
            'from'      => 'RS-School@gmail.com',
            'from_name' => 'Rising Sun Preparatory School',
            'logo' => '/storage/image/web_layout/logo.png',
        ];

        Mail::to($User->email)->send(new UserMailNotify($data));
        if (Mail::failures()) {
            return response()->Fail('Sorry! Please try again latter');
        }

        $User->delete();
        return redirect()->route('admin.users.trash')->with('Success', 'User successfully deleted.');
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

    public function trash(){
        $Users = User::onlyTrashed()->paginate(9);
        $subPageName = 'Trash';
        $Roles = Role::get();
        return view('admin.manageUser.users', compact('Users', 'subPageName', 'Roles'));
    }

    public function restore($id){
        $id = $this->decryptID($id);
        $User = User::onlyTrashed()->findOrFail($id);
        $data = [
            'name'      => $User->first_name .' '. $User->last_name,
            'message'   => '<p>Congratulation! Your account was restored. You can now login to your account.</p>',
            'subject'   => 'Account Restored',
            'from'      => 'RS-School@gmail.com',
            'from_name' => 'Rising Sun Preparatory School',
            'logo' => '/storage/image/web_layout/logo.png',
        ];

        Mail::to($User->email)->send(new UserMailNotify($data));
        if (Mail::failures()) {
            return response()->Fail('Sorry! Please try again latter');
        }
        $User->restore();
        //$Users = User::paginate(9);
        return redirect()->route('admin.users')->with('Success', 'User account successfully restored.');
    }

    public function search(Request $request){
        $Search_text = $request->search;
        $Users = User::where('first_name', 'like', '%'.$Search_text.'%')
            ->orWhere('last_name', 'like', '%'.$Search_text.'%')
            ->orWhere('email', 'like', '%'.$Search_text.'%')
            ->orderBy('created_at', 'DESC')->latest()->paginate(9);
        $subPageName = 'Search';
        return view('admin.manageUser.userSearch', compact('Users', 'subPageName', 'Search_text'));
    }
}
