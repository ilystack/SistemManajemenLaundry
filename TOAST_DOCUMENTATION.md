# Toast Notification - Dokumentasi Penggunaan

Toast notification component sudah diimplementasikan menggunakan **Alpine.js v3** dan **Tailwind CSS** dari [Penguin UI](https://www.penguinui.com/components/toast-notification).

**Versi**: Modern (dengan CSS Theme Variables)  
**Features**: 
- ✅ Auto-dismiss (5 detik)
- ✅ Stackable notifications
- ✅ Smooth animations
- ✅ Sound effect (ding sound)
- ✅ No icons (clean design)
- ✅ CSS theme variables untuk konsistensi warna

## 📁 File Component
- **Component**: `resources/views/components/toast.blade.php`
- **Sudah di-include di**: `resources/views/auth/login.blade.php`

---

## 🎯 Cara Pakai

### 1️⃣ **Include Component di Layout/View**

Tambahkan di file blade yang mau pakai toast:

```blade
<!DOCTYPE html>
<html>
<head>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js v3 (WAJIB!) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Alpine Cloak Style -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body>
    <!-- Include Toast Component -->
    @include('components.toast')
    
    <!-- Your content here -->
</body>
</html>
```

---

### 2️⃣ **Trigger Toast dari JavaScript**

Gunakan `window.dispatchEvent` dengan custom event `notify`:

```javascript
// Success Toast
window.dispatchEvent(new CustomEvent('notify', {
    detail: {
        variant: 'success',
        title: 'Berhasil!',
        message: 'Data berhasil disimpan.'
    }
}));

// Danger/Error Toast
window.dispatchEvent(new CustomEvent('notify', {
    detail: {
        variant: 'danger',
        title: 'Oops!',
        message: 'Terjadi kesalahan. Silakan coba lagi.'
    }
}));

// Warning Toast
window.dispatchEvent(new CustomEvent('notify', {
    detail: {
        variant: 'warning',
        title: 'Peringatan',
        message: 'Pastikan semua data sudah benar.'
    }
}));

// Info Toast
window.dispatchEvent(new CustomEvent('notify', {
    detail: {
        variant: 'info',
        title: 'Informasi',
        message: 'Ada update terbaru tersedia.'
    }
}));
```

---

### 3️⃣ **Trigger Toast dari Laravel Controller**

Gunakan **session flash** dengan key `toast`:

```php
// Success
return redirect()->back()->with('toast', [
    'variant' => 'success',
    'title' => 'Berhasil!',
    'message' => 'Data berhasil disimpan.'
]);

// Danger/Error
return redirect()->back()->with('toast', [
    'variant' => 'danger',
    'title' => 'Gagal!',
    'message' => 'Terjadi kesalahan saat menyimpan data.'
]);

// Warning
return redirect()->back()->with('toast', [
    'variant' => 'warning',
    'title' => 'Peringatan',
    'message' => 'Harap periksa kembali data Anda.'
]);

// Info
return redirect()->back()->with('toast', [
    'variant' => 'info',
    'title' => 'Info',
    'message' => 'Sistem akan maintenance malam ini.'
]);
```

**ATAU pakai Helper Functions (Lebih Simple!):**

```php
// Success
toast_success('Berhasil!', 'Data berhasil disimpan.');
return redirect()->back();

// Error
toast_error('Gagal!', 'Terjadi kesalahan saat menyimpan data.');
return redirect()->back();

// Warning
toast_warning('Peringatan', 'Harap periksa kembali data Anda.');
return redirect()->back();

// Info
toast_info('Info', 'Sistem akan maintenance malam ini.');
return redirect()->back();

// Generic (custom variant)
toast('success', 'Custom Title', 'Custom Message');
return redirect()->back();
```

---

### 4️⃣ **Trigger Toast dari Alpine.js Component**

Gunakan `$dispatch` directive:

```blade
<button 
    x-on:click="$dispatch('notify', { 
        variant: 'success', 
        title: 'Berhasil!', 
        message: 'Item ditambahkan ke keranjang.' 
    })"
>
    Tambah ke Keranjang
</button>
```

---

### 5️⃣ **Trigger Toast dari Validation Errors**

Sudah otomatis! Contoh implementasi di `login.blade.php`:

```blade
@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($errors->all() as $error)
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        variant: 'danger',
                        title: 'Login Gagal',
                        message: '{{ $error }}'
                    }
                }));
            @endforeach
        });
    </script>
@endif
```

---

## 🎨 Variant yang Tersedia

| Variant   | Warna  | Kapan Dipakai                          |
|-----------|--------|----------------------------------------|
| `success` | Hijau  | Operasi berhasil, data tersimpan       |
| `danger`  | Merah  | Error, gagal, akses ditolak            |
| `warning` | Kuning | Peringatan, perlu perhatian            |
| `info`    | Biru   | Informasi umum, notifikasi             |

---

## ⚙️ Konfigurasi

Edit `resources/views/components/toast.blade.php` untuk mengubah:

- **Display Duration**: Default 5000ms (5 detik)
  ```javascript
  displayDuration: 5000  // Ubah angka ini
  ```

- **Sound Effect**: Default ON (true)
  ```javascript
  soundEffect: true  // Ubah ke false untuk matikan sound
  ```

- **Max Notifications**: Default 5 toast sekaligus
  ```javascript
  if (this.notifications.length >= 5) {  // Ubah angka ini
  ```

- **Position**: Default top-right
  ```html
  class="fixed top-4 right-4 z-50..."  // Ubah position class
  ```

- **Sound File**: Default ding.mp3 dari Penguin UI CDN
  ```javascript
  const notificationSound = new Audio('URL_SOUND_ANDA');
  ```

---

## 📝 Contoh Lengkap di Controller

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Your logic here
            
            return redirect()->route('dashboard')->with('toast', [
                'variant' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Data berhasil disimpan.'
            ]);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('toast', [
                'variant' => 'danger',
                'title' => 'Gagal!',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
```

---

## ✅ Keuntungan Toast vs Error Box

| Fitur              | Toast Notification | Error Box Inline |
|--------------------|-------------------|------------------|
| Modern & Smooth    | ✅                | ❌               |
| Auto Dismiss       | ✅                | ❌               |
| Multiple Messages  | ✅ (Stackable)    | ❌               |
| Non-intrusive      | ✅                | ❌               |
| Reusable           | ✅                | ❌               |
| Animasi            | ✅                | ❌               |

---

## 🚀 Next Steps

1. ✅ Toast component sudah dibuat
2. ✅ Sudah di-include di `login.blade.php`
3. ✅ Validation errors otomatis jadi toast
4. 🔲 Include di layout utama (admin, customer, karyawan dashboard)
5. 🔲 Ganti semua error message jadi toast

---

**Dibuat dengan ❤️ menggunakan Penguin UI + Alpine.js v3**
