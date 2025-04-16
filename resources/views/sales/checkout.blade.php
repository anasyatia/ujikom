@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Checkout</h1>

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sales.process.member') }}" method="POST" id="checkout-form">
        @csrf
        <table class="w-full mb-4 text-left border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">Produk</th>
                    <th class="p-2 border">Harga</th>
                    <th class="p-2 border">Jumlah</th>
                    <th class="p-2 border">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $item)
                    <tr>
                        <td class="p-2 border">{{ $item['product']->produk }}</td>
                        <td class="p-2 border">Rp. {{ number_format($item['product']->harga, 0, ',', '.') }}</td>
                        <td class="p-2 border">{{ $item['quantity'] }}</td>
                        <td class="p-2 border">Rp. {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                    </tr>
                    <input type="hidden" name="orders[{{ $loop->index }}][product_id]" value="{{ $item['product']->id }}">
                    <input type="hidden" name="orders[{{ $loop->index }}][quantity]" value="{{ $item['quantity'] }}">
                    <input type="hidden" name="orders[{{ $loop->index }}][subtotal]" value="{{ $item['subtotal'] }}">
                @endforeach
            </tbody>
        </table>

        <div class="mb-4">
            <p>Total Harga: <strong id="total-harga-display">Rp. {{ number_format($totalPrice, 0, ',', '.') }}</strong></p>
            <input type="hidden" id="total_price_value" name="total_price" value="{{ $totalPrice }}">
        </div>

        <div class="mb-4">
            <label for="total_paid_display" class="block font-semibold">Total Bayar</label>
            <input type="text" id="total_paid_display" required inputmode="numeric"
                   class="border px-4 py-2 rounded w-full"
                   placeholder="Rp. 0">
            <input type="hidden" name="total_paid" id="total_paid" value="0">
            <p id="payment-warning" class="text-red-500 text-sm mt-1" style="display: none;">
                Total bayar tidak boleh kurang dari total harga!
            </p>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Apakah Member?</label>
                <label>
                    <input type="radio" name="is_member" value="1" required id="member-yes"> Ya
                </label>
            <label class="ml-4">
                <input type="radio" name="is_member" value="0" id="member-no" checked> Bukan
            </label>
        </div>

        <div class="mb-4" id="phone-input" style="display: none;">
            <label for="member-search" class="block font-semibold">Cari Member</label>
                <input type="text" id="member-search" class="border px-4 py-2 rounded w-full mb-2" placeholder="Cari berdasarkan Nama atau Nomor Telepon">
            <label for="number_telephone" class="block font-semibold">Pilih Member berdasarkan Nomor Telepon</label>
                <select name="number_telephone" id="number_telephone" class="border px-4 py-2 rounded w-full">
                    <option value="">-- Pilih Nomor Telepon Member --</option>
                    @foreach ($members as $member)
                        <option value="{{ $member->telp }}">
                    {{ $member->nama }} - {{ $member->telp }}
                        </option>
                    @endforeach
                </select>
            <p class="text-sm text-gray-600 mt-1">Jika nomor tidak ada, member baru akan dibuat saat transaksi selesai.</p>
        </div>

        <button type="submit" id="submit-button" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded disabled:opacity-50 disabled:cursor-not-allowed">
            Lanjutkan
        </button>
    </form>
</div>

<script>
    function togglePhoneInput(show) {
        const phoneInputDiv = document.getElementById('phone-input');
        const phoneSelect = document.getElementById('number_telephone');
        const memberSearchInput = document.getElementById('member-search');
        phoneInputDiv.style.display = show ? 'block' : 'none';
        if (show) {
            phoneSelect.setAttribute('required', 'required');
            filterMembers(''); // Show all options when member section is shown
        } else {
            phoneSelect.removeAttribute('required');
            phoneSelect.value = '';
            memberSearchInput.value = ''; // Optionally clear search bar
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const memberRadioYes = document.getElementById('member-yes');
        const memberRadioNo = document.getElementById('member-no');
        const memberSearchInput = document.getElementById('member-search');
        const memberSelect = document.getElementById('number_telephone');

        // Set initial state based on checked radio button
        const initialMemberRadio = document.querySelector('input[name="is_member"]:checked');
        if (initialMemberRadio) {
            togglePhoneInput(initialMemberRadio.value === '1');
        }

        // Add event listeners to the radio buttons
        memberRadioYes.addEventListener('click', function() {
            togglePhoneInput(true);
        });

        memberRadioNo.addEventListener('click', function() {
            togglePhoneInput(false);
        });

        function filterMembers(searchTerm) {
            const searchTermLower = searchTerm.toLowerCase();
            const options = memberSelect.querySelectorAll('option');
            options.forEach(option => {
                if (option.value === '') {
                    option.style.display = '';
                    return;
                }
                const text = option.textContent.toLowerCase();
                if (text.includes(searchTermLower)) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
        }

        memberSearchInput.addEventListener('input', function() {
            filterMembers(this.value);
        });
    });

    const totalPaidDisplayInput = document.getElementById('total_paid_display');
    const totalPaidHiddenInput = document.getElementById('total_paid');
    const paymentWarning = document.getElementById('payment-warning');
    const submitButton = document.getElementById('submit-button');
    const totalPrice = parseFloat(document.getElementById('total_price_value').value) || 0;

    function formatRupiah(angka) {
        let number_string = String(angka).replace(/[^\d]/g, '');
        if (!number_string || number_string === '0') {
            return 'Rp. 0';
        }
        let split = number_string.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return 'Rp. ' + rupiah;
    }

    function getRawValue(formattedValue) {
        if (!formattedValue) return 0;
        const raw = formattedValue.replace(/Rp\.\s?|(\.)/g, '');
        return parseInt(raw, 10) || 0;
    }

    function validatePayment() {
        const rawPaidValue = getRawValue(totalPaidDisplayInput.value);
        const isSufficient = rawPaidValue >= totalPrice;

        totalPaidHiddenInput.value = rawPaidValue;
        paymentWarning.style.display = isSufficient ? 'none' : 'block';
        submitButton.disabled = !isSufficient;
    }

    totalPaidDisplayInput.addEventListener('input', function(e) {
        const rawValue = getRawValue(this.value);
        this.value = formatRupiah(rawValue);
        validatePayment();
    });

    totalPaidDisplayInput.addEventListener('blur', function(e) {
        const rawValue = getRawValue(this.value);
        this.value = formatRupiah(rawValue);
        validatePayment();
    });

    document.addEventListener('DOMContentLoaded', () => {
        const initialRawValue = getRawValue(totalPaidDisplayInput.value);
        totalPaidDisplayInput.value = formatRupiah(initialRawValue);
        validatePayment();
    });
</script>
@endsection