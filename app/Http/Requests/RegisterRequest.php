<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       return [
            'company_name' => 'required|min:3|max:100',
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'whatsapp' => 'required|unique:users,whatsapp',
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Nama Perusahaan wajib diisi.',
            'company_name.min' => 'Nama Perusahaan tidak boleh kurang dari 3 karakter',
            'company_name.max' => 'Nama Perusahaan tidak boleh lebih dari 100 karakter',
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama tidak boleh kurang dari 3 karakter',
            'name.max' => 'Nama tidak boleh lebih dari 100 karakter',
            'name.string' => 'Nama tidak boleh berupa angka',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'whatsapp.required' => 'Nomor Whatsapp wajib diisi.',
            'whatsapp.unique' => 'Whatsapp sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}
