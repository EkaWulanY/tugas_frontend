<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DosenController extends Controller
{
    protected $apiUrl = 'http://localhost:8080/dosen'; // URL API Backend

    public function index()
    {
        $response = Http::get($this->apiUrl);

        if ($response->successful()) {
            // Ambil hanya bagian 'data' dari JSON response
            $dosen = $response->json()['data'];
        } else {
            $dosen = []; // fallback kalau API error
        }

        return view('dosen', compact('dosen'));
    }

    public function create()
    {
        return view('tambahdosen');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nidn' => 'required',
            'nama_dosen' => 'required',
            'program_studi' => 'required',
            'email' => 'required|email',
        ]);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl, $validated);

        if ($response->successful()) {
            return redirect('/dosen')->with('success', 'Dosen berhasil ditambahkan!');
        } else {
            return redirect('/dosen')->with('error', 'Gagal menambahkan dosen. ' . $response->body());
        }
    }


    public function edit($nidn)
    {
        $response = Http::get("{$this->apiUrl}/{$nidn}");

        if ($response->failed()) {
            return redirect('/dosen')->with('error', 'Gagal mengambil data dosen.');
        }

        $data = $response->json();

        if (!isset($data['data'])) {
            return redirect('/dosen')->with('error', 'Data dosen tidak ditemukan.');
        }

        $dosen = (object) $data['data'];

        return view('editdosen', [
            'dosen' => $dosen,
            'nidn' => $nidn
        ]);
    }

    public function update(Request $request, $nidn)
    {
        $validated = $request->validate([
            'nidn' => 'required',
            'nama_dosen' => 'required',
            'program_studi' => 'required',
            'email' => 'required|email',
        ]);

        $response = Http::put("{$this->apiUrl}/{$nidn}", $request->all());

        if ($response->successful()) {
            return redirect('/dosen')->with('success', 'Data dosen berhasil diperbarui!');
        } else {
            return redirect('/dosen')->with('error', 'Gagal memperbarui data dosen.');
        }
    }

    public function destroy($nidn)
    {
        $response = Http::delete("{$this->apiUrl}/{$nidn}");

        if ($response->successful()) {
            return redirect('/dosen')->with('success', 'Data dosen berhasil dihapus!');
        } else {
            return redirect('/dosen')->with('error', 'Gagal menghapus data dosen.');
        }
    }
}
