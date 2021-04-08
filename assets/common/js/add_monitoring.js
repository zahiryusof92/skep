$(document).ready(function() {
    $('#agm_date_raw').datetimepicker({
        widgetPositioning: {
            horizontal: 'left'
        },
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        },
        format: 'DD-MM-YYYY'
    }).on('dp.change', function() {
        let currentDate = $(this).val().split('-');
        $("#agm_date").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
    });

    $('#audit_start_raw').datetimepicker({
        widgetPositioning: {
            horizontal: 'left'
        },
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        },
        format: 'DD-MM-YYYY'
    }).on('dp.change', function() {
        let currentDate = $(this).val().split('-');
        $("#audit_start").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
    });

    $('#audit_end_raw').datetimepicker({
        widgetPositioning: {
            horizontal: 'left'
        },
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        },
        format: 'DD-MM-YYYY'
    }).on('dp.change', function() {
        let currentDate = $(this).val().split('-');
        $("#audit_end").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
    });

    //ADD

    // agm_file
    $('body').delegate('#agm_file', 'change', function() {
        $('#upload_agm_file').ajaxForm({
            beforeSubmit: function() {
                $("#agm_file_error").empty().hide();
                return true;
            },
            success: function(result) {
                if (result.success) {
                    $("#agm_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button class='btn btn-xs btn-danger' onclick='clearAGMFile()'><i class='fa fa-times'></i> </button>").show();
                    $("#agm_file_clear").show();
                    $("#agm_file").css("color", "green");
                    $("#agm_file_url").val(result.file);
                } else {
                    $("#agm_file_error").html("<span style='color:red;'><i>" + result.msg + "</i></span>").show();
                    $("#agm_file").css("color", "red");
                }
            },
            dataType: 'json'
        }).submit();
    });

    // egm_file
    $('body').delegate('#egm_file', 'change', function() {
        $('#upload_egm_file').ajaxForm({
            beforeSubmit: function() {
                $("#egm_file_error").empty().hide();
                return true;
            },
            success: function(result) {
                if (result.success) {
                    $("#egm_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button class='btn btn-xs btn-danger' onclick='clearEGMFile()'><i class='fa fa-times'></i> </button>").show();
                    $("#egm_file_clear").show();
                    $("#egm_file").css("color", "green");
                    $("#egm_file_url").val(result.file);
                } else {
                    $("#egm_file_error").html("<span style='color:red;'><i>" + result.msg + "</i></span>").show();
                    $("#egm_file").css("color", "red");
                }
            },
            dataType: 'json'
        }).submit();
    });

    // minutes_meeting_file
    $('body').delegate('#minutes_meeting_file', 'change', function() {
        $('#upload_minutes_meeting_file').ajaxForm({
            beforeSubmit: function() {
                $("#minutes_meeting_file_error").empty().hide();
                return true;
            },
            success: function(result) {
                if (result.success) {
                    $("#minutes_meeting_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button class='btn btn-xs btn-danger' onclick='clearMinutesMeetingFile()'><i class='fa fa-times'></i> </button>").show();
                    $("#minutes_meeting_file_clear").show();
                    $("#minutes_meeting_file").css("color", "green");
                    $("#minutes_meeting_file_url").val(result.file);
                } else {
                    $("#minutes_meeting_file_error").html("<span style='color:red;'><i>" + result.msg + "</i></span>").show();
                    $("#minutes_meeting_file").css("color", "red");
                }
            },
            dataType: 'json'
        }).submit();
    });

    // jmc_file
    $('body').delegate('#jmc_file', 'change', function() {
        $('#upload_jmc_file').ajaxForm({
            beforeSubmit: function() {
                $("#jmc_file_error").empty().hide();
                return true;
            },
            success: function(result) {
                if (result.success) {
                    $("#jmc_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button class='btn btn-xs btn-danger' onclick='clearMinutesMeetingFile()'><i class='fa fa-times'></i> </button>").show();
                    $("#jmc_file_clear").show();
                    $("#jmc_file").css("color", "green");
                    $("#jmc_file_url").val(result.file);
                } else {
                    $("#jmc_file_error").html("<span style='color:red;'><i>" + result.msg + "</i></span>").show();
                    $("#jmc_file").css("color", "red");
                }
            },
            dataType: 'json'
        }).submit();
    });

    // ic_file
    $('body').delegate('#ic_file', 'change', function() {
        $('#upload_ic_file').ajaxForm({
            beforeSubmit: function() {
                $("#ic_file_error").empty().hide();
                return true;
            },
            success: function(result) {
                if (result.success) {
                    $("#ic_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button class='btn btn-xs btn-danger' onclick='clearMinutesMeetingFile()'><i class='fa fa-times'></i> </button>").show();
                    $("#ic_file_clear").show();
                    $("#ic_file").css("color", "green");
                    $("#ic_file_url").val(result.file);
                } else {
                    $("#ic_file_error").html("<span style='color:red;'><i>" + result.msg + "</i></span>").show();
                    $("#ic_file").css("color", "red");
                }
            },
            dataType: 'json'
        }).submit();
    });

    // attendance_file
    $('body').delegate('#attendance_file', 'change', function() {
        $('#upload_attendance_file').ajaxForm({
            beforeSubmit: function() {
                $("#attendance_file_error").empty().hide();
                return true;
            },
            success: function(result) {
                if (result.success) {
                    $("#attendance_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button class='btn btn-xs btn-danger' onclick='clearMinutesMeetingFile()'><i class='fa fa-times'></i> </button>").show();
                    $("#attendance_file_clear").show();
                    $("#attendance_file").css("color", "green");
                    $("#attendance_file_url").val(result.file);
                } else {
                    $("#attendance_file_error").html("<span style='color:red;'><i>" + result.msg + "</i></span>").show();
                    $("#attendance_file").css("color", "red");
                }
            },
            dataType: 'json'
        }).submit();
    });

    // audited_financial_file
    $('body').delegate('#audited_financial_file', 'change', function() {
        $('#upload_audited_financial_file').ajaxForm({
            beforeSubmit: function() {
                $("#audited_financial_file_error").empty().hide();
                return true;
            },
            success: function(result) {
                if (result.success) {
                    $("#audited_financial_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button class='btn btn-xs btn-danger' onclick='clearMinutesMeetingFile()'><i class='fa fa-times'></i> </button>").show();
                    $("#audited_financial_file_clear").show();
                    $("#audited_financial_file").css("color", "green");
                    $("#audited_financial_file_url").val(result.file);
                } else {
                    $("#audited_financial_file_error").html("<span style='color:red;'><i>" + result.msg + "</i></span>").show();
                    $("#audited_financial_file").css("color", "red");
                }
            },
            dataType: 'json'
        }).submit();
    });

    //upload audit report file
    $('body').delegate('#audit_report_file', 'change', function() {
        $('#upload_audit_report_file').ajaxForm({
            dataType: 'json',
            beforeSubmit: function() {
                $("#validation-errors_audit_report_file").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#validation-errors_audit_report_file").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-errors_audit_report_file").show();
                    $("#audit_report_file").css("color", "red");
                } else {
                    $("#validation-errors_audit_report_file").html("<i class='fa fa-check' id='check_audit_report_file' style='color:green;'></i>");
                    $("#clear_audit_report_file").show();
                    $("#validation-errors_audit_report_file").show();
                    $("#audit_report_file").css("color", "green");
                    $("#audit_report_file_url").val(response.file);
                }
            }
        }).submit();
    });

    //upload letter integrity
    $('body').delegate('#letter_integrity', 'change', function() {
        $('#upload_letter_integrity').ajaxForm({
            dataType: 'json',
            beforeSubmit: function() {
                $("#validation-errors_letter_integrity").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#validation-errors_letter_integrity").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-errors_letter_integrity").show();
                    $("#letter_integrity").css("color", "red");
                } else {
                    $("#validation-errors_letter_integrity").html("<i class='fa fa-check' id='check_letter_integrity' style='color:green;'></i>");
                    $("#clear_letter_integrity").show();
                    $("#validation-errors_letter_integrity").show();
                    $("#letter_integrity").css("color", "green");
                    $("#letter_integrity_url").val(response.file);
                }
            }
        }).submit();
    });

    //upload letter bankruptcy
    $('body').delegate('#letter_bankruptcy', 'change', function() {
        $('#upload_letter_bankruptcy').ajaxForm({
            dataType: 'json',
            beforeSubmit: function() {
                $("#validation-errors_letter_bankruptcy").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#validation-errors_letter_bankruptcy").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-errors_letter_bankruptcy").show();
                    $("#letter_bankruptcy").css("color", "red");
                } else {
                    $("#validation-errors_letter_bankruptcy").html("<i class='fa fa-check' id='check_letter_bankruptcy' style='color:green;'></i>");
                    $("#clear_letter_bankruptcy").show();
                    $("#validation-errors_letter_bankruptcy").show();
                    $("#letter_bankruptcy").css("color", "green");
                    $("#letter_bankruptcy_url").val(response.file);
                }
            }
        }).submit();
    });

    //notice_agm_egm
    $('body').delegate('#notice_agm_egm', 'change', function() {
        $('#upload_notice_agm_egm').ajaxForm({
            dataType: 'json',
            beforeSubmit: function() {
                $("#validation-notice_agm_egm").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#validation-notice_agm_egm").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-notice_agm_egm").show();
                    $("#notice_agm_egm").css("color", "red");
                } else {
                    $("#validation-notice_agm_egm").html("<i class='fa fa-check' id='check_notice_agm_egm' style='color:green;'></i>");
                    $("#clear_notice_agm_egm").show();
                    $("#validation-notice_agm_egm").show();
                    $("#notice_agm_egm").css("color", "green");
                    $("#notice_agm_egm_url").val(response.file);
                }
            }
        }).submit();
    });

    //minutes_agm_egm
    $('body').delegate('#minutes_agm_egm', 'change', function() {
        $('#upload_minutes_agm_egm').ajaxForm({
            dataType: 'json',
            beforeSubmit: function() {
                $("#validation-minutes_agm_egm").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#validation-minutes_agm_egm").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-minutes_agm_egm").show();
                    $("#minutes_agm_egm").css("color", "red");
                } else {
                    $("#validation-minutes_agm_egm").html("<i class='fa fa-check' id='check_minutes_agm_egm' style='color:green;'></i>");
                    $("#clear_minutes_agm_egm").show();
                    $("#validation-minutes_agm_egm").show();
                    $("#minutes_agm_egm").css("color", "green");
                    $("#minutes_agm_egm_url").val(response.file);
                }
            }
        }).submit();
    });

    //minutes_ajk
    $('body').delegate('#minutes_ajk', 'change', function() {
        $('#upload_minutes_ajk').ajaxForm({
            dataType: 'json',
            beforeSubmit: function() {
                $("#validation-minutes_ajk").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#validation-minutes_ajk").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-minutes_ajk").show();
                    $("#minutes_ajk").css("color", "red");
                } else {
                    $("#validation-minutes_ajk").html("<i class='fa fa-check' id='check_minutes_ajk' style='color:green;'></i>");
                    $("#clear_minutes_ajk").show();
                    $("#validation-minutes_ajk").show();
                    $("#minutes_ajk").css("color", "green");
                    $("#minutes_ajk_url").val(response.file);
                }
            }
        }).submit();
    });

    //eligible_vote
    $('body').delegate('#eligible_vote', 'change', function() {
        $('#upload_eligible_vote').ajaxForm({
            dataType: 'json',
            beforeSubmit: function() {
                $("#validation-eligible_vote").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#validation-eligible_vote").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-eligible_vote").show();
                    $("#eligible_vote").css("color", "red");
                } else {
                    $("#validation-eligible_vote").html("<i class='fa fa-check' id='check_eligible_vote' style='color:green;'></i>");
                    $("#clear_eligible_vote").show();
                    $("#validation-eligible_vote").show();
                    $("#eligible_vote").css("color", "green");
                    $("#eligible_vote_url").val(response.file);
                }
            }
        }).submit();
    });

    //attend_meeting
    $('body').delegate('#attend_meeting', 'change', function() {
        $('#upload_attend_meeting').ajaxForm({
            dataType: 'json',
            beforeSubmit: function() {
                $("#validation-attend_meeting").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#validation-attend_meeting").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-attend_meeting").show();
                    $("#attend_meeting").css("color", "red");
                } else {
                    $("#validation-attend_meeting").html("<i class='fa fa-check' id='check_attend_meeting' style='color:green;'></i>");
                    $("#clear_attend_meeting").show();
                    $("#validation-attend_meeting").show();
                    $("#attend_meeting").css("color", "green");
                    $("#attend_meeting_url").val(response.file);
                }
            }
        }).submit();
    });

    //proksi
    $('body').delegate('#proksi', 'change', function() {
        $('#upload_proksi').ajaxForm({
            dataType: 'json',
            beforeSubmit: function() {
                $("#validation-proksi").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#validation-proksi").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-proksi").show();
                    $("#proksi").css("color", "red");
                } else {
                    $("#validation-proksi").html("<i class='fa fa-check' id='check_proksi' style='color:green;'></i>");
                    $("#clear_proksi").show();
                    $("#validation-proksi").show();
                    $("#proksi").css("color", "green");
                    $("#proksi_url").val(response.file);
                }
            }
        }).submit();
    });

    //ajk_info
    $('body').delegate('#ajk_info', 'change', function() {
        $('#upload_ajk_info').ajaxForm({
            dataType: 'json',
            beforeSubmit: function() {
                $("#validation-ajk_info").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#validation-ajk_info").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-ajk_info").show();
                    $("#ajk_info").css("color", "red");
                } else {
                    $("#validation-ajk_info").html("<i class='fa fa-check' id='check_ajk_info' style='color:green;'></i>");
                    $("#clear_ajk_info").show();
                    $("#validation-ajk_info").show();
                    $("#ajk_info").css("color", "green");
                    $("#ajk_info_url").val(response.file);
                }
            }
        }).submit();
    });

    //ic
    $('body').delegate('#ic', 'change', function() {
        $('#upload_ic').ajaxForm({
            dataType: 'json',
            beforeSubmit: function() {
                $("#validation-ic").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#validation-ic").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-ic").show();
                    $("#proksi").css("color", "red");
                } else {
                    $("#validation-ic").html("<i class='fa fa-check' id='check_ic' style='color:green;'></i>");
                    $("#clear_ic").show();
                    $("#validation-ic").show();
                    $("#ic").css("color", "green");
                    $("#ic_url").val(response.file);
                }
            }
        }).submit();
    });

    //purchase_aggrement
    $('body').delegate('#purchase_aggrement', 'change', function() {
        $('#upload_purchase_aggrement').ajaxForm({
            dataType: 'json',
            beforeSubmit: function() {
                $("#validation-purchase_aggrement").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#validation-purchase_aggrement").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-purchase_aggrement").show();
                    $("#purchase_aggrement").css("color", "red");
                } else {
                    $("#validation-purchase_aggrement").html("<i class='fa fa-check' id='check_purchase_aggrement' style='color:green;'></i>");
                    $("#clear_purchase_aggrement").show();
                    $("#validation-purchase_aggrement").show();
                    $("#purchase_aggrement").css("color", "green");
                    $("#purchase_aggrement_url").val(response.file);
                }
            }
        }).submit();
    });

    //strata_title
    $('body').delegate('#strata_title', 'change', function() {
        $('#upload_strata_title').ajaxForm({
            dataType: 'json',
            beforeSubmit: function() {
                $("#validation-strata_title").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#validation-strata_title").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-strata_title").show();
                    $("#strata_title").css("color", "red");
                } else {
                    $("#validation-strata_title").html("<i class='fa fa-check' id='check_strata_title' style='color:green;'></i>");
                    $("#clear_strata_title").show();
                    $("#validation-strata_title").show();
                    $("#strata_title").css("color", "green");
                    $("#strata_title_url").val(response.file);
                }
            }
        }).submit();
    });

    //maintenance_statement
    $('body').delegate('#maintenance_statement', 'change', function() {
        $('#upload_maintenance_statement').ajaxForm({
            dataType: 'json',
            beforeSubmit: function() {
                $("#validation-maintenance_statement").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#validation-maintenance_statement").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-maintenance_statement").show();
                    $("#maintenance_statement").css("color", "red");
                } else {
                    $("#validation-maintenance_statement").html("<i class='fa fa-check' id='check_maintenance_statement' style='color:green;'></i>");
                    $("#clear_maintenance_statement").show();
                    $("#validation-maintenance_statement").show();
                    $("#maintenance_statement").css("color", "green");
                    $("#maintenance_statement_url").val(response.file);
                }
            }
        }).submit();
    });

    //integrity_pledge
    $('body').delegate('#integrity_pledge', 'change', function() {
        $('#upload_integrity_pledge').ajaxForm({
            dataType: 'json',
            beforeSubmit: function() {
                $("#validation-integrity_pledge").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#validation-integrity_pledge").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-integrity_pledge").show();
                    $("#integrity_pledge").css("color", "red");
                } else {
                    $("#validation-integrity_pledge").html("<i class='fa fa-check' id='check_integrity_pledge' style='color:green;'></i>");
                    $("#clear_integrity_pledge").show();
                    $("#validation-integrity_pledge").show();
                    $("#integrity_pledge").css("color", "green");
                    $("#integrity_pledge_url").val(response.file);
                }
            }
        }).submit();
    });

    //report_audited_financial
    $('body').delegate('#report_audited_financial', 'change', function() {
        $('#upload_report_audited_financial').ajaxForm({
            dataType: 'json',
            beforeSubmit: function() {
                $("#validation-report_audited_financial").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#validation-report_audited_financial").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-report_audited_financial").show();
                    $("#report_audited_financial").css("color", "red");
                } else {
                    $("#validation-report_audited_financial").html("<i class='fa fa-check' id='check_report_audited_financial' style='color:green;'></i>");
                    $("#clear_report_audited_financial").show();
                    $("#validation-report_audited_financial").show();
                    $("#report_audited_financial").css("color", "green");
                    $("#report_audited_financial_url").val(response.file);
                }
            }
        }).submit();
    });

    //house_rules
    $('body').delegate('#house_rules', 'change', function() {
        $('#upload_house_rules').ajaxForm({
            dataType: 'json',
            beforeSubmit: function() {
                $("#validation-house_rules").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#validation-house_rules").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-house_rules").show();
                    $("#house_rules").css("color", "red");
                } else {
                    $("#validation-house_rules").html("<i class='fa fa-check' id='check_house_rules' style='color:green;'></i>");
                    $("#clear_house_rules").show();
                    $("#validation-house_rules").show();
                    $("#house_rules").css("color", "green");
                    $("#house_rules_url").val(response.file);
                }
            }
        }).submit();
    });
});

