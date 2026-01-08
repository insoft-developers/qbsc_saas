@component('mail::message')
# Halo, {{ $user->name }}

Terima kasih telah mendaftar sebagai Reseller di **QBSC**.  
Klik tombol di bawah untuk mengaktifkan akun Reseller Anda:

@component('mail::button', ['url' => route('reseller.activate', $token)])
Aktivasi Akun
@endcomponent

Jika tombol di atas tidak berfungsi, salin dan buka tautan berikut:
{{ route('reseller.activate', $token) }}

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent