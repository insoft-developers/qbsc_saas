@component('mail::message')
# Halo, {{ $user->name }}

Terima kasih telah mendaftar di **QBSC**.  
Klik tombol di bawah untuk mengaktifkan akun Anda:

@component('mail::button', ['url' => route('activate', $token)])
Aktivasi Akun
@endcomponent

Jika tombol di atas tidak berfungsi, salin dan buka tautan berikut:
{{ route('activate', $token) }}

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent