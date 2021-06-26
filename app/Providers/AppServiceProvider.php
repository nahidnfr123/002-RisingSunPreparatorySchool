<?php

namespace App\Providers;

use App\Visitor;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // Visit Counter ....
        $ip = request()->ip();
        $Cookie_name = 'Visited';
        $Cookie_value = $ip;
        $Expire_Time = 1440; // 1 day ....

        $value = Cookie::get($Cookie_name);

        if (auth()->guest()){
            if($value == null){
                $Vt = new Visitor();
                $Vt->ip_address = request()->ip();
                $Vt->save();
                Cookie::queue($Cookie_name, $Cookie_value, $Expire_Time);
            }
        }
    }
}
