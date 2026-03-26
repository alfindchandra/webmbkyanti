<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

class StockManagement extends Component
{
    use WithFileUploads;
    public $search = '';
    public $movements;
    public $purchases;
    public $products;
    public $suppliers;

    // Purchase Form
    public $isModalOpen = false;
    public $supplier_id = '';
    public $purchase_date = '';
    public $receipt_image = '';
    public $purchaseItems = [];
    public $newItemProductId = '';
    public $newItemProductUnitId = '';
    public $newItemQuantity = '';
    public $newItemUnitPrice = '';
    public $availableUnits = [];
    public $searchProductTerm = '';
    public $filteredProducts = [];

    public function render()
    {
        $this->purchases = Purchase::with(['supplier', 'items.product', 'user'])
            ->when($this->search, function($query) {
                $query->whereHas('supplier', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhereHas('items.product', fn($q) => $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('sku', 'like', '%' . $this->search . '%'));
            })
            ->latest()
            ->limit(50)
            ->get();
            
        $this->suppliers = Supplier::orderBy('name')->get();
        $this->products = Product::with(['baseUnit', 'productUnits.unit'])
            ->orderBy('name')
            ->get();

        return view('livewire.stock-management', [
            'filteredProducts' => $this->filteredProducts,
        ]);
    }

    public function create()
    {
        $this->resetFields();
        $this->purchase_date = now()->toDateString();
        $this->isModalOpen = true;
    }

    public function close()
    {
        $this->isModalOpen = false;
        $this->resetFields();
    }

    private function resetFields()
    {
        $this->supplier_id = '';
        $this->purchase_date = now()->toDateString();
        $this->receipt_image = '';
        $this->purchaseItems = [];
        $this->newItemProductId = '';
        $this->newItemProductUnitId = '';
        $this->newItemQuantity = '';
        $this->newItemUnitPrice = '';
        $this->availableUnits = [];
        $this->searchProductTerm = '';
        $this->filteredProducts = [];
    }


    public function updatedSearchProductTerm($value)
    {
        if (strlen($value) >= 1) {
            $this->filteredProducts = Product::where('name', 'like', '%' . $value . '%')
                ->orWhere('sku', 'like', '%' . $value . '%')
                ->with(['baseUnit', 'productUnits.unit'])
                ->limit(10)
                ->get()
                ->toArray();
        } else {
            $this->filteredProducts = [];
        }
    }
    public function selectProduct($productId)
    {
        $product = Product::with('productUnits.unit')->findOrFail($productId);
        $this->newItemProductId = $product->id;
        $this->filteredProducts = [];
        $this->searchProductTerm = $product->name;
        
        // Load available units
        $this->availableUnits = $product->productUnits
            ->map(fn($pu) => [
                'id' => $pu->id,
                'unit_code' => $pu->unit->code ?? 'N/A',
                'conversion' => $pu->conversion,
            ])
            ->toArray();
        
        // Set default unit (first one)
        if (count($this->availableUnits) > 0) {
            $this->newItemProductUnitId = $this->availableUnits[0]['id'];
        }
    }

    public function addPurchaseItem()
    {
        if (!$this->newItemProductId || !$this->newItemProductUnitId || !$this->newItemQuantity) {
            session()->flash('error', 'Lengkapi data produk, satuan, dan jumlah.');
            return;
        }

        $product = Product::findOrFail($this->newItemProductId);
        $productUnit = ProductUnit::findOrFail($this->newItemProductUnitId);

        $this->purchaseItems[] = [
            'id' => Str::random(8),
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_unit_id' => $productUnit->id,
            'unit_code' => $productUnit->unit->code ?? 'N/A',
            'quantity' => (float) $this->newItemQuantity,
            'unit_price' => (float) $this->newItemUnitPrice,
            'subtotal' => (float) $this->newItemQuantity * (float) $this->newItemUnitPrice,
        ];

        // Reset input
        $this->newItemProductId = '';
        $this->newItemProductUnitId = '';
        $this->newItemQuantity = '';
        $this->newItemUnitPrice = '';
        $this->searchProductTerm = '';
        $this->availableUnits = [];
    }

    public function removePurchaseItem($itemId)
    {
        $this->purchaseItems = array_filter($this->purchaseItems, fn($item) => $item['id'] !== $itemId);
        $this->purchaseItems = array_values($this->purchaseItems);
    }

    public function store()
{
    $this->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'purchase_date' => 'required|date',
        'receipt_image' => 'nullable|image|max:20000', 
    ]);

    if (count($this->purchaseItems) === 0) {
        session()->flash('error', 'Tambahkan minimal satu item pembelian.');
        return;
    }

    DB::beginTransaction();
    try {
        $supplier = Supplier::findOrFail($this->supplier_id); // Ambil sekali di luar loop

        $receiptImagePath = null;
        if ($this->receipt_image) {
            $receiptImagePath = $this->receipt_image->store('receipts', 'public');
        }

        $purchase = Purchase::create([
            'supplier_id' => $this->supplier_id,
            'purchase_date' => $this->purchase_date,
            'receipt_image' => $receiptImagePath,
            'user_id' => auth()->id() ?? 1,
        ]);

        $totalQty = 0;
        foreach ($this->purchaseItems as $item) {
            $productUnit = ProductUnit::findOrFail($item['product_unit_id']);
            $realQty = (float)$item['quantity'] * (float)$productUnit->conversion;

            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $item['product_id'],
                'product_unit_id' => $item['product_unit_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
            ]);

            // Update stok produk
            $product = Product::findOrFail($item['product_id']);
            $product->increment('current_stock', $realQty); // Lebih aman menggunakan increment()

            // Catat mutasi stok
            StockMovement::create([
                'product_id' => $item['product_id'],
                'user_id' => auth()->id() ?? 1,
                'type' => 'in',
                'quantity' => $realQty,
                'reason' => 'Pembelian dari ' . $supplier->name,
                'reference_id' => 'PO-' . $purchase->id,
            ]);

            $totalQty += $realQty;
        }

        DB::commit();

        session()->flash('message', "Pembelian berhasil dicatat! Total stok masuk: " . number_format($totalQty, 0, ',', '.'));
        $this->close();
        
    } catch (\Exception $e) {
        DB::rollBack();
        session()->flash('error', 'Gagal mencatat pembelian: ' . $e->getMessage());
    }
}
}

