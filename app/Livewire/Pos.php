<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\StockMovement;
use Illuminate\Support\Str;

class Pos extends Component
{
    public $search = '';
    public $cart = []; // ['cart_id', 'product_id', 'name', 'product_unit_id', 'unit_id', 'unit_code', 'quantity', 'price', 'base_price', 'conversion', 'subtotal', 'discount_amount', 'tiered_prices', 'applied_tier']
    public $discount = 0;
    public $tax = 0;
    public $payment = 0;
    public $paymentMethod = 'cash';
    public $additionalCosts = []; // [['id', 'description', 'amount', 'is_discount']]
    public $newCostDescription = '';
    public $newCostCustomDescription = '';
    public $newCostAmount = 0;

    // UI state
    public $showCheckout = false;
    public $showAdditionalCostModal = false;
    public $showTieredModal = false;
    public $showReceiptModal = false;
    public $showCustomItemModal = false;
    public $showEditPriceModal = false;
    public $selectedTieredCartId = null;
    public $editingCartId = null;
    public $editingPrice = 0;
    public $invoiceNumber = '';
    public $receiptData = null; // snapshot of the last transaction for printing

    // Custom item form
    public $customItemName = '';
    public $customItemPrice = 0;
    public $customItemQty = 1;
    public $customItemUnit = 'pcs';
    public bool $customItemSaveToDb = false;
    public $customItemCategoryId = '';
    public $customItemBaseUnitId = '';

    public function render()
    {
        $products = Product::with(['productUnits.unit', 'baseUnit'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%')
                    ->orWhere('barcode', 'like', '%' . $this->search . '%');
            })
            ->limit(24)->get();

        return view('livewire.pos', [
            'products'           => $products,
            'categories'         => Category::orderBy('name')->get(),
            'units'              => Unit::orderBy('name')->get(),
            'summary'            => $this->calculateSummary(),
            'selectedTieredItem' => $this->selectedTieredCartId
                ? collect($this->cart)->firstWhere('cart_id', $this->selectedTieredCartId)
                : null,
        ]);
    }

    public function addToCart($productId, $productUnitId = null)
    {
        $product = Product::with(['productUnits.unit', 'baseUnit'])->findOrFail($productId);

        $productUnit = null;
        if ($productUnitId) {
            $productUnit = ProductUnit::with('unit')->find($productUnitId);
        } else {
            // Default: the unit with the lowest min_qty (typically 1)
            $productUnit = $product->productUnits->sortBy('min_qty')->first();
        }

        if (!$productUnit) {
            session()->flash('error', 'Produk belum memiliki harga/unit yang diatur.');
            return;
        }

        // Find all sibling product units with the same unit_id to act as price tiers
        $sameSatuan = $product->productUnits->where('unit_id', $productUnit->unit_id)->sortBy('min_qty')->values();

        $existingIndex = collect($this->cart)->search(function ($item) use ($product, $productUnit) {
            return $item['product_id'] == $product->id && $item['unit_id'] == $productUnit->unit_id;
        });

        if ($existingIndex !== false) {
            $newQty = $this->cart[$existingIndex]['quantity'] + 1;
            $priceData = $this->resolvePrice($sameSatuan, (float) $productUnit->price, $newQty);
            $this->cart[$existingIndex]['quantity']       = $newQty;
            $this->cart[$existingIndex]['price']          = $priceData['price'];
            $this->cart[$existingIndex]['subtotal']       = $newQty * $priceData['price'];
            $this->cart[$existingIndex]['applied_tier']   = $priceData['applied_tier'];
            $this->cart[$existingIndex]['discount_amount'] = $priceData['discount_amount'];
        } else {
            $tieredPrices = $sameSatuan->filter(fn ($u) => (float) $u->min_qty > 1)->map(fn ($u) => [
                'min_quantity' => (float) $u->min_qty,
                'price'        => (float) $u->price,
            ])->values()->toArray();

            $priceData = $this->resolvePrice($sameSatuan, (float) $productUnit->price, 1);

            $this->cart[] = [
                'cart_id'          => Str::random(8),
                'product_id'       => $product->id,
                'name'             => $product->name,
                'product_unit_id'  => $productUnit->id,
                'unit_id'          => $productUnit->unit_id,
                'unit_code'        => $productUnit->unit->code ?? 'N/A',
                'quantity'         => 1,
                'base_price'       => (float) $productUnit->price,
                'price'            => $priceData['price'],
                'conversion'       => (float) $productUnit->conversion,
                'subtotal'         => $priceData['price'],
                'discount_amount'  => $priceData['discount_amount'],
                'applied_tier'     => $priceData['applied_tier'],
                'tiered_prices'    => $tieredPrices,
            ];
        }

        $this->search = '';
    }

