<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Supplier;
use Illuminate\Support\Str;

class Settings extends Component
{
    public $activeTab = 'categories';
    
    // Category states
    public $categories, $categoryId, $categoryName, $categorySlug;
    public $isCategoryModalOpen = false;
    public $searchCategory = '';

    // Unit states
    public $units, $unitId, $unitName, $unitCode, $unitIsBase = false;
    public $isUnitModalOpen = false;
    public $searchUnit = '';

    // Supplier states
    public $suppliers, $supplierId, $supplierName, $supplierEmail, $supplierPhone, $supplierAddress, $supplierCity, $supplierProvince, $supplierPostalCode, $supplierNotes;
    public $isSupplierModalOpen = false;
    public $searchSupplier = '';

    public function render()
    {
        $this->categories = Category::withCount('products')
            ->when($this->searchCategory, fn($q) => $q->where('name', 'like', '%' . $this->searchCategory . '%'))
            ->get();

        $this->units = Unit::when($this->searchUnit, fn($q) => $q->where('name', 'like', '%' . $this->searchUnit . '%')
            ->orWhere('code', 'like', '%' . $this->searchUnit . '%'))
            ->get();

        $this->suppliers = Supplier::when($this->searchSupplier, fn($q) => $q->where('name', 'like', '%' . $this->searchSupplier . '%')
            ->orWhere('email', 'like', '%' . $this->searchSupplier . '%')
            ->orWhere('phone', 'like', '%' . $this->searchSupplier . '%'))
            ->get();

        return view('livewire.settings');
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    // --- Category Methods ---

    public function createCategory()
    {
        $this->resetCategoryFields();
        $this->isCategoryModalOpen = true;
    }

    public function closeCategoryModal()
    {
        $this->isCategoryModalOpen = false;
        $this->resetCategoryFields();
    }

    private function resetCategoryFields()
    {
        $this->categoryId = '';
        $this->categoryName = '';
        $this->categorySlug = '';
    }

    public function updatedCategoryName()
    {
        $this->categorySlug = Str::slug($this->categoryName);
    }

    public function storeCategory()
    {
        $this->validate([
            'categoryName' => 'required',
            'categorySlug' => 'required|unique:categories,slug,' . $this->categoryId,
        ]);

        Category::updateOrCreate(['id' => $this->categoryId], [
            'name' => $this->categoryName,
            'slug' => $this->categorySlug,
        ]);

        session()->flash('message', $this->categoryId ? 'Category updated.' : 'Category created.');
        $this->closeCategoryModal();
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $category->id;
        $this->categoryName = $category->name;
        $this->categorySlug = $category->slug;
        
        $this->isCategoryModalOpen = true;
    }

    public function deleteCategory($id)
    {
        Category::findOrFail($id)->delete();
        session()->flash('message', 'Category deleted.');
    }

    // --- Unit Methods ---

    public function createUnit()
    {
        $this->resetUnitFields();
        $this->isUnitModalOpen = true;
    }

    public function closeUnitModal()
    {
        $this->isUnitModalOpen = false;
        $this->resetUnitFields();
    }

    private function resetUnitFields()
    {
        $this->unitId = '';
        $this->unitName = '';
        $this->unitCode = '';
        $this->unitIsBase = false;
    }

    public function storeUnit()
    {
        $this->validate([
            'unitName' => 'required',
            'unitCode' => 'required|unique:units,code,' . $this->unitId,
        ]);

        Unit::updateOrCreate(['id' => $this->unitId], [
            'name' => $this->unitName,
            'code' => $this->unitCode,
            'is_base' => $this->unitIsBase,
        ]);

        session()->flash('message', $this->unitId ? 'Unit updated.' : 'Unit created.');
        $this->closeUnitModal();
    }

    public function editUnit($id)
    {
        $unit = Unit::findOrFail($id);
        $this->unitId = $unit->id;
        $this->unitName = $unit->name;
        $this->unitCode = $unit->code;
        $this->unitIsBase = $unit->is_base;
        
        $this->isUnitModalOpen = true;
    }

    public function deleteUnit($id)
    {
        Unit::findOrFail($id)->delete();
        session()->flash('message', 'Unit deleted.');
    }

    // --- Supplier Methods ---

    public function createSupplier()
    {
        $this->resetSupplierFields();
        $this->isSupplierModalOpen = true;
    }

    public function closeSupplierModal()
    {
        $this->isSupplierModalOpen = false;
        $this->resetSupplierFields();
    }

    private function resetSupplierFields()
    {
        $this->supplierId = '';
        $this->supplierName = '';
        $this->supplierEmail = '';
        $this->supplierPhone = '';
        $this->supplierAddress = '';
        $this->supplierCity = '';
        $this->supplierProvince = '';
        $this->supplierPostalCode = '';
        $this->supplierNotes = '';
    }

    public function storeSupplier()
    {
        $this->validate([
            'supplierName' => 'required|string|max:255',
            'supplierEmail' => 'nullable|email|unique:suppliers,email,' . $this->supplierId,
            'supplierPhone' => 'nullable|string|max:20',
            'supplierAddress' => 'nullable|string|max:500',
            'supplierCity' => 'nullable|string|max:100',
            'supplierProvince' => 'nullable|string|max:100',
            'supplierPostalCode' => 'nullable|string|max:10',
        ]);

        Supplier::updateOrCreate(['id' => $this->supplierId], [
            'name' => $this->supplierName,
            'email' => $this->supplierEmail,
            'phone' => $this->supplierPhone,
            'address' => $this->supplierAddress,
            'city' => $this->supplierCity,
            'province' => $this->supplierProvince,
            'postal_code' => $this->supplierPostalCode,
            'notes' => $this->supplierNotes,
        ]);

        session()->flash('message', $this->supplierId ? 'Supplier updated.' : 'Supplier created.');
        $this->closeSupplierModal();
    }

    public function editSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        $this->supplierId = $supplier->id;
        $this->supplierName = $supplier->name;
        $this->supplierEmail = $supplier->email;
        $this->supplierPhone = $supplier->phone;
        $this->supplierAddress = $supplier->address;
        $this->supplierCity = $supplier->city;
        $this->supplierProvince = $supplier->province;
        $this->supplierPostalCode = $supplier->postal_code;
        $this->supplierNotes = $supplier->notes;
        
        $this->isSupplierModalOpen = true;
    }

    public function deleteSupplier($id)
    {
        Supplier::findOrFail($id)->delete();
        session()->flash('message', 'Supplier deleted.');
    }
}
