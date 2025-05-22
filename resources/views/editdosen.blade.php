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
