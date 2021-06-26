<?php

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'Super Admin',
            'slug' => 'super admin',
            'created_at' => Carbon::now(),
        ]);

        Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'created_at' => Carbon::now(),
        ]);

        /*Role::create([
            'name' => 'Editor',
            'slug' => 'editor',
            'created_at' => Carbon::now(),
        ]);

        Role::create([
            'name' => 'User',
            'slug' => 'user',
            'created_at' => Carbon::now(),
        ]);*/

    }
}
