<div>
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4 print:hidden">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Laporan & Analitik</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Lihat performa penjualan real-time dan produk terlaris.</p>
        </div>
        
        <div class="flex p-1 bg-gray-100 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm w-max">
            <button wire:click="setTab('daily')" class="px-5 py-2 text-sm font-medium rounded-lg transition-all {{ $activeTab === 'daily' ? 'bg-white text-indigo-600 shadow-sm dark:bg-gray-700 dark:text-indigo-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">Harian</button>
            <button wire:click="setTab('monthly')" class="px-5 py-2 text-sm font-medium rounded-lg transition-all {{ $activeTab === 'monthly' ? 'bg-white text-indigo-600 shadow-sm dark:bg-gray-700 dark:text-indigo-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">Bulanan</button>
            <button wire:click="setTab('top_products')" class="px-5 py-2 text-sm font-medium rounded-lg transition-all {{ $activeTab === 'top_products' ? 'bg-white text-indigo-600 shadow-sm dark:bg-gray-700 dark:text-indigo-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">Produk Terlaris</button>
        </div>
    </div>

    <!-- Print Header (Only visible when printing) -->
    <div class="hidden print:block mb-8 text-center pb-6 border-b-2 border-gray-200">
        <h1 class="text-3xl font-black text-gray-900 mb-2">POS Retail Tokoatik</h1>
        <h2 class="text-xl text-gray-600 font-bold uppercase tracking-widest">
            @if($activeTab === 'daily') Laporan Penjualan Harian ({{ \Carbon\Carbon::parse($dailyDate)->format('d M Y') }})
            @elseif($activeTab === 'monthly') Laporan Bulanan ({{ \Carbon\Carbon::parse($monthlyMonth)->format('F Y') }})
            @else Produk Terlaris ({{ \Carbon\Carbon::parse($monthlyMonth)->format('F Y') }}) @endif
        </h2>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden min-h-[500px]">
        
        <!-- Action Bar (Date pickers and Print button) -->
        <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50 print:hidden">
            <div>
                @if($activeTab === 'daily')
                    <input type="date" wire:model.live="dailyDate" class="rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm py-2 shadow-sm text-gray-900 dark:text-white focus:ring-indigo-500">
                @else
                    <input type="month" wire:model.live="monthlyMonth" class="rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm py-2 shadow-sm text-gray-900 dark:text-white focus:ring-indigo-500">
                @endif
            </div>
            
            <button onclick="window.print()" class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 flex items-center gap-2 text-sm font-bold bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/30 px-4 py-2 rounded-lg transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Ekspor PDF
            </button>
        </div>

        <div class="p-6">
            
            @if($activeTab === 'daily')
                <!-- Daily Content -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 p-6 rounded-2xl border border-indigo-100 dark:border-indigo-800/50">
                        <p class="text-indigo-600 dark:text-indigo-400 font-bold uppercase tracking-widest text-xs mb-1">Total Penjualan Harian</p>
                        <h3 class="text-4xl font-black text-indigo-900 dark:text-indigo-100">Rp {{ number_format($dailyData['total_sales'], 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-2xl border border-gray-200 dark:border-gray-600">
                        <p class="text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest text-xs mb-1">Transaksi</p>
                        <h3 class="text-4xl font-black text-gray-900 dark:text-white">{{ $dailyData['total_transactions'] }}</h3>
                    </div>
                </div>

                <h4 class="font-bold text-gray-900 dark:text-white mb-4">Riwayat Transaksi</h4>
                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/80">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Faktur</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kasir</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @forelse($dailyData['transactions'] as $trx)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $trx->created_at->format('H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">{{ $trx->invoice_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $trx->user->name ?? 'Sistem' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600 dark:text-indigo-400 text-right">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">Tidak ada transaksi yang tercatat pada tanggal ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            @elseif($activeTab === 'monthly')
                <!-- Monthly Content -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 p-6 rounded-2xl border border-indigo-100 dark:border-indigo-800/50">
                        <p class="text-indigo-600 dark:text-indigo-400 font-bold uppercase tracking-widest text-xs mb-1">Total Pendapatan Bulanan</p>
                        <h3 class="text-4xl font-black text-indigo-900 dark:text-indigo-100">Rp {{ number_format($monthlyData['total_sales'], 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-2xl border border-gray-200 dark:border-gray-600">
                        <p class="text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest text-xs mb-1">Total Transaksi</p>
                        <h3 class="text-4xl font-black text-gray-900 dark:text-white">{{ $monthlyData['total_transactions'] }}</h3>
                    </div>
                </div>

                <h4 class="font-bold text-gray-900 dark:text-white mb-4">Rincian Harian</h4>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-2">
                    @php
                        $daysInMonth = \Carbon\Carbon::parse($monthlyMonth . '-01')->daysInMonth;
                    @endphp
                    @for($i = 1; $i <= $daysInMonth; $i++)
                        @php
                            $dateStr = \Carbon\Carbon::parse($monthlyMonth . '-01')->setDay($i)->format('Y-m-d');
                            $hasSales = isset($monthlyData['daily_breakdown'][$dateStr]);
                            $salesAmount = $hasSales ? $monthlyData['daily_breakdown'][$dateStr] : 0;
                        @endphp
                        <div class="p-3 rounded-lg border {{ $hasSales ? 'border-indigo-200 bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-800' : 'border-gray-100 bg-gray-50 dark:bg-gray-800 dark:border-gray-700' }} flex flex-col justify-between" style="min-height: 80px;">
                            <span class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($dateStr)->format('D, d') }}</span>
                            @if($hasSales)
                                <span class="text-sm font-black text-indigo-600 dark:text-indigo-400 mt-2">Rp {{ number_format($salesAmount / 1000, 0, ',', '.') }}K</span>
                            @else
                                <span class="text-xs text-gray-400 mt-2">-</span>
                            @endif
                        </div>
                    @endfor
                </div>

            @else
                <!-- Top Products Content -->
                <h4 class="font-bold text-gray-900 dark:text-white mb-4">Produk Paling Banyak Terjual ({{ \Carbon\Carbon::parse($monthlyMonth)->format('F Y') }})</h4>
                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/80">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Satuan Terjual</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Jml Terjual</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Pendapatan Dihasilkan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @forelse($topProducts as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 dark:text-white">{{ $item->product->name ?? 'Produk Tidak Diketahui' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    <span class="bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded font-medium">{{ $item->unit->code ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white text-right">{{ $item->total_quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-emerald-600 dark:text-emerald-400 text-right">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">Tidak ada data penjualan untuk bulan ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
    
    <!-- Print specific styles -->
    <style>
        @media print {
            body { background: white !important; }
            .print\:hidden { display: none !important; }
            .print\:block { display: block !important; }
            .bg-white, .dark\:bg-gray-800, .bg-gray-50 { background-color: white !important; }
            .border-gray-100, .dark\:border-gray-700 { border-color: #e5e7eb !important; }
            * { color: black !important; box-shadow: none !important; }
        }
    </style>
</div>
