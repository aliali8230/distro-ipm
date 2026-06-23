<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadBuktiRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'jenis_pembayaran'  => 'required|in:dp,pelunasan',
            'tanggal_pembayaran'=> 'required|date',
            'nominal'           => 'required|numeric|min:1',
            'bukti_transfer'    => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'bukti_transfer.mimes'   => 'Format file harus JPG, PNG, atau PDF.',
            'bukti_transfer.max'     => 'Ukuran file maksimal 5MB.',
            'nominal.required'       => 'Nominal pembayaran wajib diisi.',
        ];
    }
}