@extends('layouts.app')

@section('content')
    {{-- Notifikasi Sukses & Error --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h1 class="text-3xl font-bold tracking-tight">Manajemen Tagihan</h1>
        <div class="flex items-center gap-4">
            {{-- Filter Form --}}
            <form id="filter-form" action="{{ route('billings.index') }}" method="GET" class="flex items-center gap-2">
                <select name="month" id="month-filter" class="flex h-9 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endfor
                </select>
                <select name="year" id="year-filter" class="flex h-9 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                    @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
            <button id="add-btn" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-black text-white shadow hover:bg-black/90 h-9 px-4 py-2">
                Buat Tagihan
            </button>
        </div>
    </div>

    {{-- Tabel Tagihan --}}
    <div class="rounded-xl border bg-card text-card-foreground shadow">
        <div class="p-0">
            <div class="relative w-full overflow-auto">
                <table class="w-full caption-bottom text-sm">
                    <thead class="[&_tr]:border-b">
                        <tr class="border-b">
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Penghuni</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Kamar</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Jumlah Tagihan</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Jatuh Tempo</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="[&_tr:last-child]:border-0">
                        @forelse ($billings as $billing)
                            <tr class="border-b">
                                <td class="p-4 align-middle font-medium">{{ $billing->penghuni->name ?? 'N/A' }}</td>
                                <td class="p-4 align-middle text-muted-foreground">{{ $billing->penghuni->room->room_number ?? 'N/A' }}</td>
                                <td class="p-4 align-middle text-muted-foreground">Rp {{ number_format($billing->amount, 0, ',', '.') }}</td>
                                <td class="p-4 align-middle text-muted-foreground">{{ \Carbon\Carbon::parse($billing->due_date)->format('d F Y') }}</td>
                                <td class="p-4 align-middle">
                                    <div class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold {{ $billing->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $billing->status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                    </div>
                                </td>
                                <td class="p-4 align-middle">
                                    @if($billing->status == 'unpaid')
                                    <form action="{{ route('billings.pay', $billing) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-sm font-medium text-green-600 hover:underline">Tandai Lunas</button>
                                    </form>
                                    <span class="text-gray-300 mx-2">|</span>
                                    @endif
                                    <form action="{{ route('billings.destroy', $billing) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tagihan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-medium text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 align-middle text-center text-gray-500">
                                    Tidak ada data tagihan untuk periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Form Buat Tagihan --}}
    <div id="form-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 transition-opacity duration-300 ease-in-out opacity-0 pointer-events-none">
        <div id="modal-content" class="relative w-full max-w-lg transition-transform duration-300 ease-in-out transform scale-95 opacity-0">
            <div class="relative rounded-xl border bg-white text-card-foreground shadow-lg">
                <form id="billing-form" action="{{ route('billings.store') }}" method="POST">
                    @csrf
                    <div class="flex flex-col space-y-1.5 p-6">
                        <h3 class="font-semibold tracking-tight text-2xl">Buat Tagihan Baru</h3>
                        <p class="text-sm text-muted-foreground">Pilih penghuni dan tanggal jatuh tempo.</p>
                    </div>

                    <div class="p-6 pt-0 space-y-4">
                        <div>
                            <label for="penghuni_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Penghuni</label>
                            <select name="penghuni_id" id="penghuni_id" required class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                                <option value="">-- Pilih Penghuni --</option>
                                @foreach($penghunis as $penghuni)
                                    <option value="{{ $penghuni->id }}">
                                        {{ $penghuni->name }} (Kamar: {{ $penghuni->room->room_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Jatuh Tempo</label>
                            <input type="date" name="due_date" id="due_date" required class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                        </div>
                    </div>

                    <div class="flex items-center justify-end p-6 pt-0 space-x-2">
                        <button type="button" id="cancel-btn" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input h-9 px-4 py-2">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-black text-white shadow hover:bg-black/90 h-9 px-4 py-2">
                            Buat Tagihan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Logika Filter ---
            const filterForm = document.getElementById('filter-form');
            const monthFilter = document.getElementById('month-filter');
            const yearFilter = document.getElementById('year-filter');

            monthFilter.addEventListener('change', () => filterForm.submit());
            yearFilter.addEventListener('change', () => filterForm.submit());

            // --- Logika Modal ---
            const formModal = document.getElementById('form-modal');
            const modalContent = document.getElementById('modal-content');
            const addBtn = document.getElementById('add-btn');
            const cancelBtn = document.getElementById('cancel-btn');

            function openModal() {
                formModal.classList.remove('pointer-events-none', 'opacity-0');
                modalContent.classList.remove('scale-95', 'opacity-0');
            }

            function closeModal() {
                modalContent.classList.add('scale-95', 'opacity-0');
                formModal.classList.add('opacity-0');
                setTimeout(() => formModal.classList.add('pointer-events-none'), 300);
            }

            addBtn.addEventListener('click', openModal);
            cancelBtn.addEventListener('click', closeModal);
            formModal.addEventListener('click', e => (e.target === formModal) && closeModal());
            document.addEventListener('keydown', e => (e.key === "Escape") && closeModal());
        });
    </script>
    @endpush
@endsection