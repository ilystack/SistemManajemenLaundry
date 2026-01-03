<form @submit.prevent="submitForm" class="p-6 space-y-5" x-data="{ 
          pickup: 'antar_sendiri',
          paymentMethod: 'cash',
          tipeOrder: null,
          selectedPaketKg: null,
          jumlahKg: 0,
          paketKg: {{ json_encode($pakets->where('satuan', 'kg')->values()->map(function ($p) {
    return ['id' => $p->id, 'nama' => $p->nama, 'harga' => $p->harga];
})) }},
          paketPcsRaw: {{ json_encode($pakets->where('satuan', 'pcs')->values()->map(function ($p) {
    return ['id' => $p->id, 'nama' => $p->nama, 'harga' => $p->harga, 'jenis_layanan' => $p->jenis_layanan];
})) }},
          paketPcsGrouped: {},
          selectedPcsItems: {}, // { 'Cuci Jas': { jenis: 'cuci_setrika', paket_id: 5, jumlah: 2, harga: 15000 } }
          
          laundryLat: {{ $laundryLocation['latitude'] ?? 'null' }},
          laundryLng: {{ $laundryLocation['longitude'] ?? 'null' }},
          customerLat: null,
          customerLng: null,
          distance: 0,
          pickupCost: 0,
          gettingLocation: false,
          locationDetected: false,
          
          init() {
              this.paketPcsRaw.forEach(p => {
                  if (!this.paketPcsGrouped[p.nama]) {
                      this.paketPcsGrouped[p.nama] = [];
                  }
                  this.paketPcsGrouped[p.nama].push(p);
              });
          },
          
          needsPayment() {
              // Antar sendiri + cash = NO payment
              if (this.pickup === 'antar_sendiri' && this.paymentMethod === 'cash') {
                  return false;
              }
              // All other combinations need payment
              return true;
          },
          
          getPaymentAmount() {
              const laundryTotal = this.tipeOrder === 'kg' 
                  ? (this.jumlahKg * (this.paketKg.find(p => p.id == this.selectedPaketKg)?.harga || 0))
                  : this.getTotalPcsPrice();
              
              // Antar sendiri + QRIS = Full laundry only
              if (this.pickup === 'antar_sendiri' && this.paymentMethod === 'qris') {
                  return laundryTotal;
              }
              
              // Dijemput + cash = DP (jarak only)
              if (this.pickup === 'dijemput' && this.paymentMethod === 'cash') {
                  return this.pickupCost;
              }
              
              // Dijemput + QRIS = Full (laundry + jarak)
              if (this.pickup === 'dijemput' && this.paymentMethod === 'qris') {
                  return laundryTotal + this.pickupCost;
              }
              
              return 0;
          },
          
          getSubmitButtonText() {
              if (!this.needsPayment()) {
                  return 'Buat Order';
              }
              
              const amount = this.getPaymentAmount();
              
              if (this.pickup === 'dijemput' && this.paymentMethod === 'cash') {
                  return `Bayar DP Rp ${amount.toLocaleString('id-ID')}`;
              }
              
              return `Bayar Rp ${amount.toLocaleString('id-ID')}`;
          },
          
          addPcsItem(namaPaket) {
              if (!this.selectedPcsItems[namaPaket]) {
                  const defaultJenis = this.paketPcsGrouped[namaPaket].find(p => p.jenis_layanan === 'cuci_setrika');
                  this.selectedPcsItems[namaPaket] = {
                      jenis: defaultJenis ? defaultJenis.jenis_layanan : this.paketPcsGrouped[namaPaket][0].jenis_layanan,
                      paket_id: defaultJenis ? defaultJenis.id : this.paketPcsGrouped[namaPaket][0].id,
                      jumlah: 1,
                      harga: defaultJenis ? defaultJenis.harga : this.paketPcsGrouped[namaPaket][0].harga
                  };
              }
          },
          
          updateJenis(namaPaket, jenis) {
              const paket = this.paketPcsGrouped[namaPaket].find(p => p.jenis_layanan === jenis);
              if (paket && this.selectedPcsItems[namaPaket]) {
                  this.selectedPcsItems[namaPaket].jenis = jenis;
                  this.selectedPcsItems[namaPaket].paket_id = paket.id;
                  this.selectedPcsItems[namaPaket].harga = paket.harga;
              }
          },
          
          incrementPcs(namaPaket) {
              if (this.selectedPcsItems[namaPaket]) {
                  this.selectedPcsItems[namaPaket].jumlah++;
              }
          },
          
          decrementPcs(namaPaket) {
              if (this.selectedPcsItems[namaPaket] && this.selectedPcsItems[namaPaket].jumlah > 1) {
                  this.selectedPcsItems[namaPaket].jumlah--;
              }
          },
          
          removePcsItem(namaPaket) {
              delete this.selectedPcsItems[namaPaket];
          },
          
          getTotalPcsItems() {
              return Object.values(this.selectedPcsItems).reduce((sum, item) => sum + item.jumlah, 0);
          },
          
          getTotalPcsPrice() {
              return Object.values(this.selectedPcsItems).reduce((sum, item) => sum + (item.jumlah * item.harga), 0);
          },
          
          getJenisLabel(jenis) {
              const labels = {
                  'cuci_saja': '🧼 Cuci Saja',
                  'cuci_setrika': '✨ Cuci + Setrika',
                  'kilat': '⚡ Kilat'
              };
              return labels[jenis] || jenis;
          },
          
          calculateDistance(lat1, lon1, lat2, lon2) {
              const R = 6371; // Radius bumi dalam km
              const dLat = (lat2 - lat1) * Math.PI / 180;
              const dLon = (lon2 - lon1) * Math.PI / 180;
              const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                        Math.sin(dLon/2) * Math.sin(dLon/2);
              const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
              return R * c; // Jarak dalam km
          },
          
          detectLocation() {
              if (!this.laundryLat || !this.laundryLng) {
                  alert('Lokasi laundry belum diset oleh admin. Silakan hubungi admin.');
                  return;
              }
              
              if (navigator.geolocation) {
                  this.gettingLocation = true;
                  navigator.geolocation.getCurrentPosition(
                      (position) => {
                          this.customerLat = position.coords.latitude;
                          this.customerLng = position.coords.longitude;
                          
                          this.distance = this.calculateDistance(
                              this.laundryLat, 
                              this.laundryLng,
                              this.customerLat,
                              this.customerLng
                          );
                          
                          this.distance = Math.round(this.distance * 10) / 10;
                          
                          this.pickupCost = Math.round(this.distance * 1000);
                          
                          document.getElementById('jarak_km').value = this.distance;
                          document.getElementById('latitude').value = this.customerLat;
                          document.getElementById('longitude').value = this.customerLng;
                          
                          this.gettingLocation = false;
                          this.locationDetected = true;
                      },
                      (error) => {
                          alert('Gagal mendapatkan lokasi: ' + error.message);
                          this.gettingLocation = false;
                      }
                  );
              } else {
                  alert('Browser Anda tidak mendukung Geolocation');
              }
          },
          
          async submitForm(event) {
              const form = event.target;
              const formData = new FormData(form);
              
              // Show loading
              if (typeof window.showLoading === 'function') {
                  window.showLoading();
              }
              
              try {
                  const response = await fetch('{{ route("order.store") }}', {
                      method: 'POST',
                      headers: {
                          'X-CSRF-TOKEN': document.querySelector('meta[name=" csrf-token"]').content, 'Accept'
    : 'application/json' , 'X-Requested-With' : 'XMLHttpRequest' }, body: formData }); const data=await response.json();
    if (typeof window.hideLoading==='function' ) { window.hideLoading(); } if (data.success) { if (data.needs_payment &&
    data.snap_token) { // Close order modal this.showModal=false; // Open Midtrans Snap modal
    window.snap.pay(data.snap_token, { onSuccess: (result)=> {
    if (typeof window.showToast === 'function') {
    window.showToast('Pembayaran berhasil!', 'success');
    }
    setTimeout(() => {
    window.location.href = '{{ Auth::user()->role === "customer" ? route("customer.dashboard") : route("order.index") }}';
    }, 1000);
    },
    onPending: (result) => {
    if (typeof window.showToast === 'function') {
    window.showToast('Menunggu pembayaran...', 'info');
    }
    setTimeout(() => {
    window.location.href = '{{ Auth::user()->role === "customer" ? route("customer.dashboard") : route("order.index") }}';
    }, 1000);
    },
    onError: (result) => {
    if (typeof window.showToast === 'function') {
    window.showToast('Pembayaran gagal. Silakan coba lagi.', 'error');
    } else {
    alert('Pembayaran gagal. Silakan coba lagi.');
    }
    },
    onClose: () => {
    console.log('Payment popup closed');
    // User closed the popup, redirect to dashboard
    setTimeout(() => {
    window.location.href = '{{ Auth::user()->role === "customer" ? route("customer.dashboard") : route("order.index") }}';
    }, 500);
    }
    });
    } else {
    // No payment needed, redirect
    if (typeof window.showToast === 'function') {
    window.showToast(data.message, 'success');
    }
    setTimeout(() => {
    window.location.href = data.redirect_url;
    }, 1000);
    }
    } else {
    throw new Error(data.message || 'Terjadi kesalahan');
    }
    } catch (error) {
    if (typeof window.hideLoading === 'function') {
    window.hideLoading();
    }

    if (typeof window.showToast === 'function') {
    window.showToast(error.message || 'Terjadi kesalahan saat membuat order', 'error');
    } else {
    alert(error.message || 'Terjadi kesalahan saat membuat order');
    }
    }
    }
    }">
    @csrf

    <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Pilih Tipe Paket</label>
        <div class="grid grid-cols-2 gap-4">
            <label class="cursor-pointer">
                <input type="radio" name="tipe_order" value="kg" x-model="tipeOrder" class="peer sr-only">
                <div
                    class="p-4 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 hover:bg-white dark:hover:bg-gray-600 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/30 transition-all text-center">
                    <div class="text-3xl mb-2">⚖️</div>
                    <span class="block font-bold text-gray-900 dark:text-white">Paket Kiloan</span>
                    <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">Per kilogram</span>
                </div>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="tipe_order" value="pcs" x-model="tipeOrder" class="peer sr-only">
                <div
                    class="p-4 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 hover:bg-white dark:hover:bg-gray-600 peer-checked:border-purple-500 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/30 transition-all text-center">
                    <div class="text-3xl mb-2">👕</div>
                    <span class="block font-bold text-gray-900 dark:text-white">Paket Satuan</span>
                    <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">Per potong</span>
                </div>
            </label>
        </div>
    </div>

    <div x-show="tipeOrder === 'kg'" x-transition class="space-y-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Pilih Paket Kiloan</label>
            <select name="paket_id_kg" x-model="selectedPaketKg" :required="tipeOrder === 'kg'"
                :disabled="tipeOrder !== 'kg'"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 dark:text-white focus:bg-white dark:focus:bg-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 transition-all">
                <option value="" disabled selected>-- Pilih Paket --</option>
                <template x-for="paket in paketKg" :key="paket.id">
                    <option :value="paket.id" x-text="`${paket.nama} - Rp ${paket.harga.toLocaleString('id-ID')}/kg`">
                    </option>
                </template>
            </select>
        </div>

        <div x-show="selectedPaketKg">
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Berat (Kilogram)</label>
            <div class="relative">
                <input type="number" name="jumlah_kg" x-model="jumlahKg" min="0.1" step="0.1" placeholder="Contoh: 3.5"
                    :required="tipeOrder === 'kg'" :disabled="tipeOrder !== 'kg'"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 dark:text-white focus:bg-white dark:focus:bg-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 transition-all pr-12">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <span class="text-gray-500 dark:text-gray-400 font-medium text-sm">kg</span>
                </div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Masukkan berat cucian dalam kilogram (bisa desimal)
            </p>
        </div>
    </div>

    <div x-show="tipeOrder === 'pcs'" x-transition class="space-y-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Pilih Paket & Jenis
                Layanan</label>

            <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4 space-y-3 max-h-96 overflow-y-auto">
                <template x-for="(jenisArray, namaPaket) in paketPcsGrouped" :key="namaPaket">
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 dark:text-white" x-text="namaPaket"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pilih jenis layanan</p>
                            </div>
                            <button type="button" @click="addPcsItem(namaPaket)" x-show="!selectedPcsItems[namaPaket]"
                                class="px-3 py-1.5 bg-purple-500 hover:bg-purple-600 text-white text-sm font-semibold rounded-lg transition">
                                + Tambah
                            </button>
                        </div>

                        <div x-show="selectedPcsItems[namaPaket]" x-transition
                            class="space-y-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Jenis
                                    Layanan:</label>
                                <select @change="updateJenis(namaPaket, $event.target.value)"
                                    :value="selectedPcsItems[namaPaket]?.jenis"
                                    class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 dark:text-white focus:border-purple-500 focus:ring-2 focus:ring-purple-200 dark:focus:ring-purple-800 transition-all">
                                    <template x-for="paket in jenisArray" :key="paket.id">
                                        <option :value="paket.jenis_layanan"
                                            x-text="`${getJenisLabel(paket.jenis_layanan)} - Rp ${paket.harga.toLocaleString('id-ID')}`">
                                        </option>
                                    </template>
                                </select>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah:</span>
                                <div class="flex items-center gap-3">
                                    <button type="button" @click="decrementPcs(namaPaket)"
                                        class="w-8 h-8 rounded-lg bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-white font-bold transition flex items-center justify-center">
                                        −
                                    </button>
                                    <span class="w-8 text-center font-bold text-gray-900 dark:text-white"
                                        x-text="selectedPcsItems[namaPaket]?.jumlah || 0"></span>
                                    <button type="button" @click="incrementPcs(namaPaket)"
                                        class="w-8 h-8 rounded-lg bg-purple-500 hover:bg-purple-600 text-white font-bold transition flex items-center justify-center">
                                        +
                                    </button>
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-between pt-2 border-t border-gray-200 dark:border-gray-600">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Subtotal:</p>
                                    <p class="font-bold text-purple-600 dark:text-purple-400"
                                        x-text="`Rp ${((selectedPcsItems[namaPaket]?.jumlah || 0) * (selectedPcsItems[namaPaket]?.harga || 0)).toLocaleString('id-ID')}`">
                                    </p>
                                </div>
                                <button type="button" @click="removePcsItem(namaPaket)"
                                    class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm font-medium transition">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div x-show="getTotalPcsItems() > 0" x-transition
                class="mt-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 border border-purple-200 dark:border-purple-800">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Item:</span>
                    <span class="font-bold text-gray-900 dark:text-white"
                        x-text="`${getTotalPcsItems()} potong`"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Subtotal:</span>
                    <span class="font-bold text-purple-600 dark:text-purple-400 text-lg"
                        x-text="`Rp ${getTotalPcsPrice().toLocaleString('id-ID')}`"></span>
                </div>
            </div>

            <template x-for="(item, namaPaket) in selectedPcsItems" :key="namaPaket">
                <div>
                    <input type="hidden" :name="`items[${item.paket_id}][paket_id]`" :value="item.paket_id">
                    <input type="hidden" :name="`items[${item.paket_id}][jumlah]`" :value="item.jumlah">
                </div>
            </template>
        </div>
    </div>

    <div x-show="tipeOrder" x-transition>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Metode Pengantaran</label>
        <div class="grid grid-cols-2 gap-4">
            <label class="cursor-pointer">
                <input type="radio" name="pickup" value="antar_sendiri" x-model="pickup" class="peer sr-only" checked>
                <div
                    class="p-4 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 hover:bg-white dark:hover:bg-gray-600 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/30 peer-checked:text-blue-700 dark:peer-checked:text-blue-400 transition-all text-center">
                    <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="block font-semibold text-sm">Antar Sendiri</span>
                </div>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="pickup" value="dijemput" x-model="pickup" class="peer sr-only">
                <div
                    class="p-4 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 hover:bg-white dark:hover:bg-gray-600 peer-checked:border-purple-500 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/30 peer-checked:text-purple-700 dark:peer-checked:text-purple-400 transition-all text-center">
                    <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="block font-semibold text-sm">Dijemput</span>
                </div>
            </label>
        </div>
    </div>

    <div x-show="pickup === 'dijemput' && tipeOrder" x-transition class="space-y-4">
        <div
            class="flex items-center justify-between p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-200 dark:border-purple-800">
            <div class="flex-1">
                <p class="text-sm font-semibold text-purple-900 dark:text-purple-300">Deteksi Lokasi Otomatis</p>
                <p class="text-xs text-purple-700 dark:text-purple-400 mt-1">Klik untuk menghitung jarak & biaya pickup
                </p>
            </div>
            <button type="button" @click="detectLocation()" :disabled="gettingLocation"
                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 text-white text-sm font-semibold rounded-lg transition flex items-center gap-2 shadow-md">
                <svg class="w-4 h-4" :class="{'animate-spin': gettingLocation}" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span x-text="gettingLocation ? 'Mendeteksi...' : 'Deteksi Lokasi'"></span>
            </button>
        </div>

        <div x-show="locationDetected" x-transition
            class="p-4 bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-green-900 dark:text-green-300">Lokasi Terdeteksi!</p>
                    <div class="mt-2 space-y-1">
                        <p class="text-sm text-green-800 dark:text-green-400">
                            <span class="font-medium">Jarak:</span>
                            <span class="font-bold" x-text="`${distance} km`"></span>
                        </p>
                        <p class="text-sm text-green-800 dark:text-green-400">
                            <span class="font-medium">Biaya Pickup:</span>
                            <span class="font-bold" x-text="`Rp ${pickupCost.toLocaleString('id-ID')}`"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="jarak_km" name="jarak_km">
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">
    </div>

    <div x-show="tipeOrder" x-transition>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Metode Pembayaran</label>
        <div class="grid grid-cols-2 gap-4">
            <label class="cursor-pointer">
                <input type="radio" name="payment_method" value="cash" x-model="paymentMethod" class="peer sr-only"
                    checked>
                <div
                    class="p-4 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 hover:bg-white dark:hover:bg-gray-600 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/30 peer-checked:text-green-700 dark:peer-checked:text-green-400 transition-all text-center">
                    <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="block font-semibold text-sm">Cash</span>
                    <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">Bayar di tempat</span>
                </div>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="payment_method" value="qris" x-model="paymentMethod" class="peer sr-only">
                <div
                    class="p-4 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 hover:bg-white dark:hover:bg-gray-600 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/30 peer-checked:text-blue-700 dark:peer-checked:text-blue-400 transition-all text-center">
                    <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    <span class="block font-semibold text-sm">QRIS</span>
                    <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">Scan & bayar</span>
                </div>
            </label>
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
        <button type="button" @click="showModal = false"
            class="px-5 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition">
            Batal
        </button>
        <button type="submit"
            :disabled="!tipeOrder || (tipeOrder === 'kg' && (!selectedPaketKg || jumlahKg <= 0)) || (tipeOrder === 'pcs' && getTotalPcsItems() === 0) || (pickup === 'dijemput' && !locationDetected)"
            :class="(!tipeOrder || (tipeOrder === 'kg' && (!selectedPaketKg || jumlahKg <= 0)) || (tipeOrder === 'pcs' && getTotalPcsItems() === 0) || (pickup === 'dijemput' && !locationDetected)) ? 'opacity-50 cursor-not-allowed' : ''"
            class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-600 rounded-lg hover:from-blue-700 hover:to-cyan-700 shadow-md hover:shadow-lg transition"
            x-text="getSubmitButtonText()">
        </button>
    </div>
</form>