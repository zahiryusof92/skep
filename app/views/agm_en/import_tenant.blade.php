@extends('layout.english_layout.default')

@section('content')

<?php
$insert_permission = 0;

foreach ($user_permission as $permission) {
    if ($permission->submodule_id == 43) {
        $insert_permission = $permission->insert_permission;
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
                    <!-- Buyer Form -->
                    {{ Form::open( array('url' => 'uploadTenantCSVAction', 'files' => true, 'class' => 'form-horizontal', 'role' => 'form') ) }}
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
                                <?php if ($insert_permission == 1) { ?>
                                    <button type="submit" class="btn btn-own" id="upload_button">
                                        {{ trans('app.forms.upload') }}
                                    </button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('AgmController@tenant')}}'">
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
                        <table class="table table-hover nowrap" id="tenant" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th style="width:10%;">{{ trans('app.forms.file_no') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.unit_number') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.tenant_name') }}</th>
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
                                @foreach($csvData as $tenant)
                                <tr>
                                    <?php
                                    for ($i = 0; $i < count($tenant) - 1; $i++) {
                                        if (end($tenant) == "Success") {
                                            print '<td>' . $tenant[$i] . '</td>';
                                        }
                                    }
                                    ?>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <?php if ($insert_permission == 1) { ?>
                        <br/>
                        <button id="submit_buyer_button" type="button" class="btn btn-own" onclick="submitUploadTenant()">{{ trans('app.forms.submit') }}</button>
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
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->

<script>
    var oTable;
    $(function () {
        oTable = $('#tenant').editableTableWidget();
    });

    function submitUploadTenant() {
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
            url: "{{ URL::action('AgmController@submitUploadTenant') }}",
            type: "POST",
            data: {
                getAllBuyer: getAllBuyer
            },
            success: function (data) {
                console.log(data);
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.tenants.import') }}</span>", function () {
                        window.location = '{{URL::action("AgmController@tenant") }}';
                    });
                }
            }
        });
    }
</script>
<!-- End Page Scripts-->

@stop
