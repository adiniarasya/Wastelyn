@extends('template.layout')

@section('title', 'Bero Asistent')

@section('content')
    <div class="bero-wrap">
        <aside class="bero-side">
            <form action="{{ route('user.ai-chat-sessions.store') }}" method="POST" class="bero-side-top">
                @csrf
                <button class="bero-new-btn">
                    <i class="bi bi-plus-lg"></i>
                    <span>Chat Baru</span>
                </button>
            </form>

            <div class="bero-side-label">Riwayat Chat</div>

            <div class="bero-side-list">
                @forelse($sessions as $s)
                    <a href="{{ route('user.ai-chat-sessions.show', $s->session_id) }}" class="bero-side-item">
                        <i class="bi bi-chat bero-side-item-icon"></i>
                        <span class="bero-side-item-title">{{ $s->title ?: 'Percakapan baru' }}</span>
                    </a>
                @empty
                    <div class="bero-side-empty">Belum ada chat</div>
                @endforelse
            </div>
        </aside>
        <main class="bero-main">
            @if(session('success'))
                <div class="bero-alert bero-alert-ok">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bero-alert bero-alert-err">{{ session('error') }}</div>
            @endif

            <div class="bero-hero">
                <div class="bero-hero-avatar">
                    <img src="{{ asset('assets/images/bero.png') }}" alt="Bero">
                </div>

                <h4 class="bero-hero-title">Halo, aku Bero!</h4>
                <p class="bero-hero-sub">
                    Teman ngobrolmu soal sampah &amp; bank sampah.<br>
                    Mau mulai dari mana?
                </p>

                <div class="bero-chips">
                    <button class="bero-chip">Misi hari ini</button>
                    <button class="bero-chip">Bank sampah terdekat</button>
                    <button class="bero-chip">Tips kelola sampah</button>
                </div>

                <p class="bero-hint">
                    Klik <strong>+ Chat Baru</strong> di kiri untuk memulai percakapan.
                </p>
            </div>
        </main>

    </div>

    <style>
        .bero-wrap {
            display: flex !important;
            min-height: calc(100vh - 80px) !important;
            background: #ffffff !important;
            font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            color: #0f172a !important;
            box-sizing: border-box !important;
            -webkit-font-smoothing: antialiased !important;
        }

        .bero-wrap * {
            box-sizing: border-box !important;
        }

        .bero-side {
            width: 280px !important;
            flex-shrink: 0 !important;
            padding: 24px 16px !important;
            border-right: 1px solid #eef2f7 !important;
            background: #fbfcfd !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .bero-side-top {
            margin-bottom: 24px !important;
        }

        .bero-new-btn {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 7px !important;
            width: 100% !important;
            padding: 11px 16px !important;
            background: #16a34a !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 10px !important;
            font-size: 13.5px !important;
            font-weight: 600 !important;
            line-height: 1 !important;
            cursor: pointer !important;
            white-space: nowrap !important;
            box-shadow: 0 1px 3px rgba(22, 163, 74, .2) !important;
            transition: background .15s ease, box-shadow .15s ease !important;
        }

        .bero-new-btn:hover {
            background: #15803d !important;
            box-shadow: 0 4px 14px rgba(22, 163, 74, .28) !important;
        }

        .bero-side-label {
            font-size: 11px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: .1em !important;
            color: #94a3b8 !important;
            padding: 0 8px !important;
            margin-bottom: 10px !important;
        }

        .bero-side-list {
            flex: 1 !important;
            overflow-y: auto !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 2px !important;
        }

        .bero-side-item {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            padding: 9px 12px !important;
            border-radius: 8px !important;
            text-decoration: none !important;
            color: #334155 !important;
            font-size: 13.5px !important;
            font-weight: 500 !important;
            transition: background .12s ease, color .12s ease !important;
            overflow: hidden !important;
        }

        .bero-side-item:hover {
            background: #f1f5f9 !important;
            color: #0f172a !important;
            text-decoration: none !important;
        }

        .bero-side-item-icon {
            font-size: 14px !important;
            color: #94a3b8 !important;
            flex-shrink: 0 !important;
            transition: color .12s ease !important;
        }

        .bero-side-item:hover .bero-side-item-icon {
            color: #16a34a !important;
        }

        .bero-side-item-title {
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
        }

        .bero-side-empty {
            font-size: 12.5px !important;
            color: #94a3b8 !important;
            padding: 8px 12px !important;
            font-style: italic !important;
        }

        .bero-main {
            flex: 1 !important;
            padding: 40px 48px !important;
            display: flex !important;
            flex-direction: column !important;
            min-width: 0 !important;
            background: #ffffff !important;
        }

        .bero-alert {
            padding: 12px 16px !important;
            border-radius: 10px !important;
            font-size: 13.5px !important;
            line-height: 1.5 !important;
            margin-bottom: 20px !important;
        }

        .bero-alert-ok {
            background: #f0fdf4 !important;
            color: #15803d !important;
            border: 1px solid #bbf7d0 !important;
        }

        .bero-alert-err {
            background: #fef2f2 !important;
            color: #b91c1c !important;
            border: 1px solid #fecaca !important;
        }

        .bero-hero {
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            padding: 40px 20px !important;
        }

        .bero-hero-avatar {
            width: 96px !important;
            height: 96px !important;
            border-radius: 50% !important;
            overflow: hidden !important;
            background: #f0fdf4 !important;
            margin: 0 auto 22px !important;
            border: 3px solid #dcfce7 !important;
            box-shadow: 0 10px 30px rgba(22, 163, 74, .15) !important;
        }

        .bero-hero-avatar img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            display: block !important;
        }

        .bero-hero-title {
            font-size: 22px !important;
            font-weight: 700 !important;
            color: #16a34a !important;
            margin: 0 0 10px !important;
            letter-spacing: -.02em !important;
        }

        .bero-hero-sub {
            font-size: 14.5px !important;
            color: #64748b !important;
            line-height: 1.65 !important;
            margin: 0 0 28px !important;
        }

        .bero-chips {
            display: flex !important;
            flex-wrap: wrap !important;
            justify-content: center !important;
            gap: 10px !important;
            margin-bottom: 22px !important;
        }

        .bero-chip {
            padding: 9px 18px !important;
            background: #ffffff !important;
            color: #16a34a !important;
            border: 1.5px solid #bbf7d0 !important;
            border-radius: 999px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            line-height: 1 !important;
            cursor: pointer !important;
            transition: background .15s ease, border-color .15s ease, transform .15s ease, box-shadow .15s ease !important;
        }

        .bero-chip:hover {
            background: #f0fdf4 !important;
            border-color: #16a34a !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(22, 163, 74, .12) !important;
        }

        .bero-hint {
            font-size: 12.5px !important;
            color: #94a3b8 !important;
            margin: 0 !important;
        }

        .bero-hint strong {
            color: #64748b !important;
            font-weight: 600 !important;
        }

        @media (max-width: 768px) {
            .bero-wrap {
                flex-direction: column !important;
            }

            .bero-side {
                width: 100% !important;
                border-right: none !important;
                border-bottom: 1px solid #eef2f7 !important;
                padding: 16px !important;
                max-height: 220px !important;
            }

            .bero-side-list {
                max-height: 140px !important;
            }

            .bero-main {
                padding: 32px 20px !important;
            }

            .bero-hero-avatar {
                width: 80px !important;
                height: 80px !important;
            }

            .bero-hero-title {
                font-size: 19px !important;
            }
        }
    </style>
@endsection