    /**
     * Resolve the best price from sibling product_units (same unit_id)
     * sorted by min_qty. Each row with a higher min_qty acts as a price tier.
     */
    private function resolvePrice($sameSatuan, float $basePrice, float $qty): array
    {
        $appliedTier = null;
        $finalPrice  = $basePrice;

        // Sort descending by min_qty and find the first tier where qty >= min_qty
        $tiers = $sameSatuan->sortByDesc('min_qty');
        foreach ($tiers as $tier) {
            if ($qty >= (float) $tier->min_qty) {
                $finalPrice  = (float) $tier->price;
                if ((float) $tier->min_qty > 1) {
                    $appliedTier = [
                        'min_quantity' => (float) $tier->min_qty,
                        'price'        => (float) $tier->price,
                    ];
                }
                break;
            }
        }

        $discountAmount = ($basePrice - $finalPrice) * $qty;

        return [
            'price'          => $finalPrice,
            'applied_tier'   => $appliedTier,
            'discount_amount' => $discountAmount > 0 ? $discountAmount : 0,
        ];
    }

    public function updateQuantity($cartId, $qty)
    {
        $index = collect($this->cart)->search(fn ($item) => $item['cart_id'] === $cartId);
        if ($index !== false) {
            if ($qty <= 0) {
                $this->removeFromCart($cartId);
                return;
            }

            $item    = $this->cart[$index];
            $product = Product::with('productUnits.unit')->find($item['product_id']);
            $sameSatuan = $product
                ? $product->productUnits->where('unit_id', $item['unit_id'])->sortBy('min_qty')->values()
                : collect();

            $priceData = $this->resolvePrice($sameSatuan, $item['base_price'], $qty);

            $this->cart[$index]['quantity']       = $qty;
            $this->cart[$index]['price']          = $priceData['price'];
            $this->cart[$index]['subtotal']       = $qty * $priceData['price'];
            $this->cart[$index]['applied_tier']   = $priceData['applied_tier'];
            $this->cart[$index]['discount_amount'] = $priceData['discount_amount'];
        }
    }

    public function removeFromCart($cartId)
    {
        $this->cart = collect($this->cart)->reject(fn ($item) => $item['cart_id'] === $cartId)->values()->toArray();
    }

    public function clearCart()
    {
        $this->cart            = [];
        $this->discount        = 0;
        $this->tax             = 0;
        $this->payment         = 0;
        $this->additionalCosts = [];
    }

    public function closeReceiptModal()
    {
        $this->showReceiptModal = false;
        $this->receiptData      = null;
    }

    // ─── Inline price edit ────────────────────────────────────────────────────
    public function openEditPriceModal($cartId)
    {
        $item = collect($this->cart)->firstWhere('cart_id', $cartId);
        if ($item) {
            $this->editingCartId = $cartId;
            $this->editingPrice = $item['price'];
            $this->showEditPriceModal = true;
        }
    }

    public function saveEditedPrice()
    {
        if ($this->editingCartId) {
            $price = max(0, (float) $this->editingPrice);
            $index = collect($this->cart)->search(fn ($item) => $item['cart_id'] === $this->editingCartId);
            if ($index !== false) {
                $this->cart[$index]['price']    = $price;
                $this->cart[$index]['subtotal'] = $this->cart[$index]['quantity'] * $price;
                $this->showEditPriceModal = false;
                $this->editingCartId = null;
                $this->editingPrice = 0;
            }
        }
    }

    public function closeEditPriceModal()
    {
        $this->showEditPriceModal = false;
        $this->editingCartId = null;
        $this->editingPrice = 0;
    }

