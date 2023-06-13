@extends('templates.report')

@section('content')
<table class="border-table">
    <tr>
        <th class="text-center" style="width: 10px">No</th>
        <th class="text-center">Pegawai</th>
        <th class="text-center">Identitas</th>
        <th class="text-center">Jabatan & Pangkat</th>
        @if($role == "superadmin")
            <th class="text-center">Role</th>  
        @endif
    </tr>
    @foreach ($accounts as $index => $account)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>
                {{ $account->name . " " . $account->gelar }} <br>
                NIP. {{ $account->nip }} <br>
                {{ $account->email }}
            </td>
            <td>
                NIK. {{ $account->nik }} <br>
                TTL. {{ $account->tanggal_lahir }} <br>
                Pendidikan. {{ $account->pendidikan }} <br>
                Alamat. {{ $account->pendidikan }}
            </td>
            <td>
                {{ $account->jabatan->nama . " kelas " . $account->jabatan->kelas }} <br>
                {{ "Golongan " . $account->pangkat->golongan }}
            </td>
            @if($role == "superadmin")
                <td class="text-center">
                    {{ $account->role == "user" ? "Pegawai" : "Admin" }}
                </td>  
            @endif
        </tr>
    @endforeach
</table>
@endsection