<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CreateOrder extends Controller
{
    public function index(Request $request)
    {
        return view('show_order', ['fio' => $request->fio]);
    }
}
