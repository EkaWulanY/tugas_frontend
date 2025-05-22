<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Jadwal Sidang TA</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-pink-50 to-purple-100 min-h-screen text-gray-800">

  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-violet-700 via-pink-300 to-gray-300 text-white flex-shrink-0 p-6 shadow-2xl rounded-r-3xl">
      <div class="mb-8">
        <h1 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center gap-2">
          <i data-lucide="book" class="w-6 h-6"></i> <span class="drop-shadow">JSTA</span>
        </h1>
        <hr class="border-white opacity-30">
      </div>
      <nav class="space-y-3 text-white text-sm font-medium">
        <a href="{{ route('dashboard') }}"
          class="flex items-center gap-2 py-2 px-4 rounded-lg bg-white bg-opacity-20 hover:bg-white hover:bg-opacity-30 transition-all duration-200">
          <i data-lucide="clipboard-list" class="w-5 h-5"></i> Dashboard
        </a>
        <a href="{{ route('dosen') }}"
          class="flex items-center gap-2 py-2 px-4 rounded-lg bg-white bg-opacity-20 hover:bg-white hover:bg-opacity-30 transition-all duration-200">
          <i data-lucide="users" class="w-5 h-5"></i> Dosen
        </a>
        <a href="{{ route('mahasiswa') }}" class="flex items-center gap-2 py-2 px-4 rounded-lg bg-pink-400 bg-opacity-30 text-white hover:bg-opacity-50 transition">
          <i data-lucide="graduation-cap" class="w-5 h-5"></i> Mahasiswa
        </a>
        <a href="{{ route('jadwal') }}" class="flex items-center gap-2 py-2 px-4 rounded-lg bg-pink-400 bg-opacity-30 text-white hover:bg-opacity-50 transition">
          <i data-lucide="calendar-clock" class="w-5 h-5"></i> Jadwal Sidang
        </a>

      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10">
      <!-- Header -->
      <!-- Header dengan efek warna kiri -->
      <div class="relative bg-white shadow-xl rounded-2xl p-6 mb-8 pl-8">
        <!-- Strip gradasi di sisi kiri -->
        <div class="absolute top-0 left-0 h-full w-2 rounded-l-2xl bg-gradient-to-b from-purple-500 via-pink-500 to-blue-500"></div>

        <h2 class="text-3xl font-extrabold text-gray-700 mb-1 flex items-center gap-2">
          🎓 <span>JSTA - Jadwal Sidang Tugas Akhir</span>
        </h2>
        <p class="text-gray-500 text-sm">Pantau data dosen, mahasiswa, dan jadwal sidang di halaman ini.</p>
      </div>


      <!-- Statistik -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Dosen -->
        <div
          class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border-l-[6px] border-blue-500 flex items-center justify-between">
          <div>
            <p class="text-gray-600 font-semibold">Jumlah Dosen</p>
            <p class="text-4xl font-bold text-blue-600 mt-1">{{ $Dosen ?? '—' }}</p>
          </div>
          <i data-lucide="user" class="w-12 h-12 text-blue-500"></i>
        </div>

        <!-- Mahasiswa -->
        <div
          class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border-l-[6px] border-pink-400 flex items-center justify-between">
          <div>
            <p class="text-gray-600 font-semibold">Jumlah Mahasiswa</p>
            <p class="text-4xl font-bold text-pink-500 mt-1">{{ $Mahasiswa ?? '—' }}</p>
          </div>
          <i data-lucide="user-graduate" class="w-12 h-12 text-pink-400"></i>
        </div>

        <!-- Jadwal Sidang -->
        <div
          class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border-l-[6px] border-purple-500 flex items-center justify-between">
          <div>
            <p class="text-gray-600 font-semibold">Jadwal Sidang</p>
            <p class="text-4xl font-bold text-purple-600 mt-1">{{ $JadwalSidang ?? '—' }}</p>
          </div>
          <i data-lucide="calendar-clock" class="w-12 h-12 text-purple-500"></i>
        </div>
      </div>
    </main>
  </div>

  <script>
    lucide.createIcons();
  </script>
</body>

</html>