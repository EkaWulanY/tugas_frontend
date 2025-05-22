<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class JadwalController extends Controller
{
    public function index()
    {
        $response = Http::get('http://localhost:8080/jadwal');
        $jadwal = $response->json();

        return view('jadwal', compact('jadwal'));
    }
}
