@extends('layouts.app')

@section('title', 'Member')

@section('content')
    <div class="container mx-auto px-6 py-10 space-y-8">
        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-500 mb-2">
            <a href="#" class="hover:underline">Home</a> /
            <span class="text-gray-700 font-medium">Member</span>
        </nav>

        <!-- Page Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-extrabold text-blue-700 mb-1">Member</h1>
                <p class="text-gray-500 text-sm">Kelola data member di sini.</p>
            </div>
            @auth
                @if (Auth::user()->role === 'employee')
                    <a href="{{ route('members.create') }}"
                        class="inline-flex items-center bg-blue-600 text-white px-5 py-2.5 rounded-lg shadow hover:bg-blue-700 transition">
                        <i class="fi fi-rr-plus mr-2"></i> Tambah Member
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

        <!-- Search -->
        <form method="GET" action="{{ route('members.index') }}" class="mb-6">
            <input type="text" name="search" placeholder="Cari nama member..."
                value="{{ request('search') }}"
                class="w-full md:w-1/3 px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200">
        </form>

        <!-- Member Table -->
        <div class="bg-white rounded-xl shadow overflow-x-auto">
            <table class="w-full text-sm text-left min-w-[700px]">
                <thead class="bg-blue-50 border-b border-blue-100 text-blue-800">
                    <tr>
                        <th class="px-6 py-4 font-semibold">#</th>
                        <th class="px-6 py-4 font-semibold">Member Code</th>
                        <th class="px-6 py-4 font-semibold">Nama</th>
                        <th class="px-6 py-4 font-semibold">Telepon</th>
                        {{-- <th class="px-6 py-4 font-semibold">Email</th> --}}
                        <th class="px-6 py-4 font-semibold">Poin</th>
                        @auth
                            @if (Auth::user()->role === 'employee')
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                            @endif
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @foreach ($members as $index => $member)
                        <tr class="border-t hover:bg-gray-50 transition">
                            <td class="px-6 py-4">{{ $loop->iteration + ($members->currentPage() - 1) * $members->perPage() }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $member->member_code }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $member->nama }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $member->telp ?? '-' }}</td>
                            {{-- <td class="px-6 py-4 text-gray-700">{{ $member->email ?? '-' }}</td> --}}
                            <td class="px-6 py-4 text-gray-700">{{ $member->poin }}</td>
                            @auth
                                @if (Auth::user()->role === 'employee')
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2 flex-wrap">
                                        <a href="{{ route('members.edit', $member->id) }}"
                                            class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-1.5 rounded-md shadow text-sm">
                                            <i class="fi fi-rr-pencil mr-1"></i> Edit
                                        </a>
                                        <form action="{{ route('members.destroy', $member->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus?')">
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

        <!-- Pagination -->
        <div class="mt-6">
            {{ $members->links() }}
        </div>
    </div>
@endsection
