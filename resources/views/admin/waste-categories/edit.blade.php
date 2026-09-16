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

        <form action="{{ route('waste-categories.update', $wasteCategory->category_id) }}" method="POST">
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
                    <input type="number" name="price_per_kg" class="form-control"
                        value="{{ old('price_per_kg', $wasteCategory->price_per_kg) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Reward per Kg</label>
                    <input type="number" name="reward_per_kg" class="form-control"
                        value="{{ old('reward_per_kg', $wasteCategory->reward_per_kg) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Poin per Kg <span class="text-danger">*</span></label>
                    <input type="number" name="point_per_kg" class="form-control"
                        value="{{ old('point_per_kg', $wasteCategory->point_per_kg) }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">CO2 Saved per Kg</label>
                    <input type="number" step="0.01" name="co2_saved_per_kg" class="form-control"
                        value="{{ old('co2_saved_per_kg', $wasteCategory->co2_saved_per_kg) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Icon (nama file)</label>
                    <input type="text" name="icon" class="form-control" value="{{ old('icon', $wasteCategory->icon) }}">
                </div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', $wasteCategory->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Aktif</label>
            </div>

            <button class="btn btn-success">Update</button>
            <a href="{{ route('waste-categories.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection