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
                {{ isset($content['affected_name']) ? $content['affected_name'] . '<br/>' : '' }}
                (Pemilik unit
                {{ isset($content['affected_unit_no']) ? $content['affected_unit_no'] . ' ' : '' }}
                {{ isset($content['building_name']) ? $content['building_name'] : '' }})
                <br />
                {{ isset($content['affected_address1']) && !empty($content['affected_address1']) ? $content['affected_address1'] . '<br/>' : '' }}
                {{ isset($content['affected_address2']) && !empty($content['affected_address2']) ? $content['affected_address2'] . '<br/>' : '' }}
                {{ isset($content['affected_address3']) && !empty($content['affected_address3']) ? $content['affected_address3'] . '<br/>' : '' }}
                {{ isset($content['affected_address4']) && !empty($content['affected_address4']) ? $content['affected_address3'] . '<br/>' : '' }}
                <span style="text-transform: uppercase; font-weight: bold;">
                    {{ isset($content['affected_postcode']) ? $content['affected_postcode'] : '' }}
                    {{ isset($content['affected_city']) ? $content['affected_city'] . '<br/>' : '' }}
                    {{ isset($content['affected_state']) ? $content['affected_state'] : '' }}
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
                ADUAN KEROSAKAN / KEBOCORAN ANTARA TINGKAT
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-transform: uppercase; font-weight: bold; text-align: justify;">
                - {{ isset($content['building_name']) ? $content['building_name'] . ', ' : '' }}
                {{ isset($content['management_address3']) && !empty($content['management_address3']) ? $content['management_address3'] . ', ' : '' }}
                {{ isset($content['management_city']) ? $content['management_city'] . ', ' : '' }}
                {{ isset($content['management_state']) ? $content['management_state'] : '' }}
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
                Jabatan ini telah menerima Borang 28, “Perakuan Pemeriksaan Kebocoran Antara Tingkat” daripada
                {{ isset($content['management_name']) ? $content['management_name'] : '' }} berhubung kerosakan siling /
                dinding di {{ isset($content['owner_unit_no']) ? $content['owner_unit_no'] : '' }}
                yang dipercayai berpunca daripada kebocoran paip/lantai di unit tuan. Kebocoran tersebut telah menyebabkan
                kerosakan pada siling /dinding dan menyebabkan ketidakselesaan penghuni unit tersebut.
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
                Sila ambil perhatian, merujuk kepada peruntukan di dalam Akta Pengurusan Strata 2013 (Akta 757) berikut:
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7"
                style="font-weight: bold; text-align: justify; text-decoration: underline; padding-left: 2em;">
                PERATURAN 61, PERATURAN-PERATURAN PENGURUSAN STRATA (PENYENGGARAAN DAN PENGURUSAN) 2015, AKTA 757
                “KEBOCORAN ANTARA TINGKAT DISEBABKAN OLEH ATAU DIKENAL PASTI BERPUNCA DARIPADA SUATU PETAK”
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td style="text-align: right; vertical-align: top;">
                (1)
            </td>
            <td colspan="6" style="text-align: justify; padding-left: 1em;">
                Jika kebocoran antara tingkat disebabkan oleh atau dikenal pasti berpunca daripada suatu petak atau
                mana-mana bahagian daripadanya, pembeli, pemunya petak atau pemilik petak itu, tanpa prejudis kepada
                haknya untuk mendapatkan indemniti dari mana-mana pihak lain, hendaklah mengambil semua langkah perlu untuk
                membaiki kebocoran antara tingkat itu dalam masa tujuh hari dari penerimaan Borang 28.
            </td>
        </tr>
        <tr>
            <td style="text-align: right; vertical-align: top;">
                (2)
            </td>
            <td colspan="6" style="text-align: justify; padding-left: 1em;">
                Sekiranya langkah-langkah yang dinyatakan dalam subperaturan 61(1) tidak dilaksanakan, mana-mana pemaju,
                badan pengurusan bersama, perbadanan pengurusan atau perbadanan pengurusan subsidiari atau ejen pengurusan
                yang dilantik oleh Pesuruhjaya di bawah subseksyen 86(1) atau 91(3) Akta, mengikut mana-mana yang berkenaan
                dengan, hendaklah secepat yang dapat dilaksanakan mengambil semua langkah perlu untuk membaiki kebocoran
                antara tingkat itu dan hendaklah mengenakan caj dan mendapatkan semua kos dan perbelanjaan dari pihak yang
                bertanggungjawab untuk membaiki kebocoran itu.
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
            <td colspan="7"
                style="font-weight: bold; text-align: justify; text-decoration: underline; padding-left: 2em;">
                PERENGGAN 8, JADUAL KETIGA, UNDANG-UNDANG KECIL PERATURAN-PERATURAN PENGURUSAN STRATA 2015 “KEWAJIPAN AM
                PEMILIK”
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
            <td style="text-align: right; vertical-align: top;">
                (6)
            </td>
            <td colspan="6" style="text-align: justify; padding-left: 1em;">
                Menyenggara petaknya termasuk semua lengkapan kebersihan, perkakas air, gas, elektrik dan penghawa dingin
                dalam keadaan yang baik supaya tidak menyebabkan apa-apa kebakaran atau letupan atau apa-apa kebocoran
                kepada mana-mana petak lain atau harta bersama atau supaya tidak menyebabkan apa-apa gangguan kepada pemilik
                petak-petak lain dalam kawasan pemajuan;
            </td>
        </tr>
        <tr>
            <td style="text-align: right; vertical-align: top;">
                (7)
            </td>
            <td colspan="6" style="text-align: justify; padding-left: 1em;">
                Dengan kadar segera membaiki dan menyiapkan atas kos dan perbelanjaan sendiri apa-apa kerosakan pada
                petaknya jika kerosakan itu dikecualikan bawah mana-mana polisi insurans yang diambil oleh perbadanan
                pengurusan dan untuk menjalan dan menyiapkan pembaikan dalam tempoh masa yang dinyatakan oleh perbadanan
                pengurusan, atas kegagalan berbuat demikian perbadanan pengurusan boleh menjalankan pembaikan itu dan kos
                sedemikian hendaklah dicaj kepada pemilik dan hendaklah kena dibayar atas tuntutan;
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
                Berdasarkan peruntukan di atas, Pihak tuan diminta untuk menjalankan kerja-kerja pembaikan dengan kadar
                segera, seterusnya memberikan maklum balas dalam tempoh <span style="font-weight: bold;">14 hari</span>
                daripada tarikh surat ini.
                Jabatan menasihatkan pihak tuan untuk terus merujuk kepada pihak pengurusan bagi mengadakan perbincangan di
                antara pemilik-pemilik petak yang terlibat untuk mendapatkan kaedah penyelesaian supaya masalah ini dapat
                diatasi
                dengan sewajarnya. Sekiranya pihak tuan gagal membaiki kerosakan tersebut, pihak pengurusan di bawah
                Peraturan 61(2) boleh melaksanakan pembaikan dan mengenakan caj dan mendapatkan semua kos dan perbelanjaan
                daripada pihak tuan dan pemilik unit yang terjejas boleh mengambil
                <span style="font-weight: bold;">tindakan undang-undang terhadap tuan melalui tuntutan di Tribunal
                    Pengurusan Strata (TPS), Kementerian Perumahan dan Kerajaan Tempatan (KPKT)</span>.
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
                Pihak tuan boleh menghubungi pegawai kami, di talian
                {{ isset($content['management_phone']) ? $content['management_phone'] : '' }}
                sekiranya terdapat sebarang pertanyaan berhubung perkara tersebut.
            </td>
        </tr>

        @include('eservice.mbsj.component.signature_tag_1')

    </table>
@endsection

@section('page3')
    @include('eservice.mbsj.component.sk_1')
@endsection
