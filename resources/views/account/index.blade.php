@extends('templates.app')

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success mb-2">{{ Session::get('success') }}</div>
    @endif
    <div class="card">
        <div class="card-header row">
            <h4 class="col">Tabel Akun Pegawai</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <div class="col">
                        <a class="btn btn-outline-primary float-left" href="#" id="print">
                            <i class="fas fa-print mr-2"></i> Laporan
                        </a>
                    </div>
                </div>
                <div class="col">
                    <a href="{{ route('account.create') }}" class="btn btn-primary float-right">
                        <i class="fas fa-plus mr-1"></i> Tambah Akun
                    </a>
                </div>
            </div>
            <div class="table-responsive pt-3">
                <div class="table-responsive w-100">
                    <table id="datatable" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Aksi</th>
                                <th class="text-center">Pegawai</th>
                                <th class="text-center">Identitas</th>
                                <th class="text-center">Jabatan | Pangkat</th>
                                <th class="text-center">Status</th>
                                @if(Auth::user()->role == "superadmin")
                                    <th class="text-center">Role</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody></tfoot>
                        <tfoot>
                            <tr>
                                <th id="no">No</th>
                                <th id="action">Aksi</th>
                                <th id="name">Nama</th>
                                <th id="identitas">Identitas</th>
                                <th id="jabatan">Jabatan | Pangkat</th>
                                <th>
                                    <div class="form-group mb-0 pr-4" style="width: 100%;">
                                        <select class="form-control select2 category-select" name="status" id="status-form">
                                            <option value="SEMUA">Semua</option>
                                            @foreach ($status as $data)
                                                <option value="{{ $data->id }}">
                                                    {{ $data->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </th>
                                @if(Auth::user()->role == "superadmin")
                                    <th id="role">Role</th>
                                @endif
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-extends')
    @if(Auth::user()->role == "superadmin")
        <script src="{{ asset('js/admin-account.js') }}"></script>
    @else
        <script src="{{ asset('js/account.js') }}"></script>
    @endif
@endsection