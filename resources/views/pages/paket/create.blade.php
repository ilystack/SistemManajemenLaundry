<h1>Tambah Paket Laundry</h1>

<form method="POST" action="{{ route('paket.store') }}">
    @csrf

    <input type="text" name="nama_paket" placeholder="Nama Paket"><br>

    <input type="number" name="harga" placeholder="Harga"><br>

    <select name="satuan">
        <option value="kg">Per KG</option>
        <option value="pcs">Per PCS</option>
    </select><br>

    <input type="number" name="estimasi_hari" placeholder="Estimasi Hari"><br>

    <button type="submit">Simpan</button>
</form>
