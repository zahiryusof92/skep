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
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h6>{{ trans('app.forms.file_no') }}: {{$file->file_no}}</h6>
                    <div id="update_files_lists">
                        @include('page_en.nav.cob_file', ['files' => $file])

                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="management" role="tabpanel">

                                <section class="panel panel-pad">
                                    @include('page_en.cob.management.form')
                                </section>
                                
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
            return "{{ trans('app.confirmation.want_to_leave') }}";
        }
    });

    $(function () {
        $('.datepicker').datetimepicker({
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
            format: 'DD-MM-YYYY'
        });

        $("[data-toggle=tooltip]").tooltip();

        $('#add-more-developer').cloneData({
            mainContainerId:'developer_form_container', // container to hold the dulicated form fields
            cloneContainer:'container-item', // Which you want to clone
            removeButtonClass:'developer-remove-item', // CSS class of remove button
            removeConfirm:true,
            removeConfirmMessage: "{{ trans('Are you sure want to delete?') }}",
            minLimitMessage: "{{ trans('You must have at least one item.') }}", // min limit message
            minLimit: 1,
            maxLimit: 0, // Default unlimited or set maximum limit of clone HTML
            defaultRender: 1, // Number of clone items rendered by default
        });
        
        $('#add-more-liquidator').cloneData({
            mainContainerId:'liquidator_form_container', // container to hold the dulicated form fields
            cloneContainer:'container-item', // Which you want to clone
            removeButtonClass:'liquidator-remove-item', // CSS class of remove button
            removeConfirm:true,
            removeConfirmMessage: "{{ trans('Are you sure want to delete?') }}",
            minLimitMessage: "{{ trans('You must have at least one item.') }}", // min limit message
            minLimit: 1,
            maxLimit: 0, // Default unlimited or set maximum limit of clone HTML
            defaultRender: 1, // Number of clone items rendered by default
        });

        $('#add-more-jmb').cloneData({
            mainContainerId:'jmb_form_container', // container to hold the dulicated form fields
            cloneContainer:'container-item', // Which you want to clone
            removeButtonClass:'jmb-remove-item', // CSS class of remove button
            removeConfirm:true,
            removeConfirmMessage: "{{ trans('Are you sure want to delete?') }}",
            minLimitMessage: "{{ trans('You must have at least one item.') }}", // min limit message
            minLimit: 1,
            maxLimit: 0, // Default unlimited or set maximum limit of clone HTML
            defaultRender: 1, // Number of clone items rendered by default
            afterRender:function() {
                $('.datepicker').datetimepicker({
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
                    format: 'DD-MM-YYYY'
                });
            },
        });
        
        $('#add-more-mc').cloneData({
            mainContainerId:'mc_form_container', // container to hold the dulicated form fields
            cloneContainer:'container-item', // Which you want to clone
            removeButtonClass:'mc-remove-item', // CSS class of remove button
            removeConfirm:true,
            removeConfirmMessage: "{{ trans('Are you sure want to delete?') }}",
            minLimitMessage: "{{ trans('You must have at least one item.') }}", // min limit message
            minLimit: 1,
            maxLimit: 0, // Default unlimited or set maximum limit of clone HTML
            defaultRender: 1, // Number of clone items rendered by default
            afterRender:function() {
                $('.datepicker').datetimepicker({
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
                    format: 'DD-MM-YYYY'
                });
            },
        });

        $('#add-more-agent').cloneData({
            mainContainerId:'agent_form_container', // container to hold the dulicated form fields
            cloneContainer:'container-item', // Which you want to clone
            removeButtonClass:'agent-remove-item', // CSS class of remove button
            removeConfirm:true,
            removeConfirmMessage: "{{ trans('Are you sure want to delete?') }}",
            minLimitMessage: "{{ trans('You must have at least one item.') }}", // min limit message
            minLimit: 1,
            maxLimit: 0, // Default unlimited or set maximum limit of clone HTML
            defaultRender: 1, // Number of clone items rendered by default
        });
        
        $('#add-more-others').cloneData({
            mainContainerId:'others_form_container', // container to hold the dulicated form fields
            cloneContainer:'container-item', // Which you want to clone
            removeButtonClass:'others-remove-item', // CSS class of remove button
            removeConfirm:true,
            removeConfirmMessage: "{{ trans('Are you sure want to delete?') }}",
            minLimitMessage: "{{ trans('You must have at least one item.') }}", // min limit message
            minLimit: 1,
            maxLimit: 0, // Default unlimited or set maximum limit of clone HTML
            defaultRender: 1, // Number of clone items rendered by default
        });

        $("#submit_button").click(function (e) {
            e.preventDefault();
            changes = false;

            let formData = $('form').serializeArray();
            $.ajax({
                url: "{{ URL::action('AdminController@submitUpdateManagement') }}",
                type: "POST",
                data: formData,
                beforeSend: function() {
                    $("#loading").css("display", "inline-block");
                    $("#submit_button").attr("disabled", "disabled");
                    $("#cancel_button").attr("disabled", "disabled");
                    $.blockUI({message: '{{ trans("app.confirmation.please_wait") }}'});
                },
                success: function (res) {
                    if (res.trim() == "true") {
                        $.notify({
                            message: '<p style="text-align: center; margin-bottom: 0px;">{{ trans("app.successes.saved_successfully") }}</p>',
                        }, {
                            type: 'success',
                            placement: {
                                align: "center"
                            }
                        });
                        window.location = "{{URL::action('AdminController@monitoring', \Helper\Helper::encode($file->id))}}";
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                },
                complete: function() {
                    $.unblockUI();
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                },
            })
        });
    });
</script>
<!-- End Page Scripts-->
@stop
