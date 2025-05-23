<h1>langkah uji coba membuat FE dari koneksi dengan BE</h1>

💡 Langkah Uji Coba Membuat FE dari Koneksi dengan BE (Jadwal Sidang)

1️⃣ Persiapan Folder & Clone Backend

Buat folder proyek, misal: UJI_COBA_KLP2

Buka folder tersebut di VS Code

Buka terminal di VS Code, jalankan:

```bash

    git clone https://github.com/MuhammadAbiAM/BE-Jadwal-Skripsi.git
    
```

2️⃣ Buat Frontend Laravel
    
Masuk ke folder UJI_COBA_KLP2 via File Explorer
    
Tekan Ctrl + A, lalu ketik cmd dan tekan Enter Jalankan:

```bash

    composer create-project laravel/laravel FE-JADWAL-SIDANG
```

3️⃣ Jalankan Project di Laragon
    
Buka folder BE dan FE di Laragon (bisa satu jendela atau dua window) Jalankan BE dengan:

```bash
    php spark serve
        
```

Note :
1. pastikan bahwa sudah ada database penjadwalan_sidang (bisa copy di file penjadwalan_sidang.sql)
   
2. .env.example diubah jadi .env terus bagian dibawah ini dinyalakan dan DB_CONNECTION diubah jadi mysql
   
DB_CONNECTION=sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=


Pastikan BE dapat diakses via Postman: 

        GET : http://localhost:8080/dosen
        POST : http://localhost:8080/dosen
        UPDATE : http://localhost:8080/dosen/nidn
        DELETE : http://localhost:8080/dosen/nidn
        
4️⃣ Buat Controller & Model di FE

📁 Masuk ke folder FE di VS Code sebelum itu install extension di VS Code bernama Laravel Extra Intellisense, lalu buat controller:
        
```bash 
    php artisan make:model Dosen -m
    php artisan make:model Mahasiswa -m
        
```
    
<h2> Mengisi Controller , Models dan View untuk Dosen</h2>
    
<h3>App\Http\Controllers\DosenCOntroller.php</h3>
    
```sql
    
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
    
```

<h3> App\Models\Dosen.php </h3>

```sql
    
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
```

<h3> routes\web.php </h3>
    
```sql
    
    <?php
    
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\DosenController;
    use App\Http\Controllers\MahasiswaController;
    
    // Route default (opsional)
    Route::get('/', function () {
        return redirect()->route('dosen'); // langsung ke dosen    
    });
    
    // Dosen
    
    Route::get('/dosen', [DosenController::class, 'index'])->name('dosen');
    Route::get('/tambahdosen', [DosenController::class, 'create']);
    Route::post('/simpandosen', [DosenController::class, 'store'])->name('dosen.store');
    Route::get('/editdosen/{nidn}', [DosenController::class, 'edit']);
    Route::put('/updatedosen/{nidn}', [DosenController::class, 'update']);
    Route::delete('/hapusdosen/{nidn}', [DosenController::class, 'destroy']);
    
```

<h3> views\dosen.blade.php </h3>
    
