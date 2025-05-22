<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    public $timestamps = false;

    protected $fillable = [
    'nim', 'nama_mahasiswa', 'program_studi', 'judul_skripsi', 'email'];

}
