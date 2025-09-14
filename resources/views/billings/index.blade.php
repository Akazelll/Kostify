@extends('layouts.app')

@section('content')
    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Header Halaman yang Responsif --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manajemen Tagihan</h1>
        {{-- Filter Form --}}
        <form id="filter-form" action="{{ route('billings.index') }}" method="GET"
            class="flex items-center gap-2 w-full sm:w-auto">
            <select name="month" id="month-filter"
                class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                        {{ Carbon\Carbon::create()->month($m)->format('F') }}
                    </option>
                @endfor
            </select>
            <select name="year" id="year-filter"
                class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}
                    </option>
                @endfor
            </select>
        </form>
    </div>

    {{-- Tampilan untuk Mobile (Card View) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:hidden gap-4">
        @forelse ($billings as $billing)
            <div class="rounded-xl border bg-white text-card-foreground shadow">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold tracking-tight text-lg">{{ $billing->penghuni->name ?? 'N/A' }}</h3>
                            <p class="text-sm text-muted-foreground">Kamar
                                {{ $billing->penghuni->room->room_number ?? 'N/A' }}</p>
                        </div>
                        <div
                            class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $billing->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $billing->status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                        </div>
                    </div>
                    <p class="text-2xl font-bold mt-4">Rp {{ number_format($billing->amount, 0, ',', '.') }}</p>
                    <p class="text-xs text-muted-foreground">Jatuh Tempo:
                        {{ Carbon\Carbon::parse($billing->due_date)->format('d M Y') }}</p>
                    <div class="flex items-center pt-4 mt-4 border-t gap-2">
                        @if ($billing->status == 'unpaid')
                            <button
                                class="pay-btn flex-1 inline-flex items-center justify-center rounded-md text-sm font-medium bg-gray-900 text-white hover:bg-gray-800 h-9 px-3"
                                data-billing-id="{{ $billing->id }}">
                                Konfirmasi Bayar
                            </button>
                        @else
                            <a href="{{ Storage::url($billing->payment_proof_path) }}" target="_blank"
                                class="flex-1 inline-flex items-center justify-center rounded-md text-sm font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 h-9 px-3">
                                Lihat Bukti
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div
                class="col-span-1 sm:col-span-2 rounded-xl border bg-white text-card-foreground shadow p-6 text-center text-gray-500">
                Tidak ada data tagihan untuk periode ini.
            </div>
        @endforelse
    </div>

    {{-- Tampilan untuk Desktop (Table View) --}}
    <div class="hidden lg:block rounded-xl border bg-white text-card-foreground shadow">
        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead class="[&_tr]:border-b">
                    <tr class="border-b">
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Penghuni</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Jumlah</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Jatuh Tempo</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Aksi</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0">
                    @forelse ($billings as $billing)
                        <tr class="border-b">
                            <td class="p-4 align-middle font-medium">{{ $billing->penghuni->name ?? 'N/A' }} <span
                                    class="text-muted-foreground font-normal">(Kamar
                                    {{ $billing->penghuni->room->room_number ?? 'N/A' }})</span></td>
                            <td class="p-4 align-middle text-muted-foreground">Rp
                                {{ number_format($billing->amount, 0, ',', '.') }}</td>
                            <td class="p-4 align-middle text-muted-foreground">
                                {{ Carbon\Carbon::parse($billing->due_date)->format('d F Y') }}</td>
                            <td class="p-4 align-middle">
                                <div
                                    class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $billing->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $billing->status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                </div>
                            </td>
                            <td class="p-4 align-middle">
                                @if ($billing->status == 'unpaid')
                                    <button class="pay-btn text-sm font-medium text-blue-600 hover:underline"
                                        data-billing-id="{{ $billing->id }}">
                                        Konfirmasi Bayar
                                    </button>
                                @else
                                    <a href="{{ Storage::url($billing->payment_proof_path) }}" target="_blank"
                                        class="text-sm font-medium text-blue-600 hover:underline">
                                        Lihat Bukti
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">Tidak ada data tagihan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Form Pembayaran --}}
    <div id="payment-modal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 transition-opacity duration-300 ease-in-out opacity-0 pointer-events-none">
        <div id="modal-content"
            class="relative w-full max-w-lg transition-transform duration-300 ease-in-out transform scale-95 opacity-0">
            <div class="relative rounded-xl border bg-white text-card-foreground shadow-lg">
                <form id="payment-form" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="flex flex-col space-y-1.5 p-6">
                        <h3 class="font-semibold tracking-tight text-2xl">Konfirmasi Pembayaran</h3>
                        <p class="text-sm text-muted-foreground">Upload bukti pembayaran untuk menandai tagihan sebagai
                            lunas.</p>
                    </div>
                    <div class="p-6 pt-0">
                        <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-1">Bukti
                            Pembayaran</label>
                        <input type="file" name="payment_proof" id="payment_proof" required
                            class="flex h-10 w-full rounded-md border border-input bg-transparent p-2 text-sm">
                    </div>
                    <div class="flex items-center justify-end p-6 pt-0 space-x-2">
                        <button type="button" id="cancel-btn"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium border h-10 px-4 py-2">Batal</button>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-gray-900 text-white h-10 px-4 py-2">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // --- Filter ---
                const filterForm = document.getElementById('filter-form');
                document.getElementById('month-filter').addEventListener('change', () => filterForm.submit());
                document.getElementById('year-filter').addEventListener('change', () => filterForm.submit());

                // --- Payment Modal ---
                const paymentModal = document.getElementById('payment-modal');
                const modalContent = paymentModal.querySelector('#modal-content');
                const paymentForm = document.getElementById('payment-form');
                const payBtns = document.querySelectorAll('.pay-btn');
                const cancelBtn = document.getElementById('cancel-btn');

                function openModal(billingId) {
                    paymentForm.action = `/billings/${billingId}/pay` + window.location.search; // Keep filter params
                    paymentModal.classList.remove('pointer-events-none', 'opacity-0');
                    modalContent.classList.remove('scale-95', 'opacity-0');
                }

                function closeModal() {
                    modalContent.classList.add('scale-95', 'opacity-0');
                    paymentModal.classList.add('opacity-0');
                    setTimeout(() => paymentModal.classList.add('pointer-events-none'), 300);
                }

                payBtns.forEach(btn => {
                    btn.addEventListener('click', () => {
                        openModal(btn.dataset.billingId);
                    });
                });

                cancelBtn.addEventListener('click', closeModal);
                paymentModal.addEventListener('click', e => (e.target === paymentModal) && closeModal());
                document.addEventListener('keydown', e => (e.key === "Escape") && closeModal());
            });
        </script>
    @endpush
@endsection
