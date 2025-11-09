<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportHistoryController extends Controller
{
    public function index()
    {
        return view("user.history");
    }
}
