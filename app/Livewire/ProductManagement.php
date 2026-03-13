<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;


class ProductManagement extends Component
{
    public $categories, $units;
    public $search = '';

    public function render()
    {
        $query = Product::with(['category', 'baseUnit', 'productUnits.unit'])
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%')
                  ->orWhereHas('category', function ($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%');
                  });
            })
            ->latest();

        $products = $query->get();

        $this->categories = Category::all();
        $this->units = Unit::all();

        return view('livewire.product-management', ['products' => $products]);
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        session()->flash('message', 'Produk berhasil dihapus!');
    }
}
