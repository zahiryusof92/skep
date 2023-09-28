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
                    LARANGAN MEMBUAT HALANGAN DI KAWASAN HARTA BERSAMA
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
                <span style="padding-right: 1em;">2.</span>Pentadbiran ini telah menerima aduan daripada pihak pengurusan
                mengenai halangan yang dibuat oleh pemilik/penghuni petak no.
                <strong>{{ isset($content['unit_no']) ? $content['unit_no'] : '' }}</strong>.
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
                <span style="padding-right: 1em;">4.</span>Sehubungan dengan itu, pihak tuan dikehendaki mematuhi
                undang-undang kecil yang telah ditetapkan untuk sama-sama menjaga, memelihara dan mengekalkan keharmonian
                dan kesejahteraan penduduk. Sekiranya ingkar, maka pihak pengurusan boleh mengenakan denda tidak lebih dua
                ratus ringgit mengikut jumlah yang ditentukan oleh mesyuarat agung. Selanjutnya, tindakan mengalih dan
                melupus tanpa notis terdahulu boleh dilaksanakan seperti yang diperuntukan di bawah subperenggan 20(2) di
                atas.
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

        @include('eservice.mbpj.component.signature_tag_3', ['sk' => true])

    </table>
@endsection