// ADD
function clearAGMFile() {
    $("#agm_file").val("");
    $("#agm_file_url").val("");
    $("#agm_file").css("color", "");
    $("#agm_file_error").empty().hide();
}

function clearEGMFile() {
    $("#egm_file").val("");
    $("#egm_file_url").val("");
    $("#egm_file").css("color", "");
    $("#egm_file_error").empty().hide();
}

function clearMinutesMeetingFile() {
    $("#minutes_meeting_file").val("");
    $("#minutes_meeting_file_url").val("");
    $("#minutes_meeting_file").css("color", "");
    $("#minutes_meeting_file_error").empty().hide();
}

function clearJMCFile() {
    $("#jmc_file").val("");
    $("#jmc_file_url").val("");
    $("#jmc_file").css("color", "");
    $("#jmc_file_error").empty().hide();
}

function clearICFile() {
    $("#ic_file").val("");
    $("#ic_file_url").val("");
    $("#ic_file").css("color", "");
    $("#ic_file_error").empty().hide();
}

function clearAttendanceFile() {
    $("#attendance_file").val("");
    $("#attendance_file_url").val("");
    $("#attendance_file").css("color", "");
    $("#attendance_file_error").empty().hide();
}

function clearAuditedFinancialFile() {
    $("#audited_financial_file").val("");
    $("#audited_financial_file_url").val("");
    $("#audited_financial_file").css("color", "");
    $("#audited_financial_file_error").empty().hide();
}

