<?php

namespace App\Livewire;

use App\Models\Borrowing;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ExtendWizard extends Component
{
    use WithFileUploads;

    public Borrowing $borrowing;
    public int $currentStep = 1;
    public int $extensionDays = 7;
    public string $paymentMethod = 'transfer';
    public $proofFile;

    public float $extensionFee = 0;
    public $newDueDate;
    public $currentDueDate;

    public function mount(Borrowing $borrowing)
    {
        $this->borrowing = $borrowing;
        $this->currentDueDate = $borrowing->due_date;
        $this->calculateFees();
    }

    public function calculateFees()
    {
        $book = $this->borrowing->bookCopy->book;
        $this->extensionFee = $book->rental_fee * ($this->extensionDays / 7);
        $this->newDueDate = $this->borrowing->due_date->copy()->addDays($this->extensionDays);
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->currentStep = 2;
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'paymentMethod' => 'required|in:cash,transfer',
            ]);

            if ($this->paymentMethod === 'cash') {
                $this->processExtension();
                $this->currentStep = 4;
            } else {
                $this->currentStep = 3;
            }
        } elseif ($this->currentStep === 3) {
            $this->validate([
                'proofFile' => 'required|image|max:2048',
            ]);

            $this->processExtension();
            $this->currentStep = 4;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    protected function processExtension()
    {
        $user = Auth::user();

        // Update borrowing
        $this->borrowing->update([
            'due_date' => $this->newDueDate,
            'extension_count' => $this->borrowing->extension_count + 1,
            'total_fee' => $this->borrowing->total_fee + $this->extensionFee,
        ]);

        // If transfer, upload proof and create payment record
        if ($this->paymentMethod === 'transfer' && $this->proofFile) {
            $proofPath = $this->proofFile->store('payment_proofs', 'public');

            Payment::create([
                'borrowing_id' => $this->borrowing->id,
                'user_id' => $user->id,
                'amount' => $this->extensionFee,
                'payment_type' => 'extension',
                'proof_file' => $proofPath,
                'status' => 'pending',
            ]);
        }

        session()->flash('success', 'Perpanjangan berhasil! Jatuh tempo baru: ' . $this->newDueDate->translatedFormat('d M Y'));
    }

    public function render()
    {
        return view('livewire.extend-wizard');
    }
}
