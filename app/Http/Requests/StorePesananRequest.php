<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePesananRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'customer_id'          => 'required|exists:customers,id',
            'tanggal_pesan'        => 'required|date',
            'jasa_kurir'           => 'required|string',
            'ongkir'               => 'required|numeric|min:0',
            'catatan'              => 'nullable|string',
            'produk_id'            => 'required|array|min:1',
            'produk_id.*'          => 'exists:produks,id',
            'jumlah'               => 'required|array|min:1',
            'jumlah.*'             => 'integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required'   => 'Customer wajib dipilih.',
            'jasa_kurir.required'    => 'Jasa kurir wajib dipilih.',
            'produk_id.required'     => 'Minimal satu produk harus dipilih.',
            'jumlah.*.min'           => 'Jumlah produk minimal 1.',
        ];
    }
}