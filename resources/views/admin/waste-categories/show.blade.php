@extends('template.layout')

@section('content')
    <div class="container">
        <h1>Detail Jenis Sampah</h1>

        <table class="table table-bordered">
            <tr>
                <th width="200">ID</th>
                <td>{{ $wasteCategory->category_id }}</td>
            </tr>
            <tr>
                <th>Nama</th>
                <td>{{ $wasteCategory->name }}</td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td>{{ $wasteCategory->description }}</td>
            </tr>
            <tr>
                <th>Harga/kg</th>
                <td>Rp {{ number_format($wasteCategory->price_per_kg, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Reward/kg</th>
                <td>{{ $wasteCategory->reward_per_kg }}</td>
            </tr>
            <tr>
                <th>Poin/kg</th>
                <td>{{ $wasteCategory->point_per_kg }}</td>
            </tr>
            <tr>
                <th>CO2 Saved/kg</th>
                <td>{{ $wasteCategory->co2_saved_per_kg }}</td>
            </tr>
            <tr>
                <th>Icon</th>
                <td>{{ $wasteCategory->icon }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if($wasteCategory->is_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Nonaktif</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Dibuat</th>
                <td>{{ $wasteCategory->created_at }}</td>
            </tr>
            <tr>
                <th>Diupdate</th>
                <td>{{ $wasteCategory->updated_at }}</td>
            </tr>
        </table>

        <h3>Transaksi Pickup yang Memakai Kategori Ini</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pickup Request</th>
                    <th>Berat (kg)</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($wasteCategory->pickupItems as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->pickup_request_id }}</td>
                        <td>{{ $item->weight }}</td>
                        <td>{{ $item->subtotal ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('waste-categories.index') }}" class="btn btn-secondary">Kembali</a>
        <a href="{{ route('waste-categories.edit', $wasteCategory->category_id) }}" class="btn btn-warning">Edit</a>
    </div>
@endsection