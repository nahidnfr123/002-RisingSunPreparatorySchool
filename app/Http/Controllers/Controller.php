<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function decryptID($id){
        try{
            $id = decrypt($id);
            return $id;
        }
        catch (Exception $e) {
            //return $e->getMessage();
            abort(403, 'Unauthorized action.');
        }
    }

    /*public function setCookie($Cookie_name, $Cookie_value, $Expire_Time){
        $response = new \Illuminate\Http\Response($Cookie_name);
        $response->withCookie(cookie($Cookie_name, $Cookie_value, $Expire_Time));
        return $response;
    }*/
}
