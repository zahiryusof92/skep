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
            <td colspan="7">
                {{ isset($content['owner_state']) ? $content['owner_state'] : '' }}
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
                    PER: &emsp; ADUAN KEBOCORAN ANTARA TINGKAT DARI UNIT ATAS YANG MENYEBABKAN KEROSAKAN DI UNIT BAWAH -
                    UNIT {{ isset($content['affected_unit']) ? Str::upper($content['affected_unit']) . ',' : '' }}
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
                Dengan segala hormatnya saya diarah merujuk perkara di atas.
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
                Pentadbiran ini telah menerima Borang 28, “Perakuan Pemeriksaan Kebocoran Antara Tingkat” daripada
                {{ isset($content['management_name']) ? Str::upper($content['management_name']) : '' }}
                berhubung kerosakan siling di unit
                {{ isset($content['affected_unit']) ? $content['affected_unit'] : '' }}
                yang dipercayai berpunca daripada kebocoran paip/ lantai di
                <strong>{{ isset($content['unit_no']) ? '(' . $content['unit_no'] . ')' : '' }}</strong>.
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
                Pentadbiran ini juga ingin menarik perhatian tuan mengenai
                <strong style="font-style: italic;">
                    Subperaturan 61(1) dan (2), Peraturan-Peraturan Pengurusan Strata (Penyenggaran dan Pengurusan) 2015
                </strong>
                seperti berikut:-
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="font-weight: bold; text-align: justify; font-style: italic; padding-left: 2em;">
                <span style="padding-right: 1em;">3.1</span>
                “61(1) Jika Kebocoran antara tingkat disebabkan oleh atau dikenal pasti berpunca daripada suatu petak atau
                mana-mana bahagian bahagian daripadanya, pembeli, pemunya petak atau pemilik petak itu, tanpa prejudis
                kepada haknya untuk mendapatkan indemnity dari mana-mana pihak lain, hendaklah mengambil semua langkah perlu
                untuk membaiki kebocoran antara tingkat itu dalam masa tujuh hari dari penerimaan Borang 28;
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="font-weight: bold; text-align: justify; font-style: italic; padding-left: 2em;">
                <span style="padding-right: 1em;">3.2</span>
                “61(2) Sekiranya langkah-langkah yang dinyatakan dalam subperaturan 61(1) tidak dilaksanakan, mana-mana
                pemaju, badan pengurusan bersama, perbadanan pengurusan atau perbadanan pengurusan subsidiari atau ejen
                pengurusan yang dilantik oleh Pesuruhjaya di bawah subseksyen 86(1) atau 91(3) Akta, mengikut mana-mana yang
                berkenaan dengan, hendaklah secepat yang dapat dilaksanakan mengambil semua langkah perlu untuk membaiki
                kebocoran antara tingkat itu dan hendaklah mengenakan caj dan mendapatkan semua kos dan perbelanjaan dari
                pihak yang bertanggungjawab untuk membaiki kebocoran itu.
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
                Sehubungan itu, pentadbiran ini menasihatkan pihak tuan supaya berhubung terus dengan pihak pengurusan bagi
                mengadakan perbincangan di antara pemilik-pemilik petak yang terlibat supaya masalah ini dapat diselesaikan
                dengan kadar segera. Sekiranya pihak tuan gagal membaiki kerosakan yang berpunca daripada unit tuan, pemilik
                unit yang terjejas boleh mengambil tindakan undang-undang terhadap tuan. Pihak tuan juga diminta untuk
                memberikan maklumbalas kepada pentadbiran ini dalam tempoh <strong>14 hari</strong> daripada tarikh surat
                ini.
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
                Pihak tuan boleh menghubungi pegawai kami, di talian 03-7960 1646/ 2410 sekiranya terdapat sebarang
                pertanyaan berhubung perkara tersebut.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7">
                Kerjasama pihak tuan didahului dengan ucapan terima kasih.
            </td>
        </tr>

        @include('eservice.mbpj.component.signature_tag_3', ['sk' => true])

    </table>
@endsection
