@extends('template.layout')

@section('content')
    <div class="container">
        <h1>Edit Jenis Sampah</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.waste-categories.update', $wasteCategory->category_id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $wasteCategory->name) }}"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control"
                    rows="3">{{ old('description', $wasteCategory->description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Harga per Kg <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="price_per_kg" class="form-control"
                        value="{{ old('price_per_kg', $wasteCategory->price_per_kg) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Reward per Kg</label>
                    <input type="number" step="1" name="reward_per_kg" class="form-control"
                        value="{{ old('reward_per_kg', $wasteCategory->reward_per_kg) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Poin per Kg <span class="text-danger">*</span></label>
                    <input type="number" step="1" name="point_per_kg" class="form-control"
                        value="{{ old('point_per_kg', $wasteCategory->point_per_kg) }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">CO2 Saved per Kg</label>
                    <input type="number" step="0.001" name="co2_saved_per_kg" class="form-control"
                        value="{{ old('co2_saved_per_kg', $wasteCategory->co2_saved_per_kg) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Foto / Icon</label>
                    <input type="file" name="icon" class="form-control" accept="image/*" id="iconInput">
                    <small class="text-muted">
                        Biarkan kosong jika tidak ingin mengganti foto.
                        jpg, jpeg, png, svg. Maks 2MB.
                    </small>

                    {{-- preview: tampilkan icon lama, atau preview baru kalau user pilih --}}
                    <div class="mt-2">
                        @if($wasteCategory->icon)
                            <img id="preview" src="{{ asset('storage/' . $wasteCategory->icon) }}" width="100"
                                style="object-fit:cover; border-radius:6px;" alt="{{ $wasteCategory->name }}">
                        @else
                            <img id="preview" src="" class="d-none" width="100" style="object-fit:cover; border-radius:6px;">
                        @endif
                    </div>
                </div>
            </div>

            <button class="btn btn-success">Update</button>
            <a href="{{ route('admin.waste-categories.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <script>
        document.getElementById('iconInput').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const img = document.getElementById('preview');
            img.src = URL.createObjectURL(file);
            img.classList.remove('d-none');
        });
    </script>
@endsection