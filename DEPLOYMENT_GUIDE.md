# 🚀 Panduan Deploy Production dengan Midtrans Sandbox

## ✅ Kode Sudah Siap Production!

Semua kode Midtrans sudah benar dan siap untuk production/hosting. Yang perlu kamu lakukan:

---

## 📋 Checklist Deployment

### 1. **Environment Variables (.env)**

Pastikan di server production, file `.env` sudah diisi:

```env
# Midtrans Configuration (SANDBOX)
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

**Cara dapat key:**
1. Login ke https://dashboard.sandbox.midtrans.com/
2. Settings → Access Keys
3. Copy Server Key & Client Key

---

### 2. **Midtrans Dashboard Configuration**

Di dashboard Midtrans Sandbox, set:

**Payment Notification URL:**
```
https://your-domain.com/payment/webhook
```

**Finish Redirect URL:**
```
https://your-domain.com/customer/dashboard
```

**Error Redirect URL:**
```
https://your-domain.com/customer/dashboard
```

---

### 3. **Server Requirements**

```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Link storage
php artisan storage:link

# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### 4. **Testing Flow**

#### **Scenario 1: Cash Payment**
```
Customer → Pilih paket → Pilih "Cash" → Submit
✅ Order dibuat langsung, bayar saat ambil/antar
```

#### **Scenario 2: QRIS + Dijemput**
```
Customer → Pilih paket → Pilih "Dijemput" → Pilih "QRIS" → Submit
→ Redirect ke halaman payment Midtrans
→ Scan QRIS (sandbox bisa pakai simulator)
→ Payment success → Webhook update status
✅ Order DP terbayar, siap dijemput
```

#### **Scenario 3: QRIS + Antar Sendiri**
```
Customer → Pilih paket → Pilih "Antar Sendiri" → Pilih "QRIS" → Submit
✅ Order dibuat, bisa bayar nanti via dashboard (optional)
```

---

### 5. **Midtrans Sandbox Testing**

**Test Card Numbers (Sandbox):**
- Success: `4811 1111 1111 1114`
- Failure: `4911 1111 1111 1113`

**QRIS Simulator:**
- Di halaman payment Midtrans sandbox, ada tombol "Simulate Payment"
- Klik untuk langsung success tanpa scan real QRIS

---

### 6. **Hosting Recommendations**

**Untuk Tugas Kuliah (Gratis/Murah):**

1. **Railway.app** (Recommended) ⭐
   - Free tier available
   - Auto deploy dari GitHub
   - Support PHP & MySQL
   - HTTPS otomatis
   - URL: `https://your-app.railway.app`

2. **Vercel + PlanetScale**
   - Vercel untuk Laravel (via serverless)
   - PlanetScale untuk MySQL
   - Free tier generous

3. **Heroku**
   - Classic option
   - Free tier (limited)
   - Easy deploy

4. **InfinityFree / 000webhost**
   - Free shared hosting
   - Support PHP & MySQL
   - Tapi agak lambat

---

### 7. **File yang Perlu Diupload**

```
✅ Semua file Laravel (kecuali node_modules, vendor)
✅ .env (edit di server dengan config production)
✅ Database migration files
✅ Storage folder structure
```

**Jangan upload:**
```
❌ node_modules/
❌ vendor/ (install via composer di server)
❌ .env.example (buat .env baru)
❌ storage/logs/* (akan auto generate)
```

---

### 8. **Quick Deploy Script**

Buat file `deploy.sh` untuk auto deploy:

```bash
#!/bin/bash

# Pull latest code
git pull origin main

# Install dependencies
composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Clear & cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "✅ Deploy complete!"
```

---

## 🎯 Kesimpulan

### **Kode Midtrans: SUDAH BENAR ✅**

Yang sudah ada:
- ✅ MidtransService dengan config proper
- ✅ PaymentController dengan webhook handler
- ✅ Signature verification untuk keamanan
- ✅ Payment model & migration
- ✅ Logic payment method (Cash/QRIS)
- ✅ Redirect flow yang benar

### **Yang Perlu Dilakukan:**

1. **Deploy ke hosting** (Railway/Vercel/Heroku)
2. **Set environment variables** di hosting
3. **Configure Midtrans dashboard** dengan URL production
4. **Test payment flow** dengan sandbox

### **Untuk Localhost (Development):**

Midtrans **TIDAK BISA** jalan di localhost karena:
- ❌ Webhook butuh public URL
- ❌ Callback URL tidak bisa akses localhost

**Solusi:**
- ✅ Deploy ke hosting (recommended)
- ✅ Pakai Ngrok untuk tunnel localhost
- ✅ Skip payment untuk testing UI (pakai Cash)

---

## 💡 Tips Presentasi Tugas Kuliah

1. **Demo Cash Payment** → Langsung jalan, tidak perlu internet
2. **Demo QRIS Payment** → Pakai Midtrans sandbox simulator
3. **Show Webhook** → Tunjukkan activity log update otomatis
4. **Show Code** → Tunjukkan signature verification untuk keamanan

---

## 📞 Support

Jika ada error saat deploy:
1. Cek `storage/logs/laravel.log`
2. Cek Midtrans dashboard → Transactions
3. Cek webhook response di Midtrans

**Common Issues:**
- 500 Error → Cek permissions storage folder
- Midtrans error → Cek server key di .env
- Webhook tidak jalan → Cek URL di Midtrans dashboard

---

**Good luck dengan tugas kuliahnya bro! 🎓🚀**
