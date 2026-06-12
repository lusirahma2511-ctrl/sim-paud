<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;

class RaporController extends Controller
{
    public function index()
    {
        return view('orangtua.rapor');
    }
}