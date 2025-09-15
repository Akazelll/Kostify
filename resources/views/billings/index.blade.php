@extends('layouts.app')

@section('content')
    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
            <p class="font-bold">Terjadi Kesalahan</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="list-disc ml-4">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Header Halaman --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manajemen Tagihan</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola pembayaran, cicilan, dan pantau denda keterlambatan.</p>
        </div>
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

    {{-- Tampilan Card Responsif --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($billings as $billing)
            @php
                $isLate = Carbon\Carbon::parse($billing->due_date)->addDays(7)->isPast() && $billing->status != 'paid';
            @endphp
            <div class="rounded-xl border bg-white text-card-foreground shadow-sm">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold tracking-tight text-lg">{{ $billing->penghuni->name ?? 'N/A' }}</h3>
                            <p class="text-sm text-gray-500">Kamar {{ $billing->penghuni->room->room_number ?? 'N/A' }}</p>
                        </div>
                        @php
                            $statusClass = '';
                            $statusText = '';
                            if ($isLate) {
                                $statusClass = 'bg-red-100 text-red-800';
                                $statusText = 'Terlambat';
                            } else {
                                switch ($billing->status) {
                                    case 'paid':
                                        $statusClass = 'bg-green-100 text-green-800';
                                        $statusText = 'Lunas';
                                        break;
                                    case 'partial':
                                        $statusClass = 'bg-blue-100 text-blue-800';
                                        $statusText = 'Cicilan';
                                        break;
                                    default:
                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                        $statusText = 'Belum Bayar';
                                        break;
                                }
                            }
                        @endphp
                        <div
                            class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $statusClass }}">
                            {{ $statusText }}
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Sisa Tagihan</p>
                        <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($billing->balance, 0, ',', '.') }}
                        </p>
                        @if ($isLate && $billing->late_fee > 0)
                            <p class="text-xs text-red-600">Termasuk denda Rp
                                {{ number_format($billing->late_fee, 0, ',', '.') }}</p>
                        @else
                            <p class="text-xs text-gray-500">Total Tagihan: Rp
                                {{ number_format($billing->amount, 0, ',', '.') }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center p-4 bg-gray-50 border-t rounded-b-xl gap-2">
                    @if ($billing->status != 'paid')
                        <button
                            class="payment-btn w-full inline-flex items-center justify-center rounded-md text-sm font-medium bg-gray-900 text-white hover:bg-gray-800 h-9 px-3"
                            data-billing-id="{{ $billing->id }}" data-billing-balance="{{ $billing->balance }}">
                            Catat Pembayaran
                        </button>
                    @endif
                    @if ($billing->status == 'paid')
                        <a href="{{ route('billings.invoice', $billing) }}"
                            class="w-full inline-flex items-center justify-center rounded-md text-sm font-medium bg-green-600 text-white hover:bg-green-700 h-9 px-3">
                            Cetak Invoice
                        </a>
                    @endif
                    <button
                        class="history-btn w-full inline-flex items-center justify-center rounded-md text-sm font-medium bg-gray-100 hover:bg-gray-200 h-9 px-3"
                        data-payments='{{ json_encode($billing->payments) }}'
                        data-billing-info='{{ json_encode($billing->only(['invoice_number', 'amount'])) }}'>
                        Lihat Riwayat
                    </button>
                </div>
            </div>
        @empty
            <div
                class="col-span-1 sm:col-span-2 lg:col-span-3 rounded-xl border-2 border-dashed bg-gray-50 p-12 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada tagihan</h3>
                <p class="mt-1 text-sm text-gray-500">Tidak ada data tagihan yang ditemukan untuk periode ini.</p>
            </div>
        @endforelse
    </div>

    {{-- Modal Form Pembayaran --}}
    <div id="payment-modal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 transition-opacity duration-300 ease-in-out opacity-0 pointer-events-none">
        <div id="payment-modal-content"
            class="relative w-full max-w-lg transition-transform duration-300 ease-in-out transform scale-95 opacity-0">
            <div class="relative rounded-xl border bg-white shadow-lg">
                <form id="payment-form" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6">
                        <h3 class="font-semibold text-2xl">Catat Pembayaran</h3>
                        <p class="text-sm text-muted-foreground">Sisa tagihan saat ini: <strong id="balance-text"></strong>
                        </p>
                    </div>
                    <div class="p-6 pt-0 space-y-4">
                        <div>
                            <label for="amount_paid" class="block text-sm font-medium">Jumlah Dibayar</label>
                            <input type="number" name="amount_paid" id="amount_paid" required
                                class="flex h-10 w-full rounded-md border px-3 mt-1">
                        </div>
                        <div>
                            <label for="payment_date" class="block text-sm font-medium">Tanggal Bayar</label>
                            <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}"
                                required class="flex h-10 w-full rounded-md border px-3 mt-1">
                        </div>
                        <div>
                            <label for="payment_proof" class="block text-sm font-medium">Bukti Pembayaran</label>
                            <input type="file" name="payment_proof" id="payment_proof" required
                                class="flex h-10 w-full rounded-md border p-2 mt-1">
                        </div>
                    </div>
                    <div class="flex items-center justify-end p-6 space-x-2">
                        <button type="button"
                            class="cancel-payment-btn inline-flex items-center justify-center rounded-md text-sm border h-10 px-4">Batal</button>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-md text-sm bg-gray-900 text-white h-10 px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Riwayat Pembayaran --}}
    <div id="history-modal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 transition-opacity duration-300 ease-in-out opacity-0 pointer-events-none">
        <div id="history-modal-content"
            class="relative w-full max-w-lg transition-transform duration-300 ease-in-out transform scale-95 opacity-0">
            <div class="relative rounded-xl border bg-white shadow-lg">
                <div class="p-6">
                    <h3 class="font-semibold text-2xl">Riwayat Pembayaran</h3>
                    <p class="text-sm text-muted-foreground">Tagihan: <strong id="history-invoice-number"></strong></p>
                </div>
                <div id="history-list" class="p-6 pt-0 max-h-80 overflow-y-auto">
                    {{-- Konten riwayat akan di-generate oleh JavaScript --}}
                </div>
                <div class="flex items-center justify-end p-6 bg-gray-50 border-t rounded-b-xl">
                    <button type="button"
                        class="close-history-btn inline-flex items-center justify-center rounded-md text-sm bg-gray-900 text-white h-10 px-4">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Filter
                const filterForm = document.getElementById('filter-form');
                const monthFilter = document.getElementById('month-filter');
                const yearFilter = document.getElementById('year-filter');
                monthFilter.addEventListener('change', () => filterForm.submit());
                yearFilter.addEventListener('change', () => filterForm.submit());

                // Payment Modal
                const paymentModal = document.getElementById('payment-modal');
                const paymentModalContent = document.getElementById('payment-modal-content');
                const paymentForm = document.getElementById('payment-form');
                const paymentBtns = document.querySelectorAll('.payment-btn');
                const cancelPaymentBtn = document.querySelector('.cancel-payment-btn');
                const balanceText = document.getElementById('balance-text');
                const amountPaidInput = document.getElementById('amount_paid');

                function openPaymentModal(billingId, balance) {
                    paymentForm.action = `/billings/${billingId}/payments`;
                    balanceText.textContent = `Rp ${Number(balance).toLocaleString('id-ID')}`;
                    amountPaidInput.max = balance;
                    amountPaidInput.value = balance;
                    paymentModal.classList.remove('pointer-events-none', 'opacity-0');
                    paymentModalContent.classList.remove('scale-95', 'opacity-0');
                }

                function closePaymentModal() {
                    paymentModal.classList.add('opacity-0');
                    paymentModalContent.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => paymentModal.classList.add('pointer-events-none'), 300);
                }

                paymentBtns.forEach(btn => btn.addEventListener('click', () => openPaymentModal(btn.dataset.billingId,
                    btn.dataset.billingBalance)));
                cancelPaymentBtn.addEventListener('click', closePaymentModal);

                // History Modal
                const historyModal = document.getElementById('history-modal');
                const historyModalContent = document.getElementById('history-modal-content');
                const historyBtns = document.querySelectorAll('.history-btn');
                const closeHistoryBtn = document.querySelector('.close-history-btn');
                const historyList = document.getElementById('history-list');
                const historyInvoiceNumber = document.getElementById('history-invoice-number');

                function openHistoryModal(payments, billingInfo) {
                    historyInvoiceNumber.textContent = billingInfo.invoice_number || 'N/A';
                    historyList.innerHTML = '';
                    if (payments && payments.length > 0) {
                        payments.forEach(payment => {
                            const paymentDate = new Date(payment.payment_date).toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: 'long',
                                year: 'numeric'
                            });
                            const proofUrl = payment.payment_proof_path ?
                                `/storage/${payment.payment_proof_path.replace('public/', '')}` : '#';

                            historyList.innerHTML += `
                            <div class="border-t py-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-semibold">Rp ${Number(payment.amount_paid).toLocaleString('id-ID')}</p>
                                        <p class="text-xs text-gray-500">Dibayar pada ${paymentDate}</p>
                                    </div>
                                    <a href="${proofUrl}" target="_blank" class="text-sm text-blue-600 hover:underline">Lihat Bukti</a>
                                </div>
                            </div>
                        `;
                        });
                    } else {
                        historyList.innerHTML =
                            '<p class="text-sm text-gray-500 py-4">Belum ada riwayat pembayaran.</p>';
                    }
                    historyModal.classList.remove('pointer-events-none', 'opacity-0');
                    historyModalContent.classList.remove('scale-95', 'opacity-0');
                }

                function closeHistoryModal() {
                    historyModal.classList.add('opacity-0');
                    historyModalContent.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => historyModal.classList.add('pointer-events-none'), 300);
                }

                historyBtns.forEach(btn => btn.addEventListener('click', () => {
                    const paymentsData = JSON.parse(btn.dataset.payments);
                    const billingInfoData = JSON.parse(btn.dataset.billingInfo);
                    openHistoryModal(paymentsData, billingInfoData);
                }));
                closeHistoryBtn.addEventListener('click', closeHistoryModal);

                document.addEventListener('keydown', (e) => {
                    if (e.key === "Escape") {
                        closePaymentModal();
                        closeHistoryModal();
                    }
                });
            });
        </script>
    @endpush
@endsection