```sql
    
    <!DOCTYPE html>
    <html lang="id">
    
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Data Dosen</title>
      <script src="https://cdn.tailwindcss.com"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script src="https://unpkg.com/lucide@latest"></script>
    </head>
    
    <body class="bg-blue-50 text-gray-800">
      <div class="flex min-h-screen overflow-x-hidden">
    
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-blue-700 via-blue-500 to-pink-200 text-white px-6 pt-6 fixed h-full shadow-xl flex flex-col">
          <h1 class="text-2xl font-bold mb-6 flex items-center gap-2">
            <i data-lucide="book-open" class="w-6 h-6 text-green-300"></i>
            <span>JSTA</span><span class="text-pink-200"></span>
          </h1>
          <nav class="space-y-3">
            <a href="/dashboard" class="flex items-center gap-2 py-2 px-4 rounded bg-white bg-opacity-10 hover:bg-green-400/20 transition">
              <i data-lucide="layout-dashboard" class="w-5 h-5 text-green-200"></i> Dashboard
            </a>
            <a href="/dosen" class="flex items-center gap-2 py-2 px-4 rounded bg-white bg-opacity-20 font-semibold hover:bg-pink-400/20 transition">
              <i data-lucide="users" class="w-5 h-5 text-pink-100"></i> Data Dosen
            </a>
          </nav>
        </aside>
    
        <!-- Main Content -->
        <div class="flex-1 ml-64" id="mainContent">
          <nav class="bg-blue-200 text-gray-800 px-6 py-4 flex justify-between items-center shadow sticky top-0 z-10">
            <h1 class="text-lg font-bold flex items-center gap-2">
              <i data-lucide="users" class="w-5 h-5 text-blue-700"></i> Data Dosen
            </h1>
          </nav>
    
          <main class="p-6">
            <div class="bg-blue-100 text-blue-800 text-center py-3 rounded shadow-md">
              <h2 class="text-2xl font-semibold flex justify-center items-center gap-2">
                <i data-lucide="user-check" class="w-6 h-6 text-blue-500"></i> DATA DOSEN
              </h2>
            </div><br>
    
            <div class="bg-white p-6 rounded-lg shadow-md max-w-6xl mx-auto">
              <div class="flex justify-between items-center mb-4">
                <a href="/tambahdosen" class="bg-green-600 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center gap-2 transition">
                  <i data-lucide="plus" class="w-4 h-4"></i> Tambah
                </a>
                <input type="text" id="searchInput" placeholder="Cari dosen..." class="border border-blue-300 p-2 rounded w-1/3 focus:outline-blue-500">
              </div>
    
              <div class="overflow-x-auto">
                <table id="dosenTable" class="min-w-full border text-sm text-center">
                  <thead class="bg-blue-300 text-gray-800">
                    <tr>
                      <th class="border px-4 py-2">Nama</th>
                      <th class="border px-4 py-2">NIDN</th>
                      <th class="border px-4 py-2">Email</th>
                      <th class="border px-4 py-2">Program Studi</th>
                      <th class="border px-4 py-2">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($dosen as $d)
                    <tr class="hover:bg-pink-100 transition">
                      <td class="border px-4 py-2">{{ $d['nama_dosen'] }}</td>
                      <td class="border px-4 py-2">{{ $d['nidn'] }}</td>
                      <td class="border px-4 py-2">{{ $d['email'] }}</td>
                      <td class="border px-4 py-2">{{ $d['program_studi'] }}</td>
                      <td class="border px-4 py-2 flex items-center justify-center gap-3">
                        <a href="/editdosen/{{ $d['nidn'] }}" class="text-blue-600 hover:text-blue-800">
                          <i data-lucide="edit-3" class="w-5 h-5"></i>
                        </a>
                        <button onclick="confirmDelete('{{ $d['nidn'] }}')" class="text-red-600 hover:text-red-800">
                          <i data-lucide="trash-2" class="w-5 h-5"></i>
                        </button>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </main>
        </div>
      </div>
    
      <script>
        lucide.createIcons();
    
        function confirmDelete(id) {
          Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data dosen akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
          }).then((result) => {
            if (result.isConfirmed) {
              const form = document.createElement('form');
              form.method = 'POST';
              form.action = `/hapusdosen/${id}`;
              form.innerHTML = `
                <input type="hidden" name="_token" value='{{ csrf_token() }}'>
                <input type="hidden" name="_method" value="DELETE">
              `;
              document.body.appendChild(form);
              form.submit();
            }
          });
        }
    
        document.getElementById("searchInput").addEventListener("keyup", function () {
          const searchTerm = this.value.toLowerCase();
          const rows = document.querySelectorAll("#dosenTable tbody tr");
          rows.forEach(row => {
            const rowText = row.innerText.toLowerCase();
            row.style.display = rowText.includes(searchTerm) ? "" : "none";
          });
        });
      </script>
    </body>
    </html>
    
```
    
<h3> views\tambahdosen.blade.php </h3>
    
