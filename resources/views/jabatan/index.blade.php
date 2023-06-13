@extends('templates.app')

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success mb-2">{{ Session::get('success') }}</div>
    @endif
    <div class="card">
        <div class="card-header row">
            <h4 class="col">Tabel Jabatan</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <a href="{{ route('jabatan.create') }}" class="btn btn-primary float-right">
                        <i class="fas fa-plus mr-1"></i> Tambah Jabatan
                    </a>
                </div>
            </div>
            <div class="table-responsive pt-3">
                <div class="table-responsive w-100">
                    <table id="datatable" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 30px">No</th>
                                <th class="text-center" style="width: 30px">Aksi</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Kelas</th>
                            </tr>
                        </thead>
                        <tbody></tfoot>
                        <tfoot>
                            <tr>
                                <th id="no">No</th>
                                <th id="action">Aksi</th>
                                <th id="nama">Nama</th>
                                <th id="kelas">Kelas</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-extends')
    <script src="{{ asset('js/jabatan.js') }}"></script>
@endsection