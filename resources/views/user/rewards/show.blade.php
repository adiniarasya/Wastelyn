@extends('template.layout')

@section('title', 'Detail Reward')

@section('content')
    <a href="{{ route('user.rewards.index') }}" class="btn btn-sm btn-link ps-0 mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Reward Center
    </a>

    <div class="card card-stat" style="max-width: 600px;">
        <div class="card-body text-center">
            @if($reward->image)
                <img src="{{ asset('storage/' . $reward->image) }}"
                     class="img-fluid rounded mb-3" style="max-height: 250px;">
            @endif
            <h4>{{ $reward->name }}</h4>
            <p class="text-muted">{{ $reward->description }}</p>
            <p><strong>Poin dibutuhkan:</strong> ⭐ {{ number_format($reward->point_required) }}</p>
            <p><strong>Stok tersedia:</strong> {{ $reward->stock }}</p>

            @if($user->points >= $reward->point_required && $reward->stock > 0)
                <form action="{{ route('user.rewards.redeem', $reward->reward_id) }}" method="POST">
                    @csrf
                    <button class="btn btn-success">
                        <i class="bi bi-gift"></i> Tukar Sekarang
                    </button>
                </form>
            @else
                <button class="btn btn-secondary" disabled>Tidak Bisa Ditukar</button>
            @endif
        </div>
    </div>
@endsection