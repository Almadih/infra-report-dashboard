<?php

namespace App\Http\Controllers;

use App\Models\DamageType;

class DamageTypeController extends Controller
{
    public function index()
    {
        return response()->json(DamageType::all());
    }
}
