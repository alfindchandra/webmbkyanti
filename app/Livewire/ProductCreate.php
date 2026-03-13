<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use App\Models\ProductUnit;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProductCreate extends Component
{
    use WithFileUploads;

    public $categories, $units;
    
    // Form fields
    public $name, $sku, $barcode, $category_id, $base_unit_id, $description;
    public $image;
    public $productUnits = [];

    public function mount()
    {
        $this->categories = Category::all();
        $this->units = Unit::all();
        $this->addProductUnit();
    }

    public function render()
    {
        return view('livewire.product-create');
    }

    public function addProductUnit()
    {
        $this->productUnits[] = [
            'id' => null,
            'unit_id' => '',
            'conversion' => 1,
            'price' => 0,
            'min_qty' => 1,
        ];
    }

    public function removeProductUnit($index)
    {
        unset($this->productUnits[$index]);
        $this->productUnits = array_values($this->productUnits);
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255|unique:products,sku',
            'barcode' => 'nullable|string|max:255|unique:products,barcode',
            'category_id' => 'required|exists:categories,id',
            'base_unit_id' => 'required|exists:units,id',
            'productUnits.*.unit_id' => 'required|exists:units,id',
            'productUnits.*.conversion' => 'required|numeric|min:0.01',
            'productUnits.*.price' => 'required|numeric|min:0',
            'productUnits.*.min_qty' => 'required|numeric|min:0.01',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;

        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }

        $product = Product::create([
            'name' => $this->name,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'category_id' => $this->category_id,
            'base_unit_id' => $this->base_unit_id,
            'description' => $this->description,
            'image' => $imagePath,
        ]);

        // Create product units
        foreach ($this->productUnits as $unit) {
            ProductUnit::create([
                'product_id' => $product->id,
                'unit_id' => $unit['unit_id'],
                'conversion' => $unit['conversion'],
                'price' => $unit['price'],
                'min_qty' => $unit['min_qty'],
            ]);
        }

        session()->flash('message', 'Produk berhasil dibuat!');
        return redirect()->route('products');
    }

    public function cancel()
    {
        return redirect()->route('products');
    }
}
