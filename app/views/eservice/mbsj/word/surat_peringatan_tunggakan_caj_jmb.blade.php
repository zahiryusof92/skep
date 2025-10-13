@extends('eservice.mbsj.layout.word')

@section('page1')
    <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
        <tr>
            <td colspan="4" rowspan="2" style="text-align: left; vertical-align: top;">
                <img width="174" height="22"
                    src="{{ asset('assets/common/img/eservice/mbsj/kita_selangor.jpg') }}" />
            </td>
            <td style="text-align: left; vertical-align: top;">
                Ruj. Kami
            </td>
            <td style="text-align: center; vertical-align: top;">
                :
            </td>
            <td style="text-align: left; vertical-align: top;">
                {{ !empty($order->bill_no) ? $order->bill_no : '' }}
            </td>
        </tr>
        <tr>
            <td style="text-align: left; vertical-align: top;">
                Tarikh
            </td>
            <td style="text-align: center; vertical-align: top;">
                :
            </td>
            <td style="text-align: left; text-decoration: underline; vertical-align: top;">
                {{ !empty($order->hijri_date) ? $order->hijri_date : '' }}
            </td>
        </tr>
        <tr>
            <td colspan="6">
                &nbsp;
            </td>
            <td style="text-align: left; vertical-align: top;">
                {{ !empty($order->date) ? \Helper\Helper::localizedDate($order->date) : '' }}
            </td>
        </tr>
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
            <td style="width: 28%;">
                &nbsp;
            </td>
            <td style="width: 15%;">
                &nbsp;
            </td>
            <td style="width: 2%;">
                &nbsp;
            </td>
            <td style="width: 40%;">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7">
                <span style="text-transform: uppercase; font-weight:bold;">
                    {{ isset($content['owner_name']) ? $content['owner_name'] . '<br/>' : '' }}
                </span>
                {{ isset($content['owner_address1']) && !empty($content['owner_address1']) ? $content['owner_address1'] . '<br/>' : '' }}
                {{ isset($content['owner_address2']) && !empty($content['owner_address2']) ? $content['owner_address2'] . '<br/>' : '' }}
                {{ isset($content['owner_address3']) && !empty($content['owner_address3']) ? $content['owner_address3'] . '<br/>' : '' }}
                {{ isset($content['owner_address4']) && !empty($content['owner_address4']) ? $content['owner_address4'] . '<br/>' : '' }}
                {{ isset($content['owner_postcode']) ? $content['owner_postcode'] : '' }}
                {{ isset($content['owner_city']) ? $content['owner_city'] . '<br/>' : '' }}
                <span style="text-transform: uppercase;">
                    {{ isset($content['owner_state']) ? $content['owner_state'] : '' }}
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7">
                Tuan,
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-transform: uppercase; font-weight: bold; text-align: justify;">
                MAKLUMAN SUPAYA MENJELASKAN CAJ DAN CARUMAN KEPADA KUMPULAN WANG PENJELAS
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-transform: uppercase; font-weight: bold; text-align: justify;">
                - UNIT NO
                {{ isset($content['owner_unit_no']) ? $content['owner_unit_no'] . ', ' : '' }}
                {{ isset($content['building_name']) ? $content['building_name'] : '' }}
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7">
                Dengan hormatnya saya diarah merujuk kepada perkara tersebut di atas.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">2.</span>
                Dimaklumkan bahawa adalah menjadi tanggungjawab kesemua pemilik unit-unit harta di
                <span
                    style="text-transform: uppercase; font-weight: bold;">{{ isset($content['building_name']) ? $content['building_name'] : '' }}</span>
                untuk menjelaskan kesemua yuran penyenggaraan bangunan tanpa gagal. Ini adalah kerana pihak tuan/puan
                berkongsi dalam penggunaan harta
                bersama khususnya dari aspek menyelenggara bangunan dari segi kebersihan, keselamatan dan lain-lain termasuk
                pembayaran insurans kebakaran. Semangat kerjasama adalah diperlukan bagi menentukan tempat tinggal tuan/puan
                dapat diselenggarakan dengan baik.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">3.</span>
                Mengikut Seksyen 25(1), Akta Pengurusan Strata 2013 (Akta 757), pembeli hendaklah membayar caj bagi
                penyenggaraan dan pengurusan harta bersama. Kegagalan tuan/puan menjelaskan yuran penyenggaraan tersebut
                akan membolehkan tindakan mahkamah di bawah Seksyen 34(2), ataupun penahanan harta alih di bawah Seksyen 35,
                Akta 757 diambil selanjutnya oleh pihak pengurusan.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">4.</span>
                Pihak tuan diingatkan bahawa menurut Seksyen 34(3), Akta 757:
                <span style="font-weight:bold;">“Mana-mana pembeli atau pemunya petak yang, tanpa alasan yang munasabah,
                    tidak mematuhi notis yang disebut dalam subseksyen (1) melakukan suatu kesalahan dan boleh, apabila
                    disabitkan, DIDENDA tidak melebihi LIMA RIBU RINGGIT atau DIPENJARAKAN selama tempoh tidak melebihi TIGA
                    TAHUN atau KEDUA-DUANYA, dan dalam hal suatu kesalahan berterusan, DIDENDA selanjutnya tidak melebihi
                    LIMA PULUH RINGGIT BAGI TIAP-TIAP HARI atau sebahagiannya kesalahan itu berterusan selepas
                    disabitkan”.</span>
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">5.</span>
                Untuk makluman, pihak pengurusan bangunan akan memulakan tindakan di bawah Seksyen 34 & 35, Akta 757 ke atas
                tuan yang mempunyai tunggakan caj dan caruman kepada kumpulan wang penjelas sehingga
                <span
                    style="font-weight:bold;">{{ isset($content['date_overdue']) ? \Helper\Helper::getDueDate($content['date_overdue']) : '' }}
                    berjumlah RM
                    {{ isset($content['total_overdue']) ? number_format($content['total_overdue'], 2) : '0.00' }}</span>.
                Sehubungan dengan itu, tuan adalah diminta menjelaskan tunggakan tuan
                <span style="text-transform: uppercase; font-weight:bold;">dalam tempoh 14 hari</span> dari tarikh notis ini
                sebelum sebarang tindakan diambil di bawah peruntukan tersebut.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
    </table>
@endsection

@section('page2')
    <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">6.</span>
                Sila ambil perhatian, tunggakan pembayaran hendaklah dijelaskan terus kepada akaun pihak pengurusan
                <span
                    style="text-transform: uppercase; font-weight:bold;">{{ isset($content['management_name']) ? $content['management_name'] : '' }}</span>
                dan sekiranya pihak tuan mempunyai sebarang pertanyaan/masalah berkenaan dengan pembayaran tersebut, sila
                hubungi pihak pengurusan bangunan di talian <span
                    style="font-weight:bold;">{{ isset($content['management_phone']) ? $content['management_phone'] : '' }}</span>.
            </td>
        </tr>
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
            <td style="width: 28%;">
                &nbsp;
            </td>
            <td style="width: 15%;">
                &nbsp;
            </td>
            <td style="width: 2%;">
                &nbsp;
            </td>
            <td style="width: 40%;">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7">
                Sekian, harap maklum.
            </td>
        </tr>

        @include('eservice.mbsj.component.signature_tag_2')

    </table>

    @include('eservice.mbsj.component.sk_2')
@endsection
