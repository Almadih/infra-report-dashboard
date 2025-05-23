<?php

namespace App\Http\Controllers;

use App\Models\Severity;

class SeverityController extends Controller
{
    public function index()
    {
        return response()->json(Severity::all());
    }
}
