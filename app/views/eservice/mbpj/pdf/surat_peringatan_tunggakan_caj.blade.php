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
        <td style="width: 43%;">
            &nbsp;
        </td>
        <td style="width: 15%;">
            &nbsp;
        </td>
        <td style="width: 2%;">
            &nbsp;
        </td>
        <td style="width: 25%;">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="5" style="text-align: right; vertical-align: top;">
            Ruj. Kami
        </td>
        <td style="text-align: center; vertical-align: top;">
            :
        </td>
        <td>
            {{ (!empty($details->bill_no) ? $details->bill_no : '') }}
        </td>
    </tr>
    <tr>
        <td colspan="5" style="text-align: right; vertical-align: top;">
            Tarikh
        </td>
        <td style="text-align: center; vertical-align: top;">
            :
        </td>
        <td>
            {{ (!empty($details->date) ?
            \Carbon\Carbon::createFromTimestamp(strtotime($details->date))->format('d F Y') : '') }}
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
                {{ (isset($content['owner_name']) ? $content['owner_name'] : '') }}
            </strong>
        </td>
    </tr>
    <tr>
        <td colspan="7">
            {{ (isset($content['owner_address1']) ? $content['owner_address1'] : '') }}
        </td>
    </tr>
    <tr>
        <td colspan="7">
            {{ (isset($content['owner_address2']) ? $content['owner_address2'] : '') }}
        </td>
    </tr>
    <tr>
        <td colspan="7">
            {{ (isset($content['owner_address3']) ? $content['owner_address3'] : '') }}
        </td>
    </tr>
    <tr>
        <td colspan="7">
            {{ (isset($content['owner_postcode']) ? $content['owner_postcode'] : '') }}
            {{ (isset($content['owner_city']) ? $content['owner_city'] : '') }}
        </td>
    </tr>
    <tr>
        <td colspan="4">
            {{ (isset($content['owner_state']) ? $content['owner_state'] : '') }}
        </td>
        <td colspan="3" style="text-align: right;">
            <strong>
                {{ (isset($content['reminder_type']) ? ($content['reminder_type'] == 'first_reminder' ? 'PERINGATAN
                PERTAMA' : 'PERINGATAN KEDUA') : '') }}
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
                TUNGGAKAN CAJ PENYENGGARAAN BANGUNAN DAN LAIN-LAIN CAJ BAGI PEMILIK UNIT DI
                {{ (isset($content['building_name']) ? Str::upper($content['building_name']) : '') }}.
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
            Dengan segala hormatnya saya merujuk kepada perkara di atas.
        </td>
    </tr>
    <tr>
        <td colspan="7">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="7" style="text-align: justify;">
            <span style="padding-right: 1em;">2.</span>Pentadbiran ini dimaklumkan ini dimaklumkan, pihak tuan
            telah mempunyai tunggakan caj penyenggaraan bangunan
            bagi unit <strong>{{ (isset($content['unit_no']) ? $content['unit_no'] : '') }}</strong> sehingga
            <strong>{{ (isset($content['date_overdue']) ?
                \Carbon\Carbon::createFromTimestamp(strtotime($content['date_overdue']))->format('d/m/Y') : '')
                }}</strong>
            berjumlah <strong>RM {{ (isset($content['total_overdue']) ? $content['total_overdue'] : '') }}</strong>
            bagi tempoh pengurusan oleh pihak pengurusan
            <strong>{{ (isset($content['management_name']) ? $content['management_name'] : '') }}</strong>.
        </td>
    </tr>
    <tr>
        <td colspan="7">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="7" style="text-align: justify;">
            <span style="padding-right: 1em;">3.</span>Sehubungan itu, pihak Pesuruhjaya Bangunan ingin mengingatkan
            tuan agar menjelaskan tunggakan caj penyenggaraan dan bayaran lain seperti sinking fund, insuran bangunan,
            cukai tanah, bil air serta sebarang tunggakan kepada pihak pengurusan,
            <strong>{{ (isset($content['management_name']) ? $content['management_name'] : '') }}</strong>
            dengan kadar segera dalam tempoh <strong>14 hari</strong> dari tarikh penyampaian surat ini mengikut
            Seksyen 25, Akta Pengurusan Strata 2013 [Akta 757] :-
        </td>
    </tr>
    <tr>
        <td colspan="7">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-weight: bold; text-decoration: underline;">
            Subsekyen 25(1), Akta 757:-
        </td>
    </tr>
    <tr>
        <td colspan="7">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-weight: bold; text-align: justify; font-style: italic;">
            <span style="padding-right: 2em;">&nbsp;</span>“setiap pembeli hendaklah membayar Caj, dan caruman
            kepada kumpulan wang penjelas, berkenaan
            dengan petaknya kepada badan pengurusan bersama bagi penyenggaraan dan pengurusan bangunan atau tanah
            yang dicadangkan untuk dipecah bahagi kepada petak-petak dan harta bersama di dalam suatu kawasan
            pemajuan.”
        </td>
    </tr>
    <tr>
        <td colspan="7">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-weight: bold; text-decoration: underline;">
            Subsekyen 25(5), Akta 757:-
        </td>
    </tr>
    <tr>
        <td colspan="7">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-weight: bold; text-align: justify; font-style: italic;">
            <span style="padding-right: 2em;">&nbsp;</span>“pemunya petak hendaklah, dalam masa tempoh empat belas
            hari daripada penerimaan notis daripada badan pengurusan bersama,membayar Caj, dan caruman kepada kumpulan
            wang penjelas, kepada badan pengurusan bersama itu.”
        </td>
    </tr>
    <tr>
        <td colspan="7">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="7" style="text-align: justify;">
            <span style="padding-right: 1em;">4.</span>Sekiranya tuan gagal untuk menjelaskan jumlah tunggakan caj
            berkenaan dalam tempoh masa yang ditetapkan, tuan boleh dikenakan tindakan undang-undang dibawah Seksyen 34
            dan Seksyen 35, Akta Pengurusan Strata 2013[Akta 757] iaitu:-
        </td>
    </tr>
    <tr>
        <td colspan="7">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-weight: bold; text-decoration: underline;">
            Subsekyen 34(3), Akta 757 :-
        </td>
    </tr>
    <tr>
        <td colspan="7">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-weight: bold; text-align: justify; font-style: italic;">
            <span style="padding-right: 2em;">&nbsp;</span>“Mana-mana pembeli atau pemunya petak yang, tanpa alasan
            yang munasabah, tidak mematuhi notis yang disebut dalam subseksyen (1) melakukan suatu kesalahan dan boleh,
            apabila disabitkan, didenda tidak melebihi lima ribu ringgit atau dipenjara selama tempoh tidak melebihi
            tiga tahun atau kedua-duanya, dan dalam hal suatu kesalahan berterusan, didenda selanjutnya tidak melebihi
            lima puluh ringgit bagi tiap-tiap hari atau sebagainya kesalahan itu berterusan selepas disabitkan.”
        </td>
    </tr>
    <tr>
        <td colspan="7">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-weight: bold; text-decoration: underline;">
            Subseksyen 35(1), Akta 757:-
        </td>
    </tr>
    <tr>
        <td colspan="7">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-weight: bold; text-align: justify; font-style: italic;">
            <span style="padding-right: 2em;">&nbsp;</span>“Pesuruhjaya boleh, apabila permohonan bersumpah secara
            bertulis dibuat oleh pemaju atau mana-mana anggota jawatankuasa pengurusan bersama, mengeluarkan suatu waran
            penahanan dalam Borang A Jadual Ketiga yang memberi kuasa bagi penahanan apa-apa harta alih yang dipunyai
            oleh pemunya petak yang ingkar yang boleh dijumpai di dalam bangunan atau di tempat lain dalam Negeri itu.”
        </td>
    </tr>
    <tr>
        <td colspan="7">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="7" style="text-align: justify;">
            <span style="padding-right: 1em;">5.</span>Sebarang kemusykilan atau masalah berhubung pembayaran
            tunggakan tersebut, sila hubungi pihak JMB. Sila abaikan surat peringatan ini jika pembayaran telah
            dilakukan.
        </td>
    </tr>
    <tr>
        <td colspan="7">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="7">
            Sekian, harap maklum.
        </td>
    </tr>

    @include('eservice.mbpj.component.signature_tag_1', ['sk' => true])

</table>

@endsection