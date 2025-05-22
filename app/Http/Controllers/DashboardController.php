<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $dosen = Http::get('http://localhost:8080/dosen')->json();
        $mahasiswa = Http::get('http://localhost:8080/mahasiswa')->json();
        $jadwal = Http::get('http://localhost:8080/jadwal')->json();
        return view('dashboard', compact('dosen', 'mahasiswa', 'jadwal'));
    }
}
