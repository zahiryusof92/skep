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
            PERBADANAN PENGURUSAN (MC)<br />
            {{ $cobLetter->building_name }}
        </strong>
        <br/>
        {{ $cobLetter->management_address_1 }}<br/>
        {{ $cobLetter->management_address_2 }}<br/>
        {{ $cobLetter->management_address_3 }}<br/>
        {{ $cobLetter->management_address_4 }}<br/>
        {{ $cobLetter->management_address_5 ? $cobLetter->management_address_5 : "" }}
    </div>
    <br/>
    Tuan,
    <br/>
    <br/>
    <h4 class="title">PEMATUHAN KEPADA ; AKTA PENGURUSAN STRATA 2013 (AKTA 757) & PERATURAN-PERATURAN PENGURUSAN STRATA (PENYENGGARAAN DAN PENGURUSAN) 2015.</h4>
    <hr style="margin: 0; border: 1px solid;">
    <p>Dengan segala hormatnya saya diarah merujuk kepada perkara diatas.</p>
    <p>2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adalah dimaklumkan pihak Jabatan Pesuruhjaya Bangunan (COB) Majlis Perbandaran Selayang telah menerima aduan berhubung dengan kebocoran air <strong>pada bahagian bumbung</strong> unit yang telah menjejaskan kehidupan unit kediaman <strong>(2-401)</strong>.
        <br/>
        <br/>
        Oleh yang demikian, kami di Jabatan Pesuruhjaya Bangunan (COB) memohon perhatian sewajarnya dari pihak pengurusan untuk melakukan siasatan dan mengambil tindakan selanjutnya terhadap aduan tersebut.
    </p>
    <p>3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sukacita diingatkan bahawa pihak tuan adalah tertakluk kepada Akta Pengurusan Strata 2013 (Akta 757), di bawah peruntukan; </p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Seksyen 59 (1)(e)</strong> – pematuhan notis pihak berkuasa tempatan untuk menghentikan kacau ganggu pada harta bersama atau memerintahkan kerja pembaikan atau kerja lain yang kena dilakukan berkenaan dengan harta bersama itu atau pembaikan lain kepada harta itu;</p> 
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Seksyen 21 (2)(d)</strong> – mendapatkan daripada mana-mana pemunya petak apa-apa jumlah wang yang dibelanjakan oleh <strong>perbadanan pengurusan (MC)</strong> berkenaan dengan petak itu dalam mematuhi apa-apa notis atau perintah yang disebut dalam perenggan (1)(e); dan</p> 
    <p>Peraturan-peraturan Pengurusan Strata (Penyenggaraan dan Pengurusan) 2015, Bahagian XV – Kebocoran Antara Tingkat di bawah peraturan seperti berikut: </p> 
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Peraturan 56</strong> – Notis bahawa petak terbabit dengan kebocoran antara tingkat daripada seorang pembeli, pemunya petak atau pemilik</p> 
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Peraturan 57</strong> – Badan Pengurusan (pemaju, JMB, MC, MC Subsidiari  atau ejen pengurusan yang dilantik oleh COB), hendaklah secepat yang dapat dilaksanakan, atau dalam masa <strong>7 hari</strong> dari tarikh penerimaan notis di bawah peraturan 56, menjalankan suatu pemeriksaan ke atas petak terbabit, mana-mana petak lain dan harta bersama atau harta bersama terhad untuk menentukan punca/penyebab kebocoran (mengikut <strong>peraturan 58, 60, 61 & 62</strong> ) dan pihak yang bertanggungjawab membaiki mana-mana kecacatan tersebut. </p> 
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Peraturan 59</strong> – Dalam masa <strong>5 hari</strong> selepas tarikh selesai pemeriksaan ke atas petak terlibat, mana-mana petak lain atau harta bersama atau harta bersama terhad atau dalam masa lanjutan atau sebagaimana yang boleh diberikan oleh Pesuruhjaya, pemaju, JMB, MC, MC Subsidiari  atau ejen pengurusan yang dilantik oleh COB hendaklah mengeluarkan suatu perakuan pemeriksaan dalam <strong>Borang 28</strong> untuk menyatakan sebab kebocoran antara tingkat dan pihak yang bertanggungjawab untuk membaiki kecacatan tersebut. </p> 
    <p>4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adalah dengan ini Pihak Jabatan Pesuruhjaya Bangunan (COB) meminta pihak tuan hendaklah secepat yang dapat dilaksanakan atau dalam <strong style="color: red;"><u>tempoh 7 hari dari surat ini</u></strong> untuk memberikan kerjasama mengenalpasti dan mengambil tindakan berdasarkan peraturan-peraturan yang telah ditetapkan seperti di atas. </p>
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
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Persint 4, Pusat Pentadbiran Kerajaan Persekutuan,<br/>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;62100 Putrajaya, Malaysia.<br/>
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
        Ketua Penolong Pengarah<br />
        Unit Pengurusan Bangunan Strata, <br />
        Lembaga Perumahan Dan Hartanah Selangor,<br />
        Tingkat 3, Bangunan Sultan Salahuddin Abdul Aziz Shah,<br />
        40503 Shah Alam, Selangor.        
        <br />
        <br />
        Bahagian Pengurusan Strata, Jabatan Perumahan Negara,<br/>
        Kementerian Perumahan dan Kerajaan Tempatan,<br/>
        Aras 34, No. 51 Persiaran Perdana, <br/>
        Presint 4, Pusat Pentadbiran Kerajaan Persekutuan,<br/>
        62100 Putrajaya, Malaysia.&nbsp;&nbsp;Tel: 03-88914000
        <br />
        <br />
        Pengarah,<br/>
        Tribunal Perumahan Dan Pengurusan Starata (TPPS),<br />
        Aras 3, No. 51, Presint 4, <br />
        Persiaran Perdana, 62100 Putrajaya.
        <br />
        <br />
        <strong>Penghuni Unit</strong><br />
        {{ $cobLetter->from_address_1 }}<br/>
        {{ $cobLetter->from_address_2 }}<br/>
        {{ $cobLetter->from_address_3 }}<br/>
        {{ $cobLetter->from_address_4 }}<br/>
        {{ $cobLetter->from_address_5 ? $cobLetter->from_address_5 : "" }}
    </p>
</div>
@endsection