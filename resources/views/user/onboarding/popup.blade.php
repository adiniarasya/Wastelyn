@push('styles')
    <style>
        .onboarding-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .onboarding-modal {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            max-width: 960px;
            width: 100%;
            padding: 32px;
            max-height: 94vh;
            overflow-y: auto;
            animation: slideUp 0.4s ease-out;
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
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 16px rgba(46, 125, 50, 0.18);
            margin: 0 auto;
        }

        .onboarding-icon svg {
            display: block;
            width: 34px;
            height: 34px;
        }

        .step-badge {
            display: inline-block;
            background: #E8F5E9;
            color: #2E7D32;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .onboarding-modal .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            border: 2px solid #E0E0E0;
        }

        .onboarding-modal .form-control:focus {
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

        #onboardingMap {
            height: 320px;
            border-radius: 12px;
            overflow: hidden;
            z-index: 1;
            background: #e5e7eb;
        }

        .mitra-card {
            border: 2px solid #E0E0E0;
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all .15s ease;
            position: relative;
            background: #fff;
        }

        .mitra-card:hover {
            border-color: #4CAF50;
            background: #F1F8E9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
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
            padding-right: 70px;
        }

        .mitra-card p {
            margin: 4px 0 0;
            font-size: 12px;
            color: #6b7280;
            line-height: 1.4;
        }

        .mitra-card .jarak {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-top: 6px;
            font-size: 12px;
            color: #2E7D32;
            font-weight: 600;
        }

        .mitra-card .badge-terdekat {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #2E7D32;
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            letter-spacing: 0.3px;
        }

        .list-scroll {
            max-height: 320px;
            overflow-y: auto;
            padding-right: 6px;
        }

        .list-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .list-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .selected-info-box {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 14px;
            background: #f9fafb;
            transition: all .2s;
        }

        .selected-info-box.active {
            border: 2px solid #2E7D32;
            background: #E8F5E9;
        }

        .gm-style-iw-c {
            border-radius: 10px !important;
            padding: 12px !important;
        }

        .gm-style-iw-d {
            overflow: hidden !important;
            padding-right: 4px !important;
        }

        .gm-style-iw-tc::after {
            background: white !important;
        }

        .gm-popup-title {
            font-weight: 600;
            font-size: 14px;
            color: #111827;
            margin-bottom: 2px;
        }

        .gm-popup-address {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.4;
            margin-bottom: 4px;
        }

        .gm-popup-distance {
            font-size: 12px;
            font-weight: 600;
            color: #2E7D32;
        }

        @media (max-width: 768px) {
            .onboarding-modal {
                padding: 24px 20px;
            }

            #onboardingMap {
                height: 220px;
            }

            .list-scroll {
                max-height: 240px;
            }
        }
    </style>
@endpush

