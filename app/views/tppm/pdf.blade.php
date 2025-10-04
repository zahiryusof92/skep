<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.25;
            margin: 0;
            padding: 10px;
            counter-reset: page;
        }

        .page-header {
            font-size: 14px;
            position: fixed;
            /* top: 5px; */
            right: 20px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: right;
            z-index: 1000;
        }

        .main-content {
            margin-top: 30px;
        }

        .coat-of-arms {
            text-align: center;
        }

        .ministry-name {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
            margin: 8px 0;
        }

        .form-title {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 16px;
            margin: 0 0 5px 0;
        }

        .file-ref {
            margin-bottom: 20px;
        }

        .file-ref-left {
            float: left;
        }

        .file-ref-right {
            float: right;
        }

        .category-section {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 15px;
        }

        .category-option {
            padding: 10px 80px;
        }

        .attention-box {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 15px;
            text-align: center;
        }

        .section-title {
            background-color: #d0d0d0;
            font-weight: bold;
            padding: 5px;
            border: 1px solid #000;
            margin-bottom: 10px;
        }

        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .form-table td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }

        .form-table .label-cell {
            background-color: #f8f8f8;
            font-weight: bold;
            text-transform: uppercase;
            /* width: 30%; */
        }

        .form-table .value-cell {
            background-color: #fff;
            /* width: 70%; */
        }

        .input-line {
            /* border-bottom: 1px solid #000;
            padding: 2px 0;
            min-height: 15px; */
        }

        .checkbox {
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            display: inline-block;
            margin-right: 10px;
            vertical-align: top;
        }

        .checked {
            background-color: #000;
        }

        .footer {
            position: fixed;
            bottom: 60px;
            left: 15px;
            right: 15px;
            font-size: 8px;
            z-index: 1000;
        }

        .footer-left {
            float: left;
        }

        .footer-right {
            position: absolute;
            right: 0;
            top: 40px;
        }

        .page-number {
            font-size: 10px;
            font-weight: bold;
        }

        .work-scope-header {
            width: 10%;
            text-align: center;
            font-weight: bold;
            background-color: #f8f8f8;
            padding: 8px;
        }

        .work-scope-column {
            text-align: center;
            font-size: 8px;
            padding: 5px;
        }

        .header-hr {
            border: 0.5px solid #2d417d;
            margin: 5px 0 -5px 0;
            width: 100%;
        }

        .footer-hr {
            border: 0.2px solid #000;
            margin: 0 0 10px 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <!-- Page Header -->
    <div class="page-header">LAMPIRAN B</div>

    <div class="main-content">
        <!-- Coat of Arms and Ministry Name -->
        <div class="coat-of-arms">
            <img src="{{ public_path('assets/common/img/kpkt-logo.png') }}" alt="KPKT Logo"
                style="height: 80px; width: auto;">
        </div>

        <div class="ministry-name">
            KEMENTERIAN PERUMAHAN DAN KERAJAAN TEMPATAN
            <hr class="header-hr">
        </div>

        <!-- File Reference -->
        <table class="form-table" style="margin-bottom: 5px;">
            <tr>
                <td class="value-cell" style="width: 70%;">
                    No. Fail:
                    <span class="input-line">
                        {{ $model->file ? $model->file->file_no : '' }}
                    </span>
                </td>
                <td class="value-cell" style="width: 30%;">
                    No. Rujukan:
                    <span class="input-line">
                        {{ $model->reference_no ?: '' }}
                    </span>
                </td>
            </tr>
        </table>

        <!-- Form Title -->
        <div class="form-title">
            Borang Permohonan
            <br>
            Tabung Penyenggaraan Perumahan Malaysia (TPPM)
        </div>

        <!-- Category Section -->
        <div class="category-section">
            <div class="category-option">
                <span class="checkbox {{ $model->cost_category == 'low_cost' ? 'checked' : '' }}"></span>
                <div style="padding: -5px 5px; display: inline-block;">
                    <strong>{{ strtoupper('Kos Rendah Swasta') }}</strong><br>
                    Harga Belian Asal:
                    (Semenanjung - RM42,000.00 dan ke bawah),
                    <div style="margin-left: 94px;">
                        (Sarawak, Sabah dan Labuan - RM59,000.00 dan ke bawah); atau
                    </div>
                </div>
            </div>
            <div class="category-option">
                <span class="checkbox {{ $model->cost_category == 'low_medium_cost' ? 'checked' : '' }}"></span>
                <div style="padding: -5px 5px; display: inline-block;">
                    <strong>{{ strtoupper('Kos Sederhana Rendah Swasta') }}</strong><br>
                    Harga Belian Asal:
                    (Semenanjung - RM42,001.00 hingga RM80,000.00),
                    <div style="margin-left: 94px;">
                        (Sarawak, Sabah dan Labuan - RM59,001.00 hingga RM100,000.00)
                    </div>
                </div>
            </div>
        </div>

        <!-- Attention Box -->
        <div class="attention-box">
            <strong>
                {{ strtoupper('Perhatian') }}:
                Sila lengkapkan semua maklumat dalam ruangan di bawah.
                <br>
                Maklumat yang tidak lengkap boleh menyebabkan permohonan tidak dapat dipertimbangkan
            </strong>
        </div>

        <!-- Section A: Application Details -->
        <table class="form-table">
            <tr>
                <td class="label-cell" colspan="10">
                    A. Butiran Permohonan
                </td>
            </tr>
            <tr>
                <td class="label-cell" colspan="3">
                    Nama Kawasan Pemajuan / Bangunan
                </td>
                <td class="value-cell" colspan="7">
                    <div class="input-line">
                        {{ $model->strata ? $model->strata->name : '' }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-cell" colspan="3">
                    Nama Pemohon
                </td>
                <td class="value-cell" colspan="7">
                    <div class="input-line">
                        {{ $model->applicant_name ?: '' }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-cell" colspan="3">
                    No. Telefon
                </td>
                <td class="value-cell" colspan="3">
                    <div class="input-line">
                        {{ $model->applicant_phone ?: '' }}
                    </div>
                </td>
                <td class="label-cell" colspan="2">
                    Emel
                </td>
                <td class="value-cell" colspan="2">
                    <div class="input-line">
                        {{ $model->applicant_email ?: '' }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-cell" colspan="3">
                    Nama JMB / MC / Persatuan Penduduk Berdaftar
                </td>
                <td class="value-cell" colspan="7">
                    <div class="input-line">
                        {{ $model->organization_name ?: '' }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-cell" colspan="3">
                    Lokasi / Alamat
                </td>
                <td class="value-cell" colspan="7">
                    <div class="input-line">
                        {{ $model->organization_address_1 ?: '' }}
                    </div>
                    @if ($model->organization_address_2)
                        <div class="input-line">
                            {{ $model->organization_address_2 }}
                        </div>
                    @endif
                    @if ($model->organization_address_3)
                        <div class="input-line">
                            {{ $model->organization_address_3 }}
                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label-cell" colspan="3">
                    Maklumat Kawasan
                </td>
                <td class="value-cell" colspan="7">
                    <div style="margin-bottom: 5px;">
                        <span class="label-cell">
                            Parlimen:
                        </span>
                        <span class="input-line" style="margin-right: 20px;">
                            {{ $model->parliament ? $model->parliament->description : '' }}
                        </span>
                        <span class="label-cell">
                            DUN:
                        </span>
                        <span class="input-line">
                            {{ $model->dun ? $model->dun->description : '' }}
                        </span>
                    </div>
                    <div>
                        <span class="label-cell">
                            Daerah
                            <span style="margin-left: 7px;">:</span>
                        </span>
                        <span class="input-line">
                            {{ $model->district ? $model->district->description : '' }}
                        </span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-cell" colspan="3" rowspan="2">
                    Harga Rumah Dibeli
                    <br>
                    <small style="font-weight: normal; text-transform: none;">
                        (Harga Belian Pertama)
                    </small>
                </td>
                <td class="value-cell" colspan="7">
                    <span class="input-line">
                        <strong>RM</strong> {{ number_format($model->first_purchase_price) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="label-cell" colspan="7">
                    <small style="font-weight: normal; text-transform: none; font-style: italic">
                        [Sila kemukakan satu (1) salinan Perjanjian Jual Beli (Sale & Purchase Agreement)
                        <strong>yang jelas menunjukkan harga belian</strong>]
                    </small>
                </td>
            </tr>
            <tr>
                <td class="label-cell" colspan="2">
                    Tahun Dibina
                </td>
                <td class="value-cell" colspan="3">
                    <div class="input-line">
                        {{ $model->year_built ?: '' }}
                    </div>
                </td>
                <td class="label-cell" colspan="3">
                    Tahun Diduduki
                    <br>
                    <small style="font-weight: normal; text-transform: none; font-style: italic">
                        [Kediaman hendaklah <strong>diduduki >10 tahun</strong>]
                    </small>
                </td>
                <td class="value-cell" colspan="2">
                    <div class="input-line">
                        {{ $model->year_occupied ?: '' }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-cell" style="font-size: 9px;">
                    Bilangan Blok
                </td>
                <td class="value-cell">
                    <div class="input-line">
                        {{ $model->num_blocks ?: '' }}
                    </div>
                </td>
                <td class="label-cell" style="font-size: 9px;">
                    Bilangan Unit
                </td>
                <td class="value-cell">
                    <div class="input-line">
                        {{ $model->num_units ?: '' }}
                    </div>
                </td>
                <td class="label-cell" style="font-size: 9px;">
                    Bilangan Unit Diduduki
                </td>
                <td class="value-cell">
                    <div class="input-line">
                        {{ $model->num_units_occupied ?: '' }}
                    </div>
                </td>
                <td class="label-cell" style="font-size: 9px;">
                    Bilangan Unit Diduduki Pemilik
                </td>
                <td class="value-cell">
                    <div class="input-line">
                        {{ $model->num_units_owner ?: '' }}
                    </div>
                </td>
                <td class="label-cell" style="font-size: 9px;">
                    Bilangan Unit Diduduki Warganegara Malaysia
                </td>
                <td class="value-cell">
                    <div class="input-line">
                        {{ $model->num_units_malaysian ?: '' }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-cell" style="font-size: 9px;">
                    Bilangan Tingkat
                </td>
                <td class="value-cell">
                    <div class="input-line">
                        {{ $model->num_storeys ?: '' }}
                    </div>
                </td>
                <td class="label-cell" style="font-size: 9px;">
                    Bilangan Penghuni
                </td>
                <td class="value-cell">
                    <div class="input-line">
                        {{ $model->num_residents ?: '' }}
                    </div>
                </td>
                <td class="label-cell" style="font-size: 9px;">
                    Bilangan Unit Kosong
                </td>
                <td class="value-cell">
                    <div class="input-line">
                        {{ $model->num_units_vacant ?: '' }}
                    </div>
                </td>
                <td class="label-cell" style="font-size: 9px;">
                    Bilangan Unit Diduduki Penyewa
                </td>
                <td class="value-cell">
                    <div class="input-line">
                        {{ $model->num_units_tenant ?: '' }}
                    </div>
                </td>
                <td class="label-cell" style="font-size: 9px;">
                    Bilangan Unit Diduduki Selain Warganegara Malaysia
                </td>
                <td class="value-cell">
                    <div class="input-line">
                        {{ $model->num_units_non_malaysian ?: '' }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-cell" colspan="2" style="font-size: 9px;">
                    Nama & Bilangan Blok Yang Dimohon Untuk Baikpulih
                </td>
                <td class="value-cell" colspan="8">
                    <div style="margin-bottom: 10px;">
                        <span class="label-cell" style="text-transform: none;">
                            Nama Blok:
                        </span>
                        <span class="input-line">
                            {{ $model->requested_block_name ?: '' }}
                        </span>
                    </div>
                    <div>
                        <span class="label-cell" style="text-transform: none;">
                            Bil. Blok
                            <span style="margin-left: 9px;">:</span>
                        </span>
                        <span class="input-line">
                            {{ $model->requested_block_no ?: '' }}
                        </span>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Footer for Page 1 -->
        <div class="footer">
            <hr class="footer-hr">
            <div class="footer-left">
                Bahagian Penyelenggaraan Perumahan,<br>
                Jabatan Perumahan Negara,<br>
                Kementerian Perumahan dan Kerajaan Tempatan<br>
                Aras 33, No. 51 Persiaran Perdana, Presint 4, 62100 PUTRAJAYA
            </div>
            <div class="footer-right">
                <span class="page-number">1</span>
            </div>
        </div>
    </div>

    <!-- Page Break -->
    <div style="page-break-before: always;"></div>

    <!-- Page Header for Page 2 -->
    <div class="page-header">LAMPIRAN B</div>

    <!-- Page 2 Content -->
    <div class="main-content">
        <table class="form-table">
            <tr>
                <td class="label-cell" colspan="10">
                    B. Kod & Skop Kerja Penyelenggaraan / Baik Pulih Yang Dipohon
                </td>
            </tr>
            <tr>
                <td class="work-scope-header" style="vertical-align: middle;">A</td>
                <td class="work-scope-header" style="vertical-align: middle;">B</td>
                <td class="work-scope-header" style="vertical-align: middle;">C</td>
                <td class="work-scope-header" style="vertical-align: middle;">D</td>
                <td class="work-scope-header" style="vertical-align: middle;">E</td>
                <td class="work-scope-header" style="vertical-align: middle;">F</td>
                <td class="work-scope-header" style="vertical-align: middle;">G</td>
                <td class="work-scope-header" style="vertical-align: middle;">H</td>
                <td class="work-scope-header" style="vertical-align: middle;">I</td>
                <td class="work-scope-header" style="vertical-align: middle;">J</td>
            </tr>
            <tr>
                <td class="work-scope-column" style="vertical-align: middle;">
                    Lif + AVAS
                </td>
                <td class="work-scope-column" style="vertical-align: middle;">
                    Tangki Air & Sistem Retikulasi
                </td>
                <td class="work-scope-column" style="vertical-align: middle;">
                    Paip Sanitari
                </td>
                <td class="work-scope-column" style="vertical-align: middle;">
                    Bumbung
                </td>
                <td class="work-scope-column" style="vertical-align: middle;">
                    Tangga / Handrail
                </td>
                <td class="work-scope-column" style="vertical-align: middle;">
                    Mengecat
                </td>
                <td class="work-scope-column" style="vertical-align: middle;">
                    Pendawaian Semula & Kerja Elektrik
                </td>
                <td class="work-scope-column" style="vertical-align: middle;">
                    Infrastruktur Asas & Kemudahan Awam (Harta Bersama)
                </td>
                <td class="work-scope-column" style="vertical-align: middle;">
                    Pagar
                </td>
                <td class="work-scope-column" style="vertical-align: middle;">
                    Cerun
                </td>
            </tr>
            <tr>
                <td class="work-scope-column" style="vertical-align: middle;">
                    Bil. Lif:<br>
                    <div class="input-line" style="width: 80%; margin: 2px 0;"></div>
                    <br>
                    *Baik Pulih / Ganti Baru:<br>
                    <div class="input-line" style="width: 80%; margin: 2px 0;"></div>
                </td>
                <td class="work-scope-column" style="vertical-align: middle;">
                    Bil. Tangki:<br>
                    <div class="input-line" style="width: 80%; margin: 2px 0;"></div>
                    <br>
                    *Baik Pulih / Ganti Baru:<br>
                    <div class="input-line" style="width: 80%; margin: 2px 0;"></div>
                </td>
                <td class="work-scope-column" style="vertical-align: middle;">
                    *Baik Pulih / Ganti Baru:<br>
                    <div class="input-line" style="width: 80%; margin: 2px 0;"></div>
                </td>
                <td class="work-scope-column" style="vertical-align: middle;">
                    *Baik Pulih / Ganti Baru:<br>
                    <div class="input-line" style="width: 80%; margin: 2px 0;"></div>
                </td>
                <td class="work-scope-column" style="vertical-align: middle;">
                    *Baik Pulih / Ganti Baru:<br>
                    <div class="input-line" style="width: 80%; margin: 2px 0;"></div>
                </td>
                <td class="work-scope-column" style="vertical-align: middle;">
                    i. <div class="input-line" style="width: 70%; display: inline-block; margin: 2px 0;"></div><br>
                    ii. <div class="input-line" style="width: 70%; display: inline-block; margin: 2px 0;"></div><br>
                    iii. <div class="input-line" style="width: 70%; display: inline-block; margin: 2px 0;"></div><br>
                    iv. <div class="input-line" style="width: 70%; display: inline-block; margin: 2px 0;"></div>
                </td>
                <td class="work-scope-column" style="vertical-align: middle;">
                    *Baik Pulih / Ganti Baru:<br>
                    <div class="input-line" style="width: 80%; margin: 2px 0;"></div>
                </td>
                <td class="work-scope-column" style="vertical-align: middle;">
                    i. <div class="input-line" style="width: 70%; display: inline-block; margin: 2px 0;"></div><br>
                    ii. <div class="input-line" style="width: 70%; display: inline-block; margin: 2px 0;"></div><br>
                    iii. <div class="input-line" style="width: 70%; display: inline-block; margin: 2px 0;"></div><br>
                    iv. <div class="input-line" style="width: 70%; display: inline-block; margin: 2px 0;"></div>
                </td>
                <td class="work-scope-column" style="vertical-align: middle;">
                    *Baik Pulih / Ganti Baru:<br>
                    <div class="input-line" style="width: 80%; margin: 2px 0;"></div>
                </td>
                <td class="work-scope-column" style="vertical-align: middle;">
                    Baik Pulih:<br>
                    <div class="input-line" style="width: 80%; margin: 2px 0;"></div>
                </td>
            </tr>
            <tr>
                <td class="label-cell" colspan="10"
                    style="padding: 5px 20px; font-size: 8px; font-style: italic; font-weight: normal; text-transform: none;">
                    • Sila tandakan (✓) dalam kotak yang berkenaan<br />
                    • Sila potong mana yang tidak berkenaan<br />
                    • * Sila nyatakan samada Baik Pulih atau Ganti Baru<br />
                    • Sila nyatakan bilangan lif atau tangki yang terlibat di kotak A dan B<br />
                    • Nyatakan kawasan yang berkenaan pada kotak F & H (Bangunan atau jenis infrastruktur/ harta
                    bersama)
                </td>
            </tr>
        </table>

        <!-- Section C: Checklist -->
        <table class="form-table">
            <tr>
                <td class="label-cell" colspan="3">
                    C. Senarai Semak Pemohon (Perkara Yang Perlu Dilampirkan)
                    <br />
                    <small style="font-weight: normal; text-transform: none;">
                        [Sila tandakan (✓) dalam kotak yang berkenaan]
                    </small>
                </td>
            </tr>
            <tr>
                <td class="value-cell" style="width: 5%; text-align: center; font-weight: bold;">
                    1.
                </td>
                <td class="value-cell" style="width: 90%; font-weight: bold;">
                    Satu (1) Salinan Perjanjian Jual Beli - <i>(Sale & Purchase Agreement)</i>
                    <br />
                    <small style="font-weight: normal; text-transform: none; font-style: italic;">
                        (Menunjukkan Harga Belian dengan Jelas)
                    </small>
                </td>
                <td class="value-cell" style="text-align: center; width: 5%; vertical-align: middle;">
                    <span class="checkbox"></span>
                </td>
            </tr>
            <tr>
                <td class="value-cell" style="width: 5%; text-align: center; font-weight: bold;">
                    2.
                </td>
                <td class="value-cell" style="width: 90%; font-weight: bold;">
                    Laporan Perincian Beserta Gambar
                    <br />
                    <small style="font-weight: normal; text-transform: none; font-style: italic;">
                        (Jenis Penyelenggaraan / Lokasi / Nama Kawasan atau Bangunan)
                    </small>
                </td>
                <td class="value-cell" style="text-align: center; width: 5%; vertical-align: middle;">
                    <span class="checkbox"></span>
                </td>
            </tr>
            <tr>
                <td class="value-cell" style="width: 5%; text-align: center; font-weight: bold;">
                    3.
                </td>
                <td class="value-cell" style="width: 90%; font-weight: bold;">
                    Salinan Minit Mesyuarat JMB atau MC atau Persatuan Penduduk
                    <br />
                    <small style="font-weight: normal; text-transform: none; font-style: italic;">
                        (Persetujuan Jawatankuasa ke atas Kerja Penyelenggaraan)
                    </small>
                </td>
                <td class="value-cell" style="text-align: center; width: 5%; vertical-align: middle;">
                    <span class="checkbox"></span>
                </td>
            </tr>
            <tr>
                <td class="value-cell" style="width: 5%; text-align: center; font-weight: bold;">
                    4.
                </td>
                <td class="value-cell" style="width: 90%; font-weight: bold;">
                    Anggaran Kos
                    <br />
                    <small style="font-weight: normal; text-transform: none; font-style: italic;">
                        (Mengikut Skop Permohonan)
                    </small>
                </td>
                <td class="value-cell" style="text-align: center; width: 5%; vertical-align: middle;">
                    <span class="checkbox"></span>
                </td>
            </tr>
        </table>

        <!-- Section D: Declaration -->
        <table class="form-table">
            <tr>
                <td class="label-cell" colspan="2">
                    D. PENGESAHAN PEMOHON
                </td>
            </tr>
            <tr>
                <td class="value-cell" style="padding: 10px; text-align: justify;" colspan="2">
                    Dengan ini saya telah melengkapkan semua perkara-perkara di atas untuk proses kelulusan pihak
                    Kementerian Perumahan dan Kerajaan Tempatan dan <strong>BERSETUJU</strong> dengan perkara-perkara
                    berikut;
                    <br />
                    <ul
                        style="padding-left: 20px; padding-top: 0px; padding-bottom: 0px; list-style-type: lower-roman;">
                        <li>
                            supaya kerja-kerja penyelenggaraan dilaksanakan di kawasan kami;
                        </li>
                        <li>
                            peruntukan 100% disalurkan oleh Kerajaan melalui Program TPPM;
                        </li>
                        <li>
                            permohonan hendaklah dikemukakan kepada COB untuk semakan dan pengesahan terlebih dahulu
                            sebelum dikemukakan kepada KPKT; dan
                        </li>
                        <li>
                            KPKT berhak menolak atau membatalkan permohonan ini dengan serta-merta sekiranya maklumat di
                            atas didapati tidak benar.
                        </li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td class="value-cell" style="padding: 10px; vertical-align: top; font-weight: bold;">
                    <div style="text-align: center; margin-top: 80px;">
                        (Tandatangan Pemohon)
                    </div>
                    <div>
                        Tarikh :
                        <div class="input-line"></div>
                    </div>
                </td>
                <td class="value-cell" style="padding: 10px; vertical-align: bottom; font-weight: bold;">
                    <div style="text-align: center;">
                        (Cop Rasmi JMB/MC)
                    </div>
                </td>
            </tr>
            <tr>
                <td class="value-cell" style="padding: 10px; vertical-align: top; font-weight: bold;" colspan="2">
                    <div style="margin-bottom: 10px;">
                        Nama Pemohon:
                        <div class="input-line"></div>
                    </div>
                    <div style="margin-bottom: 10px;">
                        Jawatan:
                        <div class="input-line"></div>
                    </div>
                    <div style="margin-bottom: 10px;">
                        No. Telefon:
                        <div class="input-line"></div>
                    </div>
                    <div>
                        Emel:
                        <div class="input-line"></div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Footer for Page 2 -->
        <div class="footer">
            <hr class="footer-hr">
            <div class="footer-left">
                Bahagian Penyelenggaraan Perumahan,<br>
                Jabatan Perumahan Negara,<br>
                Kementerian Perumahan dan Kerajaan Tempatan<br>
                Aras 33, No. 51 Persiaran Perdana, Presint 4, 62100 PUTRAJAYA
            </div>
            <div class="footer-right">
                <span class="page-number">2</span>
            </div>
        </div>
    </div>

    <!-- Page 3 -->
    <div style="page-break-before: always;"></div>

    <!-- Page 3 Header -->
    <div class="page-header">LAMPIRAN B</div>

    <!-- Page 3 Content -->
    <div class="main-content">
        <!-- Section E: ULASAN OLEH COB -->
        <table class="form-table">
            <tr>
                <td class="label-cell" colspan="2">
                    E. ULASAN OLEH COB
                </td>
            </tr>
            <tr>
                <td class="value-cell" colspan="2">
                    Permohonan ini adalah memenuhi syarat-syarat seperti di dalam Panduan Permohonan TPPM:
                    <div style="display: inline-block;">
                        YA
                        <br />
                        TIDAK
                    </div>
                </td>
            </tr>
            <tr>
                <td class="input-cell" style="padding: 10px;" colspan="2">
                    Ulasan:
                    <div style="margin-bottom: 10px;">
                        i. <div class="input-line"></div>
                    </div>
                    <div style="margin-bottom: 10px;">
                        ii. <div class="input-line"></div>
                    </div>
                    <div style="margin-bottom: 10px;">
                        iii. <div class="input-line"></div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="value-cell" style="padding: 10px; vertical-align: top; font-weight: bold;">
                    <div style="text-align: center; margin-top: 80px;">
                        (Tandatangan Pegawai)
                    </div>
                    <div>
                        Tarikh :
                        <div class="input-line"></div>
                    </div>
                </td>
                <td class="value-cell" style="padding: 10px; vertical-align: bottom; font-weight: bold;">
                    <div style="text-align: center;">
                        (Cop Rasmi Nama & Jawatan Pegawai)
                    </div>
                </td>
            </tr>
        </table>

        <br>

        <!-- Section F: NOTA PERINGATAN -->
        <table class="form-table">
            <tr>
                <td class="label-cell">
                    F. NOTA PERINGATAN
                </td>
            </tr>
            <tr>
                <td class="value-cell">
                    <ul
                        style="padding-left: 20px; padding-top: 0px; padding-bottom: 0px; list-style-type: lower-roman;">
                        <li>
                            COB hendaklah membuat semakan berhubung syarat-syarat permohonan dan
                            memastikan skop kerja dan pemajuan yang dimohon adalah kritikal dan diperlukan di skim
                            pemajuan
                            tersebut.
                        </li>
                        <li>
                            COB hendaklah memastikan pemajuan yang dimohon di atas tidak pernah
                            menerima apa-apa dana atau bantuan penyelenggaraan bagi skop yang sama.
                        </li>
                        <li>
                            COB adalah berhak untuk menolak permohonan yang didapati tidak lengkap
                            atau tidak memenuhi syarat-syarat TPPM.
                        </li>
                        <li>
                            Projek penyelenggaraan yang dimohon oleh JMB / MC hendaklah merangkumi skop
                            di <strong>ruangan B SAHAJA</strong> dan ulasan kesesuaian projek dibuat oleh COB di
                            <strong>ruangan E</strong>.
                        </li>
                        <li>
                            Anggaran kos hendaklah berpadanan dan bersesuaian dengan skop yang dimohon.
                        </li>
                        <li>
                            Sebarang pindaan kos, perubahan skop dan lokasi skim pemajuan setelah
                            kelulusan dikeluarkan hendaklah dimaklumkan oleh COB dan adalah tertakluk kepada kelulusan
                            lanjut oleh KPKT. Pihak KPKT juga berhak untuk meminda atau membatalkan kelulusan yang telah
                            dikeluarkan kepada PBT.
                        </li>
                    </ul>
                </td>
            </tr>
        </table>

        <!-- Page 3 Footer -->
        <div class="footer">
            <hr class="footer-hr">
            <div class="footer-left">
                Bahagian Penyelenggaraan Perumahan,<br>
                Jabatan Perumahan Negara,<br>
                Kementerian Perumahan dan Kerajaan Tempatan<br>
                Aras 33, No. 51 Persiaran Perdana, Presint 4, 62100 PUTRAJAYA
            </div>
            <div class="footer-right">
                <span class="page-number">3</span>
            </div>
        </div>
    </div>

</body>

</html>
