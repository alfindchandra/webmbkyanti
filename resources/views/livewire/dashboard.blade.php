<div>
    <!-- Header Section -->
    <div class="mb-10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 dark:text-white bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400 bg-clip-text text-transparent">Dashboard Overview</h1>
                <p class="mt-3 text-base text-gray-600 dark:text-gray-400">Selamat datang kembali di Tokoatik POS. Berikut adalah ringkasan performa hari ini.</p>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-10">
        
        <!-- Today Sales -->
        <div class="group relative bg-white dark:bg-gray-800 overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-xl hover:border-blue-300 dark:hover:border-blue-600">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent dark:from-blue-900/20 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900/50 dark:to-blue-900/30 rounded-xl p-3 ring-1 ring-blue-200 dark:ring-blue-800">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                        </div>
                        <dt class="mt-4 text-sm font-medium text-gray-600 dark:text-gray-400">Penjualan Hari Ini</dt>
                        <dd class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($todaySales, 0, ',', '.') }}</dd>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-400 to-blue-600"></div>
        </div>

        <!-- Weekly Sales -->
        <div class="group relative bg-white dark:bg-gray-800 overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-xl hover:border-indigo-300 dark:hover:border-indigo-600">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-transparent dark:from-indigo-900/20 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-gradient-to-br from-indigo-100 to-indigo-50 dark:from-indigo-900/50 dark:to-indigo-900/30 rounded-xl p-3 ring-1 ring-indigo-200 dark:ring-indigo-800">
                                <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                            </div>
                        </div>
                        <dt class="mt-4 text-sm font-medium text-gray-600 dark:text-gray-400">Minggu Ini</dt>
                        <dd class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($weeklySales, 0, ',', '.') }}</dd>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-400 to-indigo-600"></div>
        </div>

        <!-- Active Products -->
        <div class="group relative bg-white dark:bg-gray-800 overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-xl hover:border-amber-300 dark:hover:border-amber-600">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-50 to-transparent dark:from-amber-900/20 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-gradient-to-br from-amber-100 to-amber-50 dark:from-amber-900/50 dark:to-amber-900/30 rounded-xl p-3 ring-1 ring-amber-200 dark:ring-amber-800">
                                <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                            </div>
                        </div>
                        <dt class="mt-4 text-sm font-medium text-gray-600 dark:text-gray-400">Total Produk</dt>
                        <dd class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalProducts }}</dd>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 to-amber-600"></div>
        </div>

        <!-- Transactions Today -->
        <div class="group relative bg-white dark:bg-gray-800 overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-xl hover:border-emerald-300 dark:hover:border-emerald-600">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-transparent dark:from-emerald-900/20 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-gradient-to-br from-emerald-100 to-emerald-50 dark:from-emerald-900/50 dark:to-emerald-900/30 rounded-xl p-3 ring-1 ring-emerald-200 dark:ring-emerald-800">
                                <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                            </div>
                        </div>
                        <dt class="mt-4 text-sm font-medium text-gray-600 dark:text-gray-400">Transaksi</dt>
                        <dd class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalTransactions }}</dd>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
        </div>
    </div>

    <!-- Chart Block -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-8 shadow-sm hover:shadow-lg transition-shadow duration-300">
        <div class="mb-8">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Analitik Penjualan</h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Tren penjualan dalam 7 hari terakhir</p>
        </div>
        <div class="h-80 flex items-end justify-between space-x-3">
            @foreach($chartData as $data)
            <div class="flex-1 flex flex-col items-center group relative">
                <!-- Bar Container -->
                @php 
                    $maxAmount = max(1, $chartData->max('total'));
                    $heightPercentage = ($data['total'] / $maxAmount) * 100;
                    $heightPercentage = max(5, $heightPercentage);
                @endphp
                
                <div class="w-full flex flex-col items-center">
                    <!-- Bar -->
                    <div class="w-full relative bg-gradient-to-t from-indigo-600 to-indigo-400 dark:from-indigo-500 dark:to-indigo-400 rounded-t-lg transition-all duration-300 hover:shadow-lg hover:from-indigo-700 hover:to-indigo-500 cursor-pointer transform group-hover:scale-y-105 origin-bottom" style="height: {{ $heightPercentage }}%; min-height: 8px;">
                        <!-- Tooltip -->
                        <div class="absolute -top-12 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 bg-gray-900 dark:bg-gray-700 text-white text-xs font-semibold rounded-lg py-2 px-3 pointer-events-none transition-opacity whitespace-nowrap shadow-xl z-10">
                            Rp {{ number_format($data['total'], 0, ',', '.') }}
                            <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
                        </div>
                    </div>
                </div>
                <!-- Label -->
                <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 mt-3">{{ $data['date'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

</div>
