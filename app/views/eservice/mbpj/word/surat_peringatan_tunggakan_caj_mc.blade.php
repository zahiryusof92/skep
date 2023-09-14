@extends('layout.english_layout.eservice_word')

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
                    {{ isset($content['owner_name']) ? $content['owner_name'] : '' }}
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
                {{ isset($content['owner_address2']) ? $content['owner_address2'] : '' }}
            </td>
        </tr>
        <tr>
            <td colspan="7">
                {{ isset($content['owner_address3']) ? $content['owner_address3'] : '' }}
            </td>
        </tr>
        <tr>
            <td colspan="7">
                {{ isset($content['owner_postcode']) ? $content['owner_postcode'] : '' }}
                {{ isset($content['owner_city']) ? $content['owner_city'] : '' }}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                {{ isset($content['owner_state']) ? $content['owner_state'] : '' }}
            </td>
            <td colspan="3" style="text-align: right;">
                <strong>
                    {{ isset($content['reminder_type']) ? ($content['reminder_type'] == 'first_reminder' ? 'PERINGATAN PERTAMA' : 'PERINGATAN KEDUA') : 'PERINGATAN PERTAMA' }}
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
                    {{ isset($content['building_name']) ? Str::upper($content['building_name']) : '' }}.
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
                Dengan segala hormatnya merujuk kepada perkara di atas.
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
                {{ isset($content['reminder_type']) ? ($content['reminder_type'] == 'first_reminder' ? 'Adalah dimaklumkan bahawa pihak tuan telah mempunyai' : 'Dukacita dimaklumkan bahawa sehingga kini pihak tuan masih gagal menjelaskan') : 'Adalah dimaklumkan bahawa pihak tuan telah mempunyai' }}
                tunggakan caj penyenggaraan bangunan
                bagi unit <strong>{{ isset($content['unit_no']) ? $content['unit_no'] : '' }}</strong> sehingga
                <strong>{{ isset($content['date_overdue'])
                    ? \Carbon\Carbon::createFromTimestamp(strtotime($content['date_overdue']))->format('d/m/Y')
                    : '' }}</strong>
                berjumlah <strong>RM {{ isset($content['total_overdue']) ? $content['total_overdue'] : '' }}</strong>
                bagi tempoh pengurusan oleh
                <strong>{{ isset($content['management_name']) ? $content['management_name'] : '' }}</strong>.
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
                tuan agar menjelaskan tunggakan caj penyenggaraan dan bayaran lain seperti <i>sinking fund</i>, insuran
                bangunan, cukai tanah, bil air serta sebarang tunggakan kepada pihak
                <strong>{{ isset($content['management_name']) ? $content['management_name'] : '' }}</strong>
                dengan kadar segera dalam tempoh <strong>14 hari</strong> dari tarikh penyampaian surat ini sebagaimana yang
                termaktub di bawah Seksyen 52, Akta Pengurusan Strata 2013 [Akta 757] :-
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="font-weight: bold; text-decoration: underline;">
                Subsekyen 52(1), Akta 757-
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="font-weight: bold; text-align: justify; font-style: italic;">
                <span style="padding-right: 2em;">&nbsp;</span>“Setiap pemilik hendaklah membayar Caj, dan caruman kepada
                kumpulan wang penjelas, kepada perbadanan pengurusan bagi penyenggaraan dan pengurusan bangunan atau tanah
                yang dipecah bahagi dan harta bersama di dalam suatu kawasan pemajuan.”
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="font-weight: bold; text-decoration: underline;">
                Subsekyen 52(4), Akta 757-
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="font-weight: bold; text-align: justify; font-style: italic;">
                <span style="padding-right: 2em;">&nbsp;</span>“Pemilik hendaklah, dalam masa empat belas hari daripada
                penerimaan notis daripada pemaju, membayar Caj, dan caruman kepada kumpulan wang penjelas, kepada perbadanan
                pengurusan dan jika apa-apa jumlah wang itu masih tidak dibayar oleh pemilik itu apabila habis tempoh empat
                belas hari itu, pemaju boleh atas nama perbadanan pengurusan mendapatkan jumlah wang itu mengikut cara yang
                dinyatakan dalam seksyen 78.”
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
                berkenaan dalam tempoh masa yang ditetapkan, tuan boleh dikenakan tindakan undang-undang seperti yang
                diperuntukan dibawah Seksyen 78 dan Seksyen 79, Akta Pengurusan Strata 2013 [Akta 757] iaitu:-
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="font-weight: bold; text-decoration: underline;">
                Subsekyen 78(3), Akta 757-
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="font-weight: bold; text-align: justify; font-style: italic;">
                <span style="padding-right: 2em;">&nbsp;</span>“Mana-mana pemilik yang, tanpa alasan yang munasabah, tidak
                mematuhi notis bertulis di bawah subseksyen (1) melakukan sesuatu kesalahan dan boleh, apabila disabitkan,
                didenda tidak melebihi <u>lima ribu ringgit</u> atau dipenjarakan selama tempoh tidak melebihi
                <u>tiga tahun</u> atau kedua-duanya, dan dalam hal suatu kesalahan yang berterusan, didenda selanjutnya
                tidak melebihi <u>lima puluh ringgit</u> bagi tiap-tiap hari atau sebagainnya kesalahan itu berterusan
                selepas disabitkan.”
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="font-weight: bold; text-decoration: underline;">
                Subsekyen 79(1), Akta 757-
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="font-weight: bold; text-align: justify; font-style: italic;">
                <span style="padding-right: 2em;">&nbsp;</span>“Pesuruhjaya boleh, atas permohonan bersumpah secara bertulis
                yang dibuat oleh mana-mana anggota jawatankuasa pengurusan perbadanan pengurusan atau jawatankuasa
                pengurusan subsidiari, mengeluarkan suatu waran penahan dalam Borang A Jadual Ketiga yang memberi kuasa
                penahanan bagi apa-apa harta alih kepunyaan pemilik yang ingkar yang boleh dijumpai di dalam bangunan atau
                tempat lain di dalam Negeri itu.”
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">5.</span>Sebarang kemusykilan atau masalah berhubung pembayaran tunggakan
                tersebut, sila hubungi pihak MC. Sila abaikan surat peringatan ini jika pembayaran telah dilakukan.
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
