@extends('template.layout')

@section('title', 'Bero Asistent')

@section('content')
    <div class="bero-full">

        {{-- Sidebar kiri --}}
        <aside class="bero-sidebar">
            <div class="sidebar-header">
                <a href="{{ route('user.ai-chat-sessions.index') }}" class="btn-new-chat">
                    <i class="bi bi-plus-lg"></i> Chat Baru
                </a>
            </div>

            <div class="sidebar-section-title">Riwayat Chat</div>

            <div class="sidebar-list">
                @forelse($sessions ?? [] as $s)
                    <a href="{{ route('user.ai-chat-sessions.show', $s->session_id) }}"
                        class="sidebar-item {{ isset($session) && $session->session_id === $s->session_id ? 'active' : '' }}">
                        <i class="bi bi-chat-text"></i>
                        <span class="sidebar-item-title">{{ $s->title }}</span>
                    </a>
                @empty
                    <div class="sidebar-empty">Belum ada chat</div>
                @endforelse
            </div>
        </aside>

        {{-- Main chat --}}
        <main class="bero-main">

            {{-- Topbar --}}
            <div class="bero-topbar">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-bero">
                        <img src="{{ asset('assets/images/bero.png') }}" alt="Bero">
                    </div>
                    <div>
                        <div class="fw-semibold" style="font-size: 15px; color:#111827;">Bero</div>
                        <div class="text-muted" style="font-size: 12.5px;">Teman ngobrol soal sampah</div>
                    </div>
                </div>

                <form action="{{ route('user.ai-chat-sessions.update', $session->session_id) }}" method="POST"
                    class="d-flex gap-2 topbar-title-form">
                    @csrf
                    @method('PUT')
                    <input type="text" name="title" value="{{ $session->title }}" class="form-control form-control-sm"
                        style="max-width: 220px;">
                    <button class="btn btn-sm btn-outline-secondary">Ubah</button>
                </form>
            </div>

            {{-- Alert --}}
            @if(session('success'))
                <div class="alert alert-success py-2 mx-4 mt-3 mb-0">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger py-2 mx-4 mt-3 mb-0">{{ session('error') }}</div>
            @endif

            {{-- Messages --}}
            <div id="chat-box" class="bero-messages">
                @forelse($session->messages as $msg)
                    <div class="d-flex mb-4 {{ $msg->sender === 'user' ? 'justify-content-end' : 'justify-content-start' }}">

                        @if($msg->sender !== 'user')
                            <div class="avatar-bero-sm me-3">
                                <img src="{{ asset('assets/images/bero.png') }}" alt="Bero">
                            </div>
                        @endif

                        @if($msg->sender === 'user')
                            <div class="bubble bubble-user">{{ $msg->message }}</div>
                        @else
                            <div class="bubble bubble-bero" data-markdown="{{ e($msg->message) }}"></div>
                        @endif
                    </div>
                @empty
                    <div class="text-center text-muted mt-5" id="emptyState">
                        <div class="avatar-bero-lg mx-auto mb-3">
                            <img src="{{ asset('assets/images/bero.png') }}" alt="Bero">
                        </div>
                        <p class="mb-1 fw-semibold text-dark" style="font-size: 15px;">Halo, aku Bero</p>
                        <p class="mb-0 small text-muted">
                            Teman ngobrolmu soal sampah & bank sampah.<br>
                            Mau mulai dari mana?
                        </p>
                    </div>
                @endforelse
            </div>

            {{-- Chips --}}
            <div class="bero-chips">
                <button type="button" class="chip suggestion-chip">Misi hari ini</button>
                <button type="button" class="chip suggestion-chip">Bank sampah terdekat</button>
                <button type="button" class="chip suggestion-chip">Tips kelola sampah</button>
                <button type="button" class="chip suggestion-chip">Harga jual sampah</button>
            </div>

            {{-- Input --}}
            <div class="bero-input-wrap">
                <form id="chatForm" enctype="multipart/form-data" class="d-flex align-items-end gap-2 w-100">
                    @csrf

                    <div id="imagePreview" class="d-none position-relative">
                        <img src="" id="previewImg" style="height:42px;border-radius:6px;">
                        <button type="button" id="removeImg"
                            class="btn btn-sm btn-dark position-absolute top-0 end-0 p-0 px-1"
                            style="font-size:10px;border-radius:50%;">×</button>
                    </div>

                    <label for="imageInput" class="btn-icon" title="Kirim gambar">
                        <i class="bi bi-image"></i>
                    </label>
                    <input type="file" id="imageInput" name="image" accept="image/*" class="d-none">

                    <textarea id="messageInput" name="message" rows="1" class="form-control chat-input"
                        placeholder="Tulis pesan..." style="resize:none; max-height:160px;"></textarea>

                    <button type="submit" class="btn-send" id="sendBtn">
                        <i class="bi bi-send"></i>
                    </button>
                </form>
            </div>
        </main>
    </div>

    <style>
        /* ========= Layout Full ========= */
        .bero-full {
            display: flex;
            height: calc(100vh - 80px);
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
        }

        /* ========= Sidebar ========= */
        .bero-sidebar {
            width: 260px;
            flex-shrink: 0;
            background: #f9fafb;
            border-right: 1px solid #eef0f2;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar-header {
            padding: 16px;
        }

        .btn-new-chat {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #2e7d32;
            color: #fff;
            padding: 10px 14px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: background .15s;
        }

        .btn-new-chat:hover {
            background: #1b5e20;
            color: #fff;
        }

        .sidebar-section-title {
            padding: 0 16px 8px;
            font-size: 11.5px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .sidebar-list {
            flex: 1;
            overflow-y: auto;
            padding: 0 8px 12px;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: #374151;
            text-decoration: none;
            font-size: 13.5px;
            transition: background .12s;
            margin-bottom: 2px;
        }

        .sidebar-item i {
            color: #9ca3af;
            font-size: 14px;
            flex-shrink: 0;
        }

        .sidebar-item-title {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sidebar-item:hover {
            background: #eef0f2;
            color: #111827;
        }

        .sidebar-item.active {
            background: #e8f5e9;
            color: #1b5e20;
            font-weight: 500;
        }

        .sidebar-item.active i {
            color: #2e7d32;
        }

        .sidebar-empty {
            padding: 20px 16px;
            font-size: 12.5px;
            color: #9ca3af;
            text-align: center;
        }

        /* ========= Main ========= */
        .bero-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background: #fcfcfd;
        }

        /* Topbar */
        .bero-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 24px;
            background: #fff;
            border-bottom: 1px solid #eef0f2;
            flex-shrink: 0;
        }

        .topbar-title-form {
            align-items: center;
        }

        /* Messages */
        .bero-messages {
            flex: 1;
            overflow-y: auto;
            padding: 28px 40px;
            scroll-behavior: smooth;
        }

        .bero-messages::-webkit-scrollbar {
            width: 6px;
        }

        .bero-messages::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }

        /* Chips */
        .bero-chips {
            display: flex;
            gap: 8px;
            padding: 10px 24px;
            background: #fff;
            border-top: 1px solid #eef0f2;
            overflow-x: auto;
            flex-shrink: 0;
        }

        .bero-chips::-webkit-scrollbar {
            display: none;
        }

        .chip {
            border: 1px solid #e0e4e8;
            background: #fff;
            color: #374151;
            font-size: 12.5px;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 999px;
            white-space: nowrap;
            transition: all .15s ease;
            cursor: pointer;
        }

        .chip:hover {
            background: #f8f9fa;
            border-color: #c8cdd4;
            color: #111827;
        }

        /* Input */
        .bero-input-wrap {
            padding: 16px 24px 20px;
            background: #fff;
            border-top: 1px solid #eef0f2;
            flex-shrink: 0;
        }

        .chat-input {
            border-radius: 22px;
            border: 1px solid #e3e6ea;
            background: #f8f9fa;
            padding: 11px 18px;
            font-size: 14px;
            transition: all .15s ease;
        }

        .chat-input:focus {
            background: #fff;
            border-color: #2e7d32;
            box-shadow: 0 0 0 3px rgba(46, 125, 50, .08);
        }

        .btn-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #f1f3f5;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            cursor: pointer;
            transition: all .15s ease;
            flex-shrink: 0;
        }

        .btn-icon:hover {
            background: #e9ecef;
            color: #111827;
        }

        .btn-send {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #2e7d32;
            border: none;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .15s ease;
            flex-shrink: 0;
            font-size: 15px;
        }

        .btn-send:hover {
            background: #1b5e20;
        }

        .btn-send:disabled {
            opacity: .5;
            cursor: not-allowed;
        }

        /* ========= Avatar dengan maskot ========= */
        .avatar-bero,
        .avatar-bero-sm,
        .avatar-bero-lg {
            border-radius: 50%;
            overflow: hidden;
            background: #e8f5e9;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .avatar-bero img,
        .avatar-bero-sm img,
        .avatar-bero-lg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .avatar-bero {
            width: 42px;
            height: 42px;
        }

        .avatar-bero-sm {
            width: 32px;
            height: 32px;
        }

        .avatar-bero-lg {
            width: 72px;
            height: 72px;
        }

        /* ========= Bubble ========= */
        .bubble {
            max-width: 720px;
            padding: 11px 15px;
            border-radius: 14px;
            font-size: 14.5px;
            line-height: 1.6;
            word-wrap: break-word;
        }

        .bubble-user {
            background: #2e7d32;
            color: #fff;
            border-bottom-right-radius: 4px;
            white-space: pre-wrap;
        }

        .bubble-bero {
            background: #fff;
            color: #1f2937;
            border: 1px solid #e9ecef;
            border-bottom-left-radius: 4px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .02);
        }

        .bubble-bero p:last-child {
            margin-bottom: 0;
        }

        .bubble-bero ul,
        .bubble-bero ol {
            padding-left: 1.2rem;
            margin-bottom: .5rem;
        }

        .bubble-bero li {
            margin-bottom: .15rem;
        }

        .bubble-bero code {
            background: #f1f3f5;
            padding: 1px 5px;
            border-radius: 4px;
            font-size: .85em;
        }

        .bubble-bero pre {
            background: #f1f3f5;
            padding: 10px;
            border-radius: 6px;
            overflow-x: auto;
        }

        .bubble-bero strong {
            font-weight: 600;
        }

        .bubble-bero h1,
        .bubble-bero h2,
        .bubble-bero h3 {
            font-size: 1rem;
            font-weight: 600;
            margin-top: .5rem;
            margin-bottom: .35rem;
        }

        /* Typing */
        .typing {
            display: inline-flex;
            gap: 4px;
            align-items: center;
            padding: 3px 0;
        }

        .typing span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #9ca3af;
            animation: blink 1.2s infinite ease-in-out;
        }

        .typing span:nth-child(2) {
            animation-delay: .2s;
        }

        .typing span:nth-child(3) {
            animation-delay: .4s;
        }

        @keyframes blink {

            0%,
            60%,
            100% {
                opacity: .3;
            }

            30% {
                opacity: 1;
            }
        }

        /* Responsive */
        @media (max-width: 900px) {
            .bero-sidebar {
                display: none;
            }

            .bero-messages {
                padding: 20px;
            }

            .topbar-title-form {
                display: none !important;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dompurify@3/dist/purify.min.js"></script>
    <script>
        const chatBox = document.getElementById('chat-box');
        const form = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const removeImg = document.getElementById('removeImg');
        const sendBtn = document.getElementById('sendBtn');
        const emptyState = document.getElementById('emptyState');

        const BERO_IMG = "{{ asset('assets/images/bero.png') }}";

        marked.setOptions({ breaks: true, gfm: true });
        const renderMarkdown = (t) => DOMPurify.sanitize(marked.parse(t || ''));

        const scrollBottom = () => chatBox.scrollTop = chatBox.scrollHeight;
        scrollBottom();

        document.querySelectorAll('.bubble-bero[data-markdown]').forEach(el => {
            el.innerHTML = renderMarkdown(el.dataset.markdown);
        });

        document.querySelectorAll('.suggestion-chip').forEach(btn => {
            btn.addEventListener('click', () => {
                messageInput.value = btn.textContent.trim();
                messageInput.focus();
            });
        });

        imageInput.addEventListener('change', e => {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = ev => {
                previewImg.src = ev.target.result;
                imagePreview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        });

        removeImg.addEventListener('click', () => {
            imageInput.value = '';
            imagePreview.classList.add('d-none');
        });

        messageInput.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 160) + 'px';
        });

        messageInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                form.requestSubmit();
            }
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const text = messageInput.value.trim();
            const file = imageInput.files[0];
            if (!text && !file) return;

            if (emptyState) emptyState.style.display = 'none';

            appendUserMessage(text || '[Gambar]', file);
            messageInput.value = '';
            messageInput.style.height = 'auto';
            imageInput.value = '';
            imagePreview.classList.add('d-none');

            const loading = appendLoading();
            sendBtn.disabled = true;

            const formData = new FormData();
            if (text) formData.append('message', text);
            if (file) formData.append('image', file);

            try {
                const res = await fetch("{{ route('user.ai-chat-messages.store', $session->session_id) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const raw = await res.text();
                let data;
                try { data = JSON.parse(raw); }
                catch {
                    loading.remove();
                    appendBotMessage('Server tidak balas JSON (HTTP ' + res.status + ').');
                    return;
                }

                loading.remove();

                if (data.bot_message?.message) {
                    appendBotMessage(data.bot_message.message);
                } else if (data.error) {
                    appendBotMessage('⚠️ ' + data.error);
                } else if (data.message) {
                    appendBotMessage('⚠️ ' + data.message);
                } else {
                    appendBotMessage('Maaf, ada gangguan. Coba lagi ya.');
                }
            } catch (err) {
                console.error(err);
                loading.remove();
                appendBotMessage('Koneksi error. Coba lagi.');
            } finally {
                sendBtn.disabled = false;
                messageInput.focus();
            }
        });

        function appendUserMessage(text, file) {
            const wrap = document.createElement('div');
            wrap.className = 'd-flex mb-4 justify-content-end';
            let content = '';
            if (file) {
                const url = URL.createObjectURL(file);
                content += `<img src="${url}" style="max-height:180px;border-radius:8px;" class="mb-1"><br>`;
            }
            content += escapeHtml(text);
            wrap.innerHTML = `<div class="bubble bubble-user">${content}</div>`;
            chatBox.appendChild(wrap);
            scrollBottom();
        }

        function appendBotMessage(text) {
            const wrap = document.createElement('div');
            wrap.className = 'd-flex mb-4 justify-content-start';
            wrap.innerHTML = `
                    <div class="avatar-bero-sm me-3">
                        <img src="${BERO_IMG}" alt="Bero">
                    </div>
                    <div class="bubble bubble-bero">${renderMarkdown(text)}</div>
                `;
            chatBox.appendChild(wrap);
            scrollBottom();
        }

        function appendLoading() {
            const wrap = document.createElement('div');
            wrap.className = 'd-flex mb-4 justify-content-start';
            wrap.innerHTML = `
                    <div class="avatar-bero-sm me-3">
                        <img src="${BERO_IMG}" alt="Bero">
                    </div>
                    <div class="bubble bubble-bero">
                        <span class="typing">
                            <span></span><span></span><span></span>
                        </span>
                    </div>
                `;
            chatBox.appendChild(wrap);
            scrollBottom();
            return wrap;
        }

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.innerText = str;
            return div.innerHTML;
        }
    </script>
@endsection