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
