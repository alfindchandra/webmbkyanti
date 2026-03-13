<div> <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Pembelian Barang</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Kelola pembelian barang dari supplier dengan tracking foto struk.</p>
        </div>
         <div class="flex flex-col md:flex-row items-center gap-3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama Supplier..." class="pl-9 pr-4 py-2 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-64 shadow-sm">
            </div>
            <button wire:click="create()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-xl shadow-sm transition-colors flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Pembelian
        </button>
        </div>
        
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-50 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-200 p-4 rounded-xl mb-6 flex items-center gap-3 border border-emerald-200 dark:border-emerald-800 transition-all" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <svg class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Supplier</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Struk</th>
                    </tr>
                </thead>
                
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($purchases as $purchase)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                            {{ $purchase->purchase_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $purchase->supplier->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 dark:text-gray-300">
                                @foreach($purchase->items as $item)
                                    <div class="text-xs mb-1">{{ $item->product->name ?? 'Produk Dihapus' }} ({{ $item->quantity }} {{ $item->productUnit->unit->code ?? '' }})</div>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                            {{ $purchase->items->count() }} item
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                Rp {{ number_format($purchase->items->sum(fn($item) => $item->quantity * $item->unit_price), 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($purchase->receipt_image)
                                <a href="{{ asset('storage/' . $purchase->receipt_image) }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                    <svg class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </a>
                            @else
                                <span class="text-xs text-gray-400 dark:text-gray-500">Tidak ada</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            Belum ada catatan pembelian barang.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-data="{ open: @entangle('isModalOpen') }" x-show="open" class="fixed inset-0 z-100 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full border border-gray-100 dark:border-gray-700">
                <form wire:submit.prevent="store">
                    <div class="px-6 pt-6 pb-4">
                        <h3 class="text-xl leading-6 font-bold text-gray-900 dark:text-white">Tambah Pembelian Barang</h3>
                        
                        @if (session()->has('error'))
                            <div class="mt-4 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-200 p-3 rounded-lg text-sm">{{ session('error') }}</div>
                        @endif

                        <div class="mt-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Supplier</label>
                                    <select wire:model="supplier_id" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-2 px-3 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Pilih supplier...</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Pembelian</label>
                                    <input type="date" wire:model="purchase_date" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-2 px-3 focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('purchase_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Foto Struk (Opsional)</label>
                                <input type="file" wire:model="receipt_image" accept="image/*" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-400 py-2 px-3 focus:border-indigo-500 focus:ring-indigo-500">
                                @error('receipt_image') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-600 pt-4 mt-4">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-3">Tambah Item Pembelian</h4>
                                <div class="space-y-3">
                                    <div class="relative">
                                        <input type="text" wire:model.live.debounce.300ms="searchProductTerm" placeholder="Cari Produk..." class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-2 px-3 focus:border-indigo-500 focus:ring-indigo-500">
                                        @if(count($filteredProducts) > 0)
                                            <div class="absolute top-full left-0 right-0 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg mt-1 z-40 max-h-48 overflow-y-auto">
                                                @foreach($filteredProducts as $product)
                                                    <button type="button" wire:click="selectProduct({{ $product['id'] }})" class="w-full text-left px-3 py-2 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 border-b dark:border-gray-600 last:border-0 text-sm text-gray-900 dark:text-white">
                                                        {{ $product['name'] }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    @if(!empty($availableUnits))
                                    <div class="grid grid-cols-3 gap-3">
                                        <div>
                                            <label class="text-xs text-gray-500">Satuan</label>
                                            <select wire:model="newItemProductUnitId" class="w-full rounded-lg text-sm p-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                @foreach($availableUnits as $unit)
                                                    <option value="{{ $unit['id'] }}">{{ $unit['unit_code'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Jumlah</label>
                                            <input type="number" step="0.01" wire:model="newItemQuantity" class="w-full p-2 rounded-lg text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Harga</label>
                                            <input type="number" wire:model="newItemUnitPrice" class="w-full rounded-lg p-2 text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        </div>
                                    </div>
                                    <button type="button" wire:click="addPurchaseItem" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 rounded-xl text-sm transition">+ Tambah ke Daftar</button>
                                    @endif
                                </div>
                            </div>

                            @if(count($purchaseItems) > 0)
                            <div class="mt-4 space-y-2">
                                @foreach($purchaseItems as $item)
                                <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700/50 p-2 rounded-lg text-sm">
                                    <div class="dark:text-white">
                                        <strong>{{ $item['product_name'] }}</strong><br>
                                        <span class="text-xs text-gray-500">{{ $item['quantity'] }} {{ $item['unit_code'] }} @ Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</span>
                                    </div>
                                    <button type="button" wire:click="removePurchaseItem('{{ $item['id'] }}')" class="text-red-500 hover:underline text-xs">Hapus</button>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 rounded-b-2xl">
                        <button type="button" wire:click="close()" class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 rounded-xl">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 disabled:opacity-50" {{ count($purchaseItems) === 0 ? 'disabled' : '' }}>Simpan Pembelian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 