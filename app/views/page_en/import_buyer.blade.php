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
                                <a class="nav-link" href="{{URL::action('AdminController@house', $files->id)}}">{{ trans('app.forms.housing_scheme') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@strata', $files->id)}}">{{ trans('app.forms.developed_area') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@management', $files->id)}}">{{ trans('app.forms.management') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@monitoring', $files->id)}}">{{ trans('app.forms.monitoring') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@others', $files->id)}}">{{ trans('app.forms.others') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@scoring', $files->id)}}">{{ trans('app.forms.scoring_component_value') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active">{{ trans('app.forms.buyer_list') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{URL::action('AdminController@document', $files->id)}}">{{ trans('app.forms.document') }}</a>
                            </li>
                        </ul>
                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="buyer_tab" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <!-- Buyer Form -->
                                        {{ Form::open( array('id' => 'import_buyer_form', 'url' => 'uploadBuyerCSVAction/'.$files->id, 'files' => true, 'class' => 'form-horizontal', 'role' => 'form') ) }}
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label>{{ trans('app.forms.import_csv_file') }}</label>
                                                    <br />
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                    <input type="file" name="uploadedCSV" id="uploadedCSV" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <?php if ($update_permission == 1) { ?>
                                                        <button type="submit" class="btn btn-primary" id="upload_button">
                                                            {{ trans('app.forms.upload') }}
                                                        </button>
                                                    <?php } ?>
                                                    <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AdminController@buyer', $files->id)}}'">
                                                        {{ trans('app.forms.cancel') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        {{ Form::close() }}

                                        @if($Uploadmessage != '' && $Uploadmessage == 'success')
                                        @if($csvData=='No Data')
                                        <div class="row">
                                            <div class="col-md-8" style="color:red;font-style:italic;">
                                                {{ trans('app.forms.csv_file_empty') }}
                                            </div>
                                        </div>
                                        @else
                                        <br /><br/>
                                        <div class="table-responsive">
                                            <table class="table table-hover nowrap" id="buyerList" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th style="width:10%;">{{ trans('app.forms.file_no') }}</th>
                                                        <th style="width:5%;">{{ trans('app.forms.unit_number') }}</th>
                                                        <th style="width:5%;">{{ trans('app.forms.unit_share') }}</th>
                                                        <th style="width:10%;">{{ trans('app.forms.owner_name') }}</th>
                                                        <th style="width:10%;">{{ trans('app.forms.ic_company_number') }}</th>
                                                        <th style="width:10%;">{{ trans('app.forms.address') }}</th>
                                                        <th style="width:10%;">{{ trans('app.forms.phone_number') }}</th>
                                                        <th style="width:10%;">{{ trans('app.forms.email') }}</th>
                                                        <th style="width:10%;">{{ trans('app.forms.race') }}</th>
                                                        <th style="width:10%;">{{ trans('app.forms.nationality') }}</th>
                                                        <th style="width:10%;">{{ trans('app.forms.remarks') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($csvData as $buyer)
                                                    <tr>
                                                        <?php
                                                        for ($i = 0; $i < count($buyer) - 1; $i++) {
                                                            if (end($buyer) == "Success") {
                                                                print '<td>' . $buyer[$i] . '</td>';
                                                            }
                                                        }
                                                        ?>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php if ($update_permission == 1) { ?>
                                            <br/>
                                            <button id="submit_buyer_button" type="button" class="btn btn-primary" onclick="submitUploadBuyer()">{{ trans('app.forms.submit') }}</button>
                                            <img id="loading" src="{{ asset('assets/common/img/input-spinner.gif') }}" style="display:none;"/>
                                        <?php } ?>
                                        @endif
                                        @else
                                        <div class="row">
                                            <div class="col-md-8" style="color:red;font-style:italic;">
                                                {{$Uploadmessage}}
                                            </div>
                                        </div>
                                        @endif
                                        <!-- End Buyer Form -->
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

<!-- Page Scripts -->

<script>
    var changes = false;
    $('input, textarea, select').on('keypress change input', function () {
        changes = true;
    });

    $(window).on('beforeunload', function () {
        if (changes) {
            return "Data you have entered may not be saved, do you really want to leave?";
        }
    });

    var oTable;
    $(function () {
        oTable = $('#buyerList').editableTableWidget();
    });

    $("#import_buyer_form").submit(function () {
        changes = false;
    });

    function submitUploadBuyer() {
        changes = false;
        $("#upload_button").attr('disabled', 'disabled');
        $("#submit_buyer_button").attr('disabled', 'disabled');
        $("#loading").css('display', 'inline-block');


        var getAllBuyer = [];
        oTable.find('tr').each(function (rowIndex, r) {
            var cols = [];
            $(this).find('td').each(function (colIndex, c) {
                cols.push(c.textContent);
            });
            getAllBuyer.push(cols);
        });

        $.ajax({
            url: "{{ URL::action('AdminController@submitUploadBuyer', $files->id) }}",
            type: "POST",
            data: {
                getAllBuyer: getAllBuyer
            },
            success: function (data) {
                if (data.trim() == "true") {
                    $.notify({
                        message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                    }, {
                        type: 'success',
                        placement: {
                            align: "center"
                        }
                    });
                    location = '{{ URL::action("AdminController@buyer", $files->id) }}';
                }
            }
        });
    }
</script>
<!-- End Page Scripts-->

@stop
