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
                    {{ isset($content['building_name']) ? $content['building_name'] : '' }}
                </strong>
            </td>
        </tr>
        <tr>
            <td colspan="7">
                <strong>
                    {{ isset($content['management_name']) ? $content['management_name'] : '' }}
                </strong>
            </td>
        </tr>
        <tr>
            <td colspan="7">
                {{ isset($content['management_address1']) ? $content['management_address1'] : '' }}
            </td>
        </tr>
        <tr>
            <td colspan="7">
                {{ isset($content['management_address2']) ? $content['management_address2'] : '' }}
            </td>
        </tr>
        <tr>
            <td colspan="7">
                {{ isset($content['management_address3']) ? $content['management_address3'] : '' }}
            </td>
        </tr>
        <tr>
            <td colspan="7">
                {{ isset($content['management_postcode']) ? $content['management_postcode'] : '' }}
                {{ isset($content['management_city']) ? $content['management_city'] : '' }}
            </td>
        </tr>
        <tr>
            <td colspan="7">
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
                    ADUAN PEMELIHARAAN HAIWAN PELIHARAAN DI SKIM STRATA
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
                pemeliharaan haiwan peliharaan
                <strong>{{ isset($content['type_of_animal']) ? strtolower($content['type_of_animal']) : '' }}</strong>
                di petak no. <strong>{{ isset($content['affected_unit']) ? $content['affected_unit'] : '' }}</strong>
                sehingga menyebabkan kegusaran dan kacau ganggu kepada pemilik lain.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">3.</span>Justeru, pentadbiran ini ingin menarik perhatian pihak tuan
                mengenai bidang kuasa pihak tuan di bawah Subperenggan 14 (1) dan (2) Undang-undang Kecil Jadual ketiga,
                Peraturan-peraturan Pengurusan Strata (Penyenggaraan dan Pengurusan) 2015 seperti berikut:
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify; font-style: italic; padding-left: 2em;">
                “(1) dalam bangunan yang digunakan untuk tujuan kediaman atau tempat tinggal, pemilik hendaklah tidak
                menyimpan apa-apa haiwan tertentu dalam petaknya atau atas harta bersama yang boleh menyebabkan kegusaran
                atau kacau ganggu kepada pemilik lain atau yang bertentangan dengan mana-mana undang-undang atau peraturan
                dan kaedah-kaedah bertulis pihak berkuasa negeri atau tempatan yang berkenaan dengan.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify; font-style: italic; padding-left: 2em;">
                (2) pemilik yang mengingkari subperenggan 14(1) undang-undang kecil ini, hendaklah dalam masa tiga hari
                penerimaan suatu notis bertulis dari perbadanan pengurusan mengeluarkan haiwan tersebut dari bangunan. Jika
                gagal berbuat demikian, perbadanan pengurusan boleh mengambil apa-apa tindakan dianggap perlu untuk
                mengeluarkan haiwan itu dari bangunan dan –
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify; font-style: italic; padding-left: 3em;">
                (a) semua kos terakru hendaklah dicaj dan dikenakan terhadap pemilik itu, dan<br />
                (b) perbadanan pengurusan tidak bertanggungan terhadap apa-apa kerosakan pada harta pemilik yang secara
                munasabah disebabkan oleh proses mengeluarkan haiwan itu.”
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">4.</span>Bagi meningkatkan lagi kawalan ke atas aktiviti pemeliharaan
                haiwan peliharaan di kawasan pemajuan pihak tuan, pihak tuan dinasihatkan supaya membuat undang-undang kecil
                tambahan bagi mewujudkan suatu peraturan tambahan yang lebih khusus bagi mengawal selia kawalan terhadap
                pemeliharaan binatang peliharaan serta pengenaan denda yang tidak melebihi dua ratus ringgit terhadap
                mana-mana pemilik, penghuni atau jemputan yang melanggari mana-mana undang-undang kecil tersebut melalui
                ketetapan khas dalam mesyuarat agung seperti yang diperuntukan di bawah Seksyen 32(3)(c) dan (i) atau
                Seksyen 70(3) (c) & (i) yang mana berkenaan. Jumlah denda yang akan dikenakan hendaklah ditentukan oleh
                mesyuarat agung seperti yang dikehendaki di bawah subperenggan 7(1), Jadual Ketiga Undang-undang Kecil
                Peraturan-peraturan Pengurusan Strata (Penyenggaraan dan Pengurusan) 2015.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">5.</span>Semua belanja yang dibuat untuk mengeluarkan haiwan itu dan denda
                yang dikenakan di bawah subperenggan 7(1) Undang-Undang Kecil tersebut hendaklah menjadi suatu hutang yang
                terakru kepada pihak pengurusan dan atas pembayaran hendaklah didepositkan dalam akaun penyenggaraan.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">6.</span>Sehubungan dengan itu, pihak tuan dinasihatkan supaya
                mengeluarkan notis kepada semua pemilik/ penghuni petak berhubung peraturan pemeliharaan haiwan peliharaan
                di skim strata. Sebagai alternatif, pihak tuan boleh memohon kepada pentadbiran ini untuk mengeluarkan surat
                kepada pemilik yang terlibat berhubung peraturan pemeliharaan haiwan di skim strata. Permohonan boleh dibuat
                melalui sistem eCOB.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">7.</span>Pihak tuan juga dinasihatkan supaya menguatkuasakan undang-undang
                kecil tersebut di atas agar masalah ini dapat dikawal bagi melindungi hak dan kepentingan bersama serta
                dapat memelihara dan mengekalkan kerharmonian dan kesejahteraan penduduk.
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
