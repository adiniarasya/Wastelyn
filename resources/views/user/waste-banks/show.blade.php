@extends('template.layout')

@section('title', 'Detail Bank Sampah')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="bi bi-shop"></i> Detail Bank Sampah</h3>
        <a href="{{ route('user.waste-banks.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card card-stat">
        <div class="card-body">
            <table class="table table-borderless mb-0">
                <tr>
                    <th width="200">Nama</th>
                    <td>{{ $wasteBank->name }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $wasteBank->address ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Telepon</th>
                    <td>{{ $wasteBank->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $wasteBank->email ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Jam Operasional</th>
                    <td>{{ $wasteBank->opening_hours ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $wasteBank->status ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection