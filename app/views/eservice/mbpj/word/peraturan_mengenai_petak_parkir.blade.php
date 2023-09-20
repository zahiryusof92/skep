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
                    PERATURAN MENGENAI PETAK PARKIR
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
                <span style="padding-right: 1em;">2.</span>Pentadbiran ini telah menerima aduan daripada pihak pengurusan
                mengenai tindakan segelintir pemilik yang menggunakan petak parkir untuk kepentingan peribadi (cop parkir)
                seperti meletak objek/ membina garaj/ membuat tanda dan lain-lain dengan tujuan menghalang pemilik lain dari
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
                <span style="padding-right: 1em;">3.</span>Untuk makluman tuan/puan, petak parkir di kawasan pemajuan tuan/
                puan merupakan kemudahan harta bersama ‘common property’ yang dikongsi bersama untuk kegunaan semua pemilik.
                Petak parkir tersebut bukan kepunyaan pemilik tertentu sebaliknya kepunyaan badan pengurusan bersama/
                perbadanan pengurusan. Petak parkir tersebut diurus dan dikawal selia oleh pihak pengurusan untuk manfaat
                bersama. Peraturan petak parkir yang dipraktikkan di kawasan pemajuan tuan/ puan adalah siapa cepat dia yang
                dapat atau ‘first come first serve’.
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">3.</span>Justeru, pentadbiran ini ingin menarik perhatian pihak tuan
                mengenai subperenggan 25(4) dan (5) Undang-undang Kecil Jadual ketiga, Peraturan-peraturan Pengurusan Strata
                (Penyenggaraan dan Pengurusan) 2015 yang menyebut:
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
                terakru dalam berbuat demikian hendaklah ditanggung dan dibayar oleh orang berkenaan dengan atas tuntutan.”
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">5.</span>Jika didapati berlaku pelanggaran undang-undang kecil tersebut,
                maka pihak pengurusan boleh mengambil tindakan seperti yang disebutkan dalam subperenggan 25(5) dan
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
                <span style="padding-right: 1em;">6.</span>Semua belanja yang dibuat untuk mengalih dan melupus
                barang-barang itu termasuk denda yang dikenakan di bawah subperenggan 7(1) Undang-Undang Kecil tersebut akan
                menjadi suatu hutang yang terakru kepada pihak pengurusan dan atas pembayaran oleh pemilik akan didepositkan
                dalam akaun penyenggaraan.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">7.</span>Sehubungan dengan itu, semua pemilik/ penghuni dikehendaki
                mematuhi undang-undang kecil yang telah ditetapkan untuk sama-sama menjaga, memelihara dan mengekalkan
                keharmonian dan kesejahteraan penduduk. Sekiranya ingkar, maka pihak pengurusan boleh mengenakan denda tidak
                lebih dua ratus ringgit mengikut jumlah yang ditentukan oleh mesyuarat agung. Selanjutnya, tindakan mengalih
                dan melupus tanpa notis terdahulu boleh dilaksanakan oleh pihak pengurusan seperti yang diperuntukan di
                bawah subperenggan 25(5) di atas.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7">
                Kerjasama daripada pihak tuan/puan amat dihargai.
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
