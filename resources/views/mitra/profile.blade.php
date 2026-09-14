@extends('template.layout')

@section('title', 'Profil Mitra')

@section('content')
    <h3 class="mb-4"><i class="bi bi-person-circle"></i> Profil Mitra</h3>

    <div class="card card-stat" style="max-width: 600px;">
        <div class="card-body">
            <form action="{{ route('mitra.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $mitra->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $mitra->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $mitra->phone) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" class="form-control" rows="3">{{ old('address', $mitra->address) }}</textarea>
                </div>

                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            </form>
        </div>
    </div>
@endsection