function clearAuditFile() {
    $("#audit_report_file").val("");
    $("#audit_report_file_url").val("");
    $("#audit_report_file").css("color", "grey");
    $("#clear_audit_report_file").hide();
    $("#check_audit_report_file").hide();
}

function clearLetterIntegrity() {
    $("#letter_integrity").val("");
    $("#letter_integrity_url").val("");
    $("#letter_integrity").css("color", "grey");
    $("#clear_letter_integrity").hide();
    $("#check_letter_integrity").hide();
}

function clearLetterBankruptcy() {
    $("#letter_bankruptcy").val("");
    $("#letter_bankruptcy_url").val("");
    $("#letter_bankruptcy").css("color", "grey");
    $("#clear_letter_bankruptcy").hide();
    $("#check_letter_bankruptcy").hide();
}

function clearNoticeAgmEgm() {
    $("#notice_agm_egm").val("");
    $("#notice_agm_egm_url").val("");
    $("#notice_agm_egm").css("color", "grey");
    $("#clear_notice_agm_egm").hide();
    $("#check_notice_agm_egm").hide();
}

function clearMinutesAgmEgm() {
    $("#minutes_agm_egm").val("");
    $("#minutes_agm_egm_url").val("");
    $("#minutes_agm_egm").css("color", "grey");
    $("#clear_minutes_agm_egm").hide();
    $("#check_minutes_agm_egm").hide();
}

