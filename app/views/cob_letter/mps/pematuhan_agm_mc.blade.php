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
            {{ $cobLetter->building_name }}
        </strong>
        <br/>
        Diurus:- <br/>
        {{ $cobLetter->management_address_1 }} <br/>
        {{ $cobLetter->management_address_2 }} <br/>
        {{ $cobLetter->management_address_3 }} <br/>
        {{ $cobLetter->management_address_4 }}<br/>
        {{ $cobLetter->management_address_5 ? $cobLetter->management_address_5 : "" }}
    </div>
    <br/>
    Tuan,
    <br/>
    <br/>
    <h4 class="title">PEMATUHAN MENGADAKAN MESYUARAT AGUNG TAHUNAN BADAN PENGURUSAN BERSAMA (JMB) / PERBADANAN PENGURUSAN (MC) WARTA KERAJAAN PERSEKUTUAN DAN PEMATUHAN PERENGGAN 10, JADUAL KEDUA AKTA PENGURUSAN STRATA 2013 (AKTA 757) </h4>
    <p>Dengan segala hormatnya merujuk kepada perkara diatas dan aduan daripada pihak pemilik-pemilik {{ $cobLetter->receiver_name }}.</p>
    <p>2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jabatan Pesuruhjaya Bangunan, Majlis Perbandaran Selayang ingin menarik perhatian pihak tuan mengenai <strong>Warta Kerajaan Persekutuan</strong> yang disiarkan pada 23 Jun 2021 bagi <strong>Perintah Pengurusan Strata (Pengecualian Kepada Badan Pengurusan Bersama) 2021</strong> dan <strong>Perintah Pengurusan Strata (Pengecualian Kepada Perbadanan Pengurusan) 2021</strong> adalah dengan ini mengarahkan pihak tuan menurut di bawah Perenggan 10, Jadual Kedua, Akta Pengurusan Strata 2013 (Akta 757) bagi melaksanakan mesyuarat agung badan untuk menimbang akaun-akaun, pemilihan jawatankuasa pengurusan dan transaksi apa-apa perkara yang lain yang timbul.</p>
    <p>3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sehubungan dengan itu bagi maksud menjalankan fungsi dan peranan, serta pengurusan badan selaras dengan perundangan Akta Pengurusan Strata 2013 (Akta 757), pihak tuan adalah dengan ini dikehendaki untuk menjalankan tatacara mesyuarat agung tahunan (AGM) tuan kali ini mengikut peruntukan Akta 757 sewajarnya.</p>
    <p>Merujuk kepada Notis Mesyuarat Agung Tahunan dan ketetapan kelayakan yang telah dinyatakan berdasarkan kelayakan akaun, beberapa syarat dan pertimbangan mengikut Jadual Kedua Akta 757 berikut hendaklah dipatuhi.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kelayakan Kuorum:</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a)&nbsp;&nbsp;&nbsp;Hendaklah terdiri daripada <strong>Pemilik Berdaftar/ Pembeli atau Proksi sah.</strong> – Sekiranya perbadanan pengurusan pihak pengurusan hendaklah mendapatkan pengesahan senarai ‛pemilik berdaftar’ melalui carian rasmi <strong>‘Strata Roll’</strong> di Pejabat Tanah dan Galian (PTG). </p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b)&nbsp;&nbsp;&nbsp;Hendaklah telah menjelaskan semua tanggungjawab pembayaran caj dan caruman serta apa-apa jua bayaran yang ditetapkan oleh badan bagi petaknya, dan hendaklah <strong>dibayar 7 hari</strong> sebelum tarikh mesyuarat AGM. </p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pengesahan sebagai Proksi.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a)&nbsp;&nbsp;&nbsp;Semua lantikan Proksi hendaklah disimpan di pejabat pengurusan tidak  JMB <strong>tidak kurang 48 jam</strong> sebelum tarikh mesyuarat. Kegagalan memfailkan borang Proksi akan menyebabkan proksi itu tidak berhak mengundi atau dilantik.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b)&nbsp;&nbsp;&nbsp;Proksi tidak boleh dilantik sebagai anggota jawatankuasa melainkan Proksi dari petak pemilikan bersama.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c)&nbsp;&nbsp;&nbsp;Proksi dari milikan petak badan/ syarikat yang dilantik oleh badan/ syarikat boleh dilantik sebagai anggota jawatankuasa.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kelayakan Pencalonan dan Lantikan.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a)&nbsp;&nbsp;&nbsp;Syarat-syarat perlantikan anggota jawatankuasa  hendaklah mematuhi garis panduan seperti diperuntukkan di <strong>Perenggan 2, Jadual Kedua-Akta 757</strong> , manakala <strong>Perenggan 3(1) Jadual Kedua-Akta 757</strong> pula hendaklah diambil pertimbangan bagi menentukan syarat kelayakan dan kriteria perlantikan anggota jawatankuasa.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pihak Pengurusan hendaklah menyemak dan mengesahkan kelayakan berdasarkan ketetapan  mengikut notis yang  telah dijelaskan pada  <strong>7 hari sebelum</strong> tarikh mesyuarat.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pihak Pengurusan badan/ perbadanan hendaklah menyampaikan notis sekurang-kurangnya 14 hari bagi mana-mana mesyuarat agung diberikan kepada tiap-tiap pambeli.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.6&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pengurusan MC hendaklah mempamerkan senarai orang yang berhak mengundi (kelayakan kuorum  dan proksi) <strong>sekurang-kurangnya 48 jam (2 hari)</strong> sebelum tarikh mesyuarat serta perlu mendapatkan perakuan daripada pihak Jabatan Pesuruhjaya Bangunan</p>
    <p>4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Perlu dingat bahawa pihak jawatankuasa pengurusan semasa atau bersama ejen pengurusan (bagi tempoh sebelum AGM) adalah dikehendaki menyediakan segala dokumen-dokumen berkaitan selengkapnya bagi urusan mesyuarat tersebut. Dengan pematuhan syarat dinyatakan, pihak tuan/puan adalah dengan ini diminta untuk mematuhi ketetapan yang telah diperuntukkan dibawah Akta 757  bagi memastikan kesahihan mesyuarat dan pengesahan lantikan anggota jawatankuasa seterusnya.</p>
    <p>5.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pihak jabatan ingin juga mengingatkan bahawa sewajarnya perlaksanaan bagi mengadakan mesyuarat agung tahunan semasa dilaksanakan mengikut pematuhan <strong>Warta Kerajaan Persekutuan</strong> selaras dengan pewartaan tersebut mengikut Perenggan 2, sepertimana yang dinyatakan.</p>
    <p>6.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dengan pematuhan syarat-syarat di atas serta merujuk kepada pematuhan garis panduan pematuhan mengendalikan mesyuarat agung badan di dalam tempoh perlaksanaan <strong>PELAN PEMULIHAN NEGARA</strong>, merujuk kepada <strong>SOP SEKTOR PENGURUSAN STRATA TERKINI</strong>  yang dikeluarkan oleh <strong>MAJLIS KESELAMATAN NEGARA (MKN)</strong>. Adalah diharapkan pihak tuan hendaklah memastikan perlaksanaan mesyuarat agung badan dengan mengikuti Standard Operating Procedure (S.O.P) yang telah ditetapkan.</p>
    
    <br/>
    <br/>
    <br/>
    <p>Sekian dimaklumkan, terima kasih.</p>
    @include('cob_letter.mps.cob.signature_tag')
    <br/>
    <div style="break-after:page"></div>
    <h2>
        LAMPIRAN 1
    </h2>
    <p>SYARAT-SYARAT ASAS LANTIKAN  ATAU PEMEGANGAN JAWATAN /ANGGOTA JAWATANKUASA PENGURUSAN(JMC).</p>
    <p><strong># Perenggan 2(1) Jadual Kedua, Akta 757</strong> –  ...perlantikan jawatankuasa pengurusan.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a)&nbsp;&nbsp;&nbsp;&nbsp;Hendaklah melantik suatu jawatankuasa pengurusan yang terdiri dari <u><b>3 hingga 14</b></u> orang anggota jawatankuasa.( termasuk seorang wakil tetap pemaju).</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b)&nbsp;&nbsp;&nbsp;&nbsp;Anggota jawatankuasa hendaklah dipilih pada setiap mesyuarat agung tahunan.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c)&nbsp;&nbsp;&nbsp;&nbsp;Pengerusi, Setiausaha, Bendahari – tidak boleh memegang jawatan lebih 2 tahun berturut-turut, bermula dari Jun 2015.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d)&nbsp;&nbsp;&nbsp;&nbsp;Anggota jawatankuasa tidak boleh memegang jawatan lebih  3 tahun berturut-turut, bermula dari Jun 2015.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;e)&nbsp;&nbsp;&nbsp;&nbsp;Seseorang yang dilantik hendaklah berumur lebih 21 tahun;</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;Merupakan seorang pemilik petak atau pemilik bersama(Proksi).</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;Dicalonkan  bagi pemilihan oleh badan/syarikat/pertubuhan  yang memiliki petak.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;Anggota keluarga terdekat bagi seorang pemilik yang memiliki lebih dari dari 2 petak yang dicalonkan oleh pemilik petak tersebut.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;f)&nbsp;&nbsp;&nbsp;&nbsp;Pembeli yang tidak hadir boleh dilantik jika dia telah melantik proksi dan telah memberikan persetujuan bertulis untuk dicalon dan dipilih.</p>

    <p><strong># Perenggan 3(1) Jadual Kedua, Akta 757</strong> –  ...seseorang disifatkan telah mengosongkan jawatannya sebagai anggota sedemikian, atau akan dianggap tidak berkelayakan.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a)&nbsp;&nbsp;&nbsp;&nbsp;Jika dia meletak jawatan.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b)&nbsp;&nbsp;&nbsp;&nbsp;Jika dia meninggal dunia.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c)&nbsp;&nbsp;&nbsp;&nbsp;Jika dia menjadi bankrap.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d)&nbsp;&nbsp;&nbsp;&nbsp;Jika dia bukan seorang pemilik(pembeli).</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;e)&nbsp;&nbsp;&nbsp;&nbsp;Jika dia telah disabitkan pertuduhan berkenaan dengan;</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;Kesalahan melibatkan fraud, kecurangan atau keburukan akhlak.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;Kesalahan dibawah undang-undang berhubungan dengan rasuah.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;Kesalahan yang boleh dihukum dengan penjara selama lebih 2 tahun.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;f)&nbsp;&nbsp;&nbsp;&nbsp;Jika kelakuannya telah mencemarkan nama baik jawatankuasa pengurusan atau badan pengurusan.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;g)&nbsp;&nbsp;&nbsp;&nbsp;Jika tidak sempurna akal.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;h)&nbsp;&nbsp;&nbsp;&nbsp;Jika dia gagal hadir 3 kali berturut-turut mesyuarat jawatankuasa.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;i)&nbsp;&nbsp;&nbsp;&nbsp;Jika dia telah dipecat dari badan/syarikat ( bagi lantikan wakil/proksi)  yang diwakilinya.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;j)&nbsp;&nbsp;&nbsp;&nbsp;Jika dia ingkar pembayaran caj dan lain-lain bayaran  bagi tempoh 3 bulan berterusan.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;k)&nbsp;&nbsp;&nbsp;&nbsp;Jika dia telah melakukan  suatu pelanggaran undang-undang kecil badan (House Rules), dan gagal meremedikan pelanggaran tersebut (jika pelanggaran boleh diremedikan).</p>
    <div style="break-after:page"></div>
    <h2>
        LAMPIRAN 2
    </h2>
    <p>KEWAJIPAN JAWATANKUASA KEATAS REKOD DOKUMEN DAN MINIT MESYUARAT.> <strong>SELEPAS DARIPADA MESYUARAT</strong>.</p>
    <p><strong># Perenggan 7(1) Jadual Kedua, Akta 757</strong> –  ...jawatankuasa hendaklah menyimpan minit semua prosiding mesyuarat jawatankuasa pengurusan(jmc) dan minit mesyuarat  agung badan(AGM/EGM).</p>
    <p>Jawatankuasa Pengurusan hendaklah menyebabkan;</p>
    <p>&nbsp;1-&nbsp;&nbsp;&nbsp;&nbsp;Salinan <strong>minit mesyuarat</strong> AGM/EGM hendaklah dikemukakan dan dipamerkan dipapan notis <strong>dalam masa 21 hari</strong> selepas mesyuarat.</p>
    <p>&nbsp;2-&nbsp;&nbsp;&nbsp;&nbsp;Salinan <strong>ketetapan mesyuarat agung</strong> badan hendaklah dipamerkan dalam <strong>masa 21 hari</strong> selepas diluluskan dalam mesyuarat.</p>
    <p>&nbsp;3-&nbsp;&nbsp;&nbsp;&nbsp;Mengeluarkan satu <strong>notis ketetapan caj</strong> dan <strong>caruman wang penjelas</strong> melalui <u><strong>Borang 5A</strong></u>, didalam Peraturan-Peraturan Pengurusan Strata (Penyenggaraan dan Pengurusan) 2015.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;#&nbsp;&nbsp;&nbsp;&nbsp;Salinan <strong>minit</strong> dan <strong>ketetapan</strong>, dan <strong>Borang 5A</strong> hendaklah difailkan ke Pesuruhjaya Bangunan dalam <strong>masa 28 hari</strong> selepas mesyuarat. </p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;#&nbsp;&nbsp;&nbsp;&nbsp;Salinan akaun teraudit dengan laporan juruaudit yang dibentangkan dalam mesyuarat hendaklah difailkan ke Pesuruhjaya Bangunan dalam <strong>masa 28 hari</strong> selepas mesyuarat. </p>
    <p>&nbsp;4-&nbsp;&nbsp;&nbsp;&nbsp;Salinan <strong>minit  mesyuarat</strong> Jawatankuasa Pengurusan (yang ditandatangani oleh Pengerusi, atau Setiausaha) hendaklah dipamerkan dipapan notis <strong>dalam masa 21 hari</strong> selepas mesyuarat.</p>
    <p>&nbsp;5-&nbsp;&nbsp;&nbsp;&nbsp;Salinan <strong>ketetapan</strong> jawatankuasa dalam mesyuarat jawatankuasa hendaklah dipamerkan <strong>dalam masa 21 hari</strong> selepas diluluskan oleh jawatankuasa.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;#&nbsp;&nbsp;&nbsp;&nbsp;Salinan minit dan ketetapan tersebut hendaklah kekal dipamerkan sehingga digantikan dengan minit atau ketetapan yang baru.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;#&nbsp;&nbsp;&nbsp;&nbsp;Salinan minit dan ketetapan hendaklah difailkan ke Pesuruhjaya Bangunan (COB) <strong>dalam masa 28 hari</strong> selepas mesyuarat.</p>
    
</div>
@endsection