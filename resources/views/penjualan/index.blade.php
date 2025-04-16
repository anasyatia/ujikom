@extends('layouts.app')

@section('title', 'Penjualan')

@section('content')
    <div class="container mx-auto px-6 py-6">
        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-500 mb-6">
            <a href="#" class="hover:underline">Home</a> / <span>Penjualan</span>
        </nav>

        <!-- Header & Add Button -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
            <h1 class="text-3xl font-bold text-gray-800">Data Penjualan</h1>
            @auth
                @if (Auth::user()->role === 'employee')
                    <a href="{{ route('sales.index') }}"
                        class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2 rounded-md shadow hover:bg-blue-700 transition">
                        <i class="fi fi-rr-plus-small"></i> Tambah Penjualan
                    </a>
                @endif
            @endauth
        </div>

        <!-- Export Button -->
        <div class="mb-6">
            <a href="{{ Auth::user()->role === 'admin' ? url('/export-penjualan') : '#' }}"
                onclick="{{ Auth::user()->role === 'employee' ? 'showExportAlert(); return false;' : '' }}"
                class="inline-block bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700 transition">
                <i class="fi fi-rr-download"></i> Export Penjualan (.xlsx)
            </a>
        </div>  

        <!-- Filter & Search -->
        <form method="GET" action="{{ route('penjualan.index') }}"
            class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <div class="flex items-center gap-2 text-sm">
                <span>Tampilkan</span>
                <select name="entries" onchange="this.form.submit()"
                    class="border border-gray-300 rounded px-2 py-1">
                    <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100</option>
                </select>
                <span>entri</span>
            </div>

            <div class="flex items-center gap-2 text-sm">
                <label for="search">Cari:</label>
                <input type="text" name="search" id="search"
                    class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-300"
                    placeholder="Nama pelanggan, tanggal, dsb..." value="{{ request('search') }}">
            </div>
        </form>

        <!-- Flash Message -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-md border border-green-300">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabel Penjualan -->
        <div class="bg-white shadow-lg rounded-xl overflow-x-auto">
            <table class="w-full text-sm text-gray-700">
                <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Nama Pelanggan</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Total Harga</th>
                        <th class="px-6 py-4">Dibuat Oleh</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualans as $index => $item)
                        <tr class="border-t hover:bg-blue-50 transition">
                            <td class="px-6 py-3">{{ $penjualans->firstItem() + $index }}</td>
                            <td class="px-6 py-3">{{ $item->member->nama ?? 'NON-MEMBER' }}</td>
                            <td class="px-6 py-3">{{ $item->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-3">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-3">{{ $item->user->name }}</td>
                            <td class="px-6 py-3 text-center space-x-2">
                                <button onclick="openModal('modal-{{ $item->id }}')"
                                    class="bg-green-400 text-white px-3 py-1 rounded hover:bg-green-500 text-xs transition">
                                    Lihat
                                </button>
                                <a href="{{ route('penjualan.pdf', $item->id) }}"
                                    class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-xs transition">
                                    Unduh Bukti
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="px-6 py-4">
                {{ $penjualans->links() }}
            </div>
        </div>

        <!-- Modals -->
        @foreach ($penjualans as $item)
            <div id="modal-{{ $item->id }}"
                class="fixed inset-0 z-50 flex items-center justify-center hidden">
                <div class="fixed inset-0 bg-black bg-opacity-40" onclick="closeModal('modal-{{ $item->id }}')"></div>

                <div class="bg-white w-full max-w-2xl mx-4 p-6 rounded-lg shadow-xl relative">
                    <button onclick="closeModal('modal-{{ $item->id }}')"
                        class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl font-bold">&times;</button>

                    <h2 class="text-lg font-bold text-gray-800 mb-4">Detail Penjualan</h2>
                    <hr class="mb-4">

                    <div class="flex justify-between text-sm text-gray-700 mb-4">
                        <div>
                            <p><strong>Status Member:</strong> {{ $item->status_member === 'member' ? 'Member' : 'NON-MEMBER' }}</p>
                            <p><strong>No. HP:</strong> {{ $item->member->telp ?? '-' }}</p>
                            <p><strong>Poin:</strong> {{ $item->poin_didapat ?? '0' }}</p>
                        </div>
                        <div class="text-right">
                            <p><strong>Bergabung Sejak:</strong> 
                                {{ $item->status_member === 'member' ? optional($item->created_at)->translatedFormat('d F Y') : '-' }}
                            </p>
                        </div>
                    </div>

                    <table class="w-full text-sm text-gray-700 mb-4">
                        <thead class="border-b font-semibold">
                            <tr>
                                <th class="py-2 text-left">Nama Produk</th>
                                <th class="py-2 text-center">Qty</th>
                                <th class="py-2 text-right">Harga</th>
                                <th class="py-2 text-right">Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($item->detailpenjualans as $detail)
                                <tr class="border-b">
                                    <td class="py-2">{{ $detail->produk->produk }}</td>
                                    <td class="py-2 text-center">{{ $detail->qty }}</td>
                                    <td class="py-2 text-right">Rp {{ number_format($detail->produk->harga, 0, ',', '.') }}</td>
                                    <td class="py-2 text-right">Rp {{ number_format($detail->produk->harga * $detail->qty, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="text-right font-semibold text-base mb-2">
                        Total: Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                    </div>

                    <div class="text-xs text-gray-500 text-center mb-4">
                        <p>Dibuat pada: {{ $item->created_at->format('Y-m-d H:i:s') }}</p>
                        <p>Oleh: {{ $item->dibuat_oleh }}</p>
                    </div>

                    <div class="text-right">
                        <button onclick="closeModal('modal-{{ $item->id }}')"
                            class="px-4 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700 transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Scripts -->
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function showExportAlert() {
            alert("Anda tidak memiliki akses.");
        }

        const searchInput = document.getElementById('search');
        let debounceTimeout;
        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    </script>
@endsection
