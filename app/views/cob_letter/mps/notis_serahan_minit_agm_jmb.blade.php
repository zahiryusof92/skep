@extends('layout.english_layout.letter')

@section('content')
<div id="page-content" class="container">
    <div style="text-align: right;">
        <strong>
        {{ $cobLetter->bill_no }}
        <br/>
        {{ \Carbon\Carbon::createFromTimestamp(strtotime($cobLetter->date))->format('M Y') }}
        </strong>
    </div>
    <div class="from">
        <strong>
            BADAN PENGURUSAN BERSAMA {{ $cobLetter->building_name }}
        </strong>
        <br/>
        {{ $cobLetter->receiver_address_1 }} <br/>
        {{ $cobLetter->receiver_address_2}} <br/>
        {{ $cobLetter->receiver_address_3 }} <br/>
        {{ $cobLetter->receiver_address_4 }} <br/>
        {{ $cobLetter->receiver_address_5 ? $cobLetter->receiver_address_5 : "" }}
    </div>
    <br/>
    Tuan,
    <br/>
    <br/>
    <h4 class="title">PEMATUHAN SERAHAN DOKUMEN YANG BERKAITAN BAGI MESYUARAT AGUNG TAHUNAN BADAN PENGURUSAN BERSAMA PANGSAPURI DAMAI.</h4>
    <p>Dengan segala hormatnya merujuk kepada perkara tersebut di atas dan perlaksanaan mesyuarat agung tahunan yang telah diadakan pada 12hb Disember 2021 di skim Pangsapuri Damai adalah berkaitan. </p>
    <p>2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adalah dengan ini, pihak tuan diminta, memfailkan semula kepada Pesuruhjaya dengan mengemukakan salinan butiran menurut <strong>Lampiran 1 – ISO/MPS/COB/02-01</strong> dan lampiran-lampiran yang berkaitan sebagai rekod pengesahan jawatankuasa pengurusan.</p>
    <p>3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kerjasama dan tindakan lanjut pematuhan daripada pihak tuan dengan memberi maklumbalas mesyuarat agung tahunan yang telah di adakan kepada jabatan ini dengan kadar segera. Dan dengan ini diharapkan pihak tuan dapat memastikan hak semua pemilik berdaftar dipertimbangkan dengan saksama, dan menjalankan kuasa badan dibawah <strong>Seksyen 21, Akta Pengurusan Strata 2013 (Akta 757)</strong> dengan sewajarnya melalui mesyuarat agung tahunan tersebut.</p>
    <p>Untuk sebarang pertanyaan atau penjelasan lanjut, tuan boleh berhubung  atau datang ke :-</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;i.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jabatan Pesuruhjaya Bangunan, <br/> 
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Majlis Perbandaran Selayang. <br/></p>
    <p style="text-align: right;">Telefon	: 03-61265930 <br/>E-mail: cob@mps.gov.my</p>
    <h4>PEMATUHAN SERAHAN DOKUMEN YANG BERKAITAN BAGI MESYUARAT AGUNG TAHUNAN PERTAMA BADAN PENGURUSAN BERSAMA PANGSAPURI DAMAI.</h4>
    <br/>
    <br/>
    <br/>
    <p>Sekian, terima kasih.</p>
    @include('cob_letter.mps.cob.signature_tag', ['sk' => true])
    <p>
        Pesuruhjaya Bangunan/ Yang Dipertua<br/>
        Majlis Perbandaran Selayang
        <br />
        <br />
        Penasihat Undang-Undang,<br />
        Jabatan Perundangan<br/>
        Majlis Perbandaran Selayang.
        <br />
        <br />
        Pengarah,<br />
        Bahagian Pengurusan Strata,<br/>
        Jabatan Perumahan Negara,<br />
        Kementerian Kesejahteraan Bandar, Perumahan Dan Kerajaan Tempatan,<br />
        Aras 34, No 51 Persiaran Perdana, <br />
        Persint 4, 62100 Wilayah Persekutuan Putrajaya.
    </p>
</div>
@endsection