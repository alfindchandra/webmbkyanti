<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use App\Models\ProductUnit;
use App\Models\ProductUnitPrice;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

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
            'tierPrices' => [],
        ];
    }

    public function removeProductUnit($index)
    {
        unset($this->productUnits[$index]);
        $this->productUnits = array_values($this->productUnits);
    }

    public function addUnitTierPrice($unitIndex)
    {
        if (!isset($this->productUnits[$unitIndex]['tierPrices'])) {
            $this->productUnits[$unitIndex]['tierPrices'] = [];
        }

        $this->productUnits[$unitIndex]['tierPrices'][] = [
            'min_quantity' => 1,
            'price' => 0,
        ];
    }

    public function removeUnitTierPrice($unitIndex, $tierIndex)
    {
        unset($this->productUnits[$unitIndex]['tierPrices'][$tierIndex]);
        $this->productUnits[$unitIndex]['tierPrices'] = array_values($this->productUnits[$unitIndex]['tierPrices']);
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
        'productUnits.*.tierPrices.*.min_quantity' => 'nullable|numeric|min:1',
        'productUnits.*.tierPrices.*.price' => 'nullable|numeric|min:0',
        'image' => 'nullable|image|max:5120', 
    ]);

    $imagePath = null;

    if ($this->image) {
        try {
            
            $filename = 'products/' . uniqid() . '.jpg';
            
            
            $img = Image::read($this->image->getRealPath());

            
            $img->scale(width: 1000);

            
            $encoded = $img->toJpeg(80);

            
            Storage::disk('public')->put($filename, (string) $encoded);
            
            $imagePath = $filename;
        } catch (\Exception $e) {
           
            $imagePath = $this->image->store('products', 'public');
        }
    }

    // 2. Simpan Data Produk
    $product = Product::create([
        'name' => $this->name,
        'sku' => $this->sku,
        'barcode' => $this->barcode,
        'category_id' => $this->category_id,
        'base_unit_id' => $this->base_unit_id,
        'description' => $this->description,
        'image' => $imagePath,
    ]);

    // 3. Simpan Product Units & Tier Prices
    foreach ($this->productUnits as $unit) {
        $productUnit = ProductUnit::create([
            'product_id' => $product->id,
            'unit_id' => $unit['unit_id'],
            'conversion' => $unit['conversion'],
            'price' => $unit['price'],
            'min_qty' => $unit['min_qty'],
        ]);

        if (isset($unit['tierPrices']) && is_array($unit['tierPrices'])) {
            foreach ($unit['tierPrices'] as $tierPrice) {
                if (!empty($tierPrice['min_quantity']) && !empty($tierPrice['price'])) {
                    ProductUnitPrice::create([
                        'product_unit_id' => $productUnit->id,
                        'min_quantity' => $tierPrice['min_quantity'],
                        'price' => $tierPrice['price'],
                    ]);
                }
            }
        }
    }

    session()->flash('message', 'Produk berhasil dibuat!');
    return redirect()->route('products');
}

    public function cancel()
    {
        return redirect()->route('products');
    }
}