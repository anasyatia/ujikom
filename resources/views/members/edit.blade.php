@extends('layouts.app')

@section('title', 'Edit Member')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <nav class="text-sm text-gray-500 mb-4">
            <a href="{{ route('members.index') }}" class="hover:text-blue-600 transition">Home</a> /
            <span class="text-gray-800">Member</span>
        </nav>

        <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Member</h1>

        <form action="{{ route('members.update', $member->id) }}" method="POST"
            class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Member -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Member</label>
                    <input type="text" name="member_code" readonly
                        value="{{ old('member_code', $member->member_code) }}"
                        class="w-full px-4 py-2 border border-gray-300 bg-gray-100 rounded-lg focus:outline-none">
                </div>

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $member->nama) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="text" name="telp" value="{{ old('telp', $member->telp) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
                    @error('telp')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $member->email) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat</label>
                    <textarea name="address" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none">{{ old('address', $member->address) }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $member->date_of_birth) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
                    @error('date_of_birth')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Poin -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Poin <span class="text-red-500">*</span></label>
                    <input type="number" name="poin" value="{{ old('poin', $member->poin) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @error('poin')
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
@endsection