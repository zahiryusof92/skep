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
                    PIHAK PENGURUSAN
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
                    ADUAN MENGENAI HALANGAN DI KAWASAN HARTA BERSAMA
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
                <span style="padding-right: 1em;">2.</span>Pentadbiran ini telah menerima aduan daripada pemilik mengenai
                halangan yang dibuat oleh pemilik/ penghuni petak no.
                <strong>{{ isset($content['affected_unit']) ? $content['affected_unit'] : '' }}</strong>.
                Hal tersebut telah menyukarkan kerja-kerja penyenggaraan dilaksanakan dan menimbulkan ketidakpuasanhati
                kepada pemilik lain.
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
                mengenai bidang kuasa pihak tuan di bawah subperenggan 20(1) dan (2) Undang-undang Kecil Jadual ketiga,
                Peraturan-peraturan Pengurusan Strata (Penyenggaraan dan Pengurusan) 2015 seperti berikut:-
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify; font-style: italic; padding-left: 2em;">
                “(1) semua laluan kecemasan, termasuk tetapi tidak terhad kepada, tangga, pelantar dan laluan dalam bangunan
                atau harta bersama hendaklah tidak dihalang oleh pemilik pada bila-bila masa.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify; font-style: italic; padding-left: 2em;">
                (2) perbadanan pengurusan boleh tanpa notis terdahulu, mengalih atau merampas apa-apa harta pemilik,
                termasuk tetapi tidak terhad kepada basikal, tumbuhan berpasu, bikar, perabut, troli, kotak, barang atau
                objek dalam apa-apa bentuk sekalipun. Perbadanan pengurusan boleh menaikkan notis berkaitan apa-apa harta
                yang dialih atau dirampas yang boleh dituntut oleh pemilik dalam masa empat belas hari dari tarikh notis
                tertakluk kepada pembayaran kepada perbadanan pengurusan suatu caj tidak melebihi dua ratus ringgit. Jika
                harta yang dialih atau dirampas itu tidak dituntut pada tamat tempoh empat belas hari, perbadanan pengurusan
                boleh membuang atau melupuskan harta itu secara yang difikirkan wajar tanpa apa-apa liabiliti kepada
                pemilik.”
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">4.</span>Bagi meningkatkan lagi kawalan ke atas kawasan harta bersama,
                pihak tuan dinasihatkan supaya membuat undang-undang kecil tambahan bagi mewujudkan suatu peraturan tambahan
                yang lebih khusus bagi langkah-langkah keselamatan dan perlindungan serta pengenaan denda yang tidak
                melebihi dua ratus ringgit terhadap mana-mana pemilik, penghuni atau jemputan yang melanggari mana-mana
                undang-undang kecil tersebut melalui ketetapan khas di dalam mesyuarat agung seperti yang diperuntukan di
                bawah Seksyen 32(3) (a) dan (i) atau Seksyen 70(3)(a) & (i) yang mana berkenaan. Jumlah denda yang akan
                dikenakan hendaklah ditentukan oleh mesyuarat agung seperti yang dikehendaki di bawah subperenggan 7(1),
                Jadual Ketiga Undang-undang Kecil Peraturan-peraturan Pengurusan Strata (Penyenggaraan dan Pengurusan) 2015.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">5.</span>Semua denda yang dikenakan di bawah subperenggan 7(1)
                Undang-Undang Kecil tersebut hendaklah menjadi suatu hutang yang terakru kepada pihak pengurusan dan atas
                pembayaran hendaklah didepositkan dalam akaun penyenggaraan.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">6.</span>Sehubungan itu, pihak tuan dinasihatkan supaya mengeluarkan notis
                kepada pemilik yang terlibat berhubung larangan membuat halangan di kawasan harta bersama. Sebagai
                alternatif, pihak tuan boleh memohon kepada pentadbiran ini untuk mengeluarkan surat kepada pemilik yang
                terlibat berhubung larangan membuat halangan di kawasan harta bersama. Permohonan boleh dibuat melalui
                sistem eCOB.
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
