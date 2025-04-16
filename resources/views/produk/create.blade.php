@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
    <div class="container mx-auto px-6 py-10">
        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-500 mb-3">
            <a href="#" class="hover:underline">Home</a> /
            <a href="{{ route('produk.index') }}" class="hover:underline">Produk</a> /
            <span class="text-gray-800 font-medium">Tambah</span>
        </nav>

        <!-- Header -->
        <h1 class="text-4xl font-extrabold text-blue-700 mb-6">Tambah Produk</h1>

        <!-- Form Card -->
        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-8 rounded-xl shadow-md space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Produk -->
                <div>
                    <label class="block text-gray-600 font-semibold mb-1">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="produk" required
                        value="{{ old('produk') }}"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    @error('produk')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gambar Produk -->
                <div>
                    <label class="block text-gray-600 font-semibold mb-1">Gambar Produk <span class="text-red-500">*</span></label>
                    <input type="file" name="image" accept="image/*" required
                        class="w-full p-3 border border-gray-300 rounded-lg file:bg-blue-100 file:text-blue-700 file:border-0 file:rounded-md">
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga Produk -->
                <div>
                    <label class="block text-gray-600 font-semibold mb-1">Harga <span class="text-red-500">*</span></label>
                    <input type="text" id="harga" name="harga_display" required
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        placeholder="Rp 0">
                    <input type="hidden" name="harga" id="harga_hidden">
                    @error('harga')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stok Produk -->
                <div>
                    <label class="block text-gray-600 font-semibold mb-1">Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="stok" required value="{{ old('stok') }}"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    @error('stok')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit -->
            <div class="text-right pt-4">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow transition">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>

    <!-- Currency Formatting Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputHarga = document.getElementById('harga');
            const hiddenHarga = document.getElementById('harga_hidden');

            inputHarga.addEventListener('input', () => {
                let cleaned = inputHarga.value.replace(/[^0-9]/g, '');
                if (cleaned) {
                    let formatted = new Intl.NumberFormat('id-ID').format(cleaned);
                    inputHarga.value = 'Rp ' + formatted;
                    hiddenHarga.value = cleaned;
                } else {
                    inputHarga.value = '';
                    hiddenHarga.value = '';
                }
            });
        });
    </script>
@endsection