```sql
    
    <!DOCTYPE html>
    <html lang="id">
    
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Tambah Dosen</title>
      <script src="https://cdn.tailwindcss.com"></script>
      <script src="https://unpkg.com/lucide@latest"></script>
    </head>
    
    <body class="bg-gray-100 text-gray-800 font-sans">
    
      <!-- Navbar -->
      <nav class="bg-cyan-700 text-white px-6 py-4 flex justify-between items-center shadow-md sticky top-0 z-10">
        <h1 class="text-lg font-bold flex items-center gap-2">
          <i data-lucide="plus-circle" class="w-5 h-5"></i>
          Form Tambah Dosen
        </h1>
      </nav>
    
      <!-- Main Content -->
      <main class="p-6">
        <div class="bg-white p-8 rounded-xl shadow-lg max-w-2xl mx-auto">
          <h2 class="text-2xl font-semibold mb-6 text-cyan-700 flex items-center gap-2">
            <i data-lucide="user-plus" class="w-6 h-6"></i> Tambah Data Dosen
          </h2>
    
          <form action="{{ route('dosen.store') }}" method="POST" class="space-y-5">
            @csrf
    
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">NIDN</label>
              <input type="text" name="nidn" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500">
            </div>
    
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dosen</label>
              <input type="text" name="nama_dosen" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500">
            </div>
    
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
              <input type="email" name="email" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500">
            </div>
    
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
              <select name="program_studi" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500">
                <option value="" disabled selected>Pilih Program Studi</option>
                <option value="D3 Teknik Elektronika">D3 Teknik Elektronika</option>
                <option value="D3 Teknik Listrik">D3 Teknik Listrik</option>
                <option value="D3 Teknik Informatika">D3 Teknik Informatika</option>
                <option value="D3 Teknik Mesin">D3 Teknik Mesin</option>
                <option value="D4 Teknik Pengendalian Pencemaran Lingkungan">D4 Teknik Pengendalian Pencemaran Lingkungan</option>
                <option value="D4 Pengembangan Produk Agroindustri">D4 Pengembangan Produk Agroindustri</option>
                <option value="D4 Teknologi Rekayasa Energi Terbarukan">D4 Teknologi Rekayasa Energi Terbarukan</option>
                <option value="D4 Rekayasa Kimia Industri">D4 Rekayasa Kimia Industri</option>
                <option value="D4 Teknologi Rekayasa Mekatronika">D4 Teknologi Rekayasa Mekatronika</option>
                <option value="D4 Rekayasa Keamanan Siber">D4 Rekayasa Keamanan Siber</option>
                <option value="D4 Teknologi Rekayasa Multimedia">D4 Teknologi Rekayasa Multimedia</option>
                <option value="D4 Akuntansi Lembaga Keuangan Syariah">D4 Akuntansi Lembaga Keuangan Syariah</option>
                <option value="D4 Rekayasa Perangkat Lunak">D4 Rekayasa Perangkat Lunak</option>
              </select>
            </div>
    
    
            <div class="flex justify-end gap-3 pt-6">
              <a href="/dosen"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center gap-2 transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Batal
              </a>
              <button type="submit"
                class="bg-cyan-600 hover:bg-cyan-500 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan
              </button>
            </div>
          </form>
        </div>
      </main>
    
      <script>
        lucide.createIcons();
      </script>
    </body>
    </html>
    
```
    
<h3> views\editdosen.blade.php</h3>
    
