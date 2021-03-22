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
                    <form id="edit_profile">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.username') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.username') }}" id="username" value="{{$user->username}}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('app.forms.full_name') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.name') }}" id="name" value="{{$user->full_name}}">
                                    <div id="name_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('app.forms.email') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.email') }}" id="email" value="{{$user->email}}">
                                    <div id="email_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.phone_number') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.forms.phone_number') }}" id="phone_no" value="{{$user->phone_no}}">
                                    <div id="phone_no_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.company') }}</label>
                                    <input type="text" class="form-control" id="company" value="{{$company->name}}" disabled="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.access_group') }}</label>
                                    <input type="text" class="form-control" id="role" value="{{$user->getRole->name}}" disabled="">
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-own" id="submit_button" onclick="updateProfile()">{{ trans('app.forms.submit') }}</button>
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
    function updateProfile() {
        $("#loading").css("display", "inline-block");

        var name = $("#name").val(),
                email = $("#email").val(),
                phone_no = $("#phone_no").val(),
                remarks = $("#remarks").val(),
                is_active = $("#is_active").val();

        var error = 0;

        if (name.trim() == "") {
            $("#name_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Full Name"]) }}</span>');
            $("#name_error").css("display", "block");
            error = 1;
        }
        if (email.trim() == "" || !IsEmail(email)) {
            $("#email_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required_valid", ["attribute"=>"Email"]) }}</span>');
            $("#email_error").css("display", "block");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('UserController@submitEditProfile') }}",
                type: "POST",
                data: {
                    name: name,
                    email: email,
                    phone_no: phone_no,
                    id: '{{$user->id}}'
                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.profile_edited_successfully') }}</span>", function () {
                            window.location = '{{URL::action("HomeController@home") }}';
                        });
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        }
    }

    function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
</script>

@stop
