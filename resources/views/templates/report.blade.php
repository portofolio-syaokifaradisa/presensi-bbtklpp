<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ $title }}</title>
        <style>
            .border-table{
                border: 1px solid;
                width: 100%;
                border-collapse: collapse;
            }
            .border-table th, .border-table td{
                border: 1px solid;
            }
            .border-table td{
                padding-left:  7px;
                padding-right: 7px;
            }
            .text-center{
                text-align: center;
            }
            .align-middle{
                vertical-align: middle;
            }
            .text-right{
                text-align: right;
            }
        </style>
    </head>
    <body style="text-align: center">
        <table style="width: 100%;">
            <tr>
                <td style="width: 10%;">
                    <img src="{{ asset('img/logo.png') }}" alt="" width="90" height="90">
                </td>
                <td style="width: auto;" class="text-center">
                    <h5 style="margin-bottom: 2px; margin-top: 0px; font-size: 14pt">BALAI BESAR TEKNIK KESEHATAN LINGKUNGAN DAN PENGENDALIAN PENYAKIT</h5>
                    <h5 style="margin-bottom: 2px; margin-top: 0px; font-size: 15pt">KOTA BANJARBARU</h5>
                    <p style="margin-bottom: 2px; margin-top: 0px; font-size: 10pt">Alamat: Jl. H. Mistar Cokrokusumo No.2A, Sungai Besar</p>
                    <p style="margin-bottom: 2px; margin-top: 0px; font-size: 10pt">Kecamatan Banjarbaru Selatan Kode Pos 70714</p>
                </td>
                <td style="width: 10%;"></td>
            </tr>
        </table>
        <hr>
        @if($title)
            <h3 class="text-center" style="margin-bottom: 0px">{{ $title ?? '' }}</h3>
            <h4 class="text-center" style="margin-top: 0px">{{ $subtitle ?? '' }}</h4>
        @endif
        @yield('content')

        <table style="width: 100%; margin-top: 40px">
            <tr>
                <td style="width: 60%"></td>
                <td class="text-center">
                    <b>KEPALA BBTKLPP BANJARBARU</b>
                    <br><br><br><br>
                    <b>
                        <u>PRIAGUNG ADHI BAWONO</u><br>
                        NIP. 196509191988031001
                    </b>
                </td>
            </tr>
        </table>
    </body>
</html>