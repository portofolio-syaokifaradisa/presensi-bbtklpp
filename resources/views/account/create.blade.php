@extends('templates.app')

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success mb-2">{{ Session::get('success') }}</div>
    @elseif(Session::has('error'))
        <div class="alert alert-danger mb-2">{{ Session::get('error') }}</div>
    @endif
    <div class="card">
        <div class="card-header row">
            <h4 class="col">Form Tambah Akun</h4>
        </div>
        <div class="card-body">
            <form action="{{ URLHelper::has('edit') ? route('account.update', ['id' => $account->id]) : route('account.store') }}" method="post">
                @csrf
                @if(URLHelper::has('edit'))
                    @method('PUT')
                @endif
                
                <div class="row">
                    <div class="form-group col">
                        <label><b>Nama Lengkap</b></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $account->name ?? '' }}">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col">
                        <label><b>NIP</b></label>
                        <input type="text" class="form-control @error('nip') is-invalid @enderror" name="nip" value="{{ old('nip') ?? $account->nip ?? '' }}">
                        @error('nip')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col">
                        <label><b>NIK</b></label>
                        <input type="text" class="form-control @error('nik') is-invalid @enderror" name="nik" value="{{ old('nik') ?? $account->nik ?? '' }}">
                        @error('nik')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label><b>Pendidikan Terakhir</b></label>
                        <input type="text" class="form-control @error('pendidikan') is-invalid @enderror" name="pendidikan" value="{{ old('pendidikan') ?? $account->pendidikan ?? '' }}">
                        @error('pendidikan')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col">
                        <label><b>Gelar</b></label>
                        <input type="text" class="form-control @error('gelar') is-invalid @enderror" name="gelar" value="{{ old('gelar') ?? $account->gelar ?? '' }}">
                        @error('gelar')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col">
                        <label><b>Tanggal Lahir</b></label>
                        <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" name="tanggal_lahir" value="{{ old('tanggal_lahir') ?? $account->tanggal_lahir ?? '' }}">
                        @error('tanggal_lahir')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col" style="width: 100%;">
                        <label><b>Jabatan</b></label>
                        <select class="form-control select2 category-select @error('jabatan_id') is-invalid @enderror" name="jabatan_id">
                            <option value="" selected hidden>Pilih Jabatan</option>
                            @foreach ($jabatan as $data)
                                <option value="{{ $data->id }}" @if(($account->jabatan_id ?? '') == $data->id)
                                    selected
                                @endif>
                                    {{ $data->nama . " Kelas " . $data->kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('jabatan_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col" style="width: 100%;">
                        <label><b>Pangkat</b></label>
                        <select class="form-control select2 category-select @error('pangkat_id') is-invalid @enderror" name="pangkat_id">
                            <option value="" selected hidden>Pilih Pangkat</option>
                            @foreach ($pangkat as $data)
                                <option value="{{ $data->id }}" @if(($account->pangkat_id ?? '') == $data->id)
                                    selected
                                @endif>
                                    {{ "Golongan " . $data->golongan }}
                                </option>
                            @endforeach
                        </select>
                        @error('pangkat_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col" style="width: 100%;">
                        <label><b>Status</b></label>
                        <select class="form-control select2 category-select @error('status_id') is-invalid @enderror" name="status_id">
                            <option value="" selected hidden>Pilih Status</option>
                            @foreach ($status as $data)
                                <option value="{{ $data->id }}" @if(($account->status_id ?? '') == $data->id)
                                    selected
                                @endif>
                                    {{ $data->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('status_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label><b>Email</b></label>
                        <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') ?? $account->email ?? '' }}">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col">
                        <label><b>Alamat</b></label>
                        <input type="text" class="form-control @error('alamat') is-invalid @enderror" name="alamat" value="{{ old('alamat') ?? $account->alamat ?? '' }}">
                        @error('alamat')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    @if(Auth::user()->role == "superadmin")
                    <div class="form-group col" style="width: 100%;">
                        <label><b>Role</b></label>
                        <select class="form-control select2 category-select" name="role">
                            <option value="user" selected>Pegawai</option>
                            <option value="admin" @if(($account->role ?? '') == "admin")
                                selected
                            @endif>Admin</option>
                        </select>
                    </div>
                @endif
                </div>
                <div class="row">
                    @if(URLHelper::has('edit'))
                        <div class="form-group col">
                            <label><b>Password Lama</b></label>
                            <input type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" value="{{ old('old_password') ?? '' }}">
                            @error('old_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    @endif
                    <div class="form-group col">
                        <label><b>Password @if(URLHelper::has('edit'))
                            Baru
                        @endif</b></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') ?? '' }}">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group col">
                        <label><b>Konfirmasi Password @if(URLHelper::has('edit'))
                            Baru
                        @endif</b></label>
                        <input type="password" class="form-control @error('c_password') is-invalid @enderror" name="c_password" value="{{ old('c_password') ?? '' }}">
                        @error('c_password')
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