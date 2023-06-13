@extends('templates.app')

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success mb-2">{{ Session::get('success') }}</div>
    @elseif(Session::has('error'))
        <div class="alert alert-danger mb-2">{{ Session::get('error') }}</div>
    @endif
    <div class="card">
        <div class="card-header row">
            <h4 class="col">Form Tambah Pangkat</h4>
        </div>
        <div class="card-body">
            <form action="{{ URLHelper::has('edit') ? route('pangkat.update', ['id' => $pangkat->id]) : route('pangkat.store') }}" method="post">
                @csrf
                @if(URLHelper::has('edit'))
                    @method('PUT')
                @endif
                
                <div class="row">
                    <div class="form-group col">
                        <label><b>Golongan</b></label>
                        <input type="text" class="form-control @error('golongan') is-invalid @enderror" name="golongan" value="{{ old('golongan') ?? $pangkat->golongan ?? '' }}">
                        @error('golongan')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col">
                        <label><b>TMT</b></label>
                        <input type="date" class="form-control @error('tmt') is-invalid @enderror" name="tmt" value="{{ old('tmt') ?? $pangkat->tmt ?? '' }}">
                        @error('tmt')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary float-right">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </form>
        </div>
    </div>
@endsection