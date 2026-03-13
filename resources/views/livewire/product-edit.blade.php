<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 dark:text-white bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400 bg-clip-text text-transparent">
                    Edit Produk
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Perbarui informasi produk dan tingkat harga.</p>
            </div>
            <a href="{{ route('products') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition">
                ← Kembali
            </a>
        </div>

        <!-- Form Card -->
        <form wire:submit.prevent="update" class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Header Section -->
            <div class="px-8 py-8 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">📋 Informasi Dasar Produk</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update detail produk {{ $product->name ?? 'Anda' }}</p>
            </div>

            <div class="px-8 py-8 space-y-8">
                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Gambar Produk</label>
                    <div class="flex items-center gap-6">
                        @if($image)
                            <div class="shrink-0 w-24 h-24 rounded-xl overflow-hidden border-2 border-blue-300 dark:border-blue-600 bg-gray-100 dark:bg-gray-700 shadow-md">
                                <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                            </div>
                        @elseif($existingImage)
                            <div class="shrink-0 w-24 h-24 rounded-xl overflow-hidden border-2 border-blue-300 dark:border-blue-600 bg-gray-100 dark:bg-gray-700 shadow-md">
                                <img src="{{ asset('storage/' . $existingImage) }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="shrink-0 w-24 h-24 rounded-xl border-3 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <input type="file" wire:model="image" class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/40 dark:file:text-blue-300 dark:hover:file:bg-blue-900/60 transition-colors cursor-pointer">
                            <div wire:loading wire:target="image" class="text-sm font-medium text-blue-600 dark:text-blue-400 mt-2">⏳ Mengunggah gambar...</div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">PNG, JPG hingga 2MB (biarkan kosong jika tidak ingin ubah)</p>
                        </div>
                    </div>
                    @error('image') <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> @enderror
                </div>

                <!-- Basic Info -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Produk *</label>
                        <input type="text" wire:model="name" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 dark:focus:ring-offset-gray-800 px-4 py-3 transition" placeholder="contoh: Indomie Goreng">
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kategori *</label>
                        <select wire:model="category_id" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 dark:focus:ring-offset-gray-800 px-4 py-3 transition">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">SKU (Opsional)</label>
                        <input type="text" wire:model="sku" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 dark:focus:ring-offset-gray-800 px-4 py-3 transition" placeholder="SKU123">
                        @error('sku') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Barcode (Opsional)</label>
                        <input type="text" wire:model="barcode" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 dark:focus:ring-offset-gray-800 px-4 py-3 transition" placeholder="123456789">
                        @error('barcode') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Satuan Dasar *</label>
                        <select wire:model="base_unit_id" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 dark:focus:ring-offset-gray-800 px-4 py-3 transition">
                            <option value="">-- Pilih Satuan Dasar --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->code }}) @if($unit->is_base) [DEFAULT] @endif</option>
                            @endforeach
                        </select>
                        @error('base_unit_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Deskripsi (Opsional)</label>
                        <input type="text" wire:model="description" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 dark:focus:ring-offset-gray-800 px-4 py-3 transition" placeholder="Deskripsi singkat produk">
                        @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Price Tiers Section -->
            <div class="border-t border-gray-200 dark:border-gray-700 px-8 py-8 bg-gradient-to-b from-transparent to-gray-50 dark:to-gray-800/30">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">💰 Tingkat Harga</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Kelola berbagai tingkat harga untuk unit berbeda</p>
                    </div>
                    <button type="button" wire:click="addProductUnit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition shadow-sm">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Tambah Tingkat
                    </button>
                </div>

                <div class="space-y-4">
                    @foreach($productUnits as $index => $unit)
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600 transition relative shadow-sm">
                        @if(count($productUnits) > 1)
                        <button type="button" wire:click="removeProductUnit({{ $index }})" class="absolute top-4 right-4 p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                        @endif

                        <!-- Basic Unit Info -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">Satuan</label>
                                <select wire:model="productUnits.{{ $index }}.unit_id" class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-2 px-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition">
                                    <option value="">Pilih</option>
                                    @foreach($units as $u)
                                        <option value="{{ $u->id }}">{{ $u->code }}</option>
                                    @endforeach
                                </select>
                                @error("productUnits.$index.unit_id") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">Konversi QTY</label>
                                <input type="number" step="0.01" wire:model="productUnits.{{ $index }}.conversion" class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-2 px-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition" placeholder="cth: 1.0 atau 24.0">
                                @error("productUnits.$index.conversion") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">Harga Dasar</label>
                                <input type="number" step="0.01" wire:model="productUnits.{{ $index }}.price" class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-2 px-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition" placeholder="0">
                                @error("productUnits.$index.price") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">Minimal Qty <span class="text-[10px] text-gray-400 normal-case">(Syarat Diskon)</span></label>
                                <input type="number" step="0.01" wire:model="productUnits.{{ $index }}.min_qty" class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-2 px-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition" placeholder="1">
                                @error("productUnits.$index.min_qty") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Tiered Pricing Section -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                            <div class="flex items-center justify-between mb-3">
                                <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">💰 Harga Tingkatan</label>
                                <button type="button" wire:click="addUnitTierPrice({{ $index }})" class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 rounded hover:bg-blue-200 dark:hover:bg-blue-900/60 transition">
                                    + Tambah Tier
                                </button>
                            </div>

                            @if(isset($productUnits[$index]['tierPrices']) && count($productUnits[$index]['tierPrices']) > 0)
                            <div class="space-y-2">
                                @foreach($productUnits[$index]['tierPrices'] as $tierIndex => $tierPrice)
                                <div class="flex items-end gap-2 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                    <div class="flex-1">
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Min Qty</label>
                                        <input type="number" step="0.01" wire:model="productUnits.{{ $index }}.tierPrices.{{ $tierIndex }}.min_quantity" class="w-full text-sm rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-600 px-2 py-1 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition" placeholder="misal: 10">
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Harga</label>
                                        <input type="number" step="0.01" wire:model="productUnits.{{ $index }}.tierPrices.{{ $tierIndex }}.price" class="w-full text-sm rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-600 px-2 py-1 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition" placeholder="28000">
                                    </div>
                                    <button type="button" wire:click="removeUnitTierPrice({{ $index }}, {{ $tierIndex }})" class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-xs text-gray-500 dark:text-gray-400 italic">Belum ada tingkatan harga. Tambahkan untuk memberikan harga diskon berdasarkan kuantitas.</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3 rounded-b-3xl">
                <button type="button" wire:click="cancel()" class="px-6 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg hover:from-blue-700 hover:to-indigo-700 transition shadow-md">
                    ✏️ Perbarui Produk
                </button>
            </div>
        </form>
    </div>
</div>
