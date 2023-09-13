@extends('layout.english_layout.eservice')

@section('content')
    <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
        <tr>
            <td style="width: 8%;">
                &nbsp;
            </td>
            <td style="width: 5%;">
                &nbsp;
            </td>
            <td style="width: 2%;">
                &nbsp;
            </td>
            <td style="width: 23%;">
                &nbsp;
            </td>
            <td style="width: 10%;">
                &nbsp;
            </td>
            <td style="width: 2%;">
                &nbsp;
            </td>
            <td style="width: 50%;">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="4">
                &nbsp;
            </td>
            <td style="text-align: left; vertical-align: top;">
                Ruj. Kami
            </td>
            <td style="text-align: center; vertical-align: top;">
                :
            </td>
            <td>
                {{ !empty($order->bill_no) ? $order->bill_no : '' }}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                &nbsp;
            </td>
            <td style="text-align: left; vertical-align: top;">
                Tarikh
            </td>
            <td style="text-align: center; vertical-align: top;">
                :
            </td>
            <td>
                {{ !empty($order->date) ? \Helper\Helper::localizedDate($order->date) : '' }}
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7">
                <strong>
                    KEPADA PIHAK PENGURUSAN
                </strong>
            </td>
        </tr>
        <tr>
            <td colspan="7">
                {{ isset($content['owner_address1']) ? $content['owner_address1'] : '' }}
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7">
                Tuan/Puan,
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <strong>
                    ADUAN MENGENGENAI ANJING/ KUCING LIAR DI DALAM SKIM STRATA
                </strong>
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7">
                Dengan hormatnya saya merujuk kepada perkara di atas.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">2.</span>Pentadbiran ini telah menerima aduan daripada pemilik mengenai
                anjing/ kucing liar di dalam skim pemajuan pihak tuan sehingga menyebabkan kegusaran kepada penduduk.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">3.</span>Mengenai masalah anjing/ kucing liar di dalam skim strata, pihak
                tuan dinasihatkan supaya mengambil tindakan terus dengan memindahkan haiwan tersebut ke pusat perlindungan
                haiwan terbiar. Pihak tuan juga boleh mendapatkan bantuan dan khidmat nasihat daripada Jabatan Kesihatan dan
                Persekitaran [Unit Vektor] MBPJ untuk tindakan selanjutnya. Pihak tuan juga boleh menghubungi jabatan
                berkenaan di talian 03-79552381 sekiranya terdapat sebarang pertanyaan berhubung perkara tersebut.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7">
                Kerjasama daripada pihak tuan amat dihargai.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7">
                Sekian, terima kasih.
            </td>
        </tr>

        @include('eservice.mbpj.component.signature_tag_3', ['sk' => true])

    </table>
@endsection
