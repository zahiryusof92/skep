@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>
        <div class="panel-body">
            
            @include('alert.bootbox')

            <section class="panel panel-pad">

                @if(Auth::user()->isHR())
                    <div class="row padding-vertical-20">
                        <div class="col-lg-12">
                            <button class="btn btn-primary-outline margin-bottom-25" data-toggle="modal" data-target="#submitForm">
                                {{ trans('app.buttons.submit_payment') }}
                            </button>
                        </div>
                    </div>
                    
                    <div class="modal fade" id="submitForm" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form id="form_submit" enctype="multipart/form-data" class="form-horizontal" data-parsley-validate>
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">{{ trans('app.forms.payment') }}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div id="selected_id_error" style="display: none;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><span style="color: red;">*</span> {{ trans('app.forms.amount') }}</label>
                                                    <input type="text" name="amount" id="amount" class="form-control">
                                                    <div id="amount_error" style="display: none;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><span style="color: red;">*</span> {{ trans('app.forms.file') }}</label>
                                                    <input type="file" name="upload_file[]" id="upload_file" class="form-control form-control-file" multiple="multiple"/>
                                                    <div id="upload_file_error" style="display: none;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="selected_id" id="selected_id"/>
                                        <img id="loading_import" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                                        <button id="submit_button_import" class="btn btn-own" type="submit">
                                            {{ trans('app.forms.submit') }}
                                        </button>
                                        <button data-dismiss="modal" id="cancel_button_import" class="btn btn-default" type="button">
                                            {{ trans('app.forms.cancel') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- modal -->

                    <script>
                        
                        $("#form_submit").on('submit', (function (e) {
                            e.preventDefault();

                            $('#loading_import').css("display", "inline-block");
                            $("#submit_button_import").attr("disabled", "disabled");
                            $("#cancel_button_import").attr("disabled", "disabled");
                            $("#selected_id_error").css("display", "none");
                            $("#amount_error").css("display", "none");
                            $("#upload_file_error").css("display", "none");

                            var selected_id = $('#select_id:checked').serialize(),
                                    amount = $("#amount").val(),
                                    upload_file = $("#upload_file").val();
                            $('#selected_id').val(selected_id);
                            var error = 0;

                            if (selected_id.trim() == "") {
                                $("#selected_id_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Selected ID"]) }}</span>');
                                $("#selected_id_error").css("display", "block");
                                error = 1;
                            }
                            if (amount.trim() == "") {
                                $("#amount_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Amount"]) }}</span>');
                                $("#amount_error").css("display", "block");
                                error = 1;
                            }
                            if (upload_file.trim() == "") {
                                $("#upload_file_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.file", ["attribute"=>"PDF File"]) }}</span>');
                                $("#upload_file_error").css("display", "block");
                                error = 1;
                            }

                            if (error == 0) {
                                var formData = new FormData(this);
                                $.ajax({
                                    url: "{{ URL::action('SummonController@uploadPayment') }}",
                                    type: "POST",
                                    data: formData,
                                    async: true,
                                    contentType: false, // The content type used when sending data to the server.
                                    cache: false, // To unable request pages to be cached
                                    processData: false,
                                    success: function (data) { //function to be called if request succeeds
                                        console.log(data);

                                        $('#loading_import').css("display", "none");
                                        $("#submit_button_import").removeAttr("disabled");
                                        $("#cancel_button_import").removeAttr("disabled");

                                        if (data.trim() === "true") {
                                            $("#submitForm").modal("hide");
                                            bootbox.alert("<span style='color:green;'>{{ trans('app.successes.submit_successfully') }}</span>", function () {
                                                window.location.reload();
                                            });
                                        } else if (data.trim() === "empty_file") {
                                            $("#submitForm").modal("hide");
                                            $("#upload_file_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.file", ["attribute"=>"PDF File"]) }}</span>');
                                            $("#upload_file_error").css("display", "block");
                                        } else if (data.trim() === "valid_file") {
                                            $("#submitForm").modal("hide");
                                            $("#upload_file_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.file_valid", ["attribute"=>"PDF File"]) }}</span>');
                                            $("#upload_file_error").css("display", "block");
                                        } else if (data.trim() === "empty_data") {
                                            $("#submitForm").modal("hide");
                                            bootbox.alert("<span style='color:red;'>{{ trans('app.errors.empty_or_exist') }}</span>", function () {
                                                window.location.reload();
                                            });
                                        } else {
                                            $("#submitForm").modal("hide");
                                            bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>", function () {
                                                window.location.reload();
                                            });
                                        }
                                    }
                                });
                            } else {
                                $("#selected_id").focus();
                                $('#loading_import').css("display", "none");
                                $("#submit_button_import").removeAttr("disabled");
                                $("#cancel_button_import").removeAttr("disabled");
                            }
                        }));
                    </script>
                    {{-- End Submit process --}}
                @endif

                <div class="row padding-vertical-10">
                    <div class="col-lg-12 text-center">
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('app.forms.cob') }}</label>
                                        <select id="company" class="form-control select2">
                                            @if (count($cob) > 1)
                                            <option value="">{{ trans('app.forms.please_select') }}</option>
                                            @endif
                                            @foreach ($cob as $companies)
                                            <option value="{{ $companies->short_name }}">{{ $companies->name }} ({{ $companies->short_name }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <hr />

                <div class="row padding-vertical-20">
                    <div class="col-lg-12">                    
                        <table class="table table-hover table-own table-striped" id="summon_table" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:5%;"></th>
                                    <th style="width:15%;">{{ trans('app.summon.created_at') }}</th>
                                    <th style="width:10%;">{{ trans('app.summon.council') }}</th>
                                    <th style="width:10%;">{{ trans('app.summon.unit_no') }}</th>
                                    <th style="width:20%;">{{ trans('app.summon.name') }}</th>
                                    <th style="width:10%;">{{ trans('app.summon.phone_no') }}</th>
                                    <th style="width:15%;">{{ trans('app.summon.type') }}</th>
                                    <th style="width:10%;">{{ trans('app.summon.status') }}</th>               
                                    <th style="width:15%; text-align: center;">{{ trans('app.summon.action') }}</th>                
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </section>
    <!-- End -->
</div>

<script>
    $(document).ready(function () {
        var oTable = $('#summon_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ URL::action('SummonController@councilSummonList') }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 10,
            order: [[0, "desc"]],
            responsive: true,
            columns: [
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                {data: 'created_at', name: 'created_at'},
                {data: 'cob', name: 'company.short_name'},
                {data: 'unit_no', name: 'unit_no'},
                {data: 'name', name: 'name'},
                {data: 'phone_no', name: 'phone_no'},
                {data: 'type', name: 'type'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            'columnDefs': [
                {"targets": -1, "className": "text-center"}
            ],
        });

        $('#company').on('change', function () {
            oTable.columns(2).search(this.value).draw();
        });
        
    });
</script>
@endsection