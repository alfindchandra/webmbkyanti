<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Carbon;

class Reports extends Component
{
    public $activeTab = 'daily'; // daily, monthly, top_products
    
    // Daily Report
    public $dailyDate;
    
    // Monthly Report
    public $monthlyMonth;
    
    public function mount()
    {
        $this->dailyDate = today()->format('Y-m-d');
        $this->monthlyMonth = today()->format('Y-m');
    }

    public function render()
    {
        return view('livewire.reports', [
            'dailyData' => $this->getDailyData(),
            'monthlyData' => $this->getMonthlyData(),
            'topProducts' => $this->getTopProducts()
        ]);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    private function getDailyData()
    {
        $transactions = Transaction::with('user', 'items.product')
            ->whereDate('created_at', $this->dailyDate)
            ->get();
            
        return [
            'transactions' => $transactions,
            'total_sales' => $transactions->sum('grand_total'),
            'total_transactions' => $transactions->count(),
        ];
    }

    private function getMonthlyData()
    {
        $date = Carbon::parse($this->monthlyMonth . '-01');
        
        $transactions = Transaction::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->get();
            
        // Group by day for the chart
        $dailyBreakdown = $transactions->groupBy(function($item) {
            return $item->created_at->format('Y-m-d');
        })->map(function($group) {
            return $group->sum('grand_total');
        });

        return [
            'total_sales' => $transactions->sum('grand_total'),
            'total_transactions' => $transactions->count(),
            'daily_breakdown' => $dailyBreakdown,
        ];
    }

    private function getTopProducts()
    {
        // For the current month
        $date = Carbon::parse($this->monthlyMonth . '-01');

        $items = TransactionItem::with(['product.baseUnit', 'unit'])
            ->whereHas('transaction', function($query) use ($date) {
                $query->whereYear('created_at', $date->year)
                      ->whereMonth('created_at', $date->month);
            })
            ->select('product_id', 'unit_id', \DB::raw('SUM(quantity) as total_quantity'), \DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_id', 'unit_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        return $items;
    }
}
