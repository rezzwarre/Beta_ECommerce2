<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Produk Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                            <h3 class="text-red-800 font-semibold mb-2">Ada kesalahan:</h3>
                            <ul class="text-red-700 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-6" id="product-form" onsubmit="debugForm(event)" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk <span class="text-red-600">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                            @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug (URL) <span class="text-red-600">*</span></label>
                            <input type="text" id="slug" name="slug" value="{{ old('slug') }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                            @error('slug') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ old('description') }}</textarea>
                        </div>

                        <!-- Image Upload Section -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-semibold mb-4">Gambar Produk</h3>
                            
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">Gambar Utama</label>
                                <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4V12a4 4 0 014 4v8m0 0H12m28 0a4 4 0 00-4-4m4 4a4 4 0 014 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                <span>Upload gambar</span>
                                                <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                            </label>
                                            <p class="pl-1">atau drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                </div>
                                @error('image') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mt-6">
                                <label for="images" class="block text-sm font-medium text-gray-700">Galeri Gambar (Multiple)</label>
                                <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4V12a4 4 0 014 4v8m0 0H12m28 0a4 4 0 00-4-4m4 4a4 4 0 014 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                <span>Upload gambar</span>
                                                <input id="images" name="images[]" type="file" class="sr-only" accept="image/*" multiple>
                                            </label>
                                            <p class="pl-1">atau drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB per image</p>
                                    </div>
                                </div>
                                @foreach ($errors->get('images.*') as $messages)
                                    @foreach ($messages as $message)
                                        <span class="text-red-600 text-sm block">{{ $message }}</span>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>

                        <!-- Variants Section -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-semibold mb-2">Ukuran & Stok <span class="text-red-600">*</span></h3>
                            <p class="text-sm text-gray-600 mb-4">Minimal 1 ukuran harus dipilih</p>
                            
                            @if (empty($sizes) || $sizes->count() == 0)
                                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-md mb-4">
                                    <p class="text-yellow-800">⚠️ Tidak ada data ukuran. Hubungi admin untuk setup ukuran terlebih dahulu.</p>
                                </div>
                            @endif
                            
                            <div id="variants-container" class="space-y-3">
                                <!-- Variant rows will be added here -->
                            </div>

                            @error('variants') <span class="text-red-600 text-sm block mb-2">{{ $message }}</span> @enderror
                            @if ($errors->has('variants.*'))
                                <div class="text-red-600 text-sm mt-2 mb-2 p-3 bg-red-50 rounded border border-red-200">
                                    @foreach ($errors->get('variants.*') as $messages)
                                        @foreach ($messages as $message)
                                            <div>• {{ $message }}</div>
                                        @endforeach
                                    @endforeach
                                </div>
                            @endif

                            <button type="button" id="add-variant-btn" class="mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                + Tambah Ukuran
                            </button>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Buat Produk</button>
                            <a href="{{ route('admin.products.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const sizes = @json($sizes ?? []);
        const container = document.getElementById('variants-container');
        const addBtn = document.getElementById('add-variant-btn');
        let variantIndex = 0;

        function debugForm(e) {
            const formData = new FormData(document.getElementById('product-form'));
            console.log('Form submitted with data:');
            for (let [key, value] of formData.entries()) {
                console.log(`  ${key}: ${value}`);
            }
            
            // Validate that at least 1 variant exists
            const variantRows = container.querySelectorAll('[data-variant-idx]');
            console.log(`Found ${variantRows.length} variant rows`);
            
            if (variantRows.length === 0) {
                e.preventDefault();
                alert('Tambahkan minimal 1 ukuran sebelum submit!');
                return false;
            }
        }

        function renderVariantRow(idx, data = {}) {
            const html = `
                <div class="grid grid-cols-4 gap-3 items-end p-4 border rounded-md bg-gray-50" data-variant-idx="${idx}">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ukuran <span class="text-red-600">*</span></label>
                        <select name="variants[${idx}][size_id]" required class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-2">
                            <option value="">-- Pilih Ukuran --</option>
                            ${sizes.length > 0 ? sizes.map(s => `<option value="${s.id}" ${data.size_id == s.id ? 'selected' : ''}>${s.name}</option>`).join('') : '<option value="">Tidak ada ukuran</option>'}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Harga (Rp) <span class="text-red-600">*</span></label>
                        <input type="number" name="variants[${idx}][price]" value="${data.price || ''}" required class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-2" step="0.01" min="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stok <span class="text-red-600">*</span></label>
                        <input type="number" name="variants[${idx}][stock]" value="${data.stock || ''}" required class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-2" min="0">
                    </div>
                    <div>
                        <button type="button" class="remove-variant-btn w-full bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700">Hapus</button>
                    </div>
                </div>
            `;
            return html;
        }

        function addVariantRow(data = {}) {
            container.insertAdjacentHTML('beforeend', renderVariantRow(variantIndex, data));
            variantIndex++;
            attachRemoveListener();
        }

        function attachRemoveListener() {
            document.querySelectorAll('.remove-variant-btn').forEach(btn => {
                btn.onclick = function(e) {
                    e.preventDefault();
                    this.closest('[data-variant-idx]').remove();
                };
            });
        }

        addBtn.onclick = function(e) {
            e.preventDefault();
            addVariantRow();
        };

        // Load old data if validation failed
        const oldVariants = @json(old('variants', []));
        if (oldVariants.length > 0) {
            oldVariants.forEach((v, i) => addVariantRow(v));
        } else {
            // Add one empty row by default
            addVariantRow();
        }
    </script>
</x-app-layout>
