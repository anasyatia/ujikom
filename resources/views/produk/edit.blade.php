@extends('layouts.app')

@section('title', 'Produk')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <nav class="text-sm text-gray-500 mb-4">
            <a href="#" class="hover:text-blue-600 transition">Home</a> / <span class="text-gray-800">Produk</span>
        </nav>

        <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Produk</h1>

        <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Produk -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="produk"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        value="{{ old('produk', $produk->produk) }}" required>
                    @error('produk')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Upload Gambar -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Gambar Produk</label>
                    <input type="file" name="image"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none"
                        accept="image/*">
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    @if ($produk->image)
                        <div class="mt-3">
                            <p class="text-sm text-gray-600 mb-1">Gambar Saat Ini:</p>
                            <img src="{{ asset('storage/' . $produk->image) }}"
                                alt="{{ $produk->produk }}"
                                class="w-24 h-24 object-cover rounded shadow-md border border-gray-200">
                        </div>
                    @endif
                </div>

                <!-- Harga -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Harga <span class="text-red-500">*</span></label>
                    <input type="text" name="harga_display" id="harga"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        value="Rp {{ number_format($produk->harga, 0, ',', '.') }}" required>
                    <input type="hidden" name="harga" id="harga_hidden" value="{{ $produk->harga }}">
                    @error('harga')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stok -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="stok"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        value="{{ old('stok', $produk->stok) }}" required>
                    @error('stok')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 text-right">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition duration-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputHarga = document.getElementById('harga');
            const hiddenHarga = document.getElementById('harga_hidden');

            inputHarga.addEventListener('input', () => {
                let raw = inputHarga.value.replace(/\D/g, '');
                if (raw) {
                    inputHarga.value = 'Rp ' + parseInt(raw).toLocaleString('id-ID');
                    hiddenHarga.value = parseInt(raw);
                } else {
                    inputHarga.value = '';
                    hiddenHarga.value = '';
                }
            });
        });
    </script>
@endsection
