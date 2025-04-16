@extends('layouts.app')

@section('title', 'Index')

@section('content')
<div class="container mx-auto px-6 py-8">
    <nav class="text-sm text-gray-500 mb-4">
        <a href="#" class="hover:text-blue-600 transition">Home</a> / <span class="text-gray-800">Penjualan</span>
    </nav>

    <h1 class="text-3xl font-bold text-gray-800 mb-8">Penjualan</h1>

    <form action="{{ route('sales.process.product') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($data as $produk)
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5 flex flex-col items-center text-center hover:shadow-xl transition duration-300">
                    <img src="{{ $produk->image_url }}" alt="{{ $produk->produk }}"
                        class="w-36 h-36 object-cover rounded-lg mb-4 shadow-sm border border-gray-200">
                    
                    <h2 class="text-xl font-semibold text-gray-800 mb-1">{{ $produk->produk }}</h2>
                    <p class="text-gray-500 text-sm mb-1">Stok: <span class="font-medium">{{ $produk->stok }}</span></p>
                    <p class="text-blue-600 font-semibold text-lg mb-3">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>

                    <div class="flex items-center gap-3 mb-4">
                        <button type="button" onclick="kurang('{{ $produk->id }}')"
                            class="w-9 h-9 rounded-full bg-gray-200 hover:bg-gray-300 text-lg font-bold text-gray-700 transition">-</button>

                        <input type="number" id="jumlah_{{ $produk->id }}" name="jumlah[{{ $produk->id }}]"
                            value="0" min="0" max="{{ $produk->stok }}"
                            class="w-14 text-center border border-gray-300 rounded-md bg-gray-50 px-2 py-1 text-sm"
                            readonly>

                        <button type="button" onclick="tambah('{{ $produk->id }}', {{ $produk->stok }})"
                            class="w-9 h-9 rounded-full text-white text-lg font-bold transition
                            {{ $produk->stok == 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-blue-500 hover:bg-blue-600' }}"
                            {{ $produk->stok == 0 ? 'disabled' : '' }}>
                            +
                        </button>
                    </div>

                    <p class="text-sm text-gray-600">Sub Total: 
                        <span class="font-semibold text-gray-800">Rp <span id="subtotal_{{ $produk->id }}">0</span></span>
                    </p>
                </div>
            @endforeach
        </div>

        <div class="text-right mt-8">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow-md transition duration-200">
                Selanjutnya
            </button>
        </div>
    </form>
</div>

<script>
    function tambah(id, stok) {
        let input = document.getElementById('jumlah_' + id);
        let subtotal = document.getElementById('subtotal_' + id);
        let val = parseInt(input.value);
        if (val < stok) {
            input.value = val + 1;
            hitungSubtotal(id);
        }
    }

    function kurang(id) {
        let input = document.getElementById('jumlah_' + id);
        let subtotal = document.getElementById('subtotal_' + id);
        let val = parseInt(input.value);
        if (val > 0) {
            input.value = val - 1;
            hitungSubtotal(id);
        }
    }

    function hitungSubtotal(id) {
        let input = document.getElementById('jumlah_' + id);
        let harga = @json($data->pluck('harga', 'id'));
        let subtotal = document.getElementById('subtotal_' + id); 
        let jumlah = parseInt(input.value);
        subtotal.innerText = (jumlah * harga[id]).toLocaleString('id-ID');
    }
</script>
@endsection