function clearMinutesAjk() {
    $("#minutes_ajk").val("");
    $("#minutes_ajk_url").val("");
    $("#minutes_ajk").css("color", "grey");
    $("#clear_minutes_ajk").hide();
    $("#check_minutes_ajk").hide();
}

function clearEligbleVote() {
    $("#eligible_vote").val("");
    $("#eligible_vote_url").val("");
    $("#eligible_vote").css("color", "grey");
    $("#clear_eligible_vote").hide();
    $("#check_eligible_vote").hide();
}

function clearAttendMeeting() {
    $("#attend_meeting").val("");
    $("#attend_meeting_url").val("");
    $("#attend_meeting").css("color", "grey");
    $("#clear_attend_meeting").hide();
    $("#check_attend_meeting").hide();
}

function clearProksi() {
    $("#proksi").val("");
    $("#proksi_url").val("");
    $("#proksi").css("color", "grey");
    $("#clear_proksi").hide();
    $("#check_proksi").hide();
}

function clearAjkInfo() {
    $("#ajk_info").val("");
    $("#ajk_info_url").val("");
    $("#ajk_info").css("color", "grey");
    $("#clear_ajk_info").hide();
    $("#check_ajk_info").hide();
}

function clearIc() {
    $("#ic").val("");
    $("#ic_url").val("");
    $("#ic").css("color", "grey");
    $("#clear_ic").hide();
    $("#check_ic").hide();
}

