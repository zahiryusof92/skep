@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <section class="panel panel-pad">
                <div class="row padding-vertical-10">
                    <div class="col-lg-12 text-center">
                        <form>
                            <div class="row">
                                @if (Auth::user()->getAdmin())
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.cob') }}</label>
                                        <select id="company" class="form-control select2">
                                            @if (count($cob) > 0)
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @endif
                                            @foreach ($cob as $companies)
                                            <option value="{{ $companies->short_name }}">{{ $companies->name }} ({{ $companies->short_name }})</option>
                                            @endforeach
                                        </select>
                                        <div id="company_error" style="display:none;"></div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.file_no') }}</label>
                                        <select id="file_no" class="form-control select2">
                                        </select>
                                        <div id="file_no_error" style="display:none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.eagm_property') }}</label>
                                        <select id="eagm_property" class="form-control select2">
                                        </select>
                                        <div id="eagm_property_error" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @if (Auth::user()->getAdmin())
                            <div class="row float-right">
                                <button onclick="onSubmit()" id="btn_sync" type="button" class="btn btn-own">
                                    {{ trans('app.buttons.sync') }}
                                    <img id="loading" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </section>
    <!-- End  -->
</div>

<!-- Page Scripts -->
<script>
    $(function() {
        $('#company').change(function(e) {
            var cob = e.target.value;
            getFiles(cob);
            getProperty(cob);
        });
    });

    function getFiles(cob) {
        $.ajax({
            url: "{{ URL::action('CobController@getOption') }}",
            type: "GET",
            data: {
                short_name: cob,
            },
            success: function (res) {
                if (res['status'] == true) {
                    $('#file_no').empty();
                    $('#file_no').append("<option value=''>{{ trans('app.forms.please_select') }}</option");
                    $.each(res['data'], function(key, val) {
                        $('#file_no').append('<option value="' + val['key'] +'">'+ val['title'] +'</option')
                    });
                }
            }
        });
    }

    function getProperty(cob) {
        $("#loading").css("display", "inline-block");
        $("#btn_sync").prop( "disabled", true );
        var url = "{{ URL::action('CobSyncController@getProperty') }}";
        
        $.ajax({
            url: url,
            type: "GET",
            data: {
                code: cob,
            },
            cache: false,
            dataType: 'JSON',
            success: function (res) {
                $("#loading").css("display", "none");
                $("#btn_sync").removeAttr("disabled");
                if (res['status'] == 200) {
                    $('#eagm_property').empty();
                    $('#eagm_property').append("<option value=''>{{ trans('app.forms.please_select') }}</option");
                    $.each(res['data'], function(key, val) {
                        $('#eagm_property').append('<option value="' + val['key'] +'">'+ val['title'] +'</option')
                    });
                } else {
                    bootbox.alert("<span style='color:red;'>eAGM Property Not Found in this COB</span>");
                }
            },
        });
    }

    function onSubmit() {
        $("#loading").css("display", "inline-block");
        $("#btn_sync").prop( "disabled", true );
        
        $.ajax({
            url: "{{ URL::action('CobSyncController@submitBuyerSync') }}",
            type: "POST",
            data: {
                eagm_property: $('#eagm_property').val(),
                file_no: $("#file_no").val(),
                company: $("#company").val(),
            },
            beforeSend: function() {
                $('#file_no_error').html("");
                $('#file_no_error').hide();
                $('#eagm_property_error').html("");
                $('#eagm_property_error').hide();
                $('#company_error').html("");
                $('#company_error').hide();
            },
            success: function (res) {
                $("#loading").css("display", "none");
                $("#btn_sync").removeAttr("disabled");
                if (res['status'] == 200) {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.cob_sync.store') }}</span>", function () {
                        window.location = '{{URL::action("CobSyncController@index") }}';
                    });
                } else {
                    var errs = JSON.parse(res.data);
                    $.each(errs, function(key, val) {
                        $('#' + key + '_error').show();
                        $('#' + key + '_error').html('<span style="color:red;font-style:italic;font-size:13px;">' + val + '</span>');
                    });
                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                }
            },
            errror: function (res) {
                $("#loading").css("display", "none");
            },
        });
    }
</script>

@stop
