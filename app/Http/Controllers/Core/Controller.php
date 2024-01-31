<?php

namespace App\Http\Controllers\Core;

use App\Exports\ContractExport;
use App\Models\Admin;
use App\Models\Comercial;
use App\Models\Core\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    function getUser(){
        $user = User::find(Auth::id());
        if($user != null) {
            return $user;
        }
    }


    function MapeoWithInputRequest(Request $request)
    {
        $with = array();
        $array = $request->all();
        return $with;
    }

    function MapeoFiltro(Request $request)
    {
        $filtro = array();
        return $filtro;
    }

}
