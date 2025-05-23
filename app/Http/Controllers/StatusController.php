<?php

namespace App\Http\Controllers;

use App\Models\Status;

class StatusController extends Controller
{
    public function index()
    {
        $statuses = Status::all();
    }

    public function create() {}

    public function store() {}

    public function show() {}

    public function edit() {}

    public function update() {}

    public function destroy() {}
}