```sql
    
    <!DOCTYPE html>
    <html lang="id">
    
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Edit Dosen</title>
      <script src="https://cdn.tailwindcss.com"></script>
      <script src="https://unpkg.com/lucide@latest"></script>
    </head>
    
    <body class="bg-gray-100 text-gray-800 font-sans">
    
      <!-- Navbar -->
      <nav class="bg-cyan-700 text-white px-6 py-4 flex justify-between items-center shadow-md sticky top-0 z-10">
        <h1 class="text-lg font-bold flex items-center gap-2">
          <i data-lucide="edit-2" class="w-5 h-5"></i>
          Form Edit Dosen
        </h1>
      </nav>
    
      <!-- Main Content -->
      <main class="p-6">
        <div class="bg-white p-8 rounded-xl shadow-lg max-w-2xl mx-auto">
          <h2 class="text-2xl font-semibold mb-6 text-cyan-700 flex items-center gap-2">
            <i data-lucide="user-check" class="w-6 h-6"></i> Edit Data Dosen
          </h2>
    
          <form action="{{ url('/updatedosen/' . $dosen->nidn) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
    
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">NIDN</label>
              <!-- NIDN biasanya tidak bisa diubah, jadi dibuat readonly -->
              <input type="text" name="nidn" value="{{ $dosen->nidn }}" readonly
                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
            </div>
    
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dosen</label>
              <input type="text" name="nama_dosen" value="{{ old('nama_dosen', $dosen->nama_dosen) }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500">
            </div>
    
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
              <input type="email" name="email" value="{{ old('email', $dosen->email) }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500">
            </div>
    
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
              <select name="program_studi" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500">
                <option value="" disabled>Pilih Program Studi</option>
    
                @php
                  $prodis = [
                    "D3 Teknik Elektronika",
                    "D3 Teknik Listrik",
                    "D3 Teknik Informatika",
                    "D3 Teknik Mesin",
                    "D4 Teknik Pengendalian Pencemaran Lingkungan",
                    "D4 Pengembangan Produk Agroindustri",
                    "D4 Teknologi Rekayasa Energi Terbarukan",
                    "D4 Rekayasa Kimia Industri",
                    "D4 Teknologi Rekayasa Mekatronika",
                    "D4 Rekayasa Keamanan Siber",
                    "D4 Teknologi Rekayasa Multimedia",
                    "D4 Akuntansi Lembaga Keuangan Syariah",
                    "D4 Rekayasa Perangkat Lunak"
                  ];
                @endphp
    
                @foreach ($prodis as $prodi)
                  <option value="{{ $prodi }}" {{ $dosen->program_studi === $prodi ? 'selected' : '' }}>{{ $prodi }}</option>
                @endforeach
              </select>
            </div>
    
            <div class="flex justify-end gap-3 pt-6">
              <a href="{{ route('dosen') }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center gap-2 transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Batal
              </a>
              <button type="submit"
                class="bg-cyan-600 hover:bg-cyan-500 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                <i data-lucide="save" class="w-4 h-4"></i> Update
              </button>
            </div>
          </form>
        </div>
      </main>
    
      <script>
        lucide.createIcons();
      </script>
    </body>
    </html>
    
```
    
// Masuk ke terminal yang mengarah ke FE

// untuk migrate agar bisa muncul tampilannya di awal (opsional)
```
    php artisan migrate
    php artisan config:clear
```
// nylakan file FE

```
    php artisan serve
```

Misal mau cetak pdf

```
    composer require barryvdh/laravel-dompdf 
    resources/views/pdf/cetak.blade.php

```

```sql

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Hasil Studi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #000;
        }

        .header img {
            max-width: 100px;
            margin-bottom: 10px;
        }

        .header h3 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 5px 0;
            font-size: 12px;
            color: #333;
        }

        .content {
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <!-- <img src="https://pnc.ac.id/wp-content/uploads/2023/01/logo-pnc.png" alt="Logo PNC" onerror="this.src='https://via.placeholder.com/100x100?text=PNC+Logo';"> -->
        <h3>KEMENTERIAN PENDIDIKAN, TINGGI, SAINS, DAN TEKNOLOGI</h3>
        <h3>POLITEKNIK NEGERI CILACAP</h3>
        <p>Jalan Dr. Soetomo No. 1, Sidakaya - Cilacap 53212 Jawa Tengah</p>
        <p>Telepon: (0282) 533329, Fax: (0282) 537992</p>
        <p>www.pnc.ac.id, Email: sekretariat@pnc.ac.id</p>
    </div>

    <!-- Konten Utama -->
    <div class="content">
        <h2 style="text-align: center;">Data Prodi</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Prodi</th>
                    <th>Nama Prodi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prodi as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p['kode_prodi'] }}</td>
                    <td>{{ $p['nama_prodi'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>

    <!-- Footer (opsional) -->

</body>

</html>

```

