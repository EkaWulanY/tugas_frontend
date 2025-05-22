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
