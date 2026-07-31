@extends('layout.app')

@section('title','Tambah Siswa Bimbel . Paradise of Math')

@section('content')
    <!-- card -->
    <div class="card shadow-sm">
        <h1 class="card-title">Tambah Siswa</h1>
        <div class="card-body">
            <form action="{{ route('admin.siswa.tambah.index') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama">Nama Siswa</label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </form>
        </div>
    </div>
@endsection