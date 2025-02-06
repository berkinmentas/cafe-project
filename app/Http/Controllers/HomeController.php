<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $cafes = Admin::query()->latest()->get();

        return view('homepage', [
            'cafes' => $cafes
        ]);
    }}
