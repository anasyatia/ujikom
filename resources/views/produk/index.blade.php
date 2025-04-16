@extends('layouts.app')

@section('title', 'Produk')

@section('content')
    <div class="container mx-auto px-6 py-10 space-y-8">
        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-500 mb-2">
            <a href="#" class="hover:underline">Home</a> /
            <span class="text-gray-700 font-medium">Produk</span>
        </nav>

        <!-- Page Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-extrabold text-blue-700 mb-1">Produk</h1>
                <p class="text-gray-500 text-sm">Kelola data produk di sini.</p>
            </div>
            @auth
                @if (Auth::user()->role === 'admin')
                    <a href="{{ route('produk.create') }}"
                        class="inline-flex items-center bg-blue-600 text-white px-5 py-2.5 rounded-lg shadow hover:bg-blue-700 transition">
                        <i class="fi fi-rr-plus mr-2"></i> Tambah Produk
                    </a>
                @endif
            @endauth
        </div>

        <!-- Flash message -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Produk Table -->
        <div class="bg-white rounded-xl shadow overflow-x-auto">
            <table class="w-full text-sm text-left min-w-[800px]">
                <thead class="bg-blue-50 border-b border-blue-100 text-blue-800">
                    <tr>
                        <th class="px-6 py-4 font-semibold">#</th>
                        <th class="px-6 py-4 font-semibold">Gambar</th>
                        <th class="px-6 py-4 font-semibold">Nama Produk</th>
                        <th class="px-6 py-4 font-semibold">Harga</th>
                        <th class="px-6 py-4 font-semibold text-center">Stok</th>
                        @auth
                            @if (Auth::user()->role === 'admin')
                                <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                            @endif
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produks as $index => $produk)
                        <tr class="border-t hover:bg-gray-50 transition">
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <img src="{{ $produk->image_url }}" alt="{{ $produk->produk }}"
                                    class="rounded-lg w-20 h-20 object-cover border shadow">
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $produk->produk }}</td>
                            <td class="px-6 py-4 text-gray-700">Rp{{ number_format($produk->harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center text-gray-700">{{ $produk->stok }}</td>
                            @auth
                                @if (Auth::user()->role === 'admin')
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-2 flex-wrap">
                                            <a href="{{ route('produk.edit', $produk->id) }}"
                                                class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-1.5 rounded-md shadow text-sm">
                                                <i class="fi fi-rr-pencil mr-1"></i> Edit
                                            </a>
                                            <button onclick="openModal({{ $produk->id }}, '{{ $produk->produk }}', {{ $produk->stok }})"
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1.5 rounded-md shadow text-sm">
                                                <i class="fi fi-rr-refresh mr-1"></i> Stok
                                            </button>
                                            <form action="{{ route('produk.destroy', $produk->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-md shadow text-sm">
                                                    <i class="fi fi-rr-trash mr-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            @endauth
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="modalUpdateStok" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div id="modalContent" class="bg-white w-full max-w-md rounded-xl shadow-xl transform scale-95 opacity-0 transition-all duration-200">
            <div class="flex justify-between items-center border-b px-6 py-4">
                <h2 class="text-xl font-bold text-blue-700">Update Stok Produk</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
            </div>
            <form id="updateStokForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="p-6">
                    <label class="block text-sm text-gray-600 mb-1">Nama Produk</label>
                    <input type="text" id="produkNama" class="w-full p-3 border rounded-lg bg-gray-100" readonly>

                    <label class="block text-sm text-gray-600 mt-4 mb-1">Stok</label>
                    <input type="number" name="stok" id="produkStok"
                        class="w-full p-3 border rounded-lg" required>
                </div>
                <div class="flex justify-end px-6 py-4 border-t">
                    <button type="button" onclick="closeModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 ml-2">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Script -->
    <script>
        function openModal(id, nama, stok) {
            document.getElementById("produkNama").value = nama;
            document.getElementById("produkStok").value = stok;
            document.getElementById("updateStokForm").action = "/produk/" + id + "/stok";

            const modal = document.getElementById("modalUpdateStok");
            const modalContent = document.getElementById("modalContent");
            modal.classList.remove("hidden");
            setTimeout(() => {
                modal.classList.add("opacity-100");
                modalContent.classList.remove("scale-95", "opacity-0");
                modalContent.classList.add("scale-100", "opacity-100");
            }, 50);
        }

        function closeModal() {
            const modal = document.getElementById("modalUpdateStok");
            const modalContent = document.getElementById("modalContent");
            modalContent.classList.remove("scale-100", "opacity-100");
            modalContent.classList.add("scale-95", "opacity-0");
            setTimeout(() => {
                modal.classList.remove("opacity-100");
                modal.classList.add("hidden");
            }, 200);
        }
    </script>
@endsection
