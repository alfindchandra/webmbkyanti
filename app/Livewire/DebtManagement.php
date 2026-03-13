<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Debt;
use App\Models\Transaction;
use Illuminate\Support\Str;

class DebtManagement extends Component
{
    public $activeTab = 'debts';
    
    // Debt states
    public $debts;
    public $debtId, $customerName, $address, $amount, $debtDate, $notes, $transactionId;
    public $isDebtModalOpen = false;
    public $searchDebt = '';
    public $transactions = [];

    // Notes states
    public $notesList;
    public $isNoteModalOpen = false;
    public $noteTitle = '';
    public $noteContent = '';
    public $noteId = '';

    public function render()
    {
        $this->debts = Debt::when($this->searchDebt, fn($q) => 
            $q->where('customer_name', 'like', '%' . $this->searchDebt . '%')
              ->orWhere('address', 'like', '%' . $this->searchDebt . '%')
        )
        ->latest()
        ->get();

        $this->transactions = Transaction::orderBy('created_at', 'desc')->limit(100)->get();
        $this->loadNotes();

        return view('livewire.debt-management');
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    // --- Debt Methods ---

    public function createDebt()
    {
        $this->resetDebtFields();
        $this->debtDate = now()->toDateTimeString();
        $this->isDebtModalOpen = true;
    }

    public function closeDebtModal()
    {
        $this->isDebtModalOpen = false;
        $this->resetDebtFields();
    }

    private function resetDebtFields()
    {
        $this->debtId = '';
        $this->customerName = '';
        $this->address = '';
        $this->amount = '';
        $this->debtDate = now()->toDateTimeString();
        $this->notes = '';
        $this->transactionId = '';
    }

    public function storeDebt()
    {
        $this->validate([
            'customerName' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:0.01',
            'debtDate' => 'required|date',
        ]);

        Debt::updateOrCreate(['id' => $this->debtId], [
            'customer_name' => $this->customerName,
            'address' => $this->address,
            'amount' => $this->amount,
            'debt_date' => $this->debtDate,
            'notes' => $this->notes,
            'transaction_id' => $this->transactionId ?: null,
            'user_id' => auth()->id() ?? 1,
        ]);

        session()->flash('message', $this->debtId ? 'Hutang updated.' : 'Hutang created.');
        $this->closeDebtModal();
    }

    public function editDebt($id)
    {
        $debt = Debt::findOrFail($id);
        $this->debtId = $debt->id;
        $this->customerName = $debt->customer_name;
        $this->address = $debt->address;
        $this->amount = $debt->amount;
        $this->debtDate = $debt->debt_date->toDateTimeString();
        $this->notes = $debt->notes;
        $this->transactionId = $debt->transaction_id;
        
        $this->isDebtModalOpen = true;
    }

    public function deleteDebt($id)
    {
        Debt::findOrFail($id)->delete();
        session()->flash('message', 'Hutang deleted.');
    }

    // --- Notes Methods ---

    private function loadNotes()
    {
        $userId = auth()->id();
        $this->notesList = json_decode(cache()->get("debt_notes_$userId", '[]'), true) ?? [];
    }

    public function createNote()
    {
        $this->resetNoteFields();
        $this->isNoteModalOpen = true;
    }

    public function closeNoteModal()
    {
        $this->isNoteModalOpen = false;
        $this->resetNoteFields();
    }

    private function resetNoteFields()
    {
        $this->noteId = '';
        $this->noteTitle = '';
        $this->noteContent = '';
    }

    public function storeNote()
    {
        $this->validate([
            'noteTitle' => 'required|string|max:255',
            'noteContent' => 'required|string|max:1000',
        ]);

        $userId = auth()->id();
        $notes = json_decode(cache()->get("debt_notes_$userId", '[]'), true) ?? [];
        
        if ($this->noteId) {
            // Update existing note
            foreach ($notes as &$note) {
                if ($note['id'] === $this->noteId) {
                    $note['title'] = $this->noteTitle;
                    $note['content'] = $this->noteContent;
                    $note['updated_at'] = now()->toDateTimeString();
                    break;
                }
            }
        } else {
            // Create new note
            $notes[] = [
                'id' => Str::random(8),
                'title' => $this->noteTitle,
                'content' => $this->noteContent,
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ];
        }

        // Save to cache (persistent across session)
        cache()->put("debt_notes_$userId", json_encode($notes), now()->addDays(365));
        $this->loadNotes();

        session()->flash('message', $this->noteId ? 'Note updated.' : 'Note created.');
        $this->closeNoteModal();
    }

    public function editNote($id)
    {
        foreach ($this->notesList as $note) {
            if ($note['id'] === $id) {
                $this->noteId = $note['id'];
                $this->noteTitle = $note['title'];
                $this->noteContent = $note['content'];
                break;
            }
        }
        
        $this->isNoteModalOpen = true;
    }

    public function deleteNote($id)
    {
        $userId = auth()->id();
        $notes = json_decode(cache()->get("debt_notes_$userId", '[]'), true) ?? [];
        $notes = array_filter($notes, fn($note) => $note['id'] !== $id);
        cache()->put("debt_notes_$userId", json_encode($notes), now()->addDays(365));
        $this->loadNotes();
        
        session()->flash('message', 'Note deleted.');
    }
}
