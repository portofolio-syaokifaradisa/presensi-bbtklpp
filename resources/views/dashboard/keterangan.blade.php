@extends('templates.app')

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success mb-2">{{ Session::get('success') }}</div>
    @endif
    <div class="card">
        <div class="card-header row">
            <h4 class="col">Keterangan</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('keterangan.store', ['type' => $type]) }}" method="post">
                @csrf
                
                <div class="row">
                    <div class="form-group col">
                        <label><b>Tanggal Mulai {{ $type }}</b></label>
                        <input type="date" class="form-control" name="mulai" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group col">
                        <label><b>Tanggal Selesai {{ $type }}</b></label>
                        <input type="date" class="form-control" name="selesai" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label><b>keterangan {{ $type }}</b></label>
                    <textarea class="form-control" name="keterangan"></textarea>
                </div>

                <button type="submit" class="btn btn-primary float-right">
                    <i class="fas fa-paper-plane mr-1"></i> Kirim
                </button>
            </form>
        </div>
    </div>
@endsection