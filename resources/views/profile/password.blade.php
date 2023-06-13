@extends('templates.app')

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success mb-2">{{ Session::get('success') }}</div>
    @elseif(Session::has('error'))
        <div class="alert alert-danger mb-2">{{ Session::get('error') }}</div>
    @endif
    <div class="card">
        <div class="card-header row">
            <h4 class="col">Ubah Password</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update-password') }}">
                @csrf
                @method('PUT')

                <div class="card-body px-4 py-0">
                    <div class="row">
                        <div class="form-group col">
                            <label for="old_password">Password Lama</label>
                            <input id="old_password" type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" value="{{ old('old_password') ?? '' }}">
                            @error('old_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col">
                            <label for="new_password">Password</label>
                            <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" value="{{ old('new_password') ?? '' }}">
                            <small class="text-danger">
                                * Password Harus Terdiri Minimal 8 Karakter
                            </small>
                            @error('new_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col">
                            <label for="c_password">Konfirmasi Password Baru</label>
                            <input id="c_password" type="password" class="form-control @error('c_password') is-invalid @enderror" name="c_password" value="{{ old('c_password') ?? '' }}">
                            <small class="text-danger">
                                * Konfirmasi Password Harus Sama Dengan Password
                            </small>
                            @error('c_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary px-3" type="submit">
                        <i class="fas fa-save mr-1"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection