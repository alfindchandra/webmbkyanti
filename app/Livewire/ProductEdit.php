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

class ProductEdit extends Component
{
    use WithFileUploads;

    public $product, $categories, $units;
    
    // Form fields
    public $productId, $name, $sku, $barcode, $category_id, $base_unit_id, $description;
    public $image;
    public $existingImage;
    public $productUnits = [];

    public function mount($id)
    {
        $this->product = Product::with('productUnits.prices')->findOrFail($id);
        $this->categories = Category::all();
        $this->units = Unit::all();

        $this->productId = $this->product->id;
        $this->name = $this->product->name;
        $this->sku = $this->product->sku;
        $this->barcode = $this->product->barcode;
        $this->category_id = $this->product->category_id;
        $this->base_unit_id = $this->product->base_unit_id;
        $this->description = $this->product->description;
        $this->existingImage = $this->product->image;

        $this->productUnits = $this->product->productUnits->map(function($pu) {
            return [
                'id' => $pu->id,
                'unit_id' => $pu->unit_id,
                'conversion' => $pu->conversion,
                'price' => $pu->price,
                'min_qty' => $pu->min_qty,
                'tierPrices' => $pu->prices->map(fn ($p) => [
                    'id' => $p->id,
                    'min_quantity' => $p->min_quantity,
                    'price' => $p->price,
                ])->toArray(),
            ];
        })->toArray();

        if (count($this->productUnits) === 0) {
            $this->addProductUnit();
        }
    }

    public function render()
    {
        return view('livewire.product-edit');
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
            'id' => null,
            'min_quantity' => 1,
            'price' => 0,
        ];
    }

    public function removeUnitTierPrice($unitIndex, $tierIndex)
    {
        unset($this->productUnits[$unitIndex]['tierPrices'][$tierIndex]);
        $this->productUnits[$unitIndex]['tierPrices'] = array_values($this->productUnits[$unitIndex]['tierPrices']);
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255|unique:products,sku,' . $this->productId,
            'barcode' => 'nullable|string|max:255|unique:products,barcode,' . $this->productId,
            'category_id' => 'required|exists:categories,id',
            'base_unit_id' => 'required|exists:units,id',
            'productUnits.*.unit_id' => 'required|exists:units,id',
            'productUnits.*.conversion' => 'required|numeric|min:0.01',
            'productUnits.*.price' => 'required|numeric|min:0',
            'productUnits.*.min_qty' => 'required|numeric|min:0.01',
            'productUnits.*.tierPrices.*.min_quantity' => 'nullable|numeric|min:1',
            'productUnits.*.tierPrices.*.price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = $this->existingImage;

        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
            
            if ($this->existingImage && Storage::disk('public')->exists($this->existingImage)) {
                Storage::disk('public')->delete($this->existingImage);
            }
        }

        $this->product->update([
            'name' => $this->name,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'category_id' => $this->category_id,
            'base_unit_id' => $this->base_unit_id,
            'description' => $this->description,
            'image' => $imagePath,
        ]);

        // Manage Product Units and Tier Prices
        $keptUnitIds = collect($this->productUnits)->pluck('id')->filter()->toArray();
        
        ProductUnit::where('product_id', $this->product->id)->whereNotIn('id', $keptUnitIds)->delete();

        foreach ($this->productUnits as $unit) {
            $productUnit = ProductUnit::updateOrCreate(
                ['id' => $unit['id'] ?? null, 'product_id' => $this->product->id],
                [
                    'product_id' => $this->product->id,
                    'unit_id' => $unit['unit_id'],
                    'conversion' => $unit['conversion'],
                    'price' => $unit['price'],
                    'min_qty' => $unit['min_qty'],
                ]
            );

            // Manage tier prices for this unit
            if (isset($unit['tierPrices']) && is_array($unit['tierPrices'])) {
                $keptPriceIds = collect($unit['tierPrices'])->pluck('id')->filter()->toArray();
                
                // Delete removed tier prices
                ProductUnitPrice::where('product_unit_id', $productUnit->id)
                    ->whereNotIn('id', $keptPriceIds)
                    ->delete();

                // Create or update tier prices
                foreach ($unit['tierPrices'] as $tierPrice) {
                    if (!empty($tierPrice['min_quantity']) && !empty($tierPrice['price'])) {
                        ProductUnitPrice::updateOrCreate(
                            ['id' => $tierPrice['id'] ?? null, 'product_unit_id' => $productUnit->id],
                            [
                                'product_unit_id' => $productUnit->id,
                                'min_quantity' => $tierPrice['min_quantity'],
                                'price' => $tierPrice['price'],
                            ]
                        );
                    }
                }
            }
        }

        session()->flash('message', 'Produk berhasil diperbarui!');
        return redirect()->route('products');
    }

    public function cancel()
    {
        return redirect()->route('products');
    }
}
