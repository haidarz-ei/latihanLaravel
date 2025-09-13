<!DOCTYPE html>
<html>
<head>
    <title>Data MataKuliah</title>
</head>
<body>
    <h1>Tambah Mata MataKuliah</h1>
    <form method="POST" action="/matkul">
        @csrf
        <input type="text" name="namaMatkul" placeholder="Nama MataKuliah"><br>
        <input type="number" name="deskripsi" placeholder="Deskripsi"><br>
        <button type="submit">Simpan</button>
    </form>

    <h2>Daftar Ruangan</h2>
    <ul>
        @foreach($data as $ruangan)
            <li>{{ $matkul->namaRuangan }} - Kapasitas: {{ $matkul->kapasitas }}</li>
        @endforeach
    </ul>
</body>
</html>