<div class="onboarding-overlay" id="onboardingOverlay">
    <div class="onboarding-modal">

        <div class="text-center mb-4">
            <div class="onboarding-icon mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#2E7D32">
                    <path
                        d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z" />
                </svg>
            </div>

            <div class="mb-2">
                <span class="step-badge">LANGKAH 1 DARI 1</span>
            </div>

            <h3 class="fw-bold mb-2">Selamat Datang! </h3>

            <p class="text-muted mb-0 mx-auto" style="max-width: 520px;">
                Pilih bank sampah terdekat. Semua misi, reward, dan pengiriman kamu akan terhubung ke sana.
            </p>
        </div>

        <form action="{{ route('user.onboarding.store') }}" method="POST" id="onboardingForm">
            @csrf
            <input type="hidden" name="waste_bank_id" id="wasteBankId">

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

                    <div id="onboardingMap" class="mb-3"></div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-semibold mb-0">
                            <i class="bi bi-list-ul me-1"></i> Daftar Bank Sampah
                        </label>
                        <small class="text-muted" id="totalBank">0 bank</small>
                    </div>

                    <div id="listPanel" class="list-scroll">
                        <p class="text-muted small mb-0">Memuat peta…</p>
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
                        <div id="selectedInfo" class="selected-info-box text-muted small">
                            <i class="bi bi-info-circle me-1"></i>
                            Belum ada bank sampah dipilih. Klik salah satu kartu di sebelah kiri.
                        </div>
                    </div>

                    <div class="alert alert-success small mb-3 d-flex align-items-start gap-2">
                        <span>💡</span>
                        <span>Bank sampah ini akan jadi langganan kamu. Bisa diganti kapan saja di profil.</span>
                    </div>

                    <button type="submit" class="btn btn-success w-100" id="submitBtn" disabled>
                        <i class="bi bi-check-circle me-1"></i> Mulai Sekarang
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        let obMap = null;
        let bankMarkers = [];
        let userMarker = null;
        let selectedId = null;
        let bankData = [];
        let infoWindow = null;

        function onGoogleMapsReady() {
            const overlay = document.getElementById('onboardingOverlay');
            if (overlay && overlay.parentElement !== document.body) {
                document.body.appendChild(overlay);
            }

            const mapEl = document.getElementById('onboardingMap');
            if (!mapEl) return;

            obMap = new google.maps.Map(mapEl, {
                center: { lat: -6.2, lng: 106.8 },
                zoom: 11,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: false,
                styles: [
                    {
                        featureType: 'poi',
                        elementType: 'labels',
                        stylers: [{ visibility: 'off' }]
                    }
                ]
            });

            infoWindow = new google.maps.InfoWindow();

            document.getElementById('listPanel').innerHTML =
                '<p class="text-muted small mb-0">Klik tombol <strong>"Lokasi Saya"</strong> untuk mencari bank sampah terdekat.</p>';
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('onboardingForm').addEventListener('submit', e => {
                if (!selectedId) {
                    e.preventDefault();
                    alert('Klik "Lokasi Saya" dulu untuk mencari bank sampah terdekat.');
                }
            });
        });

        function detectLocation() {
            if (!navigator.geolocation) {
                alert('Browser kamu tidak mendukung deteksi lokasi.');
                return;
            }

            const btn = document.getElementById('detectBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mendeteksi…';

            navigator.geolocation.getCurrentPosition(
                pos => fetchNearby(pos.coords.latitude, pos.coords.longitude),
                (err) => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-crosshair"></i> Lokasi Saya';
                    let msg = 'Gagal deteksi lokasi.';
                    if (err.code === 1) msg = 'Izin lokasi ditolak. Aktifkan di pengaturan browser.';
                    if (err.code === 2) msg = 'Lokasi tidak tersedia.';
                    if (err.code === 3) msg = 'Deteksi lokasi timeout.';
                    document.getElementById('listPanel').innerHTML =
                        '<p class="text-muted small mb-0">' + msg + '</p>';
                    alert(msg);
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        }

        async function fetchNearby(lat, lng) {
            try {
                const url = `{{ route('user.onboarding.nearby') }}?lat=${lat}&lng=${lng}`;
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const json = await res.json();

                let banks = [];
                if (json.success) {
                    banks = Array.isArray(json.data) ? json.data : [json.data];
                }

                if (banks.length === 0) {
                    document.getElementById('listPanel').innerHTML =
                        '<p class="text-muted small mb-0">' + (json.message || 'Belum ada bank sampah terdekat.') + '</p>';
                    document.getElementById('totalBank').textContent = '0 bank';
                    return;
                }

                banks.sort((a, b) => (a.jarak_km ?? 999) - (b.jarak_km ?? 999));
                bankData = banks;

                setUserMarker(lat, lng);
                drawAllBankMarkers(banks);
                renderBankList(banks);
                autoSelect(banks[0]);

                const bounds = new google.maps.LatLngBounds();
                bounds.extend({ lat: lat, lng: lng });
                banks.forEach(b => bounds.extend({ lat: b.latitude, lng: b.longitude }));
                obMap.fitBounds(bounds, { top: 50, right: 50, bottom: 50, left: 50 });

                autoFillAddress(lat, lng);

            } catch (e) {
                console.error(e);
                document.getElementById('listPanel').innerHTML =
                    '<p class="text-danger small mb-0">Gagal mengambil data. Coba lagi.</p>';
            } finally {
                const btn = document.getElementById('detectBtn');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-crosshair"></i> Lokasi Saya';
            }
        }

        async function autoFillAddress(lat, lng) {
            const textarea = document.querySelector('textarea[name="address"]');
            if (!textarea || textarea.value.trim() !== '') return;

            const originalPlaceholder = textarea.placeholder;
            textarea.placeholder = 'Mengambil alamat…';

            try {
                const url = `{{ route('user.onboarding.reverse-geocode') }}?lat=${lat}&lng=${lng}`;
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const json = await res.json();

                if (json.success && json.address) {
                    textarea.value = json.address;
                }
            } catch (e) {
                console.error('Reverse geocode gagal:', e);
            } finally {
                textarea.placeholder = originalPlaceholder;
            }
        }

        function setUserMarker(lat, lng) {
            if (userMarker) userMarker.setMap(null);

            userMarker = new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: obMap,
                title: 'Lokasi kamu',
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 9,
                    fillColor: '#3b82f6',
                    fillOpacity: 1,
                    strokeColor: '#2563eb',
                    strokeWeight: 3
                },
                zIndex: 999
            });

            userMarker.addListener('click', () => {
                infoWindow.setContent(`
                    <div class="gm-popup-title">📍 Lokasi Kamu</div>
                    <div class="gm-popup-address">Titik lokasi yang terdeteksi dari browser.</div>
                `);
                infoWindow.open(obMap, userMarker);
            });
        }

        function drawAllBankMarkers(banks) {
            bankMarkers.forEach(m => m.setMap(null));
            bankMarkers = [];

            banks.forEach(bank => {
                const marker = new google.maps.Marker({
                    position: { lat: bank.latitude, lng: bank.longitude },
                    map: obMap,
                    title: bank.name,
                    icon: {
                        url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
                    }
                });

                marker.addListener('click', () => selectBank(bank.bank_id, false));

                bankMarkers.push(marker);
                marker._bankId = bank.bank_id;
            });
        }

        function renderBankList(banks) {
            const panel = document.getElementById('listPanel');
            document.getElementById('totalBank').textContent = `${banks.length} bank`;

            panel.innerHTML = banks.map((bank, idx) => {
                const isNearest = idx === 0;
                const jarak = bank.jarak_km
                    ? `<span class="jarak"><i class="bi bi-geo-alt-fill"></i> ${bank.jarak_km} km dari lokasi kamu</span>`
                    : '';

                return `
                    <div class="mitra-card ${isNearest ? 'selected' : ''}"
                         data-id="${bank.bank_id}"
                         onclick="selectBank(${bank.bank_id})">
                        ${isNearest ? '<span class="badge-terdekat">TERDEKAT</span>' : ''}
                        <h6>${bank.name}</h6>
                        <p>${bank.address ?? '-'}</p>
                        ${jarak}
                    </div>
                `;
            }).join('');
        }

        function selectBank(id, panMap = true) {
            const bank = bankData.find(b => Number(b.bank_id) === Number(id));
            if (!bank) return;

            selectedId = bank.bank_id;
            document.getElementById('wasteBankId').value = bank.bank_id;
            document.getElementById('submitBtn').disabled = false;

            document.querySelectorAll('.mitra-card').forEach(el => {
                el.classList.toggle('selected', Number(el.dataset.id) === Number(bank.bank_id));
            });

            const info = document.getElementById('selectedInfo');
            info.classList.add('active');
            info.classList.remove('text-muted');
            info.innerHTML = `
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <div>
                        <strong>${bank.name}</strong><br>
                        <span class="text-muted small">${bank.address ?? ''}</span>
                        ${bank.jarak_km ? `<br><span class="text-success small fw-semibold">📍 ${bank.jarak_km} km</span>` : ''}
                    </div>
                </div>
            `;

            if (panMap) {
                obMap.panTo({ lat: bank.latitude, lng: bank.longitude });
                if (obMap.getZoom() < 14) {
                    obMap.setZoom(15);
                }
            }

            infoWindow.setContent(`
                <div style="min-width:200px;max-width:260px;">
                    <div class="gm-popup-title">${bank.name}</div>
                    <div class="gm-popup-address">${bank.address ?? '-'}</div>
                    ${bank.jarak_km ? `<div class="gm-popup-distance">📍 ${bank.jarak_km} km dari lokasi kamu</div>` : ''}
                </div>
            `);

            const marker = bankMarkers.find(m => Number(m._bankId) === Number(bank.bank_id));
            if (marker) {
                infoWindow.open(obMap, marker);
            }
        }

        function autoSelect(bank) {
            selectBank(bank.bank_id, false);
        }

        window.detectLocation = detectLocation;
        window.selectBank = selectBank;
        window.onGoogleMapsReady = onGoogleMapsReady;
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=onGoogleMapsReady&v=weekly"
        async defer>
        </script>
@endpush