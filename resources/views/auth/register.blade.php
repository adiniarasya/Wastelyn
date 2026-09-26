<x-guest-layout>

    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold" style="color:#1a2330;">
            Buat Akun Baru
        </h2>
        <p class="mt-2 text-sm" style="color:#6b7a8c;">
            Mulai perjalanan hijau kamu bersama WasteLyn
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
            <div class="text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4" id="register-form">
        @csrf

        <div>
            <label for="name" class="block mb-2 text-sm font-medium" style="color:#1a2330;">
                Nama Lengkap
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                placeholder="Masukkan nama lengkap"
                class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition"
                style="border-color:#e6e9ee;color:#1a2330;background:#fff;">
            @error('name')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block mb-2 text-sm font-medium" style="color:#1a2330;">
                Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                placeholder="nama@email.com" class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition"
                style="border-color:#e6e9ee;color:#1a2330;background:#fff;">
            @error('email')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block mb-2 text-sm font-medium" style="color:#1a2330;">
                Daftar Sebagai
            </label>

            <div class="grid grid-cols-2 gap-3">
                <label class="relative cursor-pointer">
                    <input type="radio" name="role" value="warga" class="peer sr-only" {{ old('role') === 'mitra' ? '' : 'checked' }}>
                    <div class="p-3 border-2 border-gray-200 rounded-xl transition-all
                                peer-checked:border-green-700 peer-checked:bg-green-50
                                hover:border-green-300">
                        <div class="flex items-center gap-2">
                            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-green-100">
                                <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 21v-2a4 4 0 014-4h4a4 4 0 014 4v2M7 11a4 4 0 100-8 4 4 0 000 8z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold" style="color:#1a2330;">Warga</p>
                                <p class="text-xs" style="color:#6b7a8c;">Pengguna umum</p>
                            </div>
                        </div>
                    </div>
                </label>

                <label class="relative cursor-pointer">
                    <input type="radio" name="role" value="mitra" class="peer sr-only" {{ old('role') === 'mitra' ? 'checked' : '' }}>
                    <div class="p-3 border-2 border-gray-200 rounded-xl transition-all
                                peer-checked:border-green-700 peer-checked:bg-green-50
                                hover:border-green-300">
                        <div class="flex items-center gap-2">
                            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-green-100">
                                <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold" style="color:#1a2330;">Mitra</p>
                                <p class="text-xs" style="color:#6b7a8c;">Partner layanan</p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>

            <div class="flex items-start gap-2 mt-3 p-2.5 rounded-lg" style="background:#f6faf6;">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color:#6b7a8c;" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 100-20 10 10 0 000 20z" />
                </svg>
                <p class="text-xs leading-relaxed" style="color:#6b7a8c;">
                    Akun Mitra memerlukan persetujuan Admin sebelum dapat digunakan.
                </p>
            </div>

            @error('role')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div id="mitra-fields" class="space-y-4 {{ old('role') === 'mitra' ? '' : 'hidden' }}">
            <div>
                <label for="phone" class="block mb-2 text-sm font-medium" style="color:#1a2330;">
                    No. Telepon
                </label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx"
                    class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition"
                    style="border-color:#e6e9ee;color:#1a2330;background:#fff;">
                @error('phone')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="address" class="block mb-2 text-sm font-medium" style="color:#1a2330;">
                    Alamat Bank Sampah
                </label>
                <input id="address" type="text" name="address" value="{{ old('address') }}"
                    placeholder="Ketik alamat, atau cari di kotak pencarian peta"
                    class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition"
                    style="border-color:#e6e9ee;color:#1a2330;background:#fff;">
                <p class="mt-1 text-xs" style="color:#6b7a8c;">
                    Ketik di kotak pencarian peta di bawah, atau klik langsung di peta untuk pilih manual.
                </p>
                @error('address')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium" style="color:#1a2330;">
                    Titik Lokasi di Peta
                </label>

                <div style="position: relative; border-radius: .75rem; overflow: hidden; border: 1px solid #e6e9ee;">
                    <div id="search-container"
                        style="position: absolute; top: 10px; left: 50%; transform: translateX(-50%); z-index: 5; width: 90%; max-width: 400px;">
                    </div>
                    <div id="map" style="height: 380px; width: 100%;"></div>
                </div>

                <div class="mt-2 flex items-center justify-between gap-2">
                    <p class="text-xs" style="color:#6b7a8c;">
                        Koordinat: <span id="coords-display" class="font-mono">-</span>
                    </p>
                    <p class="text-xs text-right" style="color:#6b7a8c;">
                        <span id="alamat-terpilih">-</span>
                    </p>
                </div>

                @error('latitude')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
        </div>

        <div>
            <label for="password" class="block mb-2 text-sm font-medium" style="color:#1a2330;">
                Password
            </label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                placeholder="Masukkan password"
                class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition"
                style="border-color:#e6e9ee;color:#1a2330;background:#fff;">
            @error('password')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block mb-2 text-sm font-medium" style="color:#1a2330;">
                Konfirmasi Password
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                autocomplete="new-password" placeholder="Ulangi password"
                class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition"
                style="border-color:#e6e9ee;color:#1a2330;background:#fff;">
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full rounded-xl px-4 py-3 text-sm font-semibold text-white transition"
                style="background:#2E7D32;border:0;">
                Buat Akun
            </button>
        </div>

        <div class="text-center pt-2">
            <span class="text-sm" style="color:#6b7a8c;">Sudah punya akun?</span>
            <a href="{{ route('login') }}" class="text-sm font-semibold ml-1" style="color:#2E7D32;">
                Masuk sekarang
            </a>
        </div>

    </form>

    <style>
        gmp-place-autocomplete {
            width: 100%;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }
    </style>

    <script>
        let mapInstance = null;
        let markerInstance = null;
        let geocoder = null;
        let mapInitialized = false;
        let mapsLoadRetry = null;

        function toggleMitraFields() {
            const selected = document.querySelector('input[name="role"]:checked');
            const isMitra = selected && selected.value === 'mitra';
            const mitraFields = document.getElementById('mitra-fields');
            const phoneEl = document.getElementById('phone');
            const addressEl = document.getElementById('address');
            const latEl = document.getElementById('latitude');
            const lngEl = document.getElementById('longitude');
            const coordsEl = document.getElementById('coords-display');
            const alamatEl = document.getElementById('alamat-terpilih');

            if (isMitra) {
                mitraFields.classList.remove('hidden');
                if (phoneEl) phoneEl.required = true;
                if (addressEl) addressEl.required = true;
                ensureMapInit();
            } else {
                mitraFields.classList.add('hidden');
                if (phoneEl) phoneEl.required = false;
                if (addressEl) addressEl.required = false;
                if (latEl) latEl.value = '';
                if (lngEl) lngEl.value = '';
                if (coordsEl) coordsEl.textContent = '-';
                if (alamatEl) alamatEl.textContent = '-';
            }
        }

        function ensureMapInit() {
            if (mapInitialized) return;
            if (window.googleMapsReady) {
                initMapPicker();
                return;
            }
            if (mapsLoadRetry) return;
            let tries = 0;
            mapsLoadRetry = setInterval(() => {
                tries++;
                if (window.googleMapsReady) {
                    clearInterval(mapsLoadRetry);
                    mapsLoadRetry = null;
                    initMapPicker();
                } else if (tries > 33) {
                    clearInterval(mapsLoadRetry);
                    mapsLoadRetry = null;
                    console.warn('Google Maps gagal dimuat setelah 10 detik.');
                }
            }, 300);
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('input[name="role"]').forEach(r => {
                r.addEventListener('change', toggleMitraFields);
            });
            toggleMitraFields();
        });

        function onGoogleMapsReady() {
            window.googleMapsReady = true;
            geocoder = new google.maps.Geocoder();

            const selected = document.querySelector('input[name="role"]:checked');
            if (selected && selected.value === 'mitra') {
                initMapPicker();
            }
        }

        function initMapPicker() {
            if (mapInitialized || !window.googleMapsReady) return;
            const mapEl = document.getElementById('map');
            if (!mapEl) return;

            mapInitialized = true;

            const oldLat = parseFloat(document.getElementById('latitude').value);
            const oldLng = parseFloat(document.getElementById('longitude').value);
            const hasOld = !isNaN(oldLat) && !isNaN(oldLng);
            const defaultCenter = hasOld ? { lat: oldLat, lng: oldLng } : { lat: -6.1754, lng: 106.8272 };

            mapInstance = new google.maps.Map(mapEl, {
                center: defaultCenter,
                zoom: hasOld ? 16 : 13,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: false,
            });

            markerInstance = new google.maps.Marker({
                position: defaultCenter,
                map: mapInstance,
                draggable: true,
                title: 'Lokasi Bank Sampah',
            });

            if (hasOld) {
                setCoords(oldLat, oldLng);
                updateAlamatDisplay();
            }

            markerInstance.addListener('dragend', (e) => {
                const lat = e.latLng.lat();
                const lng = e.latLng.lng();
                setCoords(lat, lng);
                updateAlamatDisplay();
            });

            mapInstance.addListener('click', (e) => {
                const lat = e.latLng.lat();
                const lng = e.latLng.lng();
                markerInstance.setPosition({ lat, lng });
                setCoords(lat, lng);
                updateAlamatDisplay();
            });

            try {
                const placeAutocomplete = new google.maps.places.PlaceAutocompleteElement();
                document.getElementById('search-container').appendChild(placeAutocomplete);

                placeAutocomplete.addEventListener('gmp-select', async ({ placePrediction }) => {
                    const place = placePrediction.toPlace();
                    await place.fetchFields({
                        fields: ['location', 'formattedAddress', 'displayName']
                    });

                    if (!place.location) {
                        alert('Tidak ada detail lokasi yang tersedia.');
                        return;
                    }

                    const lat = place.location.lat();
                    const lng = place.location.lng();

                    mapInstance.setCenter(place.location);
                    mapInstance.setZoom(17);
                    markerInstance.setPosition(place.location);
                    markerInstance.setVisible(true);

                    setCoords(lat, lng);

                    const alamat = place.formattedAddress || place.displayName || '';
                    document.getElementById('address').value = alamat;
                    document.getElementById('alamat-terpilih').textContent = alamat;
                });
            } catch (err) {
                console.warn('PlaceAutocompleteElement tidak tersedia:', err);
            }

            if (navigator.geolocation && !hasOld) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        mapInstance.setCenter(pos);
                        mapInstance.setZoom(16);
                        markerInstance.setPosition(pos);
                        setCoords(pos.lat, pos.lng);
                        updateAlamatDisplay();
                    },
                    () => console.warn('Akses lokasi ditolak. Menggunakan lokasi cadangan.')
                );
            }
        }

        function updateAlamatDisplay() {
            const pos = markerInstance.getPosition();
            const lat = pos.lat();
            const lng = pos.lng();

            const alamatDariInput = document.getElementById('address').value.trim();

            geocoder.geocode({ location: pos }, (results, status) => {
                let alamat = '';

                if (alamatDariInput && alamatDariInput.length > 5) {
                    alamat = alamatDariInput;
                } else if (status === 'OK' && results[0]) {
                    alamat = results[0].formatted_address;
                    const addressField = document.getElementById('address');
                    if (!addressField.value.trim()) {
                        addressField.value = alamat;
                    }
                } else {
                    alamat = 'Lokasi di sekitar ' + lat.toFixed(4) + ', ' + lng.toFixed(4);
                }

                document.getElementById('alamat-terpilih').textContent = alamat;

                console.log('Geocoder status:', status);
                if (status !== 'OK') {
                    console.warn('Reverse geocode gagal:', status, results);
                }
            });
        }

        function setCoords(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);
            document.getElementById('coords-display').textContent =
                lat.toFixed(6) + ', ' + lng.toFixed(6);
        }
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=onGoogleMapsReady&v=weekly"
        async defer>
        </script>

</x-guest-layout>