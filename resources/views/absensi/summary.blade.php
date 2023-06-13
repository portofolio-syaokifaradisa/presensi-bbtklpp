@extends('templates.report')

@section('content')
    <table class="border-table">
        <tr>
            <th class="text-center" style="width: 30px">No</th>
            <th class="text-center" style="auto">Pegawai</th>
            <th class="text-center" style="auto">NIP</th>
            <th class="text-center" style="width: 55px">Hadir</th>
            <th class="text-center" style="width: 55px">Izin</th>
            <th class="text-center" style="width: 55px">Cuti</th>
            <th class="text-center" style="width: 55px">Dinas<br>Luar</th>
            <th class="text-center" style="width: 55px">Total</th>
        </tr>
        @foreach ($absensi as $data)
            <tr>
                <td class="text-center align-middle">{{ $loop->index + 1 }}</td>
                <td class="align-middle">{{ $data['name'] }}</td>
                <td class="align-middle">{{ $data['nip'] }}</td>
                <td class="text-center align-middle">{{ $data['Hadir'] ?? 0 }}</td>
                <td class="text-center align-middle">{{ $data['Izin'] ?? 0 }}</td>
                <td class="text-center align-middle">{{ $data['Cuti'] ?? 0 }}</td>
                <td class="text-center align-middle">{{ $data['Dinas Luar'] ?? 0 }}</td>
                <td class="text-center align-middle">
                    {{ ($data['Hadir'] ?? 0) + ($data['Izin'] ?? 0) + ($data['Cuti'] ?? 0) + ($data['Dinas Luar'] ?? 0) }}
                </td>
            </tr>
        @endforeach
    </table>
@endsection