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
            <p class="font-bold">Terjadi Kesalahan</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="list-disc ml-4">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Header Halaman yang Responsif --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Manajemen Penghuni</h1>
        <button id="add-btn"
            class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-gray-900 text-white shadow hover:bg-gray-800 h-10 px-4 py-2 w-full sm:w-auto">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Penghuni
        </button>
    </div>

    {{-- Tampilan untuk Mobile (Card View) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:hidden gap-4">
        @forelse ($penghunis as $penghuni)
            <div class="rounded-xl border bg-white text-card-foreground shadow">
                <div class="p-6">
                    <h3 class="font-semibold tracking-tight text-lg">{{ $penghuni->name }}</h3>
                    <p class="text-sm text-muted-foreground">{{ $penghuni->phone_number }}</p>
                    <div class="flex items-center gap-2 mt-4">
                        <span class="font-semibold">Kamar:</span>
                        <span>{{ $penghuni->room->room_number ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center pt-4 mt-4 border-t gap-2">
                        <button
                            class="edit-btn flex-1 inline-flex items-center justify-center rounded-md text-sm font-medium bg-gray-100 hover:bg-gray-200 h-9 px-3"
                            data-penghuni='{{ $penghuni->toJson() }}' data-rooms='{{ json_encode($availableRooms) }}'>
                            Edit
                        </button>
                        <button
                            class="delete-btn flex-1 inline-flex items-center justify-center rounded-md text-sm font-medium bg-red-100 text-red-700 hover:bg-red-200 h-9 px-3"
                            data-id="{{ $penghuni->id }}" data-name="{{ $penghuni->name }}">
                            Hapus
                        </button>
                    </div>
                    <a href="{{ Storage::url($penghuni->identity_card_path) }}" target="_blank"
                        class="mt-2 w-full inline-flex items-center justify-center rounded-md text-sm font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 h-9 px-3">
                        Lihat Tanda Pengenal
                    </a>
                </div>
            </div>
        @empty
            <div
                class="col-span-1 sm:col-span-2 rounded-xl border bg-white text-card-foreground shadow p-6 text-center text-gray-500">
                Belum ada data penghuni.
            </div>
        @endforelse
    </div>

    {{-- Tampilan untuk Desktop (Table View) --}}
    <div class="hidden lg:block rounded-xl border bg-white text-card-foreground shadow">
        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead class="[&_tr]:border-b">
                    <tr class="border-b">
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nama Penghuni</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nomor HP</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Kamar</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanda Pengenal</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Aksi</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0">
                    @forelse ($penghunis as $penghuni)
                        <tr class="border-b">
                            <td class="p-4 align-middle font-medium">{{ $penghuni->name }}</td>
                            <td class="p-4 align-middle text-muted-foreground">{{ $penghuni->phone_number }}</td>
                            <td class="p-4 align-middle text-muted-foreground">{{ $penghuni->room->room_number ?? 'N/A' }}
                            </td>
                            <td class="p-4 align-middle">
                                <a href="{{ Storage::url($penghuni->identity_card_path) }}" target="_blank"
                                    class="text-sm font-medium text-blue-600 hover:underline">
                                    Lihat File
                                </a>
                            </td>
                            <td class="p-4 align-middle">
                                <button class="edit-btn text-sm font-medium text-blue-600 hover:underline"
                                    data-penghuni='{{ $penghuni->toJson() }}'
                                    data-rooms='{{ json_encode($availableRooms) }}'>Edit</button>
                                <span class="text-gray-300 mx-2">|</span>
                                <button class="delete-btn text-sm font-medium text-red-600 hover:underline"
                                    data-id="{{ $penghuni->id }}" data-name="{{ $penghuni->name }}">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">Belum ada data penghuni.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                            <input type="text" name="phone_number" id="phone_number" required
                                class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                        </div>
                        <div>
                            <label for="room_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Kamar</label>
                            <select name="room_id" id="room_id" required
                                class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                                {{-- Options will be populated by JavaScript --}}
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
                    <div class="flex items-center justify-end p-6 pt-0 space-x-2">
                        <button type="button" id="cancel-btn"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input h-10 px-4 py-2">Batal</button>
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
            // Kode JavaScript lengkap dari langkah sebelumnya tetap sama dan tidak perlu diubah.
            // Cukup salin-tempel seluruh blok script yang sudah ada di sini.
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
