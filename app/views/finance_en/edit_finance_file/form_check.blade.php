<div class="row padding-vertical-10">    
    <div class="col-lg-12">
        @if(!empty($checkOldData))
            <h6><u>{{ trans("app.forms.after_change") }}</u></h6>
        @endif
        <form id="form_check">

            <div class="form-group row">
                <div class="col-md-6">
                    <label><span style="color: red;">*</span> {{ trans("app.forms.date") }}</label>
                    <input id="date" class="form-control form-control-sm" type="text" placeholder="{{ trans("app.forms.date") }}" value="{{ ($checkdata->date) ? date('d/m/Y', strtotime($checkdata->date)) : '' }}">
                    <input type="hidden" name="date" id="mirror_date" value="{{ $checkdata->date }}">
                    <div id="date_err" style="display:none;"></div>
                </div>
                <div class="col-md-6">
                    <label><span style="color: red;">*</span> {{ trans('app.forms.name') }}</label>
                    <input name="name" id="name" class="form-control form-control-sm" type="text" placeholder="{{ trans('app.forms.name') }}" value="{{ $checkdata->name }}">
                    <div id="name_err" style="display:none;"></div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label><span style="color: red;">*</span> {{ trans("app.forms.position") }}</label>
                    <input name="position" id="position" class="form-control form-control-sm" type="text" placeholder="{{ trans("app.forms.position") }}" value="{{ $checkdata->position }}">
                    <div id="position_err" style="display:none;"></div>
                </div>
                <div class="col-md-6">
                    <label><span style="color: red;">*</span> {{ trans('app.forms.admin_status') }}</label>
                    <select name="is_active" id="is_active" class="form-control form-control-sm" {{ (Auth::user()->getAdmin() || Auth::user()->isCOBManager())? "" : "disabled"}}>
                        <option value="">{{ trans('app.forms.please_select') }}</option>
                        @foreach($adminStatus as $key => $status) 
                            <option value="{{ $key }}" {{ $checkdata->is_active == $key ? "selected" : "" }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    @if(!Auth::user()->getAdmin() && !Auth::user()->isCOBManager())
                    <input type="hidden" name="is_active" id="is_active" value="{{ $checkdata->is_active }}">
                    @endif
                    <div id="is_active_err" style="display:none;"></div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <label>{{ trans('app.forms.remarks') }}</label>
                    <textarea name="remarks" id="remarks" rows="5" class="form-control form-control-sm" placeholder="{{ trans('app.forms.remarks') }}">{{ $checkdata->remarks }}</textarea>
                </div>
            </div>

            <?php if ($update_permission == 1) { ?>
                <div class="form-actions">
                    <input type="hidden" name="finance_file_id" value="{{ \Helper\Helper::encode($financefiledata->id) }}"/>
                    <button type="button"class="btn btn-own submit_button" onclick="submitCheck()">{{ trans("app.forms.submit") }}</button>
                    <img class="loading" style="display:none;" src="{{asset('assets/common/img/input-spinner.gif')}}"/>
                </div>
            <?php } ?>

        </form>

    </div>
</div>

<!-- Page Scripts -->
<script type="text/javascript">
    function submitCheck() {
        error = 0;
        var data = $('#form_check').serialize();

        $(".loading").css("display", "inline-block");
        $(".submit_button").attr("disabled", "disabled");
        $("#name_err").css("display", "none");
        $("#date_err").css("display", "none");
        $("#position_err").css("display", "none");
        $("#is_active_err").css("display", "none");
        $("#check_mandatory_fields").css("display", "none");

        if ($("#name").val().trim() == "") {
            $("#name_err").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Name"]) }}</span>');
            $("#name_err").css("display", "block");
            error = 1;
        }

        if ($("#mirror_date").val().trim() == "") {
            $("#date_err").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Date"]) }}</span>');
            $("#date_err").css("display", "block");
            error = 1;
        }

        if ($("#position").val().trim() == "") {
            $("#position_err").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Position"]) }}</span>');
            $("#position_err").css("display", "block");
            error = 1;
        }

        // if ($("#is_active").val().trim() == "") {
        //     $("#is_active_err").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Status"]) }}</span>');
        //     $("#is_active_err").css("display", "block");
        //     error = 1;
        // }

        if (error == 0) {
            $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});

            $.ajax({
                method: "POST",
                url: "{{ URL::action('FinanceController@updateFinanceFileCheck') }}",
                data: data,
                success: function (response) {
                    changes = false;
                    $.unblockUI();
                    $(".loading").css("display", "none");
                    $(".submit_button").removeAttr("disabled");

                    if (response.trim() == "true") {
                        submitSummary();
                        $.notify({
                            message: "<div class='text-center'>{{ trans('app.successes.saved_successfully') }}</div>"
                        }, {
                            type: 'success',
                            allow_dismiss: false,
                            placement: {
                                from: "top",
                                align: "center"
                            },
                            delay: 100,
                            timer: 500
                        });
                        $('a[href="' + window.location.hash + '"]').trigger('click');
                        location.reload();
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        } else {
            $(".loading").css("display", "none");
            $(".submit_button").removeAttr("disabled");
            $("#check_mandatory_fields").css("display", "block");
        }
    }
</script>

@if(!empty($checkOldData))
    @include('finance_en.show.check')
@endif