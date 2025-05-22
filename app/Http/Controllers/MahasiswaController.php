<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MahasiswaController extends Controller
{
    protected $apiUrl = 'http://localhost:8080/mahasiswa';

    public function index()
    {
        $response = Http::get($this->apiUrl);

        if ($response->successful()) {
            // Ambil hanya bagian 'data' dari JSON response
            $mahasiswa = $response->json()['data'];
        } else {
            $mahasiswa = []; // fallback kalau API error
        }

        return view('mahasiswa', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'npm' => 'required',
            'nama_mahasiswa' => 'required',
            'program_studi' => 'required',
            'judul_skripsi' => 'required',
            'email' => 'required|email',
        ],);

        Http::post($this->apiUrl, $request->all());

        return redirect('/mahasiswa')->with('success', 'Mahasiswa berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $response = Http::get("{$this->apiUrl}/{$id}");

        if ($response->failed()) {
            return redirect('/mahasiswa')->with('error', 'Gagal mengambil data mahasiswa.');
        }

        $mahasiswa = $response->json();

        return view('editmahasiswa', [
            'mahasiswa' => (object) $mahasiswa,
            'id' => $id
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'npm' => 'required',
            'nama_mahasiswa' => 'required',
            'program_studi' => 'required',
            'judul_skripsi' => 'required',
            'email' => 'required|email',
        ],);

        $response = Http::put("{$this->apiUrl}/{$id}", $request->all());

        if ($response->successful()) {
            return redirect('/mahasiswa')->with('success', 'Data mahasiswa berhasil diperbarui!');
        } else {
            return redirect('/mahasiswa')->with('error', 'Gagal memperbarui data mahasiswa.');
        }
    }

    public function destroy($id)
    {
        Http::delete("{$this->apiUrl}/{$id}");

        return redirect('/mahasiswa')->with('success', 'Data mahasiswa berhasil dihapus!');
    }
}
