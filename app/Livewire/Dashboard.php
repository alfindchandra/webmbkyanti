<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Product;

class Dashboard extends Component
{
    public function render()
    {
        $todaySales = Transaction::whereDate('created_at', today())->sum('grand_total');
        $weeklySales = Transaction::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('grand_total');
        $monthlySales = Transaction::whereMonth('created_at', now()->month)->sum('grand_total');
        
        $totalProducts = Product::count();
        $totalTransactions = Transaction::count();

        
        $chartData = collect(range(0, 6))->map(function($days) {
            $date = now()->startOfWeek()->addDays($days);
            return [
                'date' => $date->translatedFormat('D'), 
        'total' => Transaction::whereDate('created_at', $date)->sum('grand_total')
            ];
        });

        return view('livewire.dashboard', [
            'todaySales' => $todaySales,
            'weeklySales' => $weeklySales,
            'monthlySales' => $monthlySales,
            'totalProducts' => $totalProducts,
            'totalTransactions' => $totalTransactions,
            'chartData' => $chartData,
        ]);
    }
}
