<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nama_customer' => 'required|string|max:255',
            'no_whatsapp'   => 'required|string|max:20',
            'alamat'        => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_customer.required' => 'Nama customer wajib diisi.',
            'no_whatsapp.required'   => 'Nomor WhatsApp wajib diisi.',
            'alamat.required'        => 'Alamat wajib diisi.',
        ];
    }
}