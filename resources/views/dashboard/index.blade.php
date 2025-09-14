@extends('layouts.app')

@section('content')
    {{-- Header Sambutan --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Selamat Datang, {{ Auth::user()->name }}!</h1>
        <p class="text-sm text-gray-500 mt-1">Berikut adalah ringkasan dari manajemen kos Anda.</p>
    </div>

    {{-- Kartu Statistik Berwarna --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="relative rounded-xl bg-green-500 text-white shadow-lg p-6 overflow-hidden">
            <div class="absolute -right-4 -bottom-4">
                <svg class="h-24 w-24 text-green-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <h3 class="text-sm font-medium">Kamar Tersedia</h3>
            <div class="mt-4">
                <p class="text-4xl font-bold">{{ $stats['kamar_tersedia'] }}</p>
                <p class="text-xs opacity-80">dari total {{ $stats['kamar_tersedia'] + $stats['kamar_terisi'] }} kamar</p>
            </div>
        </div>

        <div class="relative rounded-xl bg-blue-500 text-white shadow-lg p-6 overflow-hidden">
            <div class="absolute -right-4 -bottom-4">
                <svg class="h-24 w-24 text-blue-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.125-1.274-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.125-1.274.356-1.857m0 0a3.001 3.001 0 015.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h3 class="text-sm font-medium">Total Penghuni</h3>
            <div class="mt-4">
                <p class="text-4xl font-bold">{{ $stats['total_penghuni'] }}</p>
                <p class="text-xs opacity-80">orang</p>
            </div>
        </div>

        <div class="relative rounded-xl bg-yellow-500 text-white shadow-lg p-6 overflow-hidden">
            <div class="absolute -right-4 -bottom-4">
                <svg class="h-24 w-24 text-yellow-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                </svg>
            </div>
            <h3 class="text-sm font-medium">Kamar Terisi</h3>
            <div class="mt-4">
                <p class="text-4xl font-bold">{{ $stats['kamar_terisi'] }}</p>
                <p class="text-xs opacity-80">kamar sedang ditempati</p>
            </div>
        </div>

        <div class="relative rounded-xl bg-red-500 text-white shadow-lg p-6 overflow-hidden">
            <div class="absolute -right-4 -bottom-4">
                <svg class="h-24 w-24 text-red-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H7a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
            <h3 class="text-sm font-medium">Tagihan Bulan Ini</h3>
            <div class="mt-4">
                <p class="text-3xl font-bold">Rp {{ number_format($stats['tagihan_belum_lunas'], 0, ',', '.') }}</p>
                <p class="text-xs opacity-80">tagihan belum lunas</p>
            </div>
        </div>
    </div>

    {{-- Tabel Penghuni Terbaru --}}
    <div class="rounded-xl border bg-white text-card-foreground shadow-sm">
        <div class="p-6 border-b">
            <h3 class="font-semibold tracking-tight text-lg text-gray-900">Penghuni Terbaru</h3>
            <p class="text-sm text-gray-500 mt-1">Daftar 5 penghuni yang baru saja masuk.</p>
        </div>
        <div class="p-0">
            <div class="relative w-full overflow-auto">
                <table class="w-full text-sm">
                    <thead class="[&_tr]:border-b">
                        <tr class="border-b">
                            <th class="h-12 px-6 text-left align-middle font-medium text-gray-500">Nama</th>
                            <th class="h-12 px-6 text-left align-middle font-medium text-gray-500">Kamar</th>
                            <th class="h-12 px-6 text-left align-middle font-medium text-gray-500 hidden sm:table-cell">
                                Tanggal Masuk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($penghuni_terbaru as $penghuni)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-6 align-middle font-medium text-gray-800">{{ $penghuni->name }}</td>
                                <td class="p-6 align-middle text-gray-600">{{ $penghuni->room->room_number }}</td>
                                <td class="p-6 align-middle text-gray-600 hidden sm:table-cell">
                                    {{ $penghuni->created_at->format('d F Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.125-1.274-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.125-1.274.356-1.857m0 0a3.001 3.001 0 015.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada penghuni</h3>
                                    <p class="mt-1 text-sm text-gray-500">Data penghuni baru akan muncul di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
