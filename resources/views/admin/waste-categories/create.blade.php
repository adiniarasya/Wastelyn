@extends('template.layout')

@section('content')
    <div class="container">
        <h1>Tambah Jenis Sampah</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.waste-categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Harga per Kg <span class="text-danger">*</span></label>
                    <input type="number" name="price_per_kg" class="form-control" value="{{ old('price_per_kg') }}"
                        required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Reward per Kg</label>
                    <input type="number" name="reward_per_kg" class="form-control" value="{{ old('reward_per_kg') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Poin per Kg <span class="text-danger">*</span></label>
                    <input type="number" name="point_per_kg" class="form-control" value="{{ old('point_per_kg') }}"
                        required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">CO2 Saved per Kg</label>
                    <input type="number" step="0.01" name="co2_saved_per_kg" class="form-control"
                        value="{{ old('co2_saved_per_kg') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Foto / Icon</label>
                    <input type="file" name="icon" class="form-control" accept="image/*">
                    <small class="text-muted">jpg, jpeg, png, svg. Maks 2MB.</small>
                    <img id="preview" src="" class="mt-2 d-none" width="80" style="object-fit:cover; border-radius:6px;">
                </div>
            </div>

            <button class="btn btn-success">Simpan</button>
            <a href="{{ route('admin.waste-categories.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <script>
        document.querySelector('input[name="icon"]').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const img = document.getElementById('preview');
            img.src = URL.createObjectURL(file);
            img.classList.remove('d-none');
        });
    </script>
@endsection