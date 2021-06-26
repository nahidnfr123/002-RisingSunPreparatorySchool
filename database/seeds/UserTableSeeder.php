<?php

use App\Models\Admin;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name'=> 'Super',
            'last_name'=> 'Admin',
            'email'=> 'super@admin.com',
            'email_verified_at'=> '2019-08-21',
            'password'=> Hash::make('super@admin.com'),
            'dob'=> '1996-08-21',
            'phone'=> '01823823823',
            'gender'=> 'Male',
            'avatar'=> 'storage/user_data/admin/Admin.gif',
            'created_at'=>Carbon::now()
        ]);
        $User = User::find(1);
        $User->roles()->sync(1);
        Admin::create([
            'user_id' => $User->id,
            'job_title' => 'Managing Director',
            'is_super' => 1,
        ]);






    }
}
