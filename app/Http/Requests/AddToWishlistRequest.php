<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToWishlistRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    return [
      'book_id' => 'required|exists:books,id',
    ];
  }

  /**
   * Get custom error messages.
   */
  public function messages(): array
  {
    return [
      'book_id.required' => 'ID buku wajib diisi.',
      'book_id.exists' => 'Buku tidak ditemukan.',
    ];
  }
}
