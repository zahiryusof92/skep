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
            {{ $cobLetter->receiver_name }}
        </strong>
        <br/>
        {{ $cobLetter->receiver_address_1 }} <br/>
        {{ $cobLetter->receiver_address_2 }} <br/>
        {{ $cobLetter->receiver_address_3 }} <br/>
        {{ $cobLetter->receiver_address_4 }} <br/>
        {{ $cobLetter->receiver_address_5 ? $cobLetter->receiver_address_5 : "" }}
    </div>
    <br/>
    Tuan,
    <br/>
    <br/>
    <h4 class="title">ARAHAN KEPADA {{ str_replace(",", "", Str::upper($cobLetter->receiver_address_1)) }} UNTUK MENGHAPUSKAN KACAU GANGGU SEGERA TERHADAP {{ str_replace(",", "", Str::upper($cobLetter->from_address_1)) }} DENGAN MEMBAIKI KEBOCORAN AIR YANG MENYEBABKAN KERUGIAN.</h4>
    <p>Dengan segala hormatnya saya diarah merujuk kepada perkara diatas.</p>
    <p>2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adalah dimaklumankan, pihak pihak jabatan telah menerima aduan mengenai kebocoran air daripada pemilik petak No. <strong style="color: red;">{{ str_replace(",", "", $cobLetter->from_address_1) }}, {{ $cobLetter->unit_name }}</strong>.  Pengadu mendakwa kebocoran air unit kediaman tuan yang telah meresap dan menjejaskan kawasan <strong style="color: red;">unit rumahnya</strong> yang menyebabkan kerosakkan serta kerugian.</p>
    <p>3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sehubungan dengan itu, dimaklumkan bahawa unit kediaman tuan tertakluk kepada Akta Pengurusan Strata 2013 (Akta 757), di bawah peruntukan;</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.1.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Seksyen 21 (1)(e)</strong> – pematuhan notis pihak berkuasa tempatan untuk menghentikan kacau ganggu pada harta bersama atau memerintahkan kerja pembaikan atau kerja lain yang kena dilakukan berkenaan dengan harta bersama itu atau pembaikan lain kepada harta itu.</p> 
    <p>4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adalah dengan ini Pihak Jabatan Pesuruhjaya Bangunan (COB) meminta pihak tuan mengambil tindakan segera untuk memberikan kerjasama dan maklumbalas segera kepada pihak kami dalam <strong style="color: red;"><u><i>tempoh 14 hari dari surat ini</i></u></strong> sebelum tindakan seterusnya diambil.</p>
    <p>Untuk sebarang pertanyaan, atau penjelasan lanjut, tuan boleh berhubung  atau datang ke :-</p>
    <p>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;i.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jabatan Pesuruhjaya Bangunan,  <br/> 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Majlis Perbandaran Selayang. <br/>
    </p>
    <p style="text-align: right;">Tel: 03-61265930 / 03-61372606</p>
    <p>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ii.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bahagian Pengurusan Strata,  <br/> 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jabatan Perumahan Negara,<br/>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kementerian Perumahan dan Kerajaan Tempatan,<br/>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Aras 34, No 51 Persiaran Perdana, <br/>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Persint 4, 62100 Putrajaya.<br/>
    </p>
    <p style="text-align: right;">Tel: 03-88914000</p>
    <p>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;iii.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Unit Pengurusan Bangunan Strata, <br/> 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lembaga Perumahan Dan Hartanah Selangor,<br/>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tingkat 3, Bangunan Sultan Salahuddin Abdul Aziz Shah,<br/>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;40503 Shah Alam.   <br/>
    </p>
    <p style="text-align: right;">Tel: 03-55447911 / 7199 / 7650</p>
    <p>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;iv.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tribunal Perumahan Dan Pengurusan Strata (TPPS) <br/> 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Aras 3, No. 51, Presint 4, <br/>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Persiaran Perdana, 62100 Putrajaya.<br/>
    </p>
    <p style="text-align: right;">
        Tel : 03-8891 3284 (Hotline)<br/>
        Faks : 03-8891 4755<br/>
        Emel : <u>tribunal@kpkt.gov.my</u><br/>
    </p>
    
    <br/>
    <br/>
    <br/>
    <p>Sekian, terima kasih.</p>
    @include('cob_letter.mps.cob.signature_tag', ['sk' => true])
    <p>
        Yang Dipertua/Pesuruhjaya Bangunan,<br/>
        Majlis Perbandaran Selayang
        <br />
        <br />
        Pengarah,<br/>
        Jabatan Bangunan, Majlis Perbandaran Selayang.
        <br />
        <br />
        Pengarah Undang-Undang,<br/>
        Jabatan Perundangan, Majlis Perbandaran Selayang.
        <br />
        <br />
        Pengarah,<br />
        Bahagian Pengurusan Strata,<br/>
        Jabatan Perumahan Negara,<br />
        Kementerian Perumahan dan Kerajaan Tempatan,<br />
        Aras 34, No 51 Persiaran Perdana, <br />
        Persint 4, 62100 Putrajaya.&nbsp;&nbsp;Tel: 03-88914000
        <br />
        <br />
        Pengarah,<br/>
        Tribunal Perumahan Dan Pengurusan Starata (TPPS),<br />
        Aras 3, No. 51, Presint 4, <br />
        Persiaran Perdana, 62100 Putrajaya.
        <br />
        <br />
        <strong>{{ $cobLetter->building_name }} JMB.</strong><br />
        {{ $cobLetter->management_address_1 }}<br/>
        {{ $cobLetter->management_address_2 }}<br/>
        {{ $cobLetter->management_address_3 }}<br/>
        {{ $cobLetter->management_address_4 }}<br/>
        {{ $cobLetter->management_address_5 ? $cobLetter->management_address_5 : "" }}
        <br />
        <br />
        <strong>PEMILIK UNIT</strong><br />
        {{ $cobLetter->from_address_1 }}<br/>
        {{ $cobLetter->from_address_2 }}<br/>
        {{ $cobLetter->from_address_3 }}<br/>
        {{ $cobLetter->from_address_4 }}<br/>
        {{ $cobLetter->from_address_5 ? $cobLetter->from_address_5 : "" }}

    </p>
</div>
@endsection