tambah fungsi di controller untuk cetak

```sql

public function exportPdf()
    {
        $response = Http::get('http://localhost:8080/prodi');
        if ($response->successful()) {
            $prodi = collect($response->json());
            $pdf = Pdf::loadView('pdf.cetak', compact('prodi')); 
            return $pdf->download('prodi.pdf');
        } else {
            return back()->with('error', 'Gagal mengambil data untuk PDF');
        }
    }

```


<h2>Versi Bukan Array Tapi Objek</h2>

Dosen Controller.php

```sql

    <?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DosenController extends Controller
{
    protected $apiUrl = 'http://localhost:8080/dosen'; // ganti sesuai URL backend kamu

    public function index()
    {
        $response = Http::get($this->apiUrl);
        $dosen = $response->json();
        return view('dosen', compact('dosen'));
    }

    public function create()
    {
        return view('tambahdosen');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nidn' => 'required',
            'email' => 'required|email',
            'prodi' => 'required',
        ]);

        Http::post($this->apiUrl, $request->all());

        return redirect('/dosen')->with('success', 'Dosen berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $response = Http::get("{$this->apiUrl}/{$id}");

        if ($response->failed()) {
            // return redirect('/dosen')->with('error', 'Gagal mengambil data dosen.');
        }

        $dosen = $response->json();

        // Karena respons tidak mengandung 'id', kirim manual
       return view('editdosen', [
    'dosen' => (object) $dosen,
    'id' => $id
]);
    }

    /**
     * Proses update data dosen.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'nidn' => 'required',
            'email' => 'required|email',
            'prodi' => 'required',
        ]);

        $response = Http::put("{$this->apiUrl}/{$id}", $request->all());
        // dd($response);

        if ($response->successful()) {
            return redirect('/dosen')->with('success', 'Data dosen berhasil diperbarui!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data dosen.');
        }
    }

    public function destroy($id)
    {
        Http::delete("$this->apiUrl/$id");

        return redirect('/dosen')->with('success', 'Data dosen berhasil dihapus!');
    }
}

```

<h2>Dosen.php (model)</h2>

```sql

    <?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dosen extends Model
{
    use HasFactory;

    // Jika nama tabel di database adalah 'dosen' (bukan 'dosens'), tambahkan baris ini:
    protected $table = 'dosen';

    // Jika tabel tidak memiliki kolom 'created_at' dan 'updated_at', matikan timestamps
    public $timestamps = false;

    // (Opsional) Tentukan kolom yang boleh diisi secara massal
    protected $fillable = [
    'nama', 'nidn', 'email', 'prodi'];

}

```

<h2>Dosen.blade.php</h2>


