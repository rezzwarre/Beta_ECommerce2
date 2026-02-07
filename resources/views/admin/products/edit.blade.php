<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                            @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug (URL)</label>
                            <input type="text" id="slug" name="slug" value="{{ old('slug', $product->slug) }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                            @error('slug') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ old('description', $product->description) }}</textarea>
                        </div>

                        <!-- Image Upload Section -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-semibold mb-4">Gambar Produk</h3>
                            
                            @if($product->image)
                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Gambar Utama Saat Ini:</p>
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="h-32 w-32 object-cover rounded-md border">
                                </div>
                            @endif
                            
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Update Gambar Utama</label>
                                <div id="drop-zone-main" class="border-2 border-dashed border-blue-400 rounded-lg p-6 text-center bg-blue-50 cursor-pointer hover:bg-blue-100 transition">
                                    <svg class="mx-auto h-12 w-12 text-blue-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v4a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32 0h-8m-8 0H8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-blue-600">Drag & drop gambar atau <span class="font-semibold">klik untuk pilih</span></p>
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF (Max 2MB)</p>
                                    <input type="file" id="image" name="image" accept="image/*" class="hidden" />
                                </div>
                                <div id="image-preview" class="mt-2"></div>
                                @error('image') <span class="text-red-600 text-sm block">{{ $message }}</span> @enderror
                            </div>

                            @if(!empty($product->images) && is_array($product->images) && count($product->images) > 0)
                                <div class="mt-6">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Galeri Saat Ini:</p>
                                    <div class="grid grid-cols-3 gap-4">
                                        @foreach($product->images as $img)
                                            <div class="border rounded-md overflow-hidden">
                                                <img src="{{ asset($img) }}" alt="{{ $product->name }}" class="h-24 w-full object-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="mt-6">
                                <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Update Galeri Gambar (Multiple)</label>
                                <div id="drop-zone-gallery" class="border-2 border-dashed border-green-400 rounded-lg p-6 text-center bg-green-50 cursor-pointer hover:bg-green-100 transition">
                                    <svg class="mx-auto h-12 w-12 text-green-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v4a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32 0h-8m-8 0H8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-green-600">Drag & drop multiple images atau <span class="font-semibold">klik untuk pilih</span></p>
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF (Max 2MB per gambar)</p>
                                    <input type="file" id="images" name="images[]" accept="image/*" multiple class="hidden" />
                                </div>
                                <div id="gallery-preview" class="mt-2 grid grid-cols-3 gap-2"></div>
                                @foreach ($errors->get('images.*') as $messages)
                                    @foreach ($messages as $message)
                                        <span class="text-red-600 text-sm block">{{ $message }}</span>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>

                        <!-- Variants Section (Static) -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-semibold mb-4">Ukuran & Stok</h3>
                            
                            @error('variants') <span class="text-red-600 text-sm block mb-2">{{ $message }}</span> @enderror
                            @if ($errors->has('variants.*'))
                                <div class="text-red-600 text-sm mt-2 mb-4">
                                    @foreach ($errors->get('variants.*') as $messages)
                                        @foreach ($messages as $message)
                                            <div>{{ $message }}</div>
                                        @endforeach
                                    @endforeach
                                </div>
                            @endif
                            
                            <div id="variants-container" class="space-y-3">
                                @foreach($variants as $idx => $variant)
                                <div class="grid grid-cols-4 gap-3 items-end p-4 border rounded-md bg-gray-50" data-variant-idx="{{ $idx }}">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Ukuran</label>
                                        <select name="variants[{{ $idx }}][size_id]" required class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-2">
                                            <option value="">-- Pilih Ukuran --</option>
                                            @foreach($sizes as $s)
                                                <option value="{{ $s->id }}" {{ $variant->size_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                                        <input type="number" name="variants[{{ $idx }}][price]" value="{{ old('variants.'.$idx.'.price', $variant->price) }}" required class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-2" step="0.01" min="0">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Stok</label>
                                        <input type="number" name="variants[{{ $idx }}][stock]" value="{{ old('variants.'.$idx.'.stock', $variant->stock) }}" required class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-2" min="0">
                                    </div>
                                    <div>
                                        <button type="button" class="w-full bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700" onclick="removeVariantRow(this)">Hapus</button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <button type="button" class="mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700" onclick="addNewVariantRow()">
                                + Tambah Ukuran
                            </button>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Perbarui Produk</button>
                            <a href="{{ route('admin.products.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const sizes = @json($sizes);
        let variantCounter = {{ $variants->count() }};

        function addNewVariantRow() {
            const container = document.getElementById('variants-container');
            const idx = variantCounter;
            variantCounter++;
            
            const html = `
                <div class="grid grid-cols-4 gap-3 items-end p-4 border rounded-md bg-gray-50" data-variant-idx="${idx}">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ukuran</label>
                        <select name="variants[${idx}][size_id]" required class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-2">
                            <option value="">-- Pilih Ukuran --</option>
                            ${sizes.map(s => `<option value="${s.id}">${s.name}</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                        <input type="number" name="variants[${idx}][price]" value="" required class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-2" step="0.01" min="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stok</label>
                        <input type="number" name="variants[${idx}][stock]" value="" required class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-2" min="0">
                    </div>
                    <div>
                        <button type="button" class="w-full bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700" onclick="removeVariantRow(this)">Hapus</button>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', html);
        }

        function removeVariantRow(btn) {
            btn.closest('[data-variant-idx]').remove();
        }

        // Main Image Upload Handlers
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
                    imagePreview.innerHTML = `<div class="text-sm text-green-600 font-semibold">✓ File dipilih: ${file.name}</div><img src="${e.target.result}" alt="Preview" class="h-32 w-32 object-cover rounded-lg border-2 border-green-400 mt-2">`;
                };
                reader.readAsDataURL(file);
            }
        }

        // Gallery Images Upload Handlers
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
