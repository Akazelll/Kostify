@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Dashboard Ringkasan</h1>
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="text-sm font-medium">Pendapatan Bulan Ini</h3>
            <p class="text-2xl font-bold">Rp 0</p>
        </div>
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="text-sm font-medium">Total Tunggakan</h3>
            <p class="text-2xl font-bold text-red-500">Rp 0</p>
        </div>
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="text-sm font-medium">Okupansi Kamar</h3>
            <p class="text-2xl font-bold">0 / 0</p>
        </div>
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="text-sm font-medium">Total Penghuni</h3>
            <p class="text-2xl font-bold">0</p>
        </div>
    </div>
@endsection