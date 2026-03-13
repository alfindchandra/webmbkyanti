<div>
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Penjualan</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Riwayat transaksi dan detail produk yang terjual.</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-4 mb-4 flex flex-col sm:flex-row gap-3 items-start sm:items-center">
        {{-- Search --}}
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" wire:model.live.debounce.400ms="search" placeholder="Cari no. invoice atau nama produk..." class="pl-9 pr-4 py-2 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-full shadow-sm">
        </div>
        {{-- Date From --}}
        <div class="flex items-center gap-2 shrink-0">
            <label class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">Dari</label>
            <input type="date" wire:model.live="dateFrom" class="text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-2 px-3 focus:ring-2 focus:ring-indigo-500 shadow-sm">
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <label class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">Sampai</label>
            <input type="date" wire:model.live="dateTo" class="text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white py-2 px-3 focus:ring-2 focus:ring-indigo-500 shadow-sm">
        </div>
        @if($search || $dateFrom || $dateTo)
        <button wire:click="$set('search', ''); $set('dateFrom', ''); $set('dateTo', '')" class="text-xs text-red-500 hover:text-red-700 dark:hover:text-red-400 font-medium whitespace-nowrap shrink-0">
            Hapus Filter
        </button>
        @endif
    </div>

    {{-- Transactions Table --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">No. Invoice</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kasir</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $trx->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $trx->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm font-bold text-indigo-600 dark:text-indigo-400">{{ $trx->invoice_number }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                            {{ $trx->user?->name ?? 'Sistem' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($trx->items->take(3) as $item)
                                <span class="inline-block px-2 py-0.5 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                    {{ $item->product?->name ?? 'Produk dihapus' }}
                                </span>
                                @endforeach
                                @if($trx->items->count() > 3)
                                <span class="inline-block px-2 py-0.5 text-xs rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400">
                                    +{{ $trx->items->count() - 3 }} lagi
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-bold text-gray-900 dark:text-white">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</div>
                            @if(isset($trx->payment_amount) && $trx->payment_amount > $trx->grand_total)
                            <div class="text-xs text-emerald-600 dark:text-emerald-400">Kembali: Rp {{ number_format($trx->payment_amount - $trx->grand_total, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button wire:click="viewDetail({{ $trx->id }})" class="text-xs font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 px-3 py-1.5 rounded-lg transition">
                                Lihat Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400 dark:text-gray-500">
                                <svg class="w-12 h-12 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="font-medium">Tidak ada transaksi ditemukan</p>
                                <p class="text-sm">Coba ubah kata kunci pencarian atau filter tanggal.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>

    {{-- Detail Modal --}}
    @if($selectedTransaction)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeDetail"></div>

            {{-- Modal Card --}}
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 w-full max-w-2xl z-10 overflow-hidden">
                {{-- Header --}}
                <div class="flex items-start justify-between px-6 pt-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Detail Transaksi</h2>
                        <p class="text-sm text-indigo-600 dark:text-indigo-400 font-mono font-bold mt-0.5">{{ $selectedTransaction->invoice_number }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $selectedTransaction->created_at->format('d M Y, H:i') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kasir: {{ $selectedTransaction->user?->name ?? 'Sistem' }}</p>
                    </div>
                </div>

                {{-- Items List --}}
                <div class="px-6 py-4 max-h-80 overflow-y-auto">
                    <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Produk yang Dibeli</h3>
                    <div class="space-y-2">
                        @foreach($selectedTransaction->items as $item)
                        <div class="flex items-center justify-between gap-4 p-3 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700/50">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $item->product?->name ?? 'Produk dihapus' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    {{ number_format($item->quantity, 0, ',', '.') }} {{ $item->unit?->code ?? '' }} × Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="text-sm font-bold text-gray-900 dark:text-white shrink-0">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
                    <div class="space-y-2">
                        @if(isset($selectedTransaction->discount) && $selectedTransaction->discount > 0)
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                            <span>Diskon</span>
                            <span class="text-red-500">- Rp {{ number_format($selectedTransaction->discount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if(isset($selectedTransaction->tax) && $selectedTransaction->tax > 0)
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                            <span>Pajak / Biaya Lain</span>
                            <span>+ Rp {{ number_format($selectedTransaction->tax, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-600">
                            <span class="font-bold text-gray-900 dark:text-white">Total</span>
                            <span class="text-lg font-black text-indigo-600 dark:text-indigo-400">Rp {{ number_format($selectedTransaction->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-3 mt-4">
                        <button onclick="printSalesReceipt()" class="flex-1 px-4 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Cetak Struk
                        </button>
                        <button wire:click="closeDetail" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl transition">
                            Tutup
                        </button>
                    </div>
                </div>

                {{-- Hidden printable receipt --}}
                <div id="sales-receipt-content" style="display:none">
                    <div style="text-align:center; margin-bottom:16px">
                        <strong style="font-size:15px">Tokoatik</strong><br>
                        <span style="font-size:11px; color:#6b7280">Point of Sale</span><br>
                        <div style="border-top:1px dashed #ccc; margin:12px 0"></div>
                    </div>
                    <div style="font-size:11px; margin-bottom:12px">
                        <div style="display:flex; justify-content:space-between"><span style="color:#6b7280">Invoice</span><strong style="font-family:monospace">{{ $selectedTransaction->invoice_number }}</strong></div>
                        <div style="display:flex; justify-content:space-between; margin-top:4px"><span style="color:#6b7280">Tanggal</span><span>{{ $selectedTransaction->created_at->format('d M Y, H:i') }}</span></div>
                        <div style="display:flex; justify-content:space-between; margin-top:4px"><span style="color:#6b7280">Kasir</span><span>{{ $selectedTransaction->user?->name ?? 'Sistem' }}</span></div>
                    </div>
                    <div style="border-top:1px dashed #ccc; margin:12px 0"></div>
                    @foreach($selectedTransaction->items as $item)
                    <div style="font-size:11px; margin-bottom:8px">
                        <div style="display:flex; justify-content:space-between">
                            <strong style="flex:1; padding-right:8px">{{ $item->product?->name ?? 'Produk dihapus' }}</strong>
                            <strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                        </div>
                        <div style="color:#6b7280; margin-top:2px">{{ number_format($item->quantity, 0, ',', '.') }} {{ $item->unit?->code ?? '' }} × Rp {{ number_format($item->unit_price, 0, ',', '.') }}</div>
                    </div>
                    @endforeach
                    <div style="border-top:2px solid #333; margin:12px 0"></div>
                    <div style="display:flex; justify-content:space-between; font-size:13px">
                        <strong>TOTAL</strong>
                        <strong>Rp {{ number_format($selectedTransaction->grand_total, 0, ',', '.') }}</strong>
                    </div>
                    <div style="border-top:1px dashed #ccc; margin:12px 0"></div>
                    <div style="text-align:center; font-size:10px; color:#6b7280">
                        Terima kasih telah berbelanja!<br>
                        Barang yang sudah dibeli tidak dapat dikembalikan.
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Print Script for Sales Receipt --}}
    @if($selectedTransaction)
    <script>
    function printSalesReceipt() {
        var content = document.getElementById('sales-receipt-content').innerHTML;
        var printWindow = window.open('', '_blank', 'width=320,height=600');
        printWindow.document.write('<html><head><title>Struk - {{ $selectedTransaction->invoice_number }}</title>');
        printWindow.document.write('<style>body { font-family: monospace; font-size: 12px; margin: 0; padding: 12px; background: white; color: black; } @media print { body { margin:0; } }</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        setTimeout(function() { printWindow.print(); }, 300);
    }
    </script>
    @endif
</div>
