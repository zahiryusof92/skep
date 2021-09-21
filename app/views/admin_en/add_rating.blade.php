@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 40) {
        $insert_permission = $permission->insert_permission;
    }
}
?>

<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">
                        <form id="ratingSubmit" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.file_no') }}</label>
                                        <select id="file_id" class="form-control select2">
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @foreach ($files as $file)
                                            <option value="{{$file->id}}">{{$file->file_no}}</option>
                                            @endforeach
                                        </select>
                                        <div id="file_id_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><span style="color: red;">*</span> {{ trans('app.forms.date') }}</label>
                                        <label class="input-group datepicker-only-init">
                                            <input type="text" class="form-control" placeholder="{{ trans('app.forms.date') }}" id="date"/>
                                            <span class="input-group-addon">
                                                <i class="icmn-calendar"></i>
                                            </span>
                                        </label>
                                        <div id="date_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <span class="text-danger"><b>Min Score : 1, Max Score : 5</b></span>
                                    <p><b>BAHAGIAN A (PENUBUHAN DAN PENGURUSAN) - Wajaran 25%</b></p>
                                    <p>Pegawai penilai dikehendaki memberikan penilaian berdasarkan penjelasan setiap kriteria seperti yang disenaraikan.</p>
                                    <table class="table table-hover nowrap" id="quality_survey1" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">BIL</th>
                                                <th style="width:80%;">KRITERIA</th>
                                                <th style="width:10%;">SKOR</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1.1</td>
                                                <td>
                                                    <p><b>Mesyuarat Agung Pertama/Tahunan</b></p>
                                                    <p>
                                                        1. &nbsp; Tidak pernah diadakan<br/>
                                                        2. &nbsp; Mesyuarat diadakan tidak setiap tahun<br/>
                                                        3. &nbsp; Mesyuarat diadakan setiap tahun dan mematuhi (kurang daripada 3) peraturan asas<br/>
                                                        4. &nbsp; Mesyuarat diadakan setiap tahun dan mematuhi sebahagian (sekurang-kurangnya 3) peraturan asas<br/>
                                                        5. &nbsp; Mesyuarat diadakan setiap tahun dan mematuhi semua (5) peraturan asas<br/>
                                                    </p>
                                                    <p><b><i>*5 Peraturan Asas - Notis, Kourum, Minit, Akaun Berauidit, Pelantikan AJK</i></b></p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score1" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                            <tr>
                                                <td>1.2</td>
                                                <td>
                                                    <p><b>Mesyuarat Ahli Jawatankuasa/Ahli Majlis</b></p>
                                                    <p>
                                                        1. &nbsp;&nbsp; Mesyuarat tidak pernah diadakan<br/>
                                                        2. &nbsp;&nbsp; Mesyuarat diadakan secara tidak berkala<br/>
                                                        3. &nbsp;&nbsp; Mesyuarat diadakan secara berkala mematuhi semua (1) peraturan asas<br/>
                                                        4. &nbsp;&nbsp; Mesyuarat diadakan secara berkala mematuhi semua (2) peraturan asas<br/>
                                                        5. &nbsp;&nbsp; Mesyuarat diadakan secara berkala mematuhi semua (3) peraturan asas<br/>
                                                    </p>
                                                    <p><b><i>*3 Peraturan Asas - Notis, Kourum, Minit</i></b></p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score2" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                            <tr>
                                                <td>1.3</td>
                                                <td>
                                                    <p><b>Pengurusan Rekod</b></p>
                                                    <p>
                                                        1. &nbsp;&nbsp; Tidak rekod<br/>
                                                        2. &nbsp;&nbsp; Badan mempunyai sekurang-kurangnya (2) rekod<br/>
                                                        3. &nbsp;&nbsp; Badan mempunyai sekurang-kurangnya (3) rekod<br/>
                                                        4. &nbsp;&nbsp; Badan mempunyai sekurang-kurangnya (4) rekod<br/>
                                                        5. &nbsp;&nbsp; Badan mempunyai semua (5) rekod lengkap dan dikemaskini<br/>
                                                    </p>
                                                    <p><b><i>*5 Peraturan Asas – Rekod Badan, Sistem Fail, Daftar Strata, Inventori, Pengurusan Aduan</i></b></p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score3" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                            <tr>
                                                <td>1.4</td>
                                                <td>
                                                    <p><b>Pengurusan Minit Mesyuarat</b></p>
                                                    <p>
                                                        1. &nbsp;&nbsp; Tiada minit mesyuarat<br/>
                                                        2. &nbsp;&nbsp; Minit Mesyuarat mematuhi semua (1) peraturan asas<br/>
                                                        3. &nbsp;&nbsp; Minit Mesyuarat mematuhi semua (2) peraturan asas<br/>
                                                        4. &nbsp;&nbsp; Minit Mesyuarat mematuhi semua (3) peraturan asas<br/>
                                                        5. &nbsp;&nbsp; Minit Mesyuarat mematuhi semua (4) peraturan asas<br/>
                                                    </p>
                                                    <p><b><i>*4 Peraturan Asas – Disediakan, Salinan Minit Disahkan, Difailkan di COB, Dipamerkan</i></b></p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score4" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                            <tr>
                                                <td>1.5</td>
                                                <td>
                                                    <p><b>Pemakluman By Laws kepada penghuni</b></p>
                                                    <p>
                                                        1. &nbsp;&nbsp; Tidak mempunyai By Laws<br/>
                                                        2. &nbsp;&nbsp; By Laws mematuhi semua (1) peraturan asas<br/>
                                                        3. &nbsp;&nbsp; By Laws mematuhi semua (2) peraturan asas<br/>
                                                        4. &nbsp;&nbsp; By Laws mematuhi semua (3) peraturan asas<br/>
                                                        5. &nbsp;&nbsp; By Laws mematuhi semua (4) peraturan asas<br/>
                                                    </p>
                                                    <p><b><i>*4 Peraturan Asas – Diluluskan dalam Mesyuarat Agung, Difailkan ke COB, Dipamerkan, Salinan kepada Pemilik</i></b></p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score5" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr/>
                                    <br/>
                                    <p><b>BAHAGIAN B (KEWANGAN) - Wajaran 25%</b></p>
                                    <p>Pegawai penilai dikehendaki memberikan penilaian berdasarkan penjelasan setiap kriteria seperti yang disenaraikan.</p>
                                    <table class="table table-hover nowrap" id="quality_survey1" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">BIL</th>
                                                <th style="width:80%;">KRITERIA</th>
                                                <th style="width:10%;">SKOR</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>2.1</td>
                                                <td>
                                                    <p><b>Penyata kewangan beraudit oleh Juruaudit Berlesen</b></p>
                                                    <p>
                                                        1. &nbsp; Penyata Kewangan tidak disediakan<br/>
                                                        2. &nbsp; Penyata kewangan disediakan dan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                                        3. &nbsp; Penyata kewangan disediakan dan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                                        4. &nbsp; Penyata kewangan disediakan dan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                                        5. &nbsp; Penyata kewangan disediakan dan mematuhi sekurang-kurangnya (4) peraturan asas<br/>
                                                    </p>
                                                    <p><b><i>*4 Peraturan Asas – Diaudit, Dibentang dalam AGM, Dihantar ke COB, Dipamer</i></b></p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score6" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                            <tr>
                                                <td>2.2</td>
                                                <td>
                                                    <p><b>Akaun penyenggaraan</b></p>
                                                    <p>
                                                        1. &nbsp;&nbsp; Akaun Penyenggaraan tidak dibuka<br/>
                                                        2. &nbsp;&nbsp; Akaun Penyenggaraan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                                        3. &nbsp;&nbsp; Akaun Penyenggaraan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                                        4. &nbsp;&nbsp; Akaun Penyenggaraan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                                        5. &nbsp;&nbsp; Akaun Penyenggaraan mematuhi semua (4) peraturan asas<br/>
                                                    </p>
                                                    <p><b><i>*4 Peraturan Asas – Buka Akaun, Deposit, Rekod Aliran Tunai, Pamer</i></b></p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score7" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                            <tr>
                                                <td>2.3</td>
                                                <td>
                                                    <p><b>Akaun Wang Penjelas</b></p>
                                                    <p>
                                                        1. &nbsp;&nbsp; Akaun Wang Penjelas tidak dibuka<br/>
                                                        2. &nbsp;&nbsp; Akaun Wang Penjelas mematuhi sekurang-kurangnya (1) peraturan<br/>
                                                        3. &nbsp;&nbsp; Akaun Wang Penjelas mematuhi sekurang-kurangnya (2) peraturan<br/>
                                                        4. &nbsp;&nbsp; Akaun Wang Penjelas mematuhi sekurang-kurangnya (3) peraturan<br/>
                                                        5. &nbsp;&nbsp; Akaun Wang Penjelas mematuhi semua (4) peraturan asas<br/>
                                                    </p>
                                                    <p><b><i>*4 Peraturan Asas – Buka Akaun, Deposit, Rekod Aliran Tunai, Pamer</i></b></p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score8" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                            <tr>
                                                <td>2.4</td>
                                                <td>
                                                    <p><b>Rekod tunggakan pemilik petak</b></p>
                                                    <p>
                                                        1. &nbsp;&nbsp; Tiada rekod<br/>
                                                        2. &nbsp;&nbsp; Senarai rekod tunggakan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                                        3. &nbsp;&nbsp; Senarai rekod tunggakan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                                        4. &nbsp;&nbsp; Senarai rekod tunggakan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                                        5. &nbsp;&nbsp; Senarai rekod tunggakan mematuhi semua (4) peraturan<br/>
                                                    </p>
                                                    <p><b><i>*4 Peraturan Asas – Rekod, Kemaskini, Penyata, Notis Peringatan</i></b></p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score9" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                            <tr>
                                                <td>2.5</td>
                                                <td>
                                                    <p><b>Penguatkuasaan kepada pemilik yang mempunyai tunggakan(defaulters)</b></p>
                                                    <p>
                                                        1. &nbsp;&nbsp; Tiada penguatkuasaan terhadap pemilik yang mempunyai tunggakan<br/>
                                                        2. &nbsp;&nbsp; Penguatkuasaan terhadap pemilik yang mempunyai tunggakan sekurang-kurangnya (1) peraturan asas<br/>
                                                        3. &nbsp;&nbsp; Penguatkuasaan terhadap pemilik yang mempunyai tunggakan sekurang-kurangnya (2) peraturan asas<br/>
                                                        4. &nbsp;&nbsp; Penguatkuasaan terhadap pemilik yang mempunyai tunggakan sekurang-kurangnya (3) peraturan asas<br/>
                                                        5. &nbsp;&nbsp; Penguatkuasaan terhadap pemilik yang mempunyai tunggakan mematuhi (4) peraturan asas<br/>
                                                    </p>
                                                    <p><b><i>*4 Peraturan Asas – Pengesahan Tunggakan di Mahkamah, Notis, Bicaraan Mahkamah, Perintah Mahkamah</i></b></p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score10" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr/>
                                    <br/>
                                    <p><b>BAHAGIAN C (PENGURUSAN PENTADBIRAN) - Wajaran 20%</b></p>
                                    <p>Pegawai penilai dikehendaki memberikan penilaian berdasarkan penjelasan setiap kriteria seperti yang disenaraikan.</p>
                                    <table class="table table-hover nowrap" id="quality_survey1" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">BIL</th>
                                                <th style="width:80%;">KRITERIA</th>
                                                <th style="width:10%;">SKOR</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>3.1</td>
                                                <td>
                                                    <p><b>Jadual kerja penyenggaraan</b></p>
                                                    <p>
                                                        1. &nbsp; Tiada jadual kerja<br/>
                                                        2. &nbsp; Ada jadual kerja berkala tetapi tidak dipatuhi<br/>
                                                        3. &nbsp; Ada jadual kerja berkala dan dipatuhi<br/>
                                                        4. &nbsp; Ada jadual kerja yang lengkap, dipatuhi dan sentiasa dipantau<br/>
                                                        5. &nbsp; Ada jadual kerja yang lengkap, dipatuhi, sentiasa dipantau dan dibuat penyediaan laporan<br/>
                                                    </p>
                                                    <p><b><i>*4 Peraturan Asas – Skop Kerja, Jadual Kerja, Berkala, Pemantauan</i></b></p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score11" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                            <tr>
                                                <td>3.2</td>
                                                <td>
                                                    <p><b>Rekod Kos Penyenggaraan (Pembaikkan Dan Penggantian)</b></p>
                                                    <p>
                                                        1. &nbsp;&nbsp; Tiada rekod kos penyenggaraan<br/>
                                                        2. &nbsp;&nbsp; Rekod kos Penyenggaraan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                                        3. &nbsp;&nbsp; Rekod kos Penyenggaraan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                                        4. &nbsp;&nbsp; Rekod kos Penyenggaraan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                                        5. &nbsp;&nbsp; Rekod kos Penyenggaraan mematuhi semua (4) peraturan asas<br/>
                                                    </p>
                                                    <p><b><i>*4 Peraturan Asas – Anggaran, Mesyuarat Pemilihan Kontraktor, Pembayaran, Dipamerkan</i></b></p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score12" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                            <tr>
                                                <td>3.3</td>
                                                <td>
                                                    <p><b>Rekod Aduan Kerosakkan</b></p>
                                                    <p>
                                                        1. &nbsp;&nbsp; Tiada rekod Aduan Kerosakan<br/>
                                                        2. &nbsp;&nbsp; Rekod kos Penyenggaraan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                                        3. &nbsp;&nbsp; Rekod kos Penyenggaraan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                                        4. &nbsp;&nbsp; Rekod kos Penyenggaraan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                                        5. &nbsp;&nbsp; Rekod kos Penyenggaraan mematuhi semua (4) peraturan asas<br/>
                                                    </p>
                                                    <p><b><i>*4 Peraturan Asas – Terima, Siasat, Mesyuarat, Tindakan</i></b></p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score13" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                            <tr>
                                                <td>3.4</td>
                                                <td>
                                                    <p><b>Kualiti Penyenggaraan</b></p>
                                                    <p>
                                                        1. &nbsp;&nbsp; Sangat tidak berpuas hati<br/>
                                                        2. &nbsp;&nbsp; Tidak berpuas hati<br/>
                                                        3. &nbsp;&nbsp; Sederhana<br/>
                                                        4. &nbsp;&nbsp; Berpuas Hati<br/>
                                                        5. &nbsp;&nbsp; Sangat Berpuas Hari<br/>
                                                    </p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score14" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr/>
                                    <br/>
                                    <p><b>BAHAGIAN D (KESEJAHTERAAN PENDUDUK) - Wajaran 20%</b></p>
                                    <p>Pegawai penilai dikehendaki memberikan penilaian berdasarkan penjelasan setiap kriteria seperti yang disenaraikan.</p>
                                    <table class="table table-hover nowrap" id="quality_survey1" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">BIL</th>
                                                <th style="width:80%;">KRITERIA</th>
                                                <th style="width:10%;">SKOR</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>4.1</td>
                                                <td>
                                                    <p><b>Aktiviti Kemasyarakatan di kawasan</b></p>
                                                    <p>
                                                        1. &nbsp;&nbsp; Tiada aktiviti Kemasyarakatan<br/>
                                                        2. &nbsp;&nbsp; Aktiviti Kemasyarakatan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                                        3. &nbsp;&nbsp; Aktiviti Kemasyarakatan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                                        4. &nbsp;&nbsp; Aktiviti Kemasyarakatan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                                        5. &nbsp;&nbsp; Aktiviti Kemasyarakatan mematuhi semua (4) peraturan asas<br/>
                                                    </p>
                                                    <p><b><i>*4 Peraturan asas – Berjadual, Berkala, Mendapat sambutan, Berfaedah</i></b></p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score15" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                            <tr>
                                                <td>4.2</td>
                                                <td>
                                                    <p><b>Rasa Jati Diri Penduduk</b></p>
                                                    <p>
                                                        1. &nbsp;&nbsp; Tiada rasa bangga terhadap kawasan kemajuan<br/>
                                                        2. &nbsp;&nbsp; Kurang rasa bangga dengan kawasan kemajuan<br/>
                                                        3. &nbsp;&nbsp; Sederhana<br/>
                                                        4. &nbsp;&nbsp; Berbangga dengan kawasan kemajuan<br/>
                                                        5. &nbsp;&nbsp; Sangat Berbangga dengan kawasan kemajuan<br/>
                                                    </p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score16" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                            <tr>
                                                <td>4.3</td>
                                                <td>
                                                    <p><b>Rasa selamat dan dilindungi</b></p>
                                                    <p>
                                                        1. &nbsp;&nbsp; Tiada rasa selamat dan dilindungi dalam kawasan kemajuan<br/>
                                                        2. &nbsp;&nbsp; Kurang rasa selamat dan dilindungi dalam kawasan kemajuan<br/>
                                                        3. &nbsp;&nbsp; Sederhana<br/>
                                                        4. &nbsp;&nbsp; Rasa selamat dan dilindungi dalam kawasan kemajuan<br/>
                                                        5. &nbsp;&nbsp; Rasa Sangat selamat dan dilindungi dalam kawasan kemajuan<br/>
                                                    </p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score17" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                            <tr>
                                                <td>4.4</td>
                                                <td>
                                                    <p><b>Semangat Kejiranan</b></p>
                                                    <p>
                                                        1. &nbsp;&nbsp; Tiada Semangat Kejiranan<br/>
                                                        2. &nbsp;&nbsp; Kurang Semangat Kejiranan<br/>
                                                        3. &nbsp;&nbsp; Sederhana<br/>
                                                        4. &nbsp;&nbsp; Tinggi Semangat Kejiranan<br/>
                                                        5. &nbsp;&nbsp; Sangat Tinggi Semangat Kejiranan<br/>
                                                    </p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score18" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr/>
                                    <br/>
                                    <p><b>BAHAGIAN E (PENGURUSAN KESELAMATAN DAN RISIKO) - Wajaran 10%</b></p>
                                    <p>Pegawai penilai dikehendaki memberikan penilaian berdasarkan penjelasan setiap kriteria seperti yang disenaraikan.</p>
                                    <table class="table table-hover nowrap" id="quality_survey1" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:10%;">BIL</th>
                                                <th style="width:80%;">KRITERIA</th>
                                                <th style="width:10%;">SKOR</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>5.1</td>
                                                <td>
                                                    <p><b>Perkhidmatan Keselamatan</b></p>
                                                    <p>
                                                        1. &nbsp;&nbsp; Tiada perkhidmatan keselamatan<br/>
                                                        2. &nbsp;&nbsp; Perkhidmatan keselamatan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                                        3. &nbsp;&nbsp; Perkhidmatan keselamatan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                                        4. &nbsp;&nbsp; Perkhidmatan keselamatan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                                        5. &nbsp;&nbsp; Pengurusan pencegahan kebakaran mematuhi semua (4) peraturan asas<br/>
                                                    </p>
                                                    <p><b><i>*4 Peraturan Asas – Pengawal, Pagar, Pencahayaan, Pelan Susun atur</i></b></p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score19" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                            <tr>
                                                <td>5.2</td>
                                                <td>
                                                    <p><b>Pengurusan Pencegahan Pambakaran</b></p>
                                                    <p>
                                                        1. &nbsp;&nbsp; Tiada pengurusan pencegahan kebakaran<br/>
                                                        2. &nbsp;&nbsp; Pengurusan pencegahan kebakaran mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                                        3. &nbsp;&nbsp; Pengurusan pencegahan kebakaran mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                                        4. &nbsp;&nbsp; Pengurusan pencegahan kebakaran mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                                        5. &nbsp;&nbsp; Akaun Penyenggaraan mematuhi semua (4) peraturan asas<br/>
                                                    </p>
                                                    <p><b><i>*4 Peraturan Asas – Pelan Laluan Kecemasan, Tempat Berkumpul, Riser, Alat Pemadam Api</i></b></p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score20" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                            <tr>
                                                <td>5.3</td>
                                                <td>
                                                    <p><b>Pengurusan Insuran</b></p>
                                                    <p>
                                                        1. &nbsp;&nbsp; Tiada pengurusan insuran<br/>
                                                        2. &nbsp;&nbsp; Pengurusan insuran mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                                        3. &nbsp;&nbsp; Pengurusan insuran mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                                        4. &nbsp;&nbsp; Pengurusan insuran mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                                        5. &nbsp;&nbsp; Pengurusan insuran mematuhi semua (4) peraturan asas<br/>
                                                    </p>
                                                    <p><b><i>*4 Peraturan Asas – Kebakaran, Public Liability, Error and omission. Lain-lain Insurance</i></b></p>
                                                </td>
                                                <td style="vertical-align:middle;"><input type="number" id="score21" class="form-control" placeholder="1" min="1" max="5"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-actions">
                                <?php if ($insert_permission == 1) { ?>
                                    <button type="button" class="btn btn-own" id="submit_button" onclick="submitAddRating()">{{ trans('app.forms.submit') }}</button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location = '{{ URL::action('AdminController@rating') }}'">{{ trans('app.forms.cancel') }}</button>
                                <img id="loading" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>

<!-- Page Scripts -->
<script>
    $(function () {
        $('#date').datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down",
                previous: "fa fa-chevron-left",
                next: "fa fa-chevron-right",
            },
            format: 'YYYY-MM-DD'
        });
        $(":input[type='number']").keyup(function() {
            if(this.value >= 5) {
                this.value = 5;
            } else if(this.value <= 1) {
                this.value = 1;
            }
        });
    });

    function submitAddRating() {
        $("#loading").css("display", "inline-block");
        $("#submit_button").attr("disabled", "disabled");
        $("#cancel_button").attr("disabled", "disabled");
        $("#file_id_error").css("display", "none");
        $("#date_error").css("display", "none");

        var file_id = $("#file_id").val(),
                survey = $("#add_survey").val(),
                date = $("#date").val(),
                score1 = $("#score1").val(),
                score2 = $("#score2").val(),
                score3 = $("#score3").val(),
                score4 = $("#score4").val(),
                score5 = $("#score5").val(),
                score6 = $("#score6").val(),
                score7 = $("#score7").val(),
                score8 = $("#score8").val(),
                score9 = $("#score9").val(),
                score10 = $("#score10").val(),
                score11 = $("#score11").val(),
                score12 = $("#score12").val(),
                score13 = $("#score13").val(),
                score14 = $("#score14").val(),
                score15 = $("#score15").val(),
                score16 = $("#score16").val(),
                score17 = $("#score17").val(),
                score18 = $("#score18").val(),
                score19 = $("#score19").val(),
                score20 = $("#score20").val(),
                score21 = $("#score21").val();

        var error = 0;

        if (file_id.trim() == "") {
            $("#file_id_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"File No"]) }}</span>');
            $("#file_id_error").css("display", "block");
            $("#file_id").focus();
            error = 1;
        }
        if (date.trim() == "") {
            $("#date_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Date"]) }}</span>');
            $("#date_error").css("display", "block");
            $("#date").focus();
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@submitAddRating') }}",
                type: "POST",
                data: {
                    survey: survey,
                    date: date,
                    score1: score1,
                    score2: score2,
                    score3: score3,
                    score4: score4,
                    score5: score5,
                    score6: score6,
                    score7: score7,
                    score8: score8,
                    score9: score9,
                    score10: score10,
                    score11: score11,
                    score12: score12,
                    score13: score13,
                    score14: score14,
                    score15: score15,
                    score16: score16,
                    score17: score17,
                    score18: score18,
                    score19: score19,
                    score20: score20,
                    score21: score21,
                    file_id: file_id
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.ratings.store') }}</span>", function () {
                            window.location = '{{URL::action("AdminController@rating") }}';
                        });
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        } else {
            $("#loading").css("display", "none");
            $("#submit_button").removeAttr("disabled");
            $("#cancel_button").removeAttr("disabled");
        }
    }
</script>
<!-- End Page Scripts-->

@stop
