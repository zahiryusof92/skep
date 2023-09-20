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
                    PERATURAN MENGENAI KERJA PENGUBAHSUAIAN DAN PEMBAIKAN
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
                mengenai kerja pengubahsuaian/ pembaikan yang dilaksanakan di petak no.
                <strong>{{ isset($content['unit_no']) ? $content['unit_no'] : '' }}</strong>.
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
                mengenai subperenggan 27 (1), (3), dan (4), Undang-undang Kecil di bawah Jadual Ketiga Peraturan-peraturan
                Pengurusan Strata (Penyenggaraan dan Pengurusan) 2015 yang menyebut-
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify; font-style: italic; padding-left: 2em;">
                “(1) pemilik hendaklah tidak menjalankan apa-apa kerja pengubahsuaian kepada petaknya tanpa mula-mula
                mendapatkan kelulusan bertulis terdahulu dari pengurusan perbadanan dan, apabila perlu, dari pihak berkuasa
                berkenaan dengan.”
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify; font-style: italic; padding-left: 2em;">
                (3) menjadi tanggungjawab sendiri pemilik untuk menyemak dengan pihak berkuasa berkenaan dengan tentang
                keperluan apa-apa kelulusan untuk melaksanakan kerja pengubahsuaian dan pemilik hendaklah merujuk perkara
                itu dengan pihak berkuasa berkenaan dengan atas ikhtiar sendiri. Jika pemilik memohon untuk apa-apa
                kelulusan dari perbadanan pengurusan untuk kebenaran melaksanakan kerja pengubahsuaian, perbadanan
                pengurusan berhak menganggap bahawa pemilik telah mendapat kelulusan yang perlu dari pihak berkuasa
                berkenaan dengan, apabila perlu, dan salinan kelulusan dari pihak berkuasa berkenaan dengan itu hendaklah
                diberi kepada perbadanan pengurusan pada masa permohonan untuk kelulusan oleh perbadanan pengurusan. Jika
                perbadanan pengurusan memberi kelulusan untuk apa-apa kerja pengubahsuaian dan ia kemudian mendapati bahawa
                kelulusan yang diperlukan dari pihak berkuasa berkenaan dengan tidak diperolehi atau tidak diperolehi dengan
                wajar, pemilik hendaklah dengan sendiri bertanggungjawab kepada pihak berkuasa berkenaan dengan dan
                kelulusan yang diberi oleh perbadanan pengurusan untuk kerja pengubahsuaian itu hendaklah dianggap langsung
                ditarik balik.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify; font-style: italic; padding-left: 2em;">
                (4) semua kerja pengubahsuaian dalam petak hendaklah terhad kepada sempadan petak itu dan hendaklah tidak
                ada kerja dilaksanakan atas mana-mana harta bersama.”
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">4.</span>Selaras dengan peraturan di atas, pentadbiran ini ingin
                menegaskan bahawa, sebarang kerja pengubahsuaian/ pembinaan yang ingin dilaksanakan hendaklah dirujuk
                terlebih dahulu dengan pihak pengurusan dan Jabatan Kawalan Bangunan untuk mendapatkan kelulusan bertulis.
                Tiada kelulusan boleh diberikan bagi kerja pengubahsuaian/ pembinaan yang melebihi sempadan petak dan atas
                harta bersama.
            </td>
        </tr>
        <tr>
            <td colspan="7">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: justify;">
                <span style="padding-right: 1em;">5.</span>Sehubungan itu, pihak tuan dikehendaki mematuhi peraturan yang
                telah ditetapkan untuk sama-sama menjaga, memelihara dan mengekalkan keharmonian dan kesejahteraan penduduk.
                Sekiranya ingkar, maka pihak pengurusan boleh mengenakan denda tidak lebih dua ratus ringgit mengikut jumlah
                yang ditentukan oleh mesyuarat agung. Selanjutnya, tindakan pendakwaan dan perobohan boleh diambil terhadap
                pemilik sekiranya pemilik masih ingkar terhadap notis yang telah disampaikan.
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

        @include('eservice.mbpj.component.signature_tag_4', ['sk' => true])

    </table>
@endsection
