<?php

namespace App\Http\Requests;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BorrowBookRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return Auth::check();
  }

  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    return [
      'book_id' => ['required', 'exists:books,id'],
      'duration' => ['required', 'in:7,14'],
    ];
  }

  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    return [
      'book_id.required' => 'Buku harus dipilih.',
      'book_id.exists' => 'Buku tidak ditemukan.',
      'duration.required' => 'Durasi pinjam harus dipilih.',
      'duration.in' => 'Durasi pinjam harus 7 atau 14 hari.',
    ];
  }

  /**
   * Configure the validator instance.
   */
  public function withValidator($validator): void
  {
    $validator->after(function ($validator) {
      $book = Book::find($this->book_id);

      if ($book && $book->available_copies <= 0) {
        $validator->errors()->add('book_id', 'Buku ini sedang tidak tersedia.');
      }
    });
  }
}
