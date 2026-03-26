<div>
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Manajemen Hutang</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Lacak hutang pelanggan dari transaksi atau input
                manual.</p>
        </div>

        <!-- <div
            class="flex p-1 bg-gray-100 dark:bg-gray-800 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm w-max">
            <button wire:click="setTab('debts')"
                class="px-5 py-2 text-sm font-medium rounded-lg transition-all {{ $activeTab === 'debts' ? 'bg-white text-indigo-600 shadow-sm dark:bg-gray-700 dark:text-indigo-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
                Daftar Hutang
            </button>
            <button wire:click="setTab('notes')"
                class="px-5 py-2 text-sm font-medium rounded-lg transition-all {{ $activeTab === 'notes' ? 'bg-white text-indigo-600 shadow-sm dark:bg-gray-700 dark:text-indigo-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
                Catatan
            </button>
        </div> -->
    </div>

    @if (session()->has('message'))
    <div class="bg-emerald-50 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-200 p-4 rounded-xl mb-6 flex items-center gap-3 border border-emerald-200 dark:border-emerald-800 transition-all"
        x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd" />
        </svg>
        {{ session('message') }}
    </div>
    @endif

    <div
        class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden min-h-[500px]">

        @if($activeTab === 'debts')
        <!-- Debts Table View -->
        <div class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-3">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Daftar Hutang</h2>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="searchDebt"
                            placeholder="Cari nama atau alamat..."
                            class="pl-9 pr-4 py-2 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-48 shadow-sm">
                    </div>
                    <button wire:click="createDebt()"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-xl shadow-sm transition-colors text-sm flex items-center whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Hutang Baru
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Nama Pelanggan</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Alamat</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Jumlah Hutang</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tanggal</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Keterangan</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($debts as $debt)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $debt->customer_name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                    {{ $debt->address ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-red-600 dark:text-red-400">Rp
                                    {{ number_format($debt->amount, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $debt->debt_date->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                    {{ $debt->notes ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="editDebt({{ $debt->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 mr-3">Edit</button>
                                <button wire:click="deleteDebt({{ $debt->id }})"
                                    onclick="confirm('Hapus hutang ini?') || event.stopImmediatePropagation()"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                Belum ada data hutang.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @elseif($activeTab === 'notes')
        <!-- Notes View -->
        <div class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-3">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Catatan Apapun</h2>
                <button wire:click="createNote()"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-xl shadow-sm transition-colors text-sm flex items-center whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Catatan Baru
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($notesList as $note)
                <div
                    class="bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-xl p-5 border border-yellow-200 dark:border-yellow-800/30 hover:shadow-md transition-all group">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $note['title'] }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-3">{{ $note['content'] }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                {{ \Carbon\Carbon::parse($note['updated_at'])->format('d M Y H:i') }}</p>
                        </div>
                        <div class=" flex gap-2 ml-3">
                            <button wire:click="editNote('{{ $note['id'] }}')"
                                class="p-1.5 rounded bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-200 dark:hover:bg-indigo-900/50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button wire:click="deleteNote('{{ $note['id'] }}')"
                                onclick="confirm('Hapus catatan?') || event.stopImmediatePropagation()"
                                class="p-1.5 rounded bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-10 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 opacity-30" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <p>Belum ada catatan.</p>
                </div>
                @endforelse
            </div>
        </div>
        @endif
    </div>

    <!-- Debt Modal -->
    @if($isDebtModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100 dark:border-gray-700">
                <form wire:submit.prevent="storeDebt">
                    <div class="px-6 pt-6 pb-4">
                        <h3 class="text-xl leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">
                            {{ $debtId ? 'Edit Hutang' : 'Hutang Baru' }}</h3>
                        <div class="mt-4 space-y-4 max-h-96 overflow-y-auto">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama
                                    Pelanggan</label>
                                <input type="text" wire:model="customerName"
                                    class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2"
                                    placeholder="Nama pelanggan">
                                @error('customerName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat</label>
                                <textarea wire:model="address" rows="2"
                                    class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2"
                                    placeholder="Alamat pelanggan"></textarea>
                                @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah
                                    Hutang</label>
                                <input type="number" step="0.01" wire:model="amount"
                                    class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2"
                                    placeholder="0">
                                @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal
                                    Hutang</label>
                                <input type="date" wire:model="debtDate"
                                    class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2">
                                @error('debtDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dari Transaksi
                                    (Opsional)</label>
                                <select wire:model="transactionId"
                                    class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2">
                                    <option value="">-- Tidak ada/Input Manual --</option>
                                    @foreach($transactions as $transaction)
                                    <option value="{{ $transaction->id }}">
                                        #{{ $transaction->id }} - Rp
                                        {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keterangan</label>
                                <textarea wire:model="notes" rows="2"
                                    class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2"
                                    placeholder="Catatan tentang hutang ini..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div
                        class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 rounded-b-2xl">
                        <button type="button" wire:click="closeDebtModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-xl hover:bg-indigo-700 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Note Modal -->
    @if($isNoteModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-gray-100 dark:border-gray-700">
                <form wire:submit.prevent="storeNote">
                    <div class="px-6 pt-6 pb-4">
                        <h3 class="text-xl leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">
                            {{ $noteId ? 'Edit Catatan' : 'Catatan Baru' }}</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul
                                    Catatan</label>
                                <input type="text" wire:model="noteTitle"
                                    class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2"
                                    placeholder="Judul catatan">
                                @error('noteTitle') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Isi
                                    Catatan</label>
                                <textarea wire:model="noteContent" rows="4"
                                    class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2"
                                    placeholder="Tulis catatan Anda di sini..."></textarea>
                                @error('noteContent') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div
                        class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 rounded-b-2xl">
                        <button type="button" wire:click="closeNoteModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 transition">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-xl hover:bg-indigo-700 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>