<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Token;
use Illuminate\Support\Facades\Input;

class TokenController extends Controller
{
    public function index()
    {
        $token = Token::all();
        return view('admin.data-token')->with([
            'token' => $token
        ]);
        
    }

    public function delete()
    {
        $input = Input::all();
        $id = $input['id'];
        $token = Token::find($id);
        $token->delete();

        echo json_encode($token);
        // return redirect()->route('data.token');
    }
}
