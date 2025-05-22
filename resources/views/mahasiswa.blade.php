<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Mahasiswa</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-gray-100 text-gray-800">
  <div class="flex min-h-screen overflow-x-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-[#F9A8D4] via-[#f472b6] to-[#c084fc] text-white px-6 pt-6 fixed h-full shadow-xl flex flex-col">
      <h1 class="text-2xl font-bold mb-6 flex items-center gap-2">
        <i data-lucide="book-open" class="w-6 h-6 text-white"></i>
        <span>JSTA</span>
      </h1>
      <nav class="space-y-3">
        <a href="/dashboard" class="flex items-center gap-2 py-2 px-4 rounded bg-white bg-opacity-10 hover:bg-white/20 transition">
          <i data-lucide="layout-dashboard" class="w-5 h-5 text-white"></i> Dashboard
        </a>
        <a href="/mahasiswa" class="flex items-center gap-2 py-2 px-4 rounded bg-white bg-opacity-20 font-semibold hover:bg-white/30 transition">
          <i data-lucide="users" class="w-5 h-5 text-white"></i> Data Mahasiswa
        </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 ml-64" id="mainContent">
      <nav class="bg-[#f472b6] text-white px-6 py-4 flex justify-between items-center shadow sticky top-0 z-10">
        <h1 class="text-lg font-bold flex items-center gap-2">
          <i data-lucide="users" class="w-5 h-5 text-white"></i> Data Mahasiswa
        </h1>
      </nav>

      <main class="p-6">
        <div class="bg-[#F9A8D4] text-[#3B82F6] text-center py-3 rounded shadow-md">
          <h2 class="text-2xl font-semibold flex justify-center items-center gap-2">
            <i data-lucide="user-check" class="w-6 h-6 text-[#3B82F6]"></i> DATA MAHASISWA
          </h2>
        </div>
        <br>

        <div class="bg-white p-6 rounded-lg shadow-md max-w-6xl mx-auto">
          <div class="flex justify-between items-center mb-4">
            <a href="/tambahmahasiswa" class="bg-[#f472b6] hover:bg-[#ec4899] text-white px-4 py-2 rounded flex items-center gap-2 transition">
              <i data-lucide="plus" class="w-4 h-4"></i> Tambah
            </a>
            <input type="text" id="searchInput" placeholder="Cari mahasiswa..." class="border border-pink-400 p-2 rounded w-1/3 focus:outline-pink-500">
          </div>

          <div class="overflow-x-auto">
            <table id="mahasiswaTable" class="min-w-full border text-sm text-center">
              <thead class="bg-[#f472b6] text-white">
                <tr>
                  <th class="border px-4 py-2">NPM</th>
                  <th class="border px-4 py-2">Nama Mahasiswa</th>
                  <th class="border px-4 py-2">Program Studi</th>
                  <th class="border px-4 py-2">Judul Skripsi</th>
                  <th class="border px-4 py-2">Email</th>
                  <th class="border px-4 py-2">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($mahasiswa as $m)
                <tr class="hover:bg-pink-100 transition">
                  <td class="border px-4 py-2">{{ $m['npm'] }}</td>
                  <td class="border px-4 py-2">{{ $m['nama_mahasiswa'] }}</td>
                  <td class="border px-4 py-2">{{ $m['program_studi'] }}</td>
                  <td class="border px-4 py-2">{{ $m['judul_skripsi'] }}</td>
                  <td class="border px-4 py-2">{{ $m['email'] }}</td>
                  <td class="border px-4 py-2 flex items-center justify-center gap-3">
                    <a href="/editmahasiswa/{{ $m['npm'] }}" class="text-[#3B82F6] hover:text-[#1e40af]">
                      <i data-lucide="edit-3" class="w-5 h-5"></i>
                    </a>
                    <button onclick="confirmDelete('{{ $m['npm'] }}')" class="text-red-600 hover:text-red-800">
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
        text: "Data mahasiswa akan dihapus permanen!",
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
          form.action = `/hapusmahasiswa/${id}`;
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
      const rows = document.querySelectorAll("#mahasiswaTable tbody tr");
      rows.forEach(row => {
        const rowText = row.innerText.toLowerCase();
        row.style.display = rowText.includes(searchTerm) ? "" : "none";
      });
    });
  </script>
</body>
</html>
