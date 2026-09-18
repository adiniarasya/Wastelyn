@extends('template.layout')

@section('content')
    <div class="container">
        <h1>Detail Jenis Sampah</h1>

        <table class="table table-bordered align-middle">
            <tr>
                <th width="200">ID</th>
                <td>{{ $wasteCategory->category_id }}</td>
            </tr>
            <tr>
                <th>Foto / Icon</th>
                <td>
                    @if($wasteCategory->icon)
                        <img src="{{ asset('storage/' . $wasteCategory->icon) }}" width="120"
                            style="object-fit:cover; border-radius:8px;" alt="{{ $wasteCategory->name }}">
                    @else
                        <span class="text-muted">Belum ada foto</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Nama</th>
                <td>{{ $wasteCategory->name }}</td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td>{{ $wasteCategory->description ?? '-' }}</td>
            </tr>
            <tr>
                <th>Harga/kg</th>
                <td>Rp {{ number_format($wasteCategory->price_per_kg, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Reward/kg</th>
                <td>{{ $wasteCategory->reward_per_kg ?? '-' }}</td>
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
                <th>Dibuat</th>
                <td>{{ $wasteCategory->created_at?->format('d M Y H:i') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Diupdate</th>
                <td>{{ $wasteCategory->updated_at?->format('d M Y H:i') ?? '-' }}</td>
            </tr>
        </table>

        <a href="{{ route('admin.waste-categories.index') }}" class="btn btn-secondary">Kembali</a>
        <a href="{{ route('admin.waste-categories.edit', $wasteCategory->category_id) }}" class="btn btn-warning">Edit</a>
    </div>
@endsection