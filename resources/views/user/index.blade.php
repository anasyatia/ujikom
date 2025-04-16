@extends('layouts.app')

@section('title', 'User')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <nav class="text-sm text-gray-600 mb-5">
            <a href="#" class="hover:text-blue-600 transition">Home</a> / <span class="text-gray-800">User</span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
            <h1 class="text-3xl font-bold text-gray-800">Daftar User</h1>
            <a href="{{ route('user.create') }}"
                class="bg-blue-600 text-white py-2 px-5 rounded-md hover:bg-blue-700 transition duration-200">
                Tambah User
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md border-l-4 border-green-500">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-lg rounded-md overflow-hidden">
            <table class="w-full text-sm text-gray-700 border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left">#</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-left">Nama</th>
                        <th class="px-6 py-4 text-left">Role</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user as $index => $item)
                        <tr class="border-t hover:bg-gray-50 transition duration-200">
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">{{ $item->email }}</td>
                            <td class="px-6 py-4">{{ $item->name }}</td>
                            <td class="px-6 py-4">{{ $item->role }}</td>
                            <td class="px-6 py-4 text-center space-x-3">
                                <a href="{{ route('user.edit', $item->id) }}"
                                    class="bg-yellow-500 text-white py-1 px-4 rounded-md hover:bg-yellow-600 transition text-sm">
                                    Edit
                                </a>
                                <form action="{{ route('user.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 text-white py-1 px-4 rounded-md hover:bg-red-600 transition text-sm"
                                        onclick="return confirm('Yakin ingin menghapus?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
