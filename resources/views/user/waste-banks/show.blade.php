@extends('template.layout')

@section('title', 'Detail Bank Sampah')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="bi bi-shop"></i> Detail Bank Sampah</h3>
        <a href="{{ route('user.waste-banks.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if(auth()->user()->preferred_bank_id == $wasteBank->bank_id)
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-star-fill fs-5"></i>
            <div>
                <strong>Ini bank sampah utamamu.</strong>
                Semua pickup request akan diarahkan ke bank sampah ini.
            </div>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card card-stat h-100">
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="180">Nama</th>
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
                            <td>
                                @if($wasteBank->status === 'active')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($wasteBank->status === 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    @if(auth()->user()->preferred_bank_id != $wasteBank->bank_id && $wasteBank->status === 'active')
                        <button type="button" class="btn btn-success w-100 mt-3"
                            onclick="pilihBank({{ $wasteBank->bank_id }}, '{{ addslashes($wasteBank->name) }}')">
                            <i class="bi bi-star"></i> Jadikan Bank Sampah Utamaku
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-stat h-100">
                <div class="card-body p-2">
                    <div id="map" style="height: 100%; min-height: 320px; width: 100%; border-radius: .5rem;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const marker = {
            name: @json($wasteBank->name),
            address: @json($wasteBank->address),
            phone: @json($wasteBank->phone),
            hours: @json($wasteBank->opening_hours),
            lat: {{ $wasteBank->latitude !== null ? (float) $wasteBank->latitude : 'null' }},
            lng: {{ $wasteBank->longitude !== null ? (float) $wasteBank->longitude : 'null' }},
        };

        function initMap() {
            const mapEl = document.getElementById('map');

            if (marker.lat === null || marker.lng === null) {
                mapEl.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-muted">Lokasi belum tersedia.</div>';
                return;
            }

            const map = new google.maps.Map(mapEl, {
                center: { lat: marker.lat, lng: marker.lng },
                zoom: 15,
                mapTypeControl: false,
                streetViewControl: false,
            });

            const markerObj = new google.maps.Marker({
                position: { lat: marker.lat, lng: marker.lng },
                map,
                title: marker.name,
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="min-width:180px;font-family:Roboto,sans-serif;font-size:14px;">
                        <strong>${marker.name}</strong><br>
                        <small class="text-muted">${marker.address ?? ''}</small><br>
                        ${marker.phone ? '📞 ' + marker.phone + '<br>' : ''}
                        ${marker.hours ? '🕐 ' + marker.hours : ''}
                    </div>
                `,
            });

            infoWindow.open(map, markerObj);

            markerObj.addListener('click', () => {
                infoWindow.open(map, markerObj);
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
                    window.location.href = '{{ route('user.waste-banks.index') }}';
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