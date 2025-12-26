<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Borrowing;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class BorrowWizard extends Component
{
    use WithFileUploads;

    public Book $book;
    public int $currentStep = 1;
    public int $duration = 7; // days
    public string $paymentMethod = 'transfer'; // 'cash' or 'transfer'
    public $proofFile;

    // Computed prices
    public float $rentalPrice = 0;
    public float $serviceFee = 1000;
    public float $totalPrice = 0;

    protected $listeners = ['refreshWizard' => '$refresh'];

    public function mount(Book $book)
    {
        $this->book = $book;
        $this->calculatePrices();
    }

    public function calculatePrices()
    {
        $this->rentalPrice = $this->book->rental_fee * ($this->duration / 7);
        $this->totalPrice = $this->rentalPrice + $this->serviceFee;
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            // Validation for step 1 (confirmation)
            $this->currentStep = 2;
        } elseif ($this->currentStep === 2) {
            // Validation for step 2 (payment method)
            $this->validate([
                'paymentMethod' => 'required|in:cash,transfer',
            ]);

            if ($this->paymentMethod === 'cash') {
                // Skip upload step for cash, go to step 4 (completion)
                $this->createBorrowing();
                $this->currentStep = 4;
            } else {
                $this->currentStep = 3;
            }
        } elseif ($this->currentStep === 3) {
            // Validation for step 3 (upload proof)
            $this->validate([
                'proofFile' => 'required|image|max:2048',
            ]);

            $this->createBorrowing();
            $this->currentStep = 4;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    protected function createBorrowing()
    {
        $user = Auth::user();

        // Find available copy
        $availableCopy = $this->book->copies()
            ->where('status', 'available')
            ->first();

        if (!$availableCopy) {
            session()->flash('error', 'Maaf, tidak ada eksemplar yang tersedia saat ini.');
            return;
        }

        // Generate borrowing code
        $borrowingCode = 'BOR-' . date('Y') . '-' . strtoupper(Str::random(4));

        // Calculate dates
        $borrowedAt = now();
        $dueDate = now()->addDays($this->duration);

        // Create borrowing
        $borrowing = Borrowing::create([
            'borrowing_code' => $borrowingCode,
            'user_id' => $user->id,
            'book_copy_id' => $availableCopy->id,
            'borrowed_at' => $borrowedAt,
            'due_date' => $dueDate,
            'rental_price' => $this->rentalPrice,
            'total_fee' => $this->totalPrice,
            'status' => $this->paymentMethod === 'cash' ? 'pending' : 'pending',
            'is_paid' => false,
        ]);

        // Update copy status
        $availableCopy->update(['status' => 'borrowed']);

        // If transfer, upload proof and create payment record
        if ($this->paymentMethod === 'transfer' && $this->proofFile) {
            $proofPath = $this->proofFile->store('payment_proofs', 'public');

            Payment::create([
                'borrowing_id' => $borrowing->id,
                'user_id' => $user->id,
                'amount' => $this->totalPrice,
                'payment_type' => 'rental',
                'proof_file' => $proofPath,
                'status' => 'pending',
            ]);
        }

        session()->flash('success', 'Peminjaman berhasil dibuat! Kode: ' . $borrowingCode);
    }

    public function render()
    {
        return view('livewire.borrow-wizard');
    }
}
