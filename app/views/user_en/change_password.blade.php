@extends('layout.english_layout.default')

@section('content')

<div class="page-content-inner">
    <!-- Basic Form Elements -->
    <section class="panel">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Horizontal Form -->
                    <form id="change_password">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('app.forms.username') }}</label>
                                    <input type="text" class="form-control" value="{{$user->username}}" disabled=""/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('app.forms.old_password') }}</label>
                                    <input type="password" id="old_password" class="form-control"/>
                                    <span id="error_old_password" style="color:red;font-style:italic;font-size:13px;display:none">{{ trans('app.forms.please_enter_old_password') }}</span>
                                    <span id="error_old_password_mismatch" style="color:red;font-style:italic;font-size:13px;display:none">{{ trans('app.forms.wrong_old_password') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('app.forms.new_password') }}</label>
                                    <input type="password" id="new_password" class="form-control"/>
                                    <span id="error_new_password" style="color:red;font-style:italic;font-size:13px;display:none">{{ trans('app.forms.please_enter_new_password') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('app.forms.retype_password') }}</label>
                                    <input type="password" id="retype_password" class="form-control"/>
                                    <span id="error_retype_password" style="color:red;font-style:italic;font-size:13px;display:none">{{ trans('app.forms.please_enter_retype_password') }}</span>
                                    <span id="error_password_mismatch" style="color:red;font-style:italic;font-size:13px;display:none">{{ trans('app.forms.retype_does_not_match') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-primary" id="submit_button" onclick="submitChangePassword()">{{ trans('app.forms.submit') }}</button>
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{URL::action('HomeController@home')}}'">{{ trans('app.forms.cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- End -->
</div>

<!-- Page Scripts -->
<script>
    $(function () {
        $('#old_password').password({
            eyeClass: '',
            eyeOpenClass: 'icmn-eye',
            eyeCloseClass: 'icmn-eye-blocked'
        });
        $('#new_password').password({
            eyeClass: '',
            eyeOpenClass: 'icmn-eye',
            eyeCloseClass: 'icmn-eye-blocked'
        });
        $('#retype_password').password({
            eyeClass: '',
            eyeOpenClass: 'icmn-eye',
            eyeCloseClass: 'icmn-eye-blocked'
        });
    });

    function submitChangePassword() {
        $("#loading").css("display", "inline-block");
        $("#error_username").css("display", "none");
        $("#error_username_in_use").css("display", "none");
        $("#error_old_password").css("display", "none");
        $("#error_old_password_mismatch").css("display", "none");
        $("#error_new_password").css("display", "none");
        $("#error_retype_password").css("display", "none");
        $("#error_password_mismatch").css("display", "none");

        var old_password = $("#old_password").val(),
                new_password = $("#new_password").val(),
                retype_password = $("#retype_password").val();
        var error = 0;

        if (old_password.trim() == "") {
            error = 1;
            $("#error_old_password").css("display", "block");
        }
        if (new_password.trim() == "") {
            error = 1;
            $("#error_new_password").css("display", "block");
        }
        if (retype_password.trim() == "") {
            error = 1;
            $("#error_retype_password").css("display", "block");
        }

        if (error == 0) {

            //check old password
            $.ajax({
                url: "{{ URL::action('UserController@checkPasswordProfile') }}",
                type: "POST",
                data: {
                    old_password: old_password
                },
                success: function (data) {
                    if (data.trim() == "false") {
                        $("#error_old_password_mismatch").css("display", "block");
                        $("#loading").css("display", "none");
                    } else {
                        if (new_password != retype_password) {
                            //check password
                            $("#error_password_mismatch").css("display", "block");
                            $("#loading").css("display", "none");
                        } else {
                            $.ajax({
                                url: "{{ URL::action('UserController@submitChangePassword') }}",
                                type: "POST",
                                data: {
                                    new_password: new_password
                                },
                                success: function (data) {
                                    $("#loading").css("display", "none");
                                    if (data.trim() == "true") {
                                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.password_changed_successfully') }}</span>", function () {
                                            window.location = '{{ URL::action("HomeController@home") }}';
                                        });
                                    } else {
                                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                                    }
                                }
                            });
                        }
                    }
                }
            });

        } else {
            $("#loading").css("display", "none");
        }
    }

</script>

@stop
