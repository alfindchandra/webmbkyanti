<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;

class Sales extends Component
{
    use WithPagination;

    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $selectedTransaction = null;

    protected $queryString = ['search', 'dateFrom', 'dateTo'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function viewDetail($id)
    {
        $this->selectedTransaction = Transaction::with(['items.product', 'items.unit', 'user'])
            ->findOrFail($id);
    }

    public function closeDetail()
    {
        $this->selectedTransaction = null;
    }

    public function render()
    {
        $transactions = Transaction::with(['items.product', 'user'])
            ->when($this->search, function ($q) {
                $q->where('invoice_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('items.product', function ($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%');
                  });
            })
            ->when($this->dateFrom, fn($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->latest()
            ->paginate(15);

        return view('livewire.sales', [
            'transactions' => $transactions,
        ])->layout('layouts.app', ['title' => 'Penjualan | Tokoatik']);
    }
}
