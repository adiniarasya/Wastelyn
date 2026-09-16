@extends('template.layout')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Daftar Jenis Sampah</h1>
            <a href="{{ route('waste-categories.create') }}" class="btn btn-primary">+ Tambah</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Icon</th>
                    <th>Nama</th>
                    <th>Harga/kg</th>
                    <th>Poin/kg</th>
                    <th>CO2/kg</th>
                    <th>Status</th>
                    <th>Total Pickup</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($wasteCategories as $c)
                    <tr>
                        <td>{{ $c->category_id }}</td>
                        <td>
                            @if($c->icon)
                                <img src="{{ asset('storage/icons/' . $c->icon) }}" width="40" alt="">
                            @endif
                        </td>
                        <td>{{ $c->name }}</td>
                        <td>Rp {{ number_format($c->price_per_kg, 0, ',', '.') }}</td>
                        <td>{{ $c->point_per_kg }}</td>
                        <td>{{ $c->co2_saved_per_kg }}</td>
                        <td>
                            @if($c->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>{{ $c->pickup_items_count }}</td>
                        <td>
                            <a href="{{ route('waste-categories.show', $c->category_id) }}"
                                class="btn btn-info btn-sm">Detail</a>
                            <a href="{{ route('waste-categories.edit', $c->category_id) }}"
                                class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('waste-categories.destroy', $c->category_id) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Belum ada data jenis sampah.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection