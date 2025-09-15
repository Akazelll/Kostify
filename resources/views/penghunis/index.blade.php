@extends('layouts.app')

@section('content')
    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
            <p class="font-bold">Sukses</p>
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
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manajemen Penghuni</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data penghuni dan kamar yang mereka tempati.</p>
        </div>
        <button id="add-btn"
            class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-gray-900 text-white shadow-sm hover:bg-gray-800 h-10 px-4 py-2 w-full sm:w-auto">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Penghuni
        </button>
    </div>

    {{-- Tampilan Grid Responsif --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse ($penghunis as $penghuni)
            <div
                class="rounded-xl border bg-white text-card-foreground shadow-sm hover:shadow-lg transition-shadow duration-300 flex flex-col">
                {{-- Gambar Tanda Pengenal --}}
                <div class="aspect-video w-full">
                    {{-- Ini adalah cara pemanggilan gambar yang paling andal --}}
                    <img src="{{ asset('uploads/' . $penghuni->identity_card_path) }}"
                        alt="Tanda Pengenal {{ $penghuni->name }}"
                        class="w-full h-full object-cover rounded-t-xl bg-gray-100">
                </div>
                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex-1">
                        <h3 class="font-semibold tracking-tight text-lg text-gray-900">{{ $penghuni->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $penghuni->phone_number }}</p>
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-xs text-gray-500">Menempati Kamar</p>
                            <p class="font-semibold text-gray-800">{{ $penghuni->room->room_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center pt-4 mt-4 border-t gap-2">
                        <button
                            class="edit-btn w-full inline-flex items-center justify-center rounded-md text-sm font-medium bg-gray-100 text-gray-800 hover:bg-gray-200 h-9 px-3"
                            data-penghuni='{{ $penghuni->toJson() }}' data-rooms='{{ json_encode($availableRooms) }}'>
                            Edit
                        </button>
                        <button
                            class="delete-btn w-full inline-flex items-center justify-center rounded-md text-sm font-medium bg-red-100 text-red-700 hover:bg-red-200 h-9 px-3"
                            data-id="{{ $penghuni->id }}" data-name="{{ $penghuni->name }}">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div
                class="col-span-1 sm:col-span-2 lg:col-span-3 xl:col-span-4 rounded-xl border-2 border-dashed bg-gray-50 p-12 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.125-1.274-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.125-1.274.356-1.857m0 0a3.001 3.001 0 015.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada penghuni</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan penghuni baru.</p>
            </div>
        @endforelse
    </div>

    {{-- Modal Form (Untuk Tambah & Edit) --}}
    <div id="form-modal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 transition-opacity duration-300 ease-in-out opacity-0 pointer-events-none">
        <div id="modal-content"
            class="relative w-full max-w-lg transition-transform duration-300 ease-in-out transform scale-95 opacity-0">
            <div class="relative rounded-xl border bg-white text-card-foreground shadow-lg">
                <form id="penghuni-form" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <div class="flex flex-col space-y-1.5 p-6">
                        <h3 id="modal-title" class="font-semibold tracking-tight text-2xl"></h3>
                        <p id="modal-description" class="text-sm text-muted-foreground"></p>
                    </div>
                    <div class="p-6 pt-0 space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" id="name" required
                                class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                        </div>
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor
                                HP</label>
                            <input type="text" name="phone_number" id="phone_number" required
                                class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                        </div>
                        <div>
                            <label for="room_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih
                                Kamar</label>
                            <select name="room_id" id="room_id" required
                                class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                                {{-- Options populated by JS --}}
                            </select>
                        </div>
                        <div>
                            <label for="identity_card" class="block text-sm font-medium text-gray-700 mb-1">Upload Tanda
                                Pengenal</label>
                            <input type="file" name="identity_card" id="identity_card"
                                class="flex h-10 w-full rounded-md border border-input bg-transparent p-2 text-sm">
                            <small id="identity-card-help" class="text-xs text-gray-500">Kosongkan jika tidak ingin
                                mengubah.</small>
                        </div>
                    </div>
                    <div class="flex items-center justify-end p-6 pt-0 space-x-2 bg-gray-50 border-t rounded-b-xl">
                        <button type="button" id="cancel-btn"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium border h-10 px-4 py-2">Batal</button>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-gray-900 text-white shadow hover:bg-gray-800 h-10 px-4 py-2 w-24">Simpan</button>
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
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="mt-5 text-lg font-semibold text-gray-900">Hapus Penghuni</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Apakah Anda yakin ingin menghapus <strong id="name-to-delete"></strong>? Tindakan ini tidak
                                dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center p-6 pt-0 space-x-2 bg-gray-50 rounded-b-xl">
                        <button type="button"
                            class="cancel-delete-btn inline-flex items-center justify-center rounded-md text-sm font-medium border h-9 px-4 py-2 w-24">Batal</button>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-red-600 text-white shadow-sm hover:bg-red-700 h-9 px-4 py-2 w-24">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // --- Variabel Modal Form ---
                const formModal = document.getElementById('form-modal');
                const modalContent = document.getElementById('modal-content');
                const addBtn = document.getElementById('add-btn');
                const cancelBtn = document.getElementById('cancel-btn');
                const form = document.getElementById('penghuni-form');
                const formMethodInput = document.getElementById('form-method');
                const modalTitle = document.getElementById('modal-title');
                const modalDescription = document.getElementById('modal-description');
                const editBtns = document.querySelectorAll('.edit-btn');
                const identityCardHelp = document.getElementById('identity-card-help');

                function openModal() {
                    formModal.classList.remove('pointer-events-none', 'opacity-0');
                    modalContent.classList.remove('scale-95', 'opacity-0');
                }

                function closeModal() {
                    modalContent.classList.add('scale-95', 'opacity-0');
                    formModal.classList.add('opacity-0');
                    setTimeout(() => formModal.classList.add('pointer-events-none'), 300);
                }

                function setupCreateForm() {
                    form.reset();
                    form.action = '{{ route('penghunis.store') }}';
                    formMethodInput.value = 'POST';
                    modalTitle.innerText = 'Tambah Penghuni Baru';
                    modalDescription.innerText = 'Isi detail penghuni dan pilih kamar yang tersedia.';
                    document.getElementById('identity_card').setAttribute('required', 'required');
                    identityCardHelp.style.display = 'none';

                    const roomSelect = document.getElementById('room_id');
                    roomSelect.innerHTML = '<option value="">Pilih Kamar</option>';
                    @foreach ($availableRooms as $room)
                        roomSelect.innerHTML +=
                            `<option value="{{ $room->id }}">{{ $room->room_number }}</option>`;
                    @endforeach

                    openModal();
                }

                function setupEditForm(penghuni, availableRooms) {
                    form.reset();
                    form.action = `/penghunis/${penghuni.id}`;
                    formMethodInput.value = 'PUT';
                    modalTitle.innerText = `Edit Penghuni: ${penghuni.name}`;
                    modalDescription.innerText = 'Perbarui detail penghuni di bawah ini.';
                    document.getElementById('identity_card').removeAttribute('required');
                    identityCardHelp.style.display = 'block';

                    document.getElementById('name').value = penghuni.name;
                    document.getElementById('phone_number').value = penghuni.phone_number;

                    const roomSelect = document.getElementById('room_id');
                    roomSelect.innerHTML = '';

                    const currentRoomOption = document.createElement('option');
                    currentRoomOption.value = penghuni.room.id;
                    currentRoomOption.text = `${penghuni.room.room_number} (Kamar Saat Ini)`;
                    currentRoomOption.selected = true;
                    roomSelect.appendChild(currentRoomOption);

                    availableRooms.forEach(room => {
                        if (room.id !== penghuni.room.id) {
                            const option = document.createElement('option');
                            option.value = room.id;
                            option.text = room.room_number;
                            roomSelect.appendChild(option);
                        }
                    });
                    openModal();
                }

                addBtn.addEventListener('click', setupCreateForm);
                cancelBtn.addEventListener('click', closeModal);
                formModal.addEventListener('click', e => (e.target === formModal) && closeModal());
                document.addEventListener('keydown', e => (e.key === "Escape") && closeModal());

                editBtns.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const penghuniData = JSON.parse(btn.dataset.penghuni);
                        const roomsData = JSON.parse(btn.dataset.rooms);
                        setupEditForm(penghuniData, roomsData);
                    });
                });

                @if ($errors->any())
                    openModal();
                @endif

                // --- LOGIKA MODAL HAPUS ---
                const deleteModal = document.getElementById('delete-modal');
                const deleteModalContent = document.getElementById('delete-modal-content');
                const deleteForm = document.getElementById('delete-form');
                const cancelDeleteBtn = document.querySelector('.cancel-delete-btn');
                const nameText = document.getElementById('name-to-delete');
                const deleteBtns = document.querySelectorAll('.delete-btn');

                function openDeleteModal(id, name) {
                    deleteForm.action = `/penghunis/${id}`;
                    nameText.innerText = name;
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
                        openDeleteModal(btn.dataset.id, btn.dataset.name);
                    });
                });
                cancelDeleteBtn.addEventListener('click', closeDeleteModal);
                deleteModal.addEventListener('click', e => (e.target === deleteModal) && closeDeleteModal());
            });
        </script>
    @endpush
@endsection
