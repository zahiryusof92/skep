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
                    ADUAN MENGENAI COP PARKIR
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
                tindakan segelintir pemilik yang menggunakan petak parkir untuk kepentingan peribadi (cop parkir) seperti
                meletak objek/ membina garaj/ membuat tanda dan lain-lain dengan tujuan menghalang pemilik lain dari
                menggunakan petak parkir itu. Hal ini telah menimbulkan ketidakpuasanhati pemilik lain yang sama-sama berhak
                ke atas petak parkir itu.
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
                mengenai bidang kuasa pihak tuan di bawah subperenggan 25(4) dan (5) Undang-undang Kecil Jadual ketiga,
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
                “(4) pembinaan atau struktur tambahan dalam apa-apa bentuk tidak boleh dinaikkan atas ruang parkir dalam
                kawasan pemajuan tanpa kelulusan bertulis terdahulu daripada perbadanan pengurusan.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify; font-style: italic; padding-left: 2em;">
                (5) mana-mana orang yang menggunakan parkir kenderaan dalam kawasan pemajuan hendaklah memastikan beliau
                tidak meninggalkan apa-apa peralatan, alat ganti, bahan yang dibuang, kekotoran dan sampah di kawasan parkir
                kenderaan. Perbadanan pengurusan boleh mengalih dan melupus barang-barang itu tanpa notis terdahulu dan
                tidak akan bertanggungan terhadap apa-apa kerosakan atau kehilangan berkaitan barang-barang itu, dan kos
                terakru dalam berbuat demikian hendaklah ditanggung dan dibayar oleh orang berkenaan dengan atas tuntutan”
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">4.</span>Bagi meningkatkan lagi kawalan ke atas kawasan parkir di pemajuan
                pihak tuan, pihak tuan dinasihatkan supaya membuat undang-undang kecil tambahan bagi mewujudkan suatu
                peraturan tambahan yang lebih khusus bagi mengawal selia kawalan tempat letak kereta serta pengenaan denda
                yang tidak melebihi dua ratus ringgit terhadap mana mana pemilik, penghuni atau jemputan yang melanggari
                mana-mana undang-undang kecil tersebut melalui ketetapan khas di dalam mesyuarat agung seperti yang
                diperuntukan di bawah Seksyen 32(3) (d) dan (i) atau Seksyen 70(3) (d) & (i) yang mana berkenaan. Jumlah
                denda yang akan dikenakan hendaklah ditentukan oleh mesyuarat agung seperti yang dikehendaki di bawah
                subperenggan 7(1), Jadual Ketiga Undang-undang Kecil Peraturan-peraturan Pengurusan Strata (Penyenggaraan
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
                <span style="padding-right: 1em;">5.</span>Semua belanja yang dibuat untuk mengalih dan melupus
                barang-barang itu termasuk denda yang dikenakan di bawah subperenggan 7(1) Undang-Undang Kecil tersebut
                hendaklah menjadi suatu hutang yang terakru kepada pihak pengurusan dan atas pembayaran hendaklah
                didepositkan dalam akaun penyenggaraan.
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
                mengeluarkan notis kepada semua pemilik petak berhubung peraturan petak parkir di kawasan pemajuan pihak
                tuan. Sebagai alternatif, pihak tuan boleh memohon kepada pentadbiran ini untuk mengeluarkan surat kepada
                pemilik yang terlibat berhubung peraturan mengenai tempat letak kereta. Permohonan boleh dibuat melalui
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
                Kerjasama daripada pihak tuan dalam hal ini amatlah dihargai.
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

        @include('eservice.mbpj.component.signature_tag_2', ['sk' => true])

    </table>
@endsection
