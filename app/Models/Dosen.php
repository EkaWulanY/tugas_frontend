<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';

    public $timestamps = false;

    protected $fillable = [
    'nidn', 'nama_dosen', 'program_studi', 'email'];

}
