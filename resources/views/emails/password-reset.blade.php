@component('mail::message')
# {{ __('Permintaan Reset Password') }}

Halo {{ $displayName }},

Kami menerima permintaan untuk **reset password** akun Anda di sistem BMKG Geofisika Yogyakarta. Klik tombol di bawah untuk membuat password baru:

@component('mail::button', ['url' => $actionUrl, 'color' => 'success'])
{{ $actionText }}
@endcomponent

---

## ⚠️ **Informasi Penting:**

- Link ini akan **expired dalam 60 menit**
- Klik tombol di atas atau copy-paste URL jika tombol tidak bekerja:
  ```
  {{ $actionUrl }}
  ```
- **Jangan bagikan link ini ke siapa pun**
- Jika Anda **tidak meminta reset password**, abaikan email ini

---

Jika ada pertanyaan atau masalah, silakan hubungi support kami.

Terima kasih,  
**BMKG Geofisika Yogyakarta**

@component('mail::subcopy')
Jika tombol "Reset Password" tidak bekerja, copy-paste URL berikut di browser Anda:
[{{ $actionUrl }}]({{ $actionUrl }})
@endcomponent
@endcomponent