    public function updatePrice($cartId, $newPrice)
    {
        $price = max(0, (float) $newPrice);
        $index = collect($this->cart)->search(fn ($item) => $item['cart_id'] === $cartId);
        if ($index !== false) {
            $this->cart[$index]['price']    = $price;
            $this->cart[$index]['subtotal'] = $this->cart[$index]['quantity'] * $price;
        }
    }

    // ─── Quick add custom product ─────────────────────────────────────────────
    public function addCustomItem()
    {
        $rules = [
            'customItemName'  => 'required|string|min:1',
            'customItemPrice' => 'required|numeric|min:0',
            'customItemQty'   => 'required|numeric|min:1',
        ];

        if ($this->customItemSaveToDb) {
            $rules['customItemCategoryId']  = 'required|exists:categories,id';
            $rules['customItemBaseUnitId']  = 'required|exists:units,id';
        }

        $this->validate($rules, [
            'customItemName.required'         => 'Nama produk wajib diisi.',
            'customItemCategoryId.required'   => 'Pilih kategori.',
            'customItemBaseUnitId.required'   => 'Pilih satuan dasar.',
        ]);

        $price = (float) $this->customItemPrice;
        $qty   = (float) $this->customItemQty;
        $unitCode = $this->customItemUnit ?: 'pcs';
        $productUnitId = null;
        $productId     = null;

        // Optionally persist to database
        if ($this->customItemSaveToDb) {
            $unit = Unit::find($this->customItemBaseUnitId);
            $unitCode = $unit ? $unit->code ?? $unit->name : $unitCode;

            $product = Product::create([
                'name'          => $this->customItemName,
                'category_id'   => $this->customItemCategoryId,
                'base_unit_id'  => $this->customItemBaseUnitId,
                'cost_price'    => 0,
                'current_stock' => 0,
                'minimum_stock' => 0,
            ]);

            $productUnit = ProductUnit::create([
                'product_id'  => $product->id,
                'unit_id'     => $this->customItemBaseUnitId,
                'conversion'  => 1,
                'price'       => $price,
                'min_qty'     => 1,
            ]);

            $productUnitId = $productUnit->id;
            $productId     = $product->id;
        }

        $this->cart[] = [
            'cart_id'         => Str::random(8),
            'product_id'      => $productId,
            'name'            => $this->customItemName,
            'product_unit_id' => $productUnitId,
            'unit_id'         => $this->customItemBaseUnitId ?: null,
            'unit_code'       => $unitCode,
            'quantity'        => $qty,
            'base_price'      => $price,
            'price'           => $price,
            'conversion'      => 1,
            'subtotal'        => $qty * $price,
            'discount_amount' => 0,
            'applied_tier'    => null,
            'tiered_prices'   => [],
        ];

        // Reset form
        $this->customItemName       = '';
        $this->customItemPrice      = 0;
        $this->customItemQty        = 1;
        $this->customItemUnit       = 'pcs';
        $this->customItemCategoryId = '';
        $this->customItemBaseUnitId = '';
        $this->customItemSaveToDb   = false;
        $this->showCustomItemModal  = false;
    }

    // ─── Additional costs ────────────────────────────────────────────────────
    public function addAdditionalCost()
    {
        $description = $this->newCostDescription === 'Lainnya'
            ? $this->newCostCustomDescription
            : $this->newCostDescription;

        if (!$description || (float) $this->newCostAmount <= 0) {
            session()->flash('error', 'Mohon isi deskripsi dan jumlah dengan benar.');
            return;
        }

        $this->additionalCosts[] = [
            'id'          => Str::random(6),
            'description' => $description,
            'amount'      => (float) $this->newCostAmount,
            'is_discount' => $this->newCostDescription === 'Diskon',
        ];

        $this->newCostDescription       = '';
        $this->newCostCustomDescription = '';
        $this->newCostAmount            = 0;
        $this->showAdditionalCostModal  = false;
    }

    public function removeAdditionalCost($costId)
    {
        $this->additionalCosts = collect($this->additionalCosts)
            ->reject(fn ($c) => $c['id'] === $costId)
            ->values()
            ->toArray();
    }

