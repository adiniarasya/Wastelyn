@extends('template.layout')

@section('title', 'Setup Awal - WasteLyn')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        .onboarding-wrapper {
            min-height: calc(100vh - 120px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%);
            border-radius: 12px;
        }

        .onboarding-modal {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 900px;
            width: 100%;
            padding: 32px;
            animation: slideUp 0.4s ease-out;
            max-height: 94vh;
            overflow-y: auto;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .onboarding-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #E8F5E9, #C8E6C9);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .onboarding-icon i {
            font-size: 34px;
            color: #2E7D32;
        }

        .step-badge {
            display: inline-block;
            background: #E8F5E9;
            color: #2E7D32;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .onboarding-modal .form-control,
        .onboarding-modal .form-select {
            border-radius: 12px;
            padding: 12px 16px;
            border: 2px solid #E0E0E0;
        }

        .onboarding-modal .form-control:focus,
        .onboarding-modal .form-select:focus {
            border-color: #2E7D32;
            box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.15);
        }

        .onboarding-modal .btn-success {
            background: linear-gradient(135deg, #2E7D32, #4CAF50);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
        }

        .onboarding-modal .btn-success:hover {
            background: linear-gradient(135deg, #1B5E20, #388E3C);
        }

        .onboarding-modal .btn-success:disabled {
            background: #9ca3af;
        }

        #map {
            height: 300px;
            border-radius: 12px;
            overflow: hidden;
        }

        .mitra-card {
            border: 2px solid #E0E0E0;
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: .15s;
        }

        .mitra-card:hover {
            border-color: #4CAF50;
            background: #F1F8E9;
        }

        .mitra-card.selected {
            border-color: #2E7D32;
            background: #E8F5E9;
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.15);
        }

        .mitra-card h6 {
            margin: 0;
            font-weight: 600;
            font-size: 14px;
        }

        .mitra-card p {
            margin: 4px 0 0;
            font-size: 12px;
            color: #6b7280;
        }

        .mitra-card .jarak {
            display: inline-block;
            margin-top: 6px;
            font-size: 12px;
            color: #2E7D32;
            font-weight: 600;
        }

        .list-scroll {
            max-height: 300px;
            overflow-y: auto;
            padding-right: 4px;
        }

        @media (max-width: 768px) {
            .onboarding-modal {
                padding: 24px 20px;
            }

            #map {
                height: 220px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="onboarding-wrapper">
        <div class="onboarding-modal">

            <div class="text-center mb-4">
                <div class="onboarding-icon">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <div class="step-badge">LANGKAH 1 DARI 1</div>
                <h3 class="fw-bold mb-2">Selamat Datang! 🌱</h3>
                <p class="text-muted mb-0">
                    Pilih bank sampah terdekat. Semua misi, reward, dan pengiriman kamu akan terhubung ke sana.
                </p>
            </div>

            <form action="{{ route('user.onboarding.store') }}" method="POST" id="onboardingForm">
                @csrf
                <input type="hidden" name="waste_bank_id" id="wasteBankId" value="{{ old('waste_bank_id') }}">

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-semibold mb-0">
                                <i class="bi bi-map me-1"></i> Peta Bank Sampah
                            </label>
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="detectLocation()"
                                id="detectBtn">
                                <i class="bi bi-crosshair"></i> Lokasi Saya
                            </button>
                        </div>

                        <div id="map" class="mb-3"></div>

                        <div id="listPanel" class="list-scroll">
                            <p class="text-muted small mb-0">Memuat bank sampah…</p>
                        </div>

                        @error('waste_bank_id')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Lengkap</label>
                            <textarea name="address" rows="4" class="form-control @error('address') is-invalid @enderror"
                                placeholder="Jl. Contoh No. 123, Depok" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Bank Sampah Terpilih</label>
                            <div id="selectedInfo" class="border rounded-3 p-3 bg-light text-muted small">
                                Belum ada bank sampah dipilih.
                            </div>
                        </div>

                        <div class="alert alert-success small mb-3">
                            💡 Bank sampah ini akan jadi langganan kamu. Bisa diganti kapan saja di profil.
                        </div>

                        <button type="submit" class="btn btn-success w-100" id="submitBtn" disabled>
                            <i class="bi bi-check-circle me-1"></i> Mulai Sekarang
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const mitras = @json($mitrasJson);

        let map, markers = [], selectedId = null, userMarker = null;

        map = L.map('map').setView([-2.5, 118], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        mitras.forEach(m => {
            const marker = L.marker([m.latitude, m.longitude])
                .addTo(map)
                .bindPopup(`<b>${m.name}</b><br>${m.address ?? ''}`);
            marker.on('click', () => selectMitra(m.bank_id));
            markers.push({ id: m.bank_id, marker, data: m });
        });

        if (markers.length) {
            const group = L.featureGroup(markers.map(m => m.marker));
            map.fitBounds(group.getBounds().pad(0.2));
        } else {
            document.getElementById('listPanel').innerHTML = `
                    <div class="alert alert-warning small mb-0">
                        <i class="bi bi-exclamation-triangle"></i>
                        Belum ada bank sampah terdaftar. Hubungi admin.
                    </div>
                `;
        }

        renderList(mitras);

        document.addEventListener('DOMContentLoaded', () => {
            detectLocation(true);
        });

        function detectLocation(silent = false) {
            if (!navigator.geolocation) {
                if (!silent) alert('Browser kamu tidak mendukung deteksi lokasi.');
                return;
            }

            const btn = document.getElementById('detectBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mendeteksi…';

            navigator.geolocation.getCurrentPosition(
                pos => fetchNearby(pos.coords.latitude, pos.coords.longitude),
                err => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-crosshair"></i> Lokasi Saya';
                    if (!silent) alert('Gagal deteksi lokasi. Pilih manual dari daftar.');
                },
                { enableHighAccuracy: true, timeout: 8000 }
            );
        }

        async function fetchNearby(lat, lng) {
            try {
                const url = `{{ route('user.onboarding.nearby') }}?lat=${lat}&lng=${lng}`;
                const res = await fetch(url, {
                    headers: { 'Accept': 'application/json' }
                });
                const json = await res.json();

                if (json.success) {
                    renderList(json.data);
                    addUserMarker(lat, lng);
                }
            } catch (e) {
                console.error(e);
                renderList(mitras);
            } finally {
                const btn = document.getElementById('detectBtn');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-crosshair"></i> Lokasi Saya';
            }
        }

        function addUserMarker(lat, lng) {
            if (userMarker) map.removeLayer(userMarker);
            userMarker = L.circleMarker([lat, lng], {
                radius: 8, color: '#2563eb', fillColor: '#3b82f6', fillOpacity: 1
            }).addTo(map).bindPopup('Lokasi kamu');
            map.setView([lat, lng], 13);
        }

        function renderList(list) {
            const panel = document.getElementById('listPanel');

            if (!list.length) {
                panel.innerHTML = '<p class="text-muted small mb-0">Belum ada bank sampah terdaftar.</p>';
                return;
            }

            panel.innerHTML = list.map(m => {
                const id = m.bank_id;
                const name = m.name;
                const addr = m.address ?? '-';
                const jarak = m.jarak_km ? `📍 ${m.jarak_km} km` : '';
                const selected = selectedId == id ? 'selected' : '';

                return `
                        <div class="mitra-card ${selected}" data-id="${id}" onclick="selectMitra(${id})">
                            <h6>${name}</h6>
                            <p>${addr}</p>
                            ${jarak ? `<span class="jarak">${jarak}</span>` : ''}
                        </div>
                    `;
            }).join('');
        }

        function selectMitra(id) {
            selectedId = id;
            document.getElementById('wasteBankId').value = id;
            document.getElementById('submitBtn').disabled = false;

            document.querySelectorAll('.mitra-card').forEach(el => {
                el.classList.toggle('selected', Number(el.dataset.id) === id);
            });

            const found = markers.find(m => m.id === id);
            const info = document.getElementById('selectedInfo');

            if (found) {
                info.classList.remove('text-muted', 'bg-light');
                info.classList.add('bg-success-subtle');
                info.innerHTML = `
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            <div>
                                <strong>${found.data.name}</strong><br>
                                <span class="text-muted small">${found.data.address ?? ''}</span>
                            </div>
                        </div>
                    `;
                map.setView(found.marker.getLatLng(), 15);
                found.marker.openPopup();
            }
        }

        document.getElementById('onboardingForm').addEventListener('submit', e => {
            if (!selectedId) {
                e.preventDefault();
                alert('Pilih bank sampah terlebih dahulu.');
            }
        });
    </script>
@endpush