@extends('templates.app')

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success mb-2">{{ Session::get('success') }}</div>
    @endif
    <div class="card">
        <div class="card-header row">
            <h4 class="col">Tabel Absensi Pegawai</h4>
        </div>
        <div class="card-body">
            <a class="btn btn-outline-primary float-right" href="#" id="print">
                <i class="fas fa-print mr-2"></i> Laporan
            </a>
            <a class="btn btn-outline-primary float-right mr-2" href="" id="print-late">
                <i class="fas fa-print mr-2"></i> Laporan Telat
            </a>
            <a class="btn btn-outline-primary float-right mr-2" href="#" id="summary">
                <i class="fas fa-print mr-2"></i> Ringkasan
            </a>
            <div class="table-responsive pt-3">
                <div class="table-responsive w-100">
                    <table id="datatable" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Pegawai</th>
                                <th class="text-center">Hari</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Jam Masuk</th>
                                <th class="text-center">Jam Keluar</th>
                                <th class="text-center">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody></tfoot>
                        <tfoot>
                            <tr>
                                <th id="no">No</th>
                                <th id="pegawai">Pegawai</th>
                                <th id="hari">Hari</th>
                                <th id="tanggal">Tanggal</th>
                                <th id="status">Status</th>
                                <th id="masuk">Jam Masuk</th>
                                <th id="keluar">Jam Keluar</th>
                                <th id="keterangan">Keterangan</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-extends')
    <script src="{{ asset('js/absensi.js') }}"></script>
@endsection