```sql

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Dosen</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-blue-50 text-gray-800">
  <div class="flex min-h-screen overflow-x-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-blue-700 via-blue-500 to-pink-200 text-white px-6 pt-6 fixed h-full shadow-xl flex flex-col">
      <h1 class="text-2xl font-bold mb-6 flex items-center gap-2 text-white">
        <i data-lucide="book-open" class="w-6 h-6 text-green-300"></i> <span class="text-white">Sip</span><span class="text-pink-200">Doma</span>
      </h1>
      <nav class="space-y-3">
        <a href="/dashboard" class="flex items-center gap-2 py-2 px-4 rounded bg-white bg-opacity-10 hover:bg-green-400/20 transition">
          <i data-lucide="layout-dashboard" class="w-5 h-5 text-green-200"></i> Dashboard
        </a>
        <a href="/dosen" class="flex items-center gap-2 py-2 px-4 rounded bg-white bg-opacity-20 font-semibold hover:bg-pink-400/20 transition">
          <i data-lucide="users" class="w-5 h-5 text-pink-100"></i> Data Dosen
        </a>
      </nav>
    </aside>


    <!-- Main Content -->
    <div class="flex-1 ml-64" id="mainContent">
      <nav class="bg-blue-200 text-gray-800 px-6 py-4 flex justify-between items-center shadow sticky top-0 z-10">
        <h1 class="text-lg font-bold flex items-center gap-2">
          <i data-lucide="users" class="w-5 h-5 text-blue-700"></i> Data Dosen dan Mahasiswa
        </h1>
      </nav>

      <main class="p-6">
        <div class="bg-blue-100 text-blue-800 text-center py-3 rounded shadow-md">
          <h2 class="text-2xl font-semibold flex justify-center items-center gap-2">
            <i data-lucide="user-check" class="w-6 h-6 text-blue-500"></i> DATA DOSEN
          </h2>
        </div><br>

        <div class="bg-white p-6 rounded-lg shadow-md max-w-6xl mx-auto">
          <div class="flex justify-between items-center mb-4">
            <a href="/tambahdosen" class="bg-green-600 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center gap-2 transition">
              <i data-lucide="plus" class="w-4 h-4"></i> Tambah
            </a>
            <input type="text" id="searchInput" placeholder="Cari dosen..." class="border border-pink-200 p-2 rounded w-1/3 focus:outline-blue-400">
          </div>

          <div class="overflow-x-auto">
            <table id="dosenTable" class="min-w-full border text-sm text-center">
              <thead class="bg-blue-300 text-gray-800">
                <tr>
                  <th class="border px-4 py-2">ID</th>
                  <th class="border px-4 py-2">Nama</th>
                  <th class="border px-4 py-2">NIDN</th>
                  <th class="border px-4 py-2">Email</th>
                  <th class="border px-4 py-2">Program Studi</th>
                  <th class="border px-4 py-2">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($dosen as $d)
                <tr class="hover:bg-pink-100">
                  <td class="border px-4 py-2">{{ $d['id'] }}</td>
                  <td class="border px-4 py-2">{{ $d['nama'] }}</td>
                  <td class="border px-4 py-2">{{ $d['nidn'] }}</td>
                  <td class="border px-4 py-2">{{ $d['email'] }}</td>
                  <td class="border px-4 py-2">{{ $d['prodi'] }}</td>
                  <td class="border px-4 py-2 flex items-center justify-center gap-3">
                    <a href="/editdosen/{{ $d['nidn'] }}" class="text-blue-600 hover:text-blue-800">
                      <i data-lucide="edit-3" class="w-5 h-5"></i>
                    </a>
                    <button onclick="confirmDelete(@js($d['nidn']))" class="text-red-600 hover:text-red-800">
                      <i data-lucide="trash-2" class="w-5 h-5"></i>
                    </button>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script>
    lucide.createIcons();

    function confirmDelete(id) {
      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data dosen akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          const form = document.createElement('form');
          form.method = 'POST';
          form.action = `hapusdosen/${id}`;
          form.innerHTML = `
            <input type="hidden" name="_token" value='{{ csrf_token() }}'>
            <input type="hidden" name="_method" value="DELETE">
          `;
          document.body.appendChild(form);
          form.submit();
        }
      });
    }

    document.getElementById("searchInput").addEventListener("keyup", function() {
      const searchTerm = this.value.toLowerCase();
      const rows = document.querySelectorAll("#dosenTable tbody tr");
      rows.forEach(row => {
        const rowText = row.innerText.toLowerCase();
        row.style.display = rowText.includes(searchTerm) ? "" : "none";
      });
    });
  </script>
</body>

</html>

```


<h2> Web.php</h2>

```sql

<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');
// Dosen
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dosen', [DosenController::class, 'index'])->name('dosen');
Route::get('/tambahdosen', [DosenController::class, 'create']);
Route::post('/simpandosen', [DosenController::class, 'store']);
Route::get('/editdosen/{id}', [DosenController::class, 'edit']);
Route::put('/updatedosen/{id}', [DosenController::class, 'update']);
Route::delete('/hapusdosen/{id}', [DosenController::class, 'destroy']);

```
