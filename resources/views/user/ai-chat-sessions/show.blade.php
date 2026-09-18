@extends('template.layout')

@section('title', 'Bero Asistent')

@section('content')
    <div class="container">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-1">{{ $session->title }}</h4>
                <a href="{{ route('user.ai-chat-sessions.index') }}" class="small">
                    ← Kembali
                </a>
            </div>

            <form action="{{ route('user.ai-chat-sessions.update', $session->session_id) }}" method="POST"
                class="d-flex gap-2">
                @csrf
                @method('PUT')

                <input type="text" name="title" value="{{ $session->title }}" class="form-control form-control-sm">

                <button class="btn btn-primary btn-sm">Ubah</button>
            </form>
        </div>

        {{-- Alert --}}
        @if(session('success'))
            <div class="alert alert-success py-2">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger py-2">
                {{ session('error') }}
            </div>
        @endif

        {{-- Chat --}}
        <div id="chat-box" class="card mx-auto" style="max-width: 800px; height: 65vh; overflow-y: auto;">

            <div class="card-body px-4">

                @forelse($session->messages as $msg)

                        <div class="d-flex mb-3
                                            {{ $msg->sender === 'user'
                    ? 'justify-content-end'
                    : 'justify-content-start' }}">

                            <div class="px-3 py-2 rounded-3
                                                {{ $msg->sender === 'user'
                    ? 'bg-primary text-white'
                    : 'bg-light text-dark' }}" style="max-width: 65%; white-space: pre-wrap;">

                                {{ $msg->message }}

                            </div>
                        </div>

                @empty

                    <div class="text-center text-muted mt-5">
                        Belum ada pesan.<br>
                        Mulai percakapan!
                    </div>

                @endforelse

            </div>
        </div>

        {{-- Input --}}
        <form action="{{ route('user.ai-chat-messages.store', $session->session_id) }}" method="POST" class="mx-auto mt-3"
            style="max-width: 800px;">

            @csrf

            <div class="input-group">
                <textarea name="message" rows="1" class="form-control" placeholder="Tulis pesan..." required></textarea>

                <button class="btn btn-primary">
                    <i class="bi bi-send"></i>
                </button>
            </div>

        </form>

    </div>

    <script>
        const box = document.getElementById('chat-box');
        box.scrollTop = box.scrollHeight;

        const textarea = document.querySelector('textarea[name="message"]');

        textarea.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.form.submit();
            }
        });
    </script>
@endsection