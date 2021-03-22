@extends('layout.english_layout.default')

@section('content')

<?php
$update_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 3) {
        $update_permission = $permission->update_permission;
    }
}
?>

<div class="page-content-inner">
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h6>{{ trans('app.forms.file_no') }}: {{$files->file_no}}</h6>
                    <div id="update_files_lists">
                        <ul class="nav nav-pills nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@viewHouse', $files->id)}}">{{ trans('app.forms.housing_scheme') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@viewStrata', $files->id)}}">{{ trans('app.forms.developed_area') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@viewManagement', $files->id)}}">{{ trans('app.forms.management') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@viewMonitoring', $files->id)}}">{{ trans('app.forms.monitoring') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@viewOthers', $files->id)}}">{{ trans('app.forms.others') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active">{{ trans('app.forms.scoring_component_value') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@viewBuyer', $files->id)}}">{{ trans('app.forms.buyer_list') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@fileApproval', $files->id)}}">{{ trans('app.forms.approval') }}</a>
                            </li>
                        </ul>
                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="scoring" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php
                                            $scoring = Scoring::where('file_id', $files->id)->where('is_deleted', 0)->count();
                                        ?>
                                        <div class="row">
                                            <table class="table table-hover nowrap" id="scoring_list" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th style="width:15%;">{{ trans('app.forms.date') }}</th>
                                                        <th style="width:10%;">A (%)</th>
                                                        <th style="width:10%;">B (%)</th>
                                                        <th style="width:10%;">C (%)</th>
                                                        <th style="width:10%;">D (%)</th>
                                                        <th style="width:10%;">E (%)</th>
                                                        <th style="width:10%;">{{ trans('app.forms.score') }} (%)</th>
                                                        <th style="width:15%;">{{ trans('app.forms.rating') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End -->
</div>

<div class="modal fade modal-size-large" id="add_strata_management_quality" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Borang Indeks Kualiti Pengurusan Bangunan Berstrata</h4>
            </div>
            <div class="modal-body">
                <form>
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
                                        0. &nbsp; Tidak rekod diadakan<br/>
                                        1. &nbsp; Mesyuarat diadakan tidak setiap tahun<br/>
                                        2. &nbsp; Mesyuarat diadakan setiap tahun dan mematuhi (kurang daripada 3) peraturan asas<br/>
                                        3. &nbsp; Mesyuarat diadakan setiap tahun dan mematuhi sebahagian (sekurang-kurangnya 3) peraturan asas<br/>
                                        4. &nbsp; Mesyuarat diadakan setiap tahun dan mematuhi semua (5) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*5 Peraturan Asas - Notis, Kourum, Minit, Akaun Berauidit, Pelantikan AJK</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score1" class="form-control" placeholder="0" max="4"></td>
                            </tr>
                            <tr>
                                <td>1.2</td>
                                <td>
                                    <p><b>Mesyuarat Ahli Jawatankuasa/Ahli Majlis</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Mesyuarat tidak pernah diadakan<br/>
                                        1. &nbsp;&nbsp; Mesyuarat diadakan secara tidak berkala<br/>
                                        2. &nbsp;&nbsp; Mesyuarat diadakan secara berkala mematuhi semua (1) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Mesyuarat diadakan secara berkala mematuhi semua (2) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Mesyuarat diadakan secara berkala mematuhi semua (3) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*3 Peraturan Asas - Notis, Kourum, Minit</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score2" class="form-control" placeholder="0" max="4"></td>
                            </tr>
                            <tr>
                                <td>1.3</td>
                                <td>
                                    <p><b>Pengurusan Rekod</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tidak rekod<br/>
                                        1. &nbsp;&nbsp; Badan mempunyai sekurang-kurangnya (2) rekod<br/>
                                        2. &nbsp;&nbsp; Badan mempunyai sekurang-kurangnya (3) rekod<br/>
                                        3. &nbsp;&nbsp; Badan mempunyai sekurang-kurangnya (4) rekod<br/>
                                        4. &nbsp;&nbsp; Badan mempunyai semua (5) rekod lengkap dan dikemaskini<br/>
                                    </p>
                                    <p><b><i>*5 Peraturan Asas – Rekod Badan, Sistem Fail, Daftar Strata, Inventori, Pengurusan Aduan</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score3" class="form-control" placeholder="0" max="4"></td>
                            </tr>
                            <tr>
                                <td>1.4</td>
                                <td>
                                    <p><b>Pengurusan Rekod</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada minit mesyuarat<br/>
                                        1. &nbsp;&nbsp; Minit Mesyuarat mematuhi semua (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Minit Mesyuarat mematuhi semua (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Minit Mesyuarat mematuhi semua (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Minit Mesyuarat mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Disediakan, Salinin Minit Disahkan, Difailkan di COB, Dipamerkan</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score4" class="form-control" placeholder="0" max="4"></td>
                            </tr>
                            <tr>
                                <td>1.5</td>
                                <td>
                                    <p><b>Pemakluman By Laws kepada penghuni</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tidak mempunyai By Laws<br/>
                                        1. &nbsp;&nbsp; By Laws mematuhi semua (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; By Laws mematuhi semua (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; By Laws mematuhi semua (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; By Laws mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Diluluskan dalam Mesyuarat Agung, Difailkan ke COB, Dipamerkan, Salinan kepada Pemilik</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score5" class="form-control" placeholder="0" max="4"></td>
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
                                        0. &nbsp; Penyata Kewangan tidak disediakan<br/>
                                        1. &nbsp; Penyata kewangan disediakan dan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp; Penyata kewangan disediakan dan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp; Penyata kewangan disediakan dan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp; Penyata kewangan disediakan dan mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Diaudit, Dibentang dalam AGM, Dihantar ke COB, Dipamer</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score6" class="form-control" placeholder="0" max="4"></td>
                            </tr>
                            <tr>
                                <td>2.2</td>
                                <td>
                                    <p><b>Akaun penyengaraan</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Akaun Penyengaraan tidak dibuka<br/>
                                        1. &nbsp;&nbsp; Akaun Penyengaraan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Akaun Penyengaraan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Akaun Penyengaraan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Akaun Penyengaraan mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Buka Akaun, Deposit, Rekod Aliran Tunai, Pamer</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score7" class="form-control" placeholder="0" max="4"></td>
                            </tr>
                            <tr>
                                <td>2.3</td>
                                <td>
                                    <p><b>Akaun Wang Penjelas</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Akaun Wang Penjelas tidak dibuka<br/>
                                        1. &nbsp;&nbsp; Akaun Wang Penjelas mematuhi sekurang-kurangnya (1) peraturan<br/>
                                        2. &nbsp;&nbsp; Akaun Wang Penjelas mematuhi sekurang-kurangnya (2) peraturan<br/>
                                        3. &nbsp;&nbsp; Akaun Wang Penjelas mematuhi sekurang-kurangnya (3) peraturan<br/>
                                        4. &nbsp;&nbsp; Akaun Wang Penjelas mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Buka Akaun, Deposit, Rekod Aliran Tunai, Pamer</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score8" class="form-control" placeholder="0" max="4"></td>
                            </tr>
                            <tr>
                                <td>2.4</td>
                                <td>
                                    <p><b>Rekod tunggakan pemilik petak</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada rekod<br/>
                                        1. &nbsp;&nbsp; Senarai rekod tunggakan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Senarai rekod tunggakan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Senarai rekod tunggakan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Ada senarai rekod tunggakan mematuhi semua (4) peraturan<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Rekod, Kemaskini, Penyata, Notis Peringatan</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score9" class="form-control" placeholder="0" max="4"></td>
                            </tr>
                            <tr>
                                <td>2.5</td>
                                <td>
                                    <p><b>Penguatkuasaan kepada pemilik yang mempunyai tunggakan(defaulters)</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada penguatkuasaan terhadap pemilik yang mempunyai tunggakan<br/>
                                        1. &nbsp;&nbsp; Penguatkuasaan terhadap pemilik yang mempunyai tunggakan sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Penguatkuasaan terhadap pemilik yang mempunyai tunggakan sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Penguatkuasaan terhadap pemilik yang mempunyai tunggakan sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Penguatkuasaan terhadap pemilik yang mempunyai tunggakan mematuhi (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Pengesahan Tunggakan di Mahkamah, Notis, Bicaraan Mahkamah, Perintah Mahkamah</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score10" class="form-control" placeholder="0" max="4"></td>
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
                                    <p><b>Jadual kerja penyengaraan</b></p>
                                    <p>
                                        0. &nbsp; Tiada jadual kerja<br/>
                                        1. &nbsp; Ada jadual kerja berkala tetapi tidak dipatuhi<br/>
                                        2. &nbsp; Ada jadual kerja berkala dan dipatuhi<br/>
                                        3. &nbsp; Ada jadual kerja yang lengkap, dipatuhi dan sentiasa dipantau<br/>
                                        4. &nbsp; Ada jadual kerja yang lengkap, dipatuhi, sentiasa dipantau dan dibuat penyediaan laporan<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Skop Kerja, Jadual Kerja, Berkala, Pemantauan</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score11" class="form-control" placeholder="0" max="4"></td>
                            </tr>
                            <tr>
                                <td>3.2</td>
                                <td>
                                    <p><b>Rekod Kos Penyengaraan (Pembaikkan Dan Penggantian)</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada rekod kos penyengaraan<br/>
                                        1. &nbsp;&nbsp; Rekod kos Penyengaraan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Rekod kos Penyengaraan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Rekod kos Penyengaraan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Rekod kos Penyengaraan mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Anggaran, Mesyuarat Pemilihan Kontraktor, Pembayaran, Dipamerkan</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score12" class="form-control" placeholder="0" max="4"></td>
                            </tr>
                            <tr>
                                <td>3.3</td>
                                <td>
                                    <p><b>Rekod Aduan Kerosakkan</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada rekod Aduan Kerosakan<br/>
                                        1. &nbsp;&nbsp; Rekod kos Penyengaraan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Rekod kos Penyengaraan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Rekod kos Penyengaraan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Rekod kos Penyengaraan mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Terima, Siasat, Mesyuarat, Tindakan</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score13" class="form-control" placeholder="0" max="4"></td>
                            </tr>
                            <tr>
                                <td>3.4</td>
                                <td>
                                    <p><b>Kualiti Penyengaraan</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Sangat tidak berpuas hati<br/>
                                        1. &nbsp;&nbsp; Tidak berpuas hati<br/>
                                        2. &nbsp;&nbsp; Sederhana<br/>
                                        3. &nbsp;&nbsp; Berpuas Hati<br/>
                                        4. &nbsp;&nbsp; Sangat Berpuas Hari<br/>
                                    </p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score14" class="form-control" placeholder="0" max="4"></td>
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
                                        0. &nbsp;&nbsp; Tiada aktiviti Kemasyarakatan<br/>
                                        1. &nbsp;&nbsp; Aktiviti Kemasyarakatan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Aktiviti Kemasyarakatan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Aktiviti Kemasyarakatan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Aktiviti Kemasyarakatan mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan asas – Berjadual, Berkala, Mendapat sambutan, Berfaedah</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score15" class="form-control" placeholder="0" max="4"></td>
                            </tr>
                            <tr>
                                <td>4.2</td>
                                <td>
                                    <p><b>Rasa Jati Diri Penduduk</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada rasa bangga terhadap kawasan kemajuan<br/>
                                        1. &nbsp;&nbsp; Kurang rasa bangga dengan kawasan kemajuan<br/>
                                        2. &nbsp;&nbsp; Sederhana<br/>
                                        3. &nbsp;&nbsp; Berbangga dengan kawasan kemajuan<br/>
                                        4. &nbsp;&nbsp; Sangat Berbangga dengan kawasan kemajuan<br/>
                                    </p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score16" class="form-control" placeholder="0" max="4"></td>
                            </tr>
                            <tr>
                                <td>4.3</td>
                                <td>
                                    <p><b>Rasa selamat dan dilindungi</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada rasa selamat dan dilindungi dalam kawasan kemajuan<br/>
                                        1. &nbsp;&nbsp; Kurang rasa selamat dan dilindungi dalam kawasan kemajuan<br/>
                                        2. &nbsp;&nbsp; Sederhana<br/>
                                        3. &nbsp;&nbsp; Rasa selamat dan dilindungi dalam kawasan kemajuan<br/>
                                        4. &nbsp;&nbsp; Rasa Sangat selamat dan dilindungi dalam kawasan kemajuan<br/>
                                    </p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score17" class="form-control" placeholder="0" max="4"></td>
                            </tr>
                            <tr>
                                <td>4.4</td>
                                <td>
                                    <p><b>Semangat Kejiranan</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada Semangat Kejiranan<br/>
                                        1. &nbsp;&nbsp; Kurang Semangat Kejiranan<br/>
                                        2. &nbsp;&nbsp; Sederhana<br/>
                                        3. &nbsp;&nbsp; Tinggi Semangat Kejiranan<br/>
                                        4. &nbsp;&nbsp; Sangat Tinggi Semangat Kejiranan<br/>
                                    </p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score18" class="form-control" placeholder="0" max="4"></td>
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
                                        0. &nbsp;&nbsp; Tiada perkhidmatan keselamatan<br/>
                                        1. &nbsp;&nbsp; Perkhidmatan keselamatan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Perkhidmatan keselamatan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Perkhidmatan keselamatan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Pengurusan pencegahan kebakaran mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Pengawal, Pagar, Pencahayaan, Pelan Susun atur</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score19" class="form-control" placeholder="0" max="4"></td>
                            </tr>
                            <tr>
                                <td>5.2</td>
                                <td>
                                    <p><b>Pengurusan pencegahan Pambakaran</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada pengurusan pencegahan kebakaran<br/>
                                        1. &nbsp;&nbsp; Pengurusan pencegahan kebakaran mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Pengurusan pencegahan kebakaran mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Pengurusan pencegahan kebakaran mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Akaun Penyengaraan mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Pelan Laluan Kecemasan, Tempat Berkumpul, Riser, Alat Pemadam Api</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score20" class="form-control" placeholder="0" max="4"></td>
                            </tr>
                            <tr>
                                <td>5.3</td>
                                <td>
                                    <p><b>Pengurusan Insuran</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada pengurusan insuran<br/>
                                        1. &nbsp;&nbsp; Pengurusan insuran mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Pengurusan insuran mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Pengurusan insuran mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Pengurusan insuran mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Kebakaran, Public Liability, Error and omission. Lain-lain Insurance</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score21" class="form-control" placeholder="0" max="4"></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <form>
                    <button type="button" id="cancel_button" class="btn" data-dismiss="modal">
                        {{ trans('app.forms.close') }}
                    </button>
                    <button type="button" id="submit_button" class="btn btn-own" onclick="addScoring()">
                        {{ trans('app.forms.submit') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-size-large" id="edit_strata_management_quality" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Borang Indeks Kualiti Pengurusan Bangunan Berstrata</h4>
            </div>
            <div class="modal-body">
                <form>
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
                                        0. &nbsp; Tidak rekod diadakan<br/>
                                        1. &nbsp; Mesyuarat diadakan tidak setiap tahun<br/>
                                        2. &nbsp; Mesyuarat diadakan setiap tahun dan mematuhi (kurang daripada 3) peraturan asas<br/>
                                        3. &nbsp; Mesyuarat diadakan setiap tahun dan mematuhi sebahagian (sekurang-kurangnya 3) peraturan asas<br/>
                                        4. &nbsp; Mesyuarat diadakan setiap tahun dan mematuhi semua (5) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*5 Peraturan Asas - Notis, Kourum, Minit, Akaun Berauidit, Pelantikan AJK</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score1_edit" class="form-control" max="4"></td>
                            </tr>
                            <tr>
                                <td>1.2</td>
                                <td>
                                    <p><b>Mesyuarat Ahli Jawatankuasa/Ahli Majlis</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Mesyuarat tidak pernah diadakan<br/>
                                        1. &nbsp;&nbsp; Mesyuarat diadakan secara tidak berkala<br/>
                                        2. &nbsp;&nbsp; Mesyuarat diadakan secara berkala mematuhi semua (1) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Mesyuarat diadakan secara berkala mematuhi semua (2) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Mesyuarat diadakan secara berkala mematuhi semua (3) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*3 Peraturan Asas - Notis, Kourum, Minit</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score2_edit" class="form-control" max="4"></td>
                            </tr>
                            <tr>
                                <td>1.3</td>
                                <td>
                                    <p><b>Pengurusan Rekod</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tidak rekod<br/>
                                        1. &nbsp;&nbsp; Badan mempunyai sekurang-kurangnya (2) rekod<br/>
                                        2. &nbsp;&nbsp; Badan mempunyai sekurang-kurangnya (3) rekod<br/>
                                        3. &nbsp;&nbsp; Badan mempunyai sekurang-kurangnya (4) rekod<br/>
                                        4. &nbsp;&nbsp; Badan mempunyai semua (5) rekod lengkap dan dikemaskini<br/>
                                    </p>
                                    <p><b><i>*5 Peraturan Asas – Rekod Badan, Sistem Fail, Daftar Strata, Inventori, Pengurusan Aduan</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score3_edit" class="form-control" max="4"></td>
                            </tr>
                            <tr>
                                <td>1.4</td>
                                <td>
                                    <p><b>Pengurusan Rekod</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada minit mesyuarat<br/>
                                        1. &nbsp;&nbsp; Minit Mesyuarat mematuhi semua (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Minit Mesyuarat mematuhi semua (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Minit Mesyuarat mematuhi semua (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Minit Mesyuarat mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Disediakan, Salinin Minit Disahkan, Difailkan di COB, Dipamerkan</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score4_edit" class="form-control" max="4"></td>
                            </tr>
                            <tr>
                                <td>1.5</td>
                                <td>
                                    <p><b>Pemakluman By Laws kepada penghuni</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tidak mempunyai By Laws<br/>
                                        1. &nbsp;&nbsp; By Laws mematuhi semua (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; By Laws mematuhi semua (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; By Laws mematuhi semua (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; By Laws mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Diluluskan dalam Mesyuarat Agung, Difailkan ke COB, Dipamerkan, Salinan kepada Pemilik</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score5_edit" class="form-control" max="4"></td>
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
                                        0. &nbsp; Penyata Kewangan tidak disediakan<br/>
                                        1. &nbsp; Penyata kewangan disediakan dan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp; Penyata kewangan disediakan dan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp; Penyata kewangan disediakan dan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp; Penyata kewangan disediakan dan mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Diaudit, Dibentang dalam AGM, Dihantar ke COB, Dipamer</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score6_edit" class="form-control" max="4"></td>
                            </tr>
                            <tr>
                                <td>2.2</td>
                                <td>
                                    <p><b>Akaun penyengaraan</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Akaun Penyengaraan tidak dibuka<br/>
                                        1. &nbsp;&nbsp; Akaun Penyengaraan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Akaun Penyengaraan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Akaun Penyengaraan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Akaun Penyengaraan mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Buka Akaun, Deposit, Rekod Aliran Tunai, Pamer</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score7_edit" class="form-control" max="4"></td>
                            </tr>
                            <tr>
                                <td>2.3</td>
                                <td>
                                    <p><b>Akaun Wang Penjelas</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Akaun Wang Penjelas tidak dibuka<br/>
                                        1. &nbsp;&nbsp; Akaun Wang Penjelas mematuhi sekurang-kurangnya (1) peraturan<br/>
                                        2. &nbsp;&nbsp; Akaun Wang Penjelas mematuhi sekurang-kurangnya (2) peraturan<br/>
                                        3. &nbsp;&nbsp; Akaun Wang Penjelas mematuhi sekurang-kurangnya (3) peraturan<br/>
                                        4. &nbsp;&nbsp; Akaun Wang Penjelas mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Buka Akaun, Deposit, Rekod Aliran Tunai, Pamer</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score8_edit" class="form-control" max="4"></td>
                            </tr>
                            <tr>
                                <td>2.4</td>
                                <td>
                                    <p><b>Rekod tunggakan pemilik petak</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada rekod<br/>
                                        1. &nbsp;&nbsp; Senarai rekod tunggakan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Senarai rekod tunggakan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Senarai rekod tunggakan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Ada senarai rekod tunggakan mematuhi semua (4) peraturan<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Rekod, Kemaskini, Penyata, Notis Peringatan</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score9_edit" class="form-control" max="4"></td>
                            </tr>
                            <tr>
                                <td>2.5</td>
                                <td>
                                    <p><b>Penguatkuasaan kepada pemilik yang mempunyai tunggakan(defaulters)</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada penguatkuasaan terhadap pemilik yang mempunyai tunggakan<br/>
                                        1. &nbsp;&nbsp; Penguatkuasaan terhadap pemilik yang mempunyai tunggakan sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Penguatkuasaan terhadap pemilik yang mempunyai tunggakan sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Penguatkuasaan terhadap pemilik yang mempunyai tunggakan sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Penguatkuasaan terhadap pemilik yang mempunyai tunggakan mematuhi (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Pengesahan Tunggakan di Mahkamah, Notis, Bicaraan Mahkamah, Perintah Mahkamah</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score10_edit" class="form-control" max="4"></td>
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
                                    <p><b>Jadual kerja penyengaraan</b></p>
                                    <p>
                                        0. &nbsp; Tiada jadual kerja<br/>
                                        1. &nbsp; Ada jadual kerja berkala tetapi tidak dipatuhi<br/>
                                        2. &nbsp; Ada jadual kerja berkala dan dipatuhi<br/>
                                        3. &nbsp; Ada jadual kerja yang lengkap, dipatuhi dan sentiasa dipantau<br/>
                                        4. &nbsp; Ada jadual kerja yang lengkap, dipatuhi, sentiasa dipantau dan dibuat penyediaan laporan<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Skop Kerja, Jadual Kerja, Berkala, Pemantauan</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score11_edit" class="form-control" max="4"></td>
                            </tr>
                            <tr>
                                <td>3.2</td>
                                <td>
                                    <p><b>Rekod Kos Penyengaraan (Pembaikkan Dan Penggantian)</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada rekod kos penyengaraan<br/>
                                        1. &nbsp;&nbsp; Rekod kos Penyengaraan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Rekod kos Penyengaraan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Rekod kos Penyengaraan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Rekod kos Penyengaraan mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Anggaran, Mesyuarat Pemilihan Kontraktor, Pembayaran, Dipamerkan</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score12_edit" class="form-control" max="4"></td>
                            </tr>
                            <tr>
                                <td>3.3</td>
                                <td>
                                    <p><b>Rekod Aduan Kerosakkan</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada rekod Aduan Kerosakan<br/>
                                        1. &nbsp;&nbsp; Rekod kos Penyengaraan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Rekod kos Penyengaraan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Rekod kos Penyengaraan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Rekod kos Penyengaraan mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Terima, Siasat, Mesyuarat, Tindakan</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score13_edit" class="form-control" max="4"></td>
                            </tr>
                            <tr>
                                <td>3.4</td>
                                <td>
                                    <p><b>Kualiti Penyengaraan</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Sangat tidak berpuas hati<br/>
                                        1. &nbsp;&nbsp; Tidak berpuas hati<br/>
                                        2. &nbsp;&nbsp; Sederhana<br/>
                                        3. &nbsp;&nbsp; Berpuas Hati<br/>
                                        4. &nbsp;&nbsp; Sangat Berpuas Hari<br/>
                                    </p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score14_edit" class="form-control" max="4"></td>
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
                                        0. &nbsp;&nbsp; Tiada aktiviti Kemasyarakatan<br/>
                                        1. &nbsp;&nbsp; Aktiviti Kemasyarakatan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Aktiviti Kemasyarakatan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Aktiviti Kemasyarakatan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Aktiviti Kemasyarakatan mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan asas – Berjadual, Berkala, Mendapat sambutan, Berfaedah</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score15_edit" class="form-control" max="4"></td>
                            </tr>
                            <tr>
                                <td>4.2</td>
                                <td>
                                    <p><b>Rasa Jati Diri Penduduk</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada rasa bangga terhadap kawasan kemajuan<br/>
                                        1. &nbsp;&nbsp; Kurang rasa bangga dengan kawasan kemajuan<br/>
                                        2. &nbsp;&nbsp; Sederhana<br/>
                                        3. &nbsp;&nbsp; Berbangga dengan kawasan kemajuan<br/>
                                        4. &nbsp;&nbsp; Sangat Berbangga dengan kawasan kemajuan<br/>
                                    </p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score16_edit" class="form-control" max="4"></td>
                            </tr>
                            <tr>
                                <td>4.3</td>
                                <td>
                                    <p><b>Rasa selamat dan dilindungi</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada rasa selamat dan dilindungi dalam kawasan kemajuan<br/>
                                        1. &nbsp;&nbsp; Kurang rasa selamat dan dilindungi dalam kawasan kemajuan<br/>
                                        2. &nbsp;&nbsp; Sederhana<br/>
                                        3. &nbsp;&nbsp; Rasa selamat dan dilindungi dalam kawasan kemajuan<br/>
                                        4. &nbsp;&nbsp; Rasa Sangat selamat dan dilindungi dalam kawasan kemajuan<br/>
                                    </p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score17_edit" class="form-control" max="4"></td>
                            </tr>
                            <tr>
                                <td>4.4</td>
                                <td>
                                    <p><b>Semangat Kejiranan</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada Semangat Kejiranan<br/>
                                        1. &nbsp;&nbsp; Kurang Semangat Kejiranan<br/>
                                        2. &nbsp;&nbsp; Sederhana<br/>
                                        3. &nbsp;&nbsp; Tinggi Semangat Kejiranan<br/>
                                        4. &nbsp;&nbsp; Sangat Tinggi Semangat Kejiranan<br/>
                                    </p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score18_edit" class="form-control" max="4"></td>
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
                                        0. &nbsp;&nbsp; Tiada perkhidmatan keselamatan<br/>
                                        1. &nbsp;&nbsp; Perkhidmatan keselamatan mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Perkhidmatan keselamatan mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Perkhidmatan keselamatan mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Pengurusan pencegahan kebakaran mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Pengawal, Pagar, Pencahayaan, Pelan Susun atur</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score19_edit" class="form-control" max="4"></td>
                            </tr>
                            <tr>
                                <td>5.2</td>
                                <td>
                                    <p><b>Pengurusan pencegahan Pambakaran</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada pengurusan pencegahan kebakaran<br/>
                                        1. &nbsp;&nbsp; Pengurusan pencegahan kebakaran mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Pengurusan pencegahan kebakaran mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Pengurusan pencegahan kebakaran mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Akaun Penyengaraan mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Pelan Laluan Kecemasan, Tempat Berkumpul, Riser, Alat Pemadam Api</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score20_edit" class="form-control" max="4"></td>
                            </tr>
                            <tr>
                                <td>5.3</td>
                                <td>
                                    <p><b>Pengurusan Insuran</b></p>
                                    <p>
                                        0. &nbsp;&nbsp; Tiada pengurusan insuran<br/>
                                        1. &nbsp;&nbsp; Pengurusan insuran mematuhi sekurang-kurangnya (1) peraturan asas<br/>
                                        2. &nbsp;&nbsp; Pengurusan insuran mematuhi sekurang-kurangnya (2) peraturan asas<br/>
                                        3. &nbsp;&nbsp; Pengurusan insuran mematuhi sekurang-kurangnya (3) peraturan asas<br/>
                                        4. &nbsp;&nbsp; Pengurusan insuran mematuhi semua (4) peraturan asas<br/>
                                    </p>
                                    <p><b><i>*4 Peraturan Asas – Kebakaran, Public Liability, Error and omission. Lain-lain Insurance</i></b></p>
                                </td>
                                <td style="vertical-align:middle;"><input type="number" id="score21_edit" class="form-control" max="4"></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <form>
                    <input type="hidden" id="scoring_id"/>
                    <button type="button" id="cancel_button_edit" class="btn" data-dismiss="modal">
                        {{ trans('app.forms.close') }}
                    </button>
                    <button type="button" id="submit_button_edit" class="btn btn-own" onclick="editScoring()">
                        {{ trans('app.forms.submit') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Page Scripts -->
<script>
    var oTable;
    $(document).ready(function () {
        oTable = $('#scoring_list').DataTable({
            "sAjaxSource": "{{URL::action('AdminController@getScoring', $files->id)}}",
            "order": [[ 0, "desc" ]],
            "autoWidth": true,
            "scrollX": true,
            "responsive": false
        });
    });

    function addSurveyForm(){
        var addsurvey = $("#add_survey").val();
        if (addsurvey.trim() == "strata_management") {
            $("#add_strata_management_quality").modal("show");
        } else {
            bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
        }
    }

    function addScoring() {
        $("#loading").css("display", "inline-block");

        var score1 = $("#score1").val(),
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
            score21 = $("#score21").val(),
            survey = $("#add_survey").val();

        var error = 0;

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@addScoring') }}",
                type: "POST",
                data: {
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
                    survey: survey,
                    file_id: '{{$files->id}}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $('#add_strata_management_quality').modal('hide');
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>'
                        }, {
                            type: 'success',
                            placement: {
                                align: "center"
                            }
                        });
                        location.reload();
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        }
    }

    function editSurveyForm($survey){
        var editsurvey = $survey;
        if (editsurvey.trim() == "strata_management") {
            $("#edit_strata_management_quality").modal("show");
        } else {
            bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
        }
    }

    $(document).on("click", '.edit_survey', function (e) {
        var score1 = $(this).data('score1'),
            score2 = $(this).data('score2'),
            score3 = $(this).data('score3'),
            score4 = $(this).data('score4'),
            score5 = $(this).data('score5'),
            score6 = $(this).data('score6'),
            score7 = $(this).data('score7'),
            score8 = $(this).data('score8'),
            score9 = $(this).data('score9'),
            score10 = $(this).data('score10'),
            score11 = $(this).data('score11'),
            score12 = $(this).data('score12'),
            score13 = $(this).data('score13'),
            score14 = $(this).data('score14'),
            score15 = $(this).data('score15'),
            score16 = $(this).data('score16'),
            score17 = $(this).data('score17'),
            score18 = $(this).data('score18'),
            score19 = $(this).data('score19'),
            score20 = $(this).data('score20'),
            score21 = $(this).data('score21'),
            id = $(this).data('id');

        $("#score1_edit").val(score1);
        $("#score2_edit").val(score2);
        $("#score3_edit").val(score3);
        $("#score4_edit").val(score4);
        $("#score5_edit").val(score5);
        $("#score6_edit").val(score6);
        $("#score7_edit").val(score7);
        $("#score8_edit").val(score8);
        $("#score9_edit").val(score9);
        $("#score10_edit").val(score10);
        $("#score11_edit").val(score11);
        $("#score12_edit").val(score12);
        $("#score13_edit").val(score13);
        $("#score14_edit").val(score14);
        $("#score15_edit").val(score15);
        $("#score16_edit").val(score16);
        $("#score17_edit").val(score17);
        $("#score18_edit").val(score18);
        $("#score19_edit").val(score19);
        $("#score20_edit").val(score20);
        $("#score21_edit").val(score21);
        $("#scoring_id").val(id);
    });

    function editScoring() {
        $("#loading").css("display", "inline-block");

        var score1 = $("#score1_edit").val(),
            score2 = $("#score2_edit").val(),
            score3 = $("#score3_edit").val(),
            score4 = $("#score4_edit").val(),
            score5 = $("#score5_edit").val(),
            score6 = $("#score6_edit").val(),
            score7 = $("#score7_edit").val(),
            score8 = $("#score8_edit").val(),
            score9 = $("#score9_edit").val(),
            score10 = $("#score10_edit").val(),
            score11 = $("#score11_edit").val(),
            score12 = $("#score12_edit").val(),
            score13 = $("#score13_edit").val(),
            score14 = $("#score14_edit").val(),
            score15 = $("#score15_edit").val(),
            score16 = $("#score16_edit").val(),
            score17 = $("#score17_edit").val(),
            score18 = $("#score18_edit").val(),
            score19 = $("#score19_edit").val(),
            score20 = $("#score20_edit").val(),
            score21 = $("#score21_edit").val(),
            id = $("#scoring_id").val();

        var error = 0;

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@editScoring') }}",
                type: "POST",
                data: {
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
                    id: id
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $('#edit_strata_management_quality').modal('hide');
                    if (data.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.updated_successfully") }}</p>'
                        }, {
                            type: 'success',
                            placement: {
                                align: "center"
                            }
                        });
                        location.reload();
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        }
    }

    function deleteScoring(id) {
        swal({
            title: "{{ trans('app.confirmation.are_you_sure') }}",
            text: "{{ trans('app.confirmation.no_recover_file') }}",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            cancelButtonClass: "btn-default",
            confirmButtonText: "Delete",
            closeOnConfirm: true
        },
        function(){
            $.ajax({
                url: "{{ URL::action('AdminController@deleteScoring') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data) {
                    if (data.trim() == "true") {
                        swal({
                            title: "{{ trans('app.successes.deleted_title') }}",
                            text: "{{ trans('app.successes.deleted_text_file') }}",
                            type: "success",
                            confirmButtonClass: "btn-success",
                            closeOnConfirm: false
                        });
                        location.reload();
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        });
    }
</script>


<!-- End Page Scripts-->

@stop
