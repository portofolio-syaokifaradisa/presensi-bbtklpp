@extends('templates.app')

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success mb-2">{{ Session::get('success') }}</div>
    @elseif(Session::has('error'))
        <div class="alert alert-danger mb-2">{{ Session::get('error') }}</div>
    @endif
    <div class="card">
        <div class="card-header row">
            <h4 class="col">Form Tambah Jabatan</h4>
        </div>
        <div class="card-body">
            <form action="{{ URLHelper::has('edit') ? route('jabatan.update', ['id' => $jabatan->id]) : route('jabatan.store') }}" method="post">
                @csrf
                @if(URLHelper::has('edit'))
                    @method('PUT')
                @endif
                
                <div class="row">
                    <div class="form-group col">
                        <label><b>Nama Jabatan</b></label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama') ?? $jabatan->nama ?? '' }}">
                        @error('nama')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col">
                        <label><b>Kelas</b></label>
                        <input type="text" class="form-control @error('kelas') is-invalid @enderror" name="kelas" value="{{ old('kelas') ?? $jabatan->kelas ?? '' }}">
                        @error('kelas')
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