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
                    PERATURAN MENGENAI PEMELIHARAAN HAIWAN PELIHARAAN DI SKIM STRATA
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
                <span style="padding-right: 1em;">2.</span>Dimaklumkan bahawa pemilik/penghuni yang tinggal di skim strata
                adalah dilarang memelihara atau menyimpan apa-apa haiwan di dalam petak atau di kawasan harta bersama yang
                boleh menyebabkan kegusaran atau kacau ganggu atau yang boleh mendatangkan bahaya kepada keselamatan dan
                kesihatan kepada pemilik lain atau yang bertentangan dengan mana-mana undang-undang bertulis pihak berkuasa
                tempatan. Ini seperti yang termaktub di bawah subperenggan 14 (1) & (2), Jadual Ketiga, Undang-undang Kecil,
                Peraturan-peraturan Pengurusan Strata (Penyenggaraan dan Pengurusan) 2015 yang menyebut:
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
                <span style="padding-right: 1em;">3.</span>Jika didapati berlaku pelanggaran undang-undang kecil tersebut,
                maka pihak pengurusan boleh mengambil tindakan seperti yang disebutkan dalam subperenggan 14(2) dan
                mengenakan denda pada kadar yang ditentukan oleh mesyuarat agung melalui ketetapan khas selaras dengan
                subperenggan 7(1), Jadual Ketiga, Undang-undang Kecil Peraturan-peraturan Pengurusan Strata (Penyenggaraan
                dan Pengurusan) 2015.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">4.</span>Semua belanja yang dibuat untuk mengeluarkan haiwan itu dan denda
                yang dikenakan di bawah subperenggan 7(1) undang-undang kecil tersebut akan menjadi suatu hutang yang
                terakru kepada pihak pengurusan dan atas pembayaran oleh pemilik hendaklah didepositkan dalam akaun
                penyenggaraan.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">5.</span>Sehubungan dengan itu, pihak tuan selaku pemilik/penghuni
                dikehendaki mematuhi peruntukan undang-undang kecil yang telah ditetapkan untuk sama-sama menjaga,
                memelihara dan mengekalkan keharmonian dan kesejahteraan penduduk.
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
