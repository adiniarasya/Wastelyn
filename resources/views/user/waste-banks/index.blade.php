@extends('template.layout')

@section('title', 'Bank Sampah')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="bi bi-shop"></i> Daftar Bank Sampah</h3>
        <a href="{{ route('user.pickup-requests.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if(!auth()->user()->preferred_bank_id)
        <div class="alert alert-info d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-info-circle fs-5"></i>
            <div>
                <strong>Pilih bank sampah tujuanmu.</strong>
                Klik salah satu marker di peta, lalu klik tombol <em>Pilih Bank Sampah Ini</em>.
            </div>
        </div>
    @else
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-check-circle fs-5"></i>
            <div>
                Bank sampah utamamu:
                <strong>{{ optional($wasteBanks->firstWhere('bank_id', auth()->user()->preferred_bank_id))->name ?? '-' }}</strong>
            </div>
        </div>
    @endif

    <div class="card card-stat mb-4">
        <div class="card-body p-2">
            <div id="map" style="height: 400px; width: 100%; border-radius: .5rem;"></div>
        </div>
    </div>

    <div class="row g-3">
        @forelse ($wasteBanks as $bank)
            <div class="col-md-4">
                <div
                    class="card card-stat h-100 {{ auth()->user()->preferred_bank_id == $bank->bank_id ? 'border-success border-2' : '' }}">
                    <div class="card-body">
                        @if(auth()->user()->preferred_bank_id == $bank->bank_id)
                            <span class="badge bg-success mb-2">
                                <i class="bi bi-star-fill"></i> Bank Sampah Utamamu
                            </span>
                        @endif

                        <h5 class="card-title mb-2">{{ $bank->name }}</h5>

                        <p class="text-muted mb-1">
                            <i class="bi bi-geo-alt"></i> {{ $bank->address ?? '-' }}
                        </p>
                        @if($bank->phone)
                            <p class="text-muted mb-1">
                                <i class="bi bi-telephone"></i> {{ $bank->phone }}
                            </p>
                        @endif
                        @if($bank->opening_hours)
                            <p class="text-muted mb-0">
                                <i class="bi bi-clock"></i> {{ $bank->opening_hours }}
                            </p>
                        @endif

                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('user.waste-banks.show', $bank) }}" class="btn btn-sm btn-outline-success">
                                Lihat Detail
                            </a>

                            @if(auth()->user()->preferred_bank_id != $bank->bank_id)
                                <button type="button" class="btn btn-sm btn-success"
                                    onclick="pilihBank({{ $bank->bank_id }}, '{{ addslashes($bank->name) }}')">
                                    Pilih
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Belum ada bank sampah terdaftar.
                </div>
            </div>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const banks = @json($mapMarkers);
        const preferredBankId = {{ auth()->user()->preferred_bank_id ?? 'null' }};
        let mapInstance = null;
        let infoWindowInstance = null;
        let markersById = {};

        function initMap() {
            const mapEl = document.getElementById('map');

            if (!banks.length) {
                mapEl.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-muted">Belum ada data koordinat bank sampah.</div>';
                return;
            }

            mapInstance = new google.maps.Map(mapEl, {
                center: { lat: banks[0].lat, lng: banks[0].lng },
                zoom: 12,
                mapTypeControl: false,
                streetViewControl: false,
            });

            infoWindowInstance = new google.maps.InfoWindow();
            const bounds = new google.maps.LatLngBounds();

            banks.forEach(b => {
                const isPreferred = preferredBankId && b.bank_id == preferredBankId;

                const marker = new google.maps.Marker({
                    position: { lat: b.lat, lng: b.lng },
                    map: mapInstance,
                    title: b.name,
                    icon: isPreferred ? 'https://maps.google.com/mapfiles/ms/icons/green-dot.png' : undefined,
                    zIndex: isPreferred ? 999 : 1,
                });

                markersById[b.bank_id] = marker;

                marker.addListener('click', () => {
                    tampilkanInfoBank(b, isPreferred);
                });

                bounds.extend(marker.getPosition());
            });

            if (banks.length > 1) {
                mapInstance.fitBounds(bounds);
            }
        }

        function tampilkanInfoBank(bank, isPreferred) {
            const lat = bank.lat;
            const lng = bank.lng;

            mapInstance.panTo({ lat, lng });
            if (mapInstance.getZoom() < 15) {
                mapInstance.setZoom(16);
            }

            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: { lat, lng } }, (results, status) => {
                let alamat = bank.address || '';
                if (!alamat && status === 'OK' && results[0]) {
                    alamat = results[0].formatted_address;
                }
                if (!alamat) {
                    alamat = 'Lokasi di sekitar ' + lat.toFixed(4) + ', ' + lng.toFixed(4);
                }

                const escapedNama = (bank.name || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');

                let tombolHtml = '';
                if (isPreferred) {
                    tombolHtml = '<div style="color:#2E7D32;margin-top:8px;font-weight:600;">✓ Bank sampah utamamu</div>';
                } else {
                    tombolHtml =
                        '<button type="button" ' +
                        'onclick="pilihBank(' + bank.bank_id + ', \'' + escapedNama + '\')" ' +
                        'style="background-color:#2E7D32;color:white;border:none;padding:10px 12px;' +
                        'border-radius:6px;cursor:pointer;width:100%;margin-top:8px;font-weight:600;">' +
                        'Pilih Bank Sampah Ini</button>';
                }

                const content =
                    '<div style="font-family:Roboto,sans-serif;font-size:14px;max-width:280px;">' +
                    '<h3 style="margin:0 0 8px;font-size:15px;">' + bank.name + '</h3>' +
                    '<p style="margin:4px 0;"><strong>Alamat:</strong><br>' + alamat + '</p>' +
                    '<p style="margin:4px 0;"><strong>Koordinat:</strong><br>' +
                    'Lat: ' + lat.toFixed(6) + '<br>' +
                    'Lng: ' + lng.toFixed(6) + '</p>' +
                    (bank.phone ? '<p style="margin:4px 0;">📞 ' + bank.phone + '</p>' : '') +
                    (bank.hours ? '<p style="margin:4px 0;">🕐 ' + bank.hours + '</p>' : '') +
                    tombolHtml +
                    '</div>';

                infoWindowInstance.setContent(content);
                infoWindowInstance.open(mapInstance, markersById[bank.bank_id]);
            });
        }

        window.pilihBank = async function (bankId, bankName) {
            const result = await Swal.fire({
                title: 'Pilih Bank Sampah?',
                html: 'Jadikan <strong>' + (bankName || 'bank sampah ini') + '</strong> sebagai bank sampah utamamu?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Pilih',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#2E7D32',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
            });

            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading(),
            });

            try {
                const res = await fetch('{{ route('user.waste-banks.set-preferred') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ bank_id: bankId }),
                });

                const data = await res.json();

                if (data.ok) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Bank sampah berhasil dipilih.',
                        timer: 1200,
                        showConfirmButton: false,
                    });
                    window.location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.error || 'Terjadi kesalahan',
                    });
                }
            } catch (err) {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal terhubung',
                    text: 'Periksa koneksi internetmu.',
                });
            }
        };
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap&loading=async"
        async defer>
        </script>
@endpush