    // ─── Checkout ─────────────────────────────────────────────────────────────
    public function openCheckout()
    {
        if (count($this->cart) === 0) return;
        $this->showCheckout = true;
        $summary = $this->calculateSummary();
        $this->payment = $summary['grand_total'];
    }

    public function closeCheckout()
    {
        $this->showCheckout = false;
    }

    public function calculateSummary()
    {
        $subtotal = collect($this->cart)->sum('subtotal');

        $additionalTotal = collect($this->additionalCosts)->reduce(function ($carry, $cost) {
            return $cost['is_discount'] ? $carry - $cost['amount'] : $carry + $cost['amount'];
        }, 0);

        $grandTotal = max(0, $subtotal - (float) $this->discount + (float) $this->tax + $additionalTotal);

        return [
            'subtotal'    => $subtotal,
            'discount'    => (float) $this->discount,
            'tax'         => (float) $this->tax,
            'additional'  => $additionalTotal,
            'grand_total' => $grandTotal,
            'change'      => max(0, (float) $this->payment - $grandTotal),
            'savings'     => collect($this->cart)->sum('discount_amount'),
        ];
    }

    public function processCheckout()
    {
        if (count($this->cart) === 0) return;

        $summary = $this->calculateSummary();

        if ($this->payment < $summary['grand_total'] && $this->paymentMethod === 'cash') {
            session()->flash('checkout_error', 'Pembayaran kurang dari total transaksi.');
            return;
        }

        $invNumber = 'INV-' . date('YmdHis') . '-' . strtoupper(Str::random(4));

        \DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'user_id'        => auth()->id() ?? 1,
                'invoice_number' => $invNumber,
                'total_amount'   => $summary['subtotal'],
                'discount'       => (float) $this->discount,
                'tax'            => (float) $this->tax + $summary['additional'],
                'grand_total'    => $summary['grand_total'],
                'payment_method' => $this->paymentMethod,
                'payment_status' => 'paid',
            ]);

            foreach ($this->cart as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['product_id'],
                    'unit_id'        => $item['unit_id'],
                    'quantity'       => $item['quantity'],
                    'conversion'     => $item['conversion'],
                    'unit_price'     => $item['price'],
                    'subtotal'       => $item['subtotal'],
                ]);

                $product = Product::find($item['product_id']);
                if ($product) {
                    $deductQty = $item['quantity'] * $item['conversion'];
                    $product->current_stock = max(0, $product->current_stock - $deductQty);
                    $product->save();

                    StockMovement::create([
                        'product_id'   => $product->id,
                        'user_id'      => auth()->id() ?? 1,
                        'type'         => 'out',
                        'quantity'     => $deductQty,
                        'reason'       => 'Penjualan',
                        'reference_id' => $invNumber,
                    ]);
                }
            }

            \DB::commit();

            // Save receipt snapshot BEFORE clearing cart
            $this->receiptData = [
                'invoice_number' => $invNumber,
                'date'           => now()->format('d M Y, H:i'),
                'cashier'        => auth()->user()?->name ?? 'Admin',
                'items'          => $this->cart,
                'subtotal'       => $summary['subtotal'],
                'discount'       => $summary['discount'],
                'tax'            => $summary['tax'],
                'additional'     => $summary['additional'],
                'grand_total'    => $summary['grand_total'],
                'payment'        => (float) $this->payment,
                'payment_method' => $this->paymentMethod,
                'change'         => $summary['change'],
                'savings'        => $summary['savings'],
                'additional_costs' => $this->additionalCosts,
            ];

            $this->invoiceNumber = $invNumber;
            $this->clearCart();
            $this->showCheckout    = false;
            $this->showReceiptModal = true;
            session()->flash('message', 'Transaksi Berhasil: ' . $invNumber);
        } catch (\Exception $e) {
            \DB::rollBack();
            session()->flash('checkout_error', 'Transaksi gagal: ' . $e->getMessage());
        }
    }

    public function showTieredInfo($cartId)
    {
        $this->selectedTieredCartId = $cartId;
        $this->showTieredModal      = true;
    }

    public function getSelectedTieredItemProperty()
    {
        if (!$this->selectedTieredCartId) return null;
        return collect($this->cart)->firstWhere('cart_id', $this->selectedTieredCartId);
    }
}