function clearPurchaseAggrement() {
    $("#purchase_aggrement").val("");
    $("#purchase_aggrement_url").val("");
    $("#purchase_aggrement").css("color", "grey");
    $("#clear_purchase_aggrement").hide();
    $("#check_purchase_aggrement").hide();
}

function clearStrataTitle() {
    $("#strata_title").val("");
    $("#strata_title_url").val("");
    $("#strata_title").css("color", "grey");
    $("#clear_strata_title").hide();
    $("#check_strata_title").hide();
}

function clearMaintenanceStatement() {
    $("#maintenance_statement").val("");
    $("#maintenance_statement_url").val("");
    $("#maintenance_statement").css("color", "grey");
    $("#clear_maintenance_statement").hide();
    $("#check_maintenance_statement").hide();
}

function clearIntegrityPledge() {
    $("#integrity_pledge").val("");
    $("#integrity_pledge_url").val("");
    $("#integrity_pledge").css("color", "grey");
    $("#clear_integrity_pledge").hide();
    $("#check_integrity_pledge").hide();
}

function clearReportAuditedFinancial() {
    $("#report_audited_financial").val("");
    $("#report_audited_financial_url").val("");
    $("#report_audited_financial").css("color", "grey");
    $("#clear_report_audited_financial").hide();
    $("#check_report_audited_financial").hide();
}

function clearHouseRules() {
    $("#house_rules").val("");
    $("#house_rules_url").val("");
    $("#house_rules").css("color", "grey");
    $("#clear_house_rules").hide();
    $("#check_house_rules").hide();
}