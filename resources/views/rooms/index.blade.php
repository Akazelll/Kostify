@extends('layouts.app')

@section('content')
    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Header Halaman yang Responsif --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manajemen Kamar</h1>
        <button id="add-room-btn"
            class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-gray-900 text-white shadow hover:bg-gray-800 h-10 px-4 py-2 w-full sm:w-auto">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Kamar
        </button>
    </div>

    {{-- Tampilan untuk Mobile (Card View) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:hidden gap-4">
        @forelse ($rooms as $room)
            <div class="rounded-xl border bg-white text-card-foreground shadow">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold tracking-tight text-lg">{{ $room->room_number }}</h3>
                            <p class="text-sm text-muted-foreground">{{ $room->type }}</p>
                        </div>
                        <div
                            class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $room->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($room->status) }}
                        </div>
                    </div>
                    <p class="text-2xl font-bold mt-4">Rp {{ number_format($room->price, 0, ',', '.') }}</p>
                    <div class="flex items-center pt-4 mt-4 border-t gap-2">
                        <button
                            class="edit-btn flex-1 inline-flex items-center justify-center rounded-md text-sm font-medium bg-gray-100 hover:bg-gray-200 h-9 px-3"
                            data-room='{{ $room->toJson() }}'>
                            Edit
                        </button>
                        <button
                            class="delete-btn flex-1 inline-flex items-center justify-center rounded-md text-sm font-medium bg-red-100 text-red-700 hover:bg-red-200 h-9 px-3"
                            data-id="{{ $room->id }}" data-number="{{ $room->room_number }}">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div
                class="col-span-1 sm:col-span-2 rounded-xl border bg-white text-card-foreground shadow p-6 text-center text-gray-500">
                Belum ada data kamar.
            </div>
        @endforelse
    </div>

    {{-- Tampilan untuk Desktop (Table View) --}}
    <div class="hidden lg:block rounded-xl border bg-white text-card-foreground shadow">
        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead class="[&_tr]:border-b">
                    <tr class="border-b">
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">No. Kamar</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tipe</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Harga</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Aksi</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0">
                    @forelse ($rooms as $room)
                        <tr class="border-b">
                            <td class="p-4 align-middle font-medium">{{ $room->room_number }}</td>
                            <td class="p-4 align-middle text-muted-foreground">{{ $room->type }}</td>
                            <td class="p-4 align-middle text-muted-foreground">Rp
                                {{ number_format($room->price, 0, ',', '.') }}</td>
                            <td class="p-4 align-middle">
                                <div
                                    class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $room->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($room->status) }}
                                </div>
                            </td>
                            <td class="p-4 align-middle">
                                <button class="edit-btn text-sm font-medium text-blue-600 hover:underline"
                                    data-room='{{ $room->toJson() }}'>Edit</button>
                                <span class="text-gray-300 mx-2">|</span>
                                <button class="delete-btn text-sm font-medium text-red-600 hover:underline"
                                    data-id="{{ $room->id }}" data-number="{{ $room->room_number }}">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">Belum ada data kamar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Form (Sama seperti sebelumnya, tidak perlu diubah) --}}
    <div id="room-modal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 transition-opacity duration-300 ease-in-out opacity-0 pointer-events-none">
        <div id="modal-content"
            class="relative w-full max-w-lg transition-transform duration-300 ease-in-out transform scale-95 opacity-0">
            <div class="relative rounded-xl border bg-white text-card-foreground shadow-lg">
                <form id="room-form" action="" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">

                    <div class="flex flex-col space-y-1.5 p-6">
                        <h3 id="modal-title" class="font-semibold tracking-tight text-2xl"></h3>
                        <p id="modal-description" class="text-sm text-muted-foreground"></p>
                    </div>

                    <div class="p-6 pt-0 space-y-4">
                        <div>
                            <label for="room_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor
                                Kamar</label>
                            <input type="text" name="room_number" id="room_number" value="{{ old('room_number') }}"
                                required
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm @error('room_number') border-red-500 @enderror">
                            @error('room_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Kamar</label>
                            <select name="type" id="type" required
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                                <option value="Reguler" @if (old('type') == 'Reguler') selected @endif>Reguler</option>
                                <option value="Eksklusif" @if (old('type') == 'Eksklusif') selected @endif>Eksklusif
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga per
                                Bulan</label>
                            <input type="number" name="price" id="price" value="{{ old('price') }}" required
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm @error('price') border-red-500 @enderror">
                            @error('price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status" required
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                                <option value="available" @if (old('status') == 'available') selected @endif>Available
                                </option>
                                <option value="occupied" @if (old('status') == 'occupied') selected @endif>Occupied
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end p-6 pt-0 space-x-2">
                        <button type="button" id="cancel-btn"
                            class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium border border-input bg-background shadow-sm hover:bg-accent h-9 px-4 py-2">
                            Batal
                        </button>
                        <button type="submit" id="submit-btn"
                            class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-black text-white shadow hover:bg-black/90 h-9 px-4 py-2 w-24">
                            <span class="btn-text">Simpan</span>
                            <svg class="animate-spin h-5 w-5 text-white hidden btn-spinner"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="delete-modal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 transition-opacity duration-300 ease-in-out opacity-0 pointer-events-none">
        <div id="delete-modal-content"
            class="relative w-full max-w-md transition-transform duration-300 ease-in-out transform scale-95 opacity-0">
            <div class="relative rounded-xl border bg-white text-card-foreground shadow-lg">
                <form id="delete-form" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="p-6 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="mt-5 text-lg font-semibold text-gray-900">Hapus Kamar</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Apakah Anda yakin ingin menghapus kamar <strong id="room-number-to-delete"></strong>?
                                Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center p-6 pt-0 space-x-2 bg-gray-50 rounded-b-xl">
                        <button type="button"
                            class="cancel-delete-btn inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background shadow-sm hover:bg-accent h-9 px-4 py-2 w-24">
                            Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-red-600 text-white shadow-sm hover:bg-red-700 h-9 px-4 py-2 w-24">
                            Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // --- VARIABEL UMUM ---
                const form = document.getElementById('room-form');
                const submitBtn = document.getElementById('submit-btn');
                const btnText = submitBtn.querySelector('.btn-text');
                const btnSpinner = submitBtn.querySelector('.btn-spinner');

                // --- LOGIKA MODAL TAMBAH/EDIT ---
                const roomModal = document.getElementById('room-modal');
                const modalContent = document.getElementById('modal-content');
                const addBtn = document.getElementById('add-room-btn');
                const cancelBtn = document.getElementById('cancel-btn');
                const formMethodInput = document.getElementById('form-method');
                const modalTitle = document.getElementById('modal-title');
                const modalDescription = document.getElementById('modal-description');
                const editBtns = document.querySelectorAll('.edit-btn');

                function openRoomModal() {
                    roomModal.classList.remove('pointer-events-none', 'opacity-0');
                    modalContent.classList.remove('scale-95', 'opacity-0');
                }

                function closeRoomModal() {
                    modalContent.classList.add('scale-95', 'opacity-0');
                    roomModal.classList.add('opacity-0');
                    setTimeout(() => roomModal.classList.add('pointer-events-none'), 300);
                }

                function setupCreateForm() {
                    form.reset();
                    form.action = '{{ route('rooms.store') }}';
                    formMethodInput.value = 'POST';
                    modalTitle.innerText = 'Tambah Kamar Baru';
                    modalDescription.innerText = 'Isi detail kamar di bawah ini untuk menambahkannya ke sistem.';
                    openRoomModal();
                }

                function setupEditForm(room) {
                    form.reset();
                    form.action = `/rooms/${room.id}`;
                    formMethodInput.value = 'PUT';
                    modalTitle.innerText = `Edit Kamar ${room.room_number}`;
                    modalDescription.innerText = 'Perbarui detail kamar di bawah ini.';

                    document.getElementById('room_number').value = room.room_number;
                    document.getElementById('type').value = room.type;
                    document.getElementById('price').value = room.price;
                    document.getElementById('status').value = room.status;

                    openRoomModal();
                }

                addBtn.addEventListener('click', setupCreateForm);
                cancelBtn.addEventListener('click', closeRoomModal);
                roomModal.addEventListener('click', e => (e.target === roomModal) && closeRoomModal());

                editBtns.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const roomData = JSON.parse(btn.dataset.room);
                        setupEditForm(roomData);
                    });
                });

                // --- LOGIKA MODAL HAPUS ---
                const deleteModal = document.getElementById('delete-modal');
                const deleteModalContent = document.getElementById('delete-modal-content');
                const deleteForm = document.getElementById('delete-form');
                const cancelDeleteBtn = document.querySelector('.cancel-delete-btn');
                const roomNumberText = document.getElementById('room-number-to-delete');
                const deleteBtns = document.querySelectorAll('.delete-btn');

                function openDeleteModal(id, roomNumber) {
                    deleteForm.action = `/rooms/${id}`;
                    roomNumberText.innerText = roomNumber;
                    deleteModal.classList.remove('pointer-events-none', 'opacity-0');
                    deleteModalContent.classList.remove('scale-95', 'opacity-0');
                }

                function closeDeleteModal() {
                    deleteModalContent.classList.add('scale-95', 'opacity-0');
                    deleteModal.classList.add('opacity-0');
                    setTimeout(() => deleteModal.classList.add('pointer-events-none'), 300);
                }

                deleteBtns.forEach(btn => {
                    btn.addEventListener('click', () => {
                        openDeleteModal(btn.dataset.id, btn.dataset.number);
                    });
                });
                cancelDeleteBtn.addEventListener('click', closeDeleteModal);
                deleteModal.addEventListener('click', e => (e.target === deleteModal) && closeDeleteModal());

                // --- EVENT LISTENERS UMUM ---
                document.addEventListener('keydown', function(event) {
                    if (event.key === "Escape") {
                        closeRoomModal();
                        closeDeleteModal();
                    }
                });

                form.addEventListener('submit', function() {
                    submitBtn.disabled = true;
                    btnText.classList.add('hidden');
                    btnSpinner.classList.remove('hidden');
                });

                @if ($errors->any())
                    openRoomModal();
                @endif
            });
        </script>
    @endpush
@endsection
