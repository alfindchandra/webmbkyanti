<div>
    
<div
    class="min-h-screen md:h-[calc(100vh-80px)] -m-6 flex flex-col md:flex-row bg-slate-50 dark:bg-gray-950 overflow-y-auto md:overflow-hidden text-gray-800 dark:text-gray-100"
    x-data="{
        showAdditionalCostModal: @entangle('showAdditionalCostModal').live,
        showTieredModal: @entangle('showTieredModal').live,
        showCheckout: @entangle('showCheckout').live,
        showReceiptModal: @entangle('showReceiptModal').live,
        showEditPriceModal: @entangle('showEditPriceModal').live,
        initKeyShortcuts() {
            document.addEventListener('keydown', (e) => {
                if (e.key === 'F1') { e.preventDefault(); this.$refs.searchInput?.focus(); }
                if (e.key === 'F2') { e.preventDefault(); this.$refs.paymentInput?.focus(); }
                if (e.key === 'F3') { e.preventDefault(); $wire.openCheckout(); }
                if (e.key === 'Escape') { this.showAdditionalCostModal = false; this.showTieredModal = false; this.showCheckout = false; this.showEditPriceModal = false; }
            });
        }
    }"
    x-init="initKeyShortcuts()"
>

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- LEFT PANEL – Search + Cart --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-h-0 overflow-hidden border-r border-gray-200 dark:border-gray-800">
        
        {{-- Search Section --}}
        <div class="bg-white dark:bg-gray-900 p-4 sticky top-0 z-30 border-b border-gray-100 dark:border-gray-800">
            <div class="relative w-full mx-auto">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Scan atau cari produk... (F1)"
                    class="w-full pl-12 pr-12 py-4 rounded-2xl bg-gray-50 dark:bg-gray-800 border-none focus:ring-2 focus:ring-blue-500 text-lg shadow-inner dark:text-white"
                    autofocus
                    x-ref="searchInput"
                >
            </div>

            {{-- SEARCH RESULTS DROPDOWN (FULL WIDTH) --}}
            @if($search)
            <div class="absolute left-0 top-full w-full p-4 bg-white dark:bg-gray-800 shadow-2xl border-b border-blue-100 dark:border-gray-700 z-50 max-h-[70vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4 px-2">
                    <h3 class="text-sm font-black text-blue-600 uppercase">Hasil Pencarian "{{ $search }}"</h3>
                    <button @click="$wire.set('search', '')" class="p-2 bg-red-50 text-red-500 rounded-full">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                @if($products->isEmpty())
                    <div class="text-center py-10 text-gray-400 font-medium">Produk tidak ditemukan</div>
                @else
                    <div class="w-full">
                        @foreach($products as $product)
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-2xl border border-transparent hover:border-blue-500 transition cursor-pointer group">
                                <div class="font-bold text-gray-800 dark:text-white mb-2">{{ $product->name }}</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($product->productUnits as $pu)
                                        <button wire:click="addToCart({{ $product->id }}, {{ $pu->id }})" class="flex-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 text-[11px] font-bold py-2 px-1 rounded-xl hover:bg-blue-600 hover:text-white transition">
                                            {{ $pu->unit->code }}: {{ number_format($pu->price, 0, ',', '.') }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            @endif
        </div>

        {{-- Cart Section --}}
        <div class="flex-1 overflow-y-auto p-4 custom-scrollbar bg-slate-50 dark:bg-gray-950">
            <div class="flex items-center justify-between mb-4 px-2">
                <h2 class="text-lg font-black dark:text-white uppercase tracking-tighter">🛒 Keranjang Belanja</h2>
                <span class="text-xs bg-blue-600 text-white px-3 py-1 rounded-full font-bold">{{ count($cart) }} Items</span>
            </div>

            @if(count($cart) === 0)
                <div class="py-20 flex flex-col items-center justify-center opacity-20">
                    <svg class="w-20 h-20 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <p class="font-bold">Belum ada produk</p>
                </div>
            @else
                <div class="space-y-3 pb-24 md:pb-4">
                    @foreach($cart as $item)
                        <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center gap-3">
                            <div class="flex-1">
                                <h4 class="font-bold text-sm md:text-base dark:text-white">{{ $item['name'] }}</h4>
                                <button 
                                    wire:click="openEditPriceModal('{{ $item['cart_id'] }}')"
                                    class="text-xs text-blue-500 font-bold hover:text-blue-700 hover:underline transition">
                                    Rp {{ number_format($item['price'], 0, ',', '.') }} / {{ $item['unit_code'] }}
                                </button>
                            </div>
                            
                            <div 
                                class="flex items-center bg-gray-50 dark:bg-gray-800 rounded-xl p-1 border dark:border-gray-700"
                                x-data="{ 
                                    qty: {{ $item['quantity'] }},
                                    updateQty(newQty) {
                                        if (newQty < 1) newQty = 1;
                                        this.qty = newQty;
                                        {{-- Mengirim data ke Livewire hanya saat berhenti sejenak (debounce) --}}
                                        $wire.updateQuantity('{{ $item['cart_id'] }}', this.qty);
                                    }
                                }"
                                {{-- Memastikan input sinkron jika ada update dari luar (misal: scan barcode ulang) --}}
                                x-effect="qty = {{ $item['quantity'] }}"
                            >
                                <button 
                                    @click="updateQty(qty - 1)" 
                                    type="button"
                                    class="w-8 h-8 flex items-center justify-center text-red-500 hover:bg-white dark:hover:bg-gray-700 rounded-lg transition"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/>
                                    </svg>
                                </button>

                                <input 
                                    type="number" 
                                    x-model.number="qty"
                                    @change="updateQty(qty)"
                                    @keydown.enter.prevent="updateQty(qty)"
                                    class="w-12 text-center font-black text-sm bg-transparent border-none focus:ring-0 dark:text-white [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                    min="1"
                                >

                                <button 
                                    @click="updateQty(qty + 1)" 
                                    type="button"
                                    class="w-8 h-8 flex items-center justify-center text-green-500 hover:bg-white dark:hover:bg-gray-700 rounded-lg transition"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="text-right">
                                <p class="font-black text-sm md:text-base dark:text-white">Rp {{ number_format($item['quantity'] * $item['price'], 0, ',', '.') }}</p>
                                <button wire:click="removeFromCart('{{ $item['cart_id'] }}')" class="text-[10px] text-red-400 font-bold uppercase">Hapus</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- RIGHT PANEL – Summary & Checkout --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <div class="w-full md:w-[400px] bg-white dark:bg-gray-900 border-t md:border-t-0 md:border-l border-gray-200 dark:border-gray-800 shadow-2xl z-20">
        
        <div class="p-6 h-full flex flex-col">
            <h3 class="hidden md:block text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Ringkasan Transaksi</h3>
            
            <div class="space-y-3 mb-6">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
                    <span class="font-bold dark:text-white">Rp {{ number_format($summary['subtotal'], 0, ',', '.') }}</span>
                </div>

                @if($summary['savings'] > 0)
                <div class="flex justify-between text-sm text-emerald-500 font-bold">
                    <span>Hemat</span>
                    <span>- Rp {{ number_format($summary['savings'], 0, ',', '.') }}</span>
                </div>
                @endif

                @foreach($additionalCosts as $cost)
                <div class="flex justify-between text-sm text-amber-600">
                    <span class="italic text-xs">{{ $cost['description'] }}</span>
                    <div class="flex items-center gap-2">
                        <span>+ Rp {{ number_format($cost['amount'], 0, ',', '.') }}</span>
                        <button wire:click="removeAdditionalCost('{{ $cost['id'] }}')" class="text-red-400 font-bold">×</button>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Grand Total Card --}}
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-6 rounded-3xl text-white shadow-xl mb-6">
                <div class="flex justify-between items-center mb-1 opacity-80">
                    <span class="text-[10px] font-black uppercase tracking-widest">Total Bayar</span>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h2 class="text-3xl md:text-4xl font-black tracking-tighter">Rp {{ number_format($summary['grand_total'], 0, ',', '.') }}</h2>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <button @click="showAdditionalCostModal = true" class="py-3 bg-gray-100 dark:bg-gray-800 dark:text-white rounded-2xl font-black text-[10px] uppercase tracking-tighter hover:bg-gray-200 transition">
                    + Biaya Lain
                </button>
                <button wire:click="clearCart" class="py-3 bg-red-50 text-red-600 rounded-2xl font-black text-[10px] uppercase tracking-tighter hover:bg-red-100 transition">
                    Reset
                </button>
            </div>

            <button
                wire:click="openCheckout"
                @disabled(count($cart) === 0)
                class="w-full py-5 rounded-3xl font-black text-xl transition-all flex items-center justify-center gap-3 shadow-2xl
                {{ count($cart) > 0 ? 'bg-emerald-500 hover:bg-emerald-600 text-white shadow-emerald-500/40 transform active:scale-95' : 'bg-gray-200 dark:bg-gray-800 text-gray-400 cursor-not-allowed' }}"
            >
                <span>BAYAR (F3)</span>
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </button>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- MODAL – Additional Cost --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <div
        x-show="showAdditionalCostModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        @click.self="showAdditionalCostModal = false"
    >
        <div
            x-show="showAdditionalCostModal"
            x-transition:enter="transition ease-out duration-200 transform"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md"
        >
            <div class="p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Tambah Biaya Lain
                    </h3>
                    <button @click="showAdditionalCostModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="addAdditionalCost" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Deskripsi</label>
                        <select wire:model.live="newCostDescription" class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Pilih deskripsi...</option>
                            <option value="Hutang">Hutang</option>
                            <option value="Biaya Admin">Biaya Admin</option>
                            <option value="Pajak Tambahan">Pajak Tambahan</option>
                            <option value="Diskon">Diskon (pengurang)</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    @if($newCostDescription === 'Lainnya')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Deskripsi Custom</label>
                            <input type="text" wire:model="newCostCustomDescription" class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan deskripsi...">
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Jumlah (Rp)
                            @if($newCostDescription === 'Diskon')
                                <span class="text-red-500 text-xs ml-1">(akan mengurangi total)</span>
                            @endif
                        </label>
                        <input
                            type="number"
                            wire:model="newCostAmount"
                            class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg font-semibold"
                            min="0" step="100" required
                            placeholder="0"
                        >
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showAdditionalCostModal = false" class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition font-medium">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl transition font-bold">
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- MODAL – Tiered Pricing Info --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <div
        x-show="showTieredModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        @click.self="showTieredModal = false"
    >
        <div
            x-show="showTieredModal"
            x-transition:enter="transition ease-out duration-200 transform"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto"
        >
            <div class="p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Info Harga Bertingkat
                    </h3>
                    <button wire:click="$set('showTieredModal', false)" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                @if($selectedTieredItem)
                    @php $item = $selectedTieredItem; @endphp
                    <h4 class="font-bold text-lg text-gray-900 dark:text-white mb-4">{{ $item['name'] }}</h4>

                    <div class="space-y-3">
                        {{-- Base price --}}
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-3">
                            <div class="flex justify-between items-center">
                                <span class="text-blue-800 dark:text-blue-300 font-medium text-sm">Harga Dasar</span>
                                <span class="text-blue-900 dark:text-blue-200 font-bold">Rp {{ number_format($item['base_price'], 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Tiers --}}
                        @if(!empty($item['tiered_prices']))
                            <div>
                                <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tingkat Harga:</h5>
                                <div class="space-y-2">
                                    @foreach($item['tiered_prices'] as $tier)
                                        @php $isActive = isset($item['applied_tier']) && $item['applied_tier']['min_quantity'] == $tier['min_quantity']; @endphp
                                        <div class="bg-orange-50 dark:bg-orange-900/20 border {{ $isActive ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-orange-200 dark:border-orange-800' }} rounded-xl p-3">
                                            <div class="flex justify-between items-center">
                                                <span class="text-orange-800 dark:text-orange-300 font-medium text-sm">≥ {{ $tier['min_quantity'] }} unit</span>
                                                <span class="text-orange-900 dark:text-orange-200 font-bold">Rp {{ number_format($tier['price'], 0, ',', '.') }}</span>
                                            </div>
                                            <div class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">
                                                Hemat Rp {{ number_format($item['base_price'] - $tier['price'], 0, ',', '.') }} / unit
                                            </div>
                                            @if($isActive)
                                                <div class="text-xs text-emerald-600 font-bold mt-1 flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    Tier Aktif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Current status --}}
                        @if(isset($item['applied_tier']))
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl p-3">
                                <h5 class="text-sm font-semibold text-emerald-800 dark:text-emerald-300 mb-2">Tier Aktif</h5>
                                <div class="text-sm text-emerald-700 dark:text-emerald-400 space-y-1">
                                    <div class="flex justify-between">
                                        <span>Tier:</span>
                                        <span class="font-medium">≥ {{ $item['applied_tier']['min_quantity'] }} unit</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Harga Tier:</span>
                                        <span class="font-medium">Rp {{ number_format($item['applied_tier']['price'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between font-bold">
                                        <span>Total Hemat:</span>
                                        <span>Rp {{ number_format($item['discount_amount'] ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-5 text-center">
                        <button wire:click="$set('showTieredModal', false)" class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition font-medium">
                            Tutup
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- MODAL – Edit Price --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <div
        x-show="showEditPriceModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        @click.self="showEditPriceModal = false"
    >
        <div
            x-show="showEditPriceModal"
            x-transition:enter="transition ease-out duration-200 transform"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md"
        >
            <div class="p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Edit Harga
                    </h3>
                    <button @click="showEditPriceModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Harga Baru</label>
                        <input 
                            type="number" 
                            wire:model.number="editingPrice" 
                            min="0"
                            step="100"
                            placeholder="Masukkan harga baru"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>
                    
                    <div class="flex gap-3 mt-6">
                        <button 
                            @click="showEditPriceModal = false"
                            class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition"
                        >
                            Batal
                        </button>
                        <button 
                            wire:click="saveEditedPrice"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition"
                        >
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- MODAL – Checkout / Payment --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    @if($showCheckout)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/80 backdrop-blur-md">
        <div class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">

            {{-- Header --}}
            <div class="px-8 pt-8 pb-6 bg-linear-to-r from-blue-600 to-indigo-600 text-white text-center relative">
                <button wire:click="closeCheckout" class="absolute top-4 right-4 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 p-2 rounded-full transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <p class="text-blue-100 font-medium uppercase tracking-widest text-xs mb-1">Total Pembayaran</p>
                <h2 class="text-4xl font-black">Rp {{ number_format($summary['grand_total'], 0, ',', '.') }}</h2>
                @if($summary['savings'] > 0)
                    <p class="text-blue-200 text-sm mt-1">Hemat Rp {{ number_format($summary['savings'], 0, ',', '.') }}</p>
                @endif
            </div>

            <div class="p-8 space-y-5">
                @if(session('checkout_error'))
                    <div class="bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 p-3 rounded-xl text-sm font-bold text-center">
                        {{ session('checkout_error') }}
                    </div>
                @endif

                {{-- Payment Method --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Metode Pembayaran</label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['cash' => 'Tunai', 'transfer' => 'Transfer', 'e-wallet' => 'E-Wallet'] as $value => $label)
                            <label class="cursor-pointer">
                                <input type="radio" wire:model.live="paymentMethod" value="{{ $value }}" class="peer sr-only">
                                <div class="peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/30 peer-checked:border-indigo-500 peer-checked:text-indigo-700 dark:peer-checked:text-indigo-300 rounded-xl border-2 border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-700 p-3 text-center transition font-bold text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600 text-sm">
                                    {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Amount (Cash) --}}
                @if($paymentMethod === 'cash')
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Uang Diterima (Rp)
                            <span class="ml-1 text-xs bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-300 px-1.5 py-0.5 rounded">F2</span>
                        </label>
                        <input
                            type="number"
                            wire:model.live="payment"
                            class="w-full text-2xl font-black py-4 px-5 rounded-2xl border-2 border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring-0 transition"
                            placeholder="0"
                            x-ref="paymentInput"
                        >

                        {{-- Quick amounts --}}
                        <div class="grid grid-cols-4 gap-2 mt-3">
                            <button type="button" wire:click="$set('payment', {{ $summary['grand_total'] }})" class="py-2 text-xs font-bold bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl transition">Pas</button>
                            <button type="button" wire:click="$set('payment', 50000)"  class="py-2 text-xs font-bold bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl transition">50K</button>
                            <button type="button" wire:click="$set('payment', 100000)" class="py-2 text-xs font-bold bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl transition">100K</button>
                            <button type="button" wire:click="$set('payment', 200000)" class="py-2 text-xs font-bold bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl transition">200K</button>
                        </div>
                    </div>

                    {{-- Change --}}
                    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-700">
                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Kembalian</span>
                        <span class="text-2xl font-black {{ $summary['change'] > 0 ? 'text-emerald-500' : 'text-gray-900 dark:text-white' }}">
                            Rp {{ number_format($summary['change'], 0, ',', '.') }}
                        </span>
                    </div>
                @endif

                {{-- Confirm button --}}
                <button
                    wire:click="processCheckout"
                    @disabled($paymentMethod === 'cash' && $payment < $summary['grand_total'])
                    class="w-full py-4 rounded-2xl font-black text-xl transition-all duration-200 focus:ring-4 focus:ring-indigo-500/50
                        {{ ($paymentMethod !== 'cash' || $payment >= $summary['grand_total'])
                            ? 'bg-linear-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white shadow-xl shadow-indigo-600/30 hover:-translate-y-0.5'
                            : 'bg-gray-100 dark:bg-gray-700 text-gray-400 cursor-not-allowed' }}"
                >
                    <span wire:loading.remove wire:target="processCheckout" class="flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9a2 2 0 10-4 0v5a2 2 0 01-2 2h6m-6-4h4m8 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Konfirmasi Pembayaran
                    </span>
                    <span wire:loading wire:target="processCheckout" class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Memproses…
                    </span>
                </button>

                <p class="text-center text-xs text-gray-400 dark:text-gray-500">Tekan F3 untuk mengkonfirmasi transaksi</p>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- RECEIPT MODAL – Struk Belanja --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if($showReceiptModal && $receiptData)
<div class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-gray-900/80 backdrop-blur-md print:hidden-backdrop" id="receipt-overlay">
    <div class="bg-white dark:bg-gray-800 w-full max-w-xs rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-5 pt-5 pb-3 border-b border-gray-100 dark:border-gray-700 print:hidden">
            <h3 class="font-bold text-gray-800 dark:text-white">🧾 Struk Transaksi</h3>
            <button wire:click="closeReceiptModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Receipt Content --}}
        <div class="p-5" id="pos-receipt">
            {{-- Store Header --}}
            <div class="text-center mb-4">
                <h2 class="text-lg font-black text-gray-900 dark:text-white tracking-tight">Tokoatik</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Dk. Jepar Ds.Dander</p>
                <div class="border-t border-dashed border-gray-300 dark:border-gray-600 my-3"></div>
            </div>

            {{-- Invoice Info --}}
            <div class="space-y-1 text-xs mb-3">
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Invoice</span>
                    <span class="font-bold font-mono text-gray-800 dark:text-white">{{ $receiptData['invoice_number'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Tanggal</span>
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $receiptData['date'] }}</span>
                </div>
                
            </div>

            <div class="border-t border-dashed border-gray-300 dark:border-gray-600 my-3"></div>

            {{-- Items --}}
            <div class="space-y-2 mb-3">
                @foreach($receiptData['items'] as $item)
                <div class="text-xs">
                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-800 dark:text-white flex-1 pr-2 leading-tight">{{ $item['name'] }}</span>
                        <span class="font-bold text-gray-800 dark:text-white whitespace-nowrap">Rp {{ number_format($item['quantity'] * $item['price'], 0, ',', '.') }}</span>
                    </div>
                    <div class="text-gray-500 dark:text-gray-400 mt-0.5">
                        {{ $item['quantity'] }} {{ $item['unit_code'] }} × Rp {{ number_format($item['price'], 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>

            <div class="border-t border-dashed border-gray-300 dark:border-gray-600 my-3"></div>

            {{-- Totals --}}
            <div class="space-y-1 text-xs mb-3">
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
                    <span class="text-gray-700 dark:text-gray-300">Rp {{ number_format($receiptData['subtotal'], 0, ',', '.') }}</span>
                </div>
                @if($receiptData['savings'] > 0)
                <div class="flex justify-between text-emerald-600">
                    <span>Hemat</span>
                    <span>- Rp {{ number_format($receiptData['savings'], 0, ',', '.') }}</span>
                </div>
                @endif
                @foreach($receiptData['additional_costs'] as $cost)
                <div class="flex justify-between text-amber-600">
                    <span>{{ $cost['description'] }}</span>
                    <span>{{ $cost['is_discount'] ? '- ' : '+ ' }}Rp {{ number_format($cost['amount'], 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>

            <div class="border-t-2 border-gray-800 dark:border-gray-200 my-3"></div>

            {{-- Grand Total --}}
            <div class="flex justify-between items-center mb-3">
                <span class="font-black text-sm text-gray-900 dark:text-white uppercase">Total</span>
                <span class="font-black text-base text-gray-900 dark:text-white">Rp {{ number_format($receiptData['grand_total'], 0, ',', '.') }}</span>
            </div>

            <div class="space-y-1 text-xs">
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Bayar ({{ strtoupper($receiptData['payment_method']) }})</span>
                    <span class="text-gray-700 dark:text-gray-300">Rp {{ number_format($receiptData['payment'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold">
                    <span class="text-gray-800 dark:text-white">Kembalian</span>
                    <span class="text-emerald-600 dark:text-emerald-400">Rp {{ number_format($receiptData['change'], 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="border-t border-dashed border-gray-300 dark:border-gray-600 my-3"></div>

            {{-- Footer --}}
            <p class="text-center text-xs text-gray-400 dark:text-gray-500">Terima kasih telah berbelanja!</p>
            <p class="text-center text-xs text-gray-400 dark:text-gray-500 mt-1">Barang yang sudah dibeli tidak dapat dikembalikan.</p>
        </div>

        {{-- Action Buttons --}}
        <div class="px-5 pb-5 flex gap-3 print:hidden">
            <button onclick="printReceipt()" class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition text-sm flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Struk
            </button>
            <button wire:click="closeReceiptModal" class="flex-1 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition text-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- Print Script & Styles --}}
<script>
function printReceipt() {
    var receiptContent = document.getElementById('pos-receipt').innerHTML;
    var printWindow = window.open('', '_blank', 'width=320,height=600');
    printWindow.document.write('<html><head><title>Struk Tokoatik</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('body { font-family: monospace; font-size: 12px; margin: 0; padding: 12px; background: white; color: black; }');
    printWindow.document.write('.text-xs { font-size: 11px; }');
    printWindow.document.write('.text-lg { font-size: 15px; }');
    printWindow.document.write('.text-sm { font-size: 12px; }');
    printWindow.document.write('.text-base { font-size: 13px; }');
    printWindow.document.write('.font-black { font-weight: 900; }');
    printWindow.document.write('.font-bold { font-weight: 700; }');
    printWindow.document.write('.font-semibold { font-weight: 600; }');
    printWindow.document.write('.font-medium { font-weight: 500; }');
    printWindow.document.write('.font-mono { font-family: monospace; }');
    printWindow.document.write('.text-center { text-align: center; }');
    printWindow.document.write('.flex { display: flex; }');
    printWindow.document.write('.justify-between { justify-content: space-between; }');
    printWindow.document.write('.items-center { align-items: center; }');
    printWindow.document.write('.space-y-1 > * + * { margin-top: 4px; }');
    printWindow.document.write('.space-y-2 > * + * { margin-top: 8px; }');
    printWindow.document.write('.mb-3 { margin-bottom: 12px; }');
    printWindow.document.write('.mb-4 { margin-bottom: 16px; }');
    printWindow.document.write('.my-3 { margin-top: 12px; margin-bottom: 12px; }');
    printWindow.document.write('.mt-0\.5 { margin-top: 2px; }');
    printWindow.document.write('.mt-1 { margin-top: 4px; }');
    printWindow.document.write('.border-t { border-top: 1px solid #ccc; }');
    printWindow.document.write('.border-t-2 { border-top: 2px solid #333; }');
    printWindow.document.write('.border-dashed { border-style: dashed; }');
    printWindow.document.write('.tracking-tight { letter-spacing: -0.5px; }');
    printWindow.document.write('.flex-1 { flex: 1; }');
    printWindow.document.write('.pr-2 { padding-right: 8px; }');
    printWindow.document.write('.whitespace-nowrap { white-space: nowrap; }');
    printWindow.document.write('.leading-tight { line-height: 1.2; }');
    printWindow.document.write('.text-gray-500, .text-gray-400 { color: #6b7280; }');
    printWindow.document.write('.text-gray-700, .text-gray-800, .text-gray-900 { color: #111; }');
    printWindow.document.write('.text-emerald-600 { color: #059669; }');
    printWindow.document.write('.text-amber-600 { color: #d97706; }');
    printWindow.document.write('.uppercase { text-transform: uppercase; }');
    printWindow.document.write('@media print { body { margin: 0; } }');
    printWindow.document.write('</style></head><body>');
    printWindow.document.write(receiptContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    setTimeout(function() { printWindow.print(); }, 300);
}
</script>
@endif

</div>
