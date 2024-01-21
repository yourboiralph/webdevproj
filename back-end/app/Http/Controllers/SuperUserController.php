<?php

namespace App\Http\Controllers;

use App\Models\SuperUser;

class SuperUserController extends Controller
{
    public function index() 
    {
        $super_user = SuperUser::all();

        if ($super_user->count() > 0) {
            return response()->json(['super_user' => $super_user]);
        } else {
            return response()->json([
                'super_user' => 'No Super User Found'
            ]);
        }
    }
}
