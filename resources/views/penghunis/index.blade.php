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


    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold tracking-tight">Manajemen Penghuni</h1>
        <button id="add-btn" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium bg-black text-white shadow hover:bg-black/90 h-9 px-4 py-2">
            Tambah Penghuni
        </button>
    </div>

    {{-- Tabel Penghuni --}}
    <div class="rounded-xl border bg-card text-card-foreground shadow">
        <div class="p-0">
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
                                <td class="p-4 align-middle text-muted-foreground">{{ $penghuni->room->room_number ?? 'N/A' }}</td>
                                <td class="p-4 align-middle">
                                    <a href="{{ Storage::url($penghuni->identity_card_path) }}" target="_blank" class="text-sm font-medium text-blue-600 hover:underline">
                                        Lihat File
                                    </a>
                                </td>
                                <td class="p-4 align-middle">
                                    <button class="edit-btn text-sm font-medium text-blue-600 hover:underline"
                                            data-penghuni='{{ $penghuni->toJson() }}'
                                            data-rooms='{{ json_encode($availableRooms) }}'>
                                        Edit
                                    </button>
                                    <span class="text-gray-300 mx-2">|</span>
                                    <button class="delete-btn text-sm font-medium text-red-600 hover:underline"
                                            data-id="{{ $penghuni->id }}"
                                            data-name="{{ $penghuni->name }}">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 align-middle text-center text-gray-500">Belum ada data penghuni.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Form (Untuk Tambah & Edit) --}}
    <div id="form-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 transition-opacity duration-300 ease-in-out opacity-0 pointer-events-none">
        <div id="modal-content" class="relative w-full max-w-lg transition-transform duration-300 ease-in-out transform scale-95 opacity-0">
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
                            <input type="text" name="name" id="name" required class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                        </div>
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                            <input type="text" name="phone_number" id="phone_number" required class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                        </div>
                        <div>
                            <label for="room_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Kamar</label>
                            <select name="room_id" id="room_id" required class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm">
                                {{-- Options will be populated by JavaScript --}}
                            </select>
                        </div>
                         <div>
                            <label for="identity_card" class="block text-sm font-medium text-gray-700 mb-1">Upload Tanda Pengenal</label>
                            <input type="file" name="identity_card" id="identity_card" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm">
                            <small id="identity-card-help" class="text-xs text-gray-500">Kosongkan jika tidak ingin mengubah.</small>
                        </div>
                    </div>

                    <div class="flex items-center justify-end p-6 pt-0 space-x-2">
                        <button type="button" id="cancel-btn" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background shadow-sm hover:bg-accent h-9 px-4 py-2">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-black text-white shadow hover:bg-black/90 h-9 px-4 py-2 w-24">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="delete-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 transition-opacity duration-300 ease-in-out opacity-0 pointer-events-none">
       {{-- ... Konten Modal Hapus (Sama seperti di Rooms) ... --}}
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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

            // --- Logika Modal ---
            function openModal() {
                formModal.classList.remove('pointer-events-none', 'opacity-0');
                modalContent.classList.remove('scale-95', 'opacity-0');
            }

            function closeModal() {
                modalContent.classList.add('scale-95', 'opacity-0');
                formModal.classList.add('opacity-0');
                setTimeout(() => formModal.classList.add('pointer-events-none'), 300);
            }

            // --- Pengaturan Form ---
            function setupCreateForm() {
                form.reset();
                form.action = '{{ route("penghunis.store") }}';
                formMethodInput.value = 'POST';
                modalTitle.innerText = 'Tambah Penghuni Baru';
                modalDescription.innerText = 'Isi detail penghuni dan pilih kamar yang tersedia.';
                document.getElementById('identity_card').setAttribute('required', 'required');
                identityCardHelp.style.display = 'none';

                // Populate room options
                const roomSelect = document.getElementById('room_id');
                roomSelect.innerHTML = '<option value="">Pilih Kamar</option>';
                @foreach($availableRooms as $room)
                    roomSelect.innerHTML += `<option value="{{ $room->id }}">{{ $room->room_number }}</option>`;
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

                // Isi form
                document.getElementById('name').value = penghuni.name;
                document.getElementById('phone_number').value = penghuni.phone_number;

                // Populate room options for edit
                const roomSelect = document.getElementById('room_id');
                roomSelect.innerHTML = ''; // Clear existing options
                
                // Add current room of the tenant
                const currentRoomOption = document.createElement('option');
                currentRoomOption.value = penghuni.room.id;
                currentRoomOption.text = `${penghuni.room.room_number} (Kamar Saat Ini)`;
                currentRoomOption.selected = true;
                roomSelect.appendChild(currentRoomOption);

                // Add other available rooms
                availableRooms.forEach(room => {
                    const option = document.createElement('option');
                    option.value = room.id;
                    option.text = room.room_number;
                    roomSelect.appendChild(option);
                });

                openModal();
            }

            // --- Event Listeners ---
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

            // Jika ada error validasi, buka kembali modal
            @if ($errors->any())
                openModal();
            @endif

            // --- (Logika untuk modal hapus bisa ditambahkan di sini, sama seperti di Rooms) ---

        });
    </script>
    @endpush
@endsection