<form method="POST" action="{{ route('order.store') }}">
@csrf

<select name="paket_id">
@foreach($pakets as $paket)
<option value="{{ $paket->id }}">
{{ $paket->nama }} - {{ $paket->harga }}/kg
</option>
@endforeach
</select>

<input type="number" name="berat" placeholder="Berat (kg)">

<select name="metode_pengantaran">
<option value="antar_sendiri">Antar Sendiri</option>
<option value="dijemput">Dijemput Karyawan</option>
</select>

<input type="number" name="jarak_km" placeholder="Jarak (km)">

<button>Buat Order</button>
</form>
