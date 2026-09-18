@extends('template.layout')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">BERO AI</h4>

        <form action="{{ route('user.ai-chat-sessions.store') }}" method="POST">
            @csrf
            <button class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Chat Baru
            </button>
        </form>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            @forelse ($sessions as $s)

                <div class="d-flex align-items-center justify-content-between border-bottom py-3">

                    <a href="{{ route('user.ai-chat-sessions.show', $s->session_id) }}"
                       class="text-decoration-none flex-grow-1">

                        <div class="fw-semibold">
                            {{ $s->title }}
                        </div>

                        <small class="text-muted">
                            {{ $s->created_at->diffForHumans() }}

                            @if (isset($s->messages_count))
                                · {{ $s->messages_count }} pesan
                            @endif
                        </small>

                    </a>

                    <form action="{{ route('user.ai-chat-sessions.destroy', $s->session_id) }}"
                          method="POST"
                          onsubmit="return confirm('Hapus sesi ini?')">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>

                    </form>

                </div>

            @empty

                <div class="text-center text-muted py-5">
                    <i class="bi bi-chat-dots fs-1"></i>
                    <p class="mt-2 mb-0">
                        Belum ada sesi chat.
                    </p>
                </div>

            @endforelse

        </div>
    </div>

    <div class="mt-3">
        {{ $sessions->links() }}
    </div>

</div>
@endsection