<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Produk Baru - SIMPLE TEST') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                            <h3 class="text-red-800 font-semibold mb-2">Validation Errors:</h3>
                            <ul class="text-red-700 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk *</label>
                            <input type="text" id="name" name="name" value="Test Product {{ now()->timestamp }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                        </div>

                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug *</label>
                            <input type="text" id="slug" name="slug" value="test-{{ now()->timestamp }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">Test description</textarea>
                        </div>

                        <!-- Image Upload Section -->
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 bg-gray-50">
                            <h4 class="font-semibold mb-4 text-gray-700">Upload Gambar</h4>
                            
                            <!-- Main Image -->
                            <div class="mb-6">
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Utama</label>
                                <div id="drop-zone-main" class="border-2 border-dashed border-blue-400 rounded-lg p-6 text-center bg-blue-50 cursor-pointer hover:bg-blue-100 transition">
                                    <svg class="mx-auto h-12 w-12 text-blue-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v4a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32 0h-8m-8 0H8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-blue-600">Drag & drop gambar atau <span class="font-semibold">klik untuk pilih</span></p>
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF (Max 2MB)</p>
                                    <input type="file" id="image" name="image" accept="image/*" class="hidden" />
                                </div>
                                <div id="image-preview" class="mt-2"></div>
                            </div>

                            <!-- Gallery Images -->
                            <div>
                                <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Galeri Gambar (Opsional)</label>
                                <div id="drop-zone-gallery" class="border-2 border-dashed border-green-400 rounded-lg p-6 text-center bg-green-50 cursor-pointer hover:bg-green-100 transition">
                                    <svg class="mx-auto h-12 w-12 text-green-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v4a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32 0h-8m-8 0H8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-green-600">Drag & drop multiple images atau <span class="font-semibold">klik untuk pilih</span></p>
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF (Max 2MB per gambar)</p>
                                    <input type="file" id="images" name="images[]" accept="image/*" multiple class="hidden" />
                                </div>
                                <div id="gallery-preview" class="mt-2 grid grid-cols-3 gap-2"></div>
                            </div>
                        </div>

                        <!-- Variant 1 -->
                        <div class="border p-4 rounded-md background-gray-50">
                            <h4 class="font-semibold mb-3">Variant 1 - Ukuran S</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Size ID *</label>
                                    <select name="variants[0][size_id]" required class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-2">
                                        <option value="">-- Select --</option>
                                        @foreach($sizes as $size)
                                            <option value="{{ $size->id }}" @if($size->name === 'S') selected @endif>{{ $size->name }} (ID: {{ $size->id }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Price *</label>
                                    <input type="number" name="variants[0][price]" value="100000" required class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-2" step="0.01" min="0">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Stock *</label>
                                    <input type="number" name="variants[0][stock]" value="10" required class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-2" min="0">
                                </div>
                            </div>
                        </div>

                        <!-- Variant 2 -->
                        <div class="border p-4 rounded-md background-gray-50">
                            <h4 class="font-semibold mb-3">Variant 2 - Ukuran M</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Size ID *</label>
                                    <select name="variants[1][size_id]" required class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-2">
                                        <option value="">-- Select --</option>
                                        @foreach($sizes as $size)
                                            <option value="{{ $size->id }}" @if($size->name === 'M') selected @endif>{{ $size->name }} (ID: {{ $size->id }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Price *</label>
                                    <input type="number" name="variants[1][price]" value="100000" required class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-2" step="0.01" min="0">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Stock *</label>
                                    <input type="number" name="variants[1][stock]" value="15" required class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-2" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Product (Simple Test)</button>
                            <a href="{{ route('admin.products.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Cancel</a>
                        </div>
                    </form>

                    <hr class="my-8">
                    <h3 class="font-semibold mb-4">Debug Info:</h3>
                    <div class="bg-gray-100 p-4 rounded text-sm">
                        <p>Sizes count: {{ $sizes->count() }}</p>
                        <p>Sizes:</p>
                        <ul class="ml-4">
                            @foreach($sizes as $size)
                                <li>- ID {{ $size->id }}: {{ $size->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Main Image Upload
        const dropZoneMain = document.getElementById('drop-zone-main');
        const inputImage = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');

        dropZoneMain.addEventListener('click', () => inputImage.click());
        dropZoneMain.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZoneMain.classList.add('bg-blue-200');
        });
        dropZoneMain.addEventListener('dragleave', () => {
            dropZoneMain.classList.remove('bg-blue-200');
        });
        dropZoneMain.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZoneMain.classList.remove('bg-blue-200');
            if (e.dataTransfer.files.length > 0) {
                inputImage.files = e.dataTransfer.files;
                handleImageChange();
            }
        });

        inputImage.addEventListener('change', handleImageChange);

        function handleImageChange() {
            imagePreview.innerHTML = '';
            if (inputImage.files.length > 0) {
                const file = inputImage.files[0];
                const reader = new FileReader();
                reader.onload = (e) => {
                    imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="h-32 w-32 object-cover rounded-lg border-2 border-blue-400">`;
                };
                reader.readAsDataURL(file);
            }
        }

        // Gallery Images Upload
        const dropZoneGallery = document.getElementById('drop-zone-gallery');
        const inputImages = document.getElementById('images');
        const galleryPreview = document.getElementById('gallery-preview');

        dropZoneGallery.addEventListener('click', () => inputImages.click());
        dropZoneGallery.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZoneGallery.classList.add('bg-green-200');
        });
        dropZoneGallery.addEventListener('dragleave', () => {
            dropZoneGallery.classList.remove('bg-green-200');
        });
        dropZoneGallery.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZoneGallery.classList.remove('bg-green-200');
            if (e.dataTransfer.files.length > 0) {
                inputImages.files = e.dataTransfer.files;
                handleGalleryChange();
            }
        });

        inputImages.addEventListener('change', handleGalleryChange);

        function handleGalleryChange() {
            galleryPreview.innerHTML = '';
            const files = inputImages.files;
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Gallery" class="h-24 w-24 object-cover rounded border-2 border-green-400">
                        <span class="absolute top-1 right-1 bg-green-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">${i + 1}</span>
                    `;
                    galleryPreview.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>
