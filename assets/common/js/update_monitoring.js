$(document).ready(function () {
    //ADD
    //upload audit report file
    $('body').delegate('#audit_report_file', 'change', function () {
        $('#upload_audit_report_file').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#validation-errors_audit_report_file").hide().empty();
                return true;
            },
            success: function (response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function (index, value) {
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
    $('body').delegate('#letter_integrity', 'change', function () {
        $('#upload_letter_integrity').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#validation-errors_letter_integrity").hide().empty();
                return true;
            },
            success: function (response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function (index, value) {
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
    $('body').delegate('#letter_bankruptcy', 'change', function () {
        $('#upload_letter_bankruptcy').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#validation-errors_letter_bankruptcy").hide().empty();
                return true;
            },
            success: function (response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function (index, value) {
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
    $('body').delegate('#notice_agm_egm', 'change', function () {
        $('#upload_notice_agm_egm').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#validation-notice_agm_egm").hide().empty();
                return true;
            },
            success: function (response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function (index, value) {
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
    $('body').delegate('#minutes_agm_egm', 'change', function () {
        $('#upload_minutes_agm_egm').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#validation-minutes_agm_egm").hide().empty();
                return true;
            },
            success: function (response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function (index, value) {
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
    $('body').delegate('#minutes_ajk', 'change', function () {
        $('#upload_minutes_ajk').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#validation-minutes_ajk").hide().empty();
                return true;
            },
            success: function (response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function (index, value) {
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
    $('body').delegate('#eligible_vote', 'change', function () {
        $('#upload_eligible_vote').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#validation-eligible_vote").hide().empty();
                return true;
            },
            success: function (response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function (index, value) {
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
    $('body').delegate('#attend_meeting', 'change', function () {
        $('#upload_attend_meeting').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#validation-attend_meeting").hide().empty();
                return true;
            },
            success: function (response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function (index, value) {
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
    $('body').delegate('#proksi', 'change', function () {
        $('#upload_proksi').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#validation-proksi").hide().empty();
                return true;
            },
            success: function (response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function (index, value) {
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
    $('body').delegate('#ajk_info', 'change', function () {
        $('#upload_ajk_info').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#validation-ajk_info").hide().empty();
                return true;
            },
            success: function (response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#validation-ajk_info").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-ajk_info").show();
                    $("#proksi").css("color", "red");
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
    $('body').delegate('#ic', 'change', function () {
        $('#upload_ic').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#validation-ic").hide().empty();
                return true;
            },
            success: function (response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function (index, value) {
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
    $('body').delegate('#purchase_aggrement', 'change', function () {
        $('#upload_purchase_aggrement').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#validation-purchase_aggrement").hide().empty();
                return true;
            },
            success: function (response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function (index, value) {
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

    //maintenance_statement
    $('body').delegate('#maintenance_statement', 'change', function () {
        $('#upload_maintenance_statement').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#validation-maintenance_statement").hide().empty();
                return true;
            },
            success: function (response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function (index, value) {
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
    $('body').delegate('#integrity_pledge', 'change', function () {
        $('#upload_integrity_pledge').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#validation-integrity_pledge").hide().empty();
                return true;
            },
            success: function (response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function (index, value) {
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
    $('body').delegate('#report_audited_financial', 'change', function () {
        $('#upload_report_audited_financial').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#validation-report_audited_financial").hide().empty();
                return true;
            },
            success: function (response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function (index, value) {
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
    $('body').delegate('#house_rules', 'change', function () {
        $('#upload_house_rules').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#validation-house_rules").hide().empty();
                return true;
            },
            success: function (response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function (index, value) {
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

    // EDIT
    //upload audit report file edit
    $('body').delegate('#audit_report_file_edit', 'change', function () {
        $('#upload_audit_report_file_edit').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#validation-errors_audit_report_file_edit").hide().empty();
                return true;
            },
            success: function (response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#validation-errors_audit_report_file_edit").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-errors_audit_report_file_edit").show();
                    $("#audit_report_file_edit").css("color", "red");
                } else {
                    $("#report_edit").hide();
                    $("#validation-errors_audit_report_file_edit").html("<i class='fa fa-check' id='check_audit_report_file_edit' style='color:green;'></i>");
                    $("#clear_audit_report_file_edit").show();
                    $("#validation-errors_audit_report_file_edit").show();
                    $("#audit_report_file_edit").css("color", "green");
                    $("#audit_report_file_url_edit").val(response.file);
                }
            }
        }).submit();
    });

    //upload letter integrity edit
    $('body').delegate('#letter_integrity_edit', 'change', function () {
        $('#upload_letter_integrity_edit').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#validation-errors_letter_integrity_edit").hide().empty();
                return true;
            },
            success: function (response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#validation-errors_letter_integrity_edit").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-errors_letter_integrity_edit").show();
                    $("#letter_integrity_edit").css("color", "red");
                } else {
                    $("#integrity_edit").hide();
                    $("#validation-errors_letter_integrity_edit").html("<i class='fa fa-check' id='check_letter_integrity_edit' style='color:green;'></i>");
                    $("#clear_letter_integrity_edit").show();
                    $("#validation-errors_letter_integrity_edit").show();
                    $("#letter_integrity_edit").css("color", "green");
                    $("#letter_integrity_url_edit").val(response.file);
                }
            }
        }).submit();
    });

    //upload letter bankruptcy edit
    $('body').delegate('#letter_bankruptcy_edit', 'change', function () {
        $('#upload_letter_bankruptcy_edit').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#validation-errors_letter_bankruptcy_edit").hide().empty();
                return true;
            },
            success: function (response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#validation-errors_letter_bankruptcy_edit").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-errors_letter_bankruptcy_edit").show();
                    $("#letter_bankruptcy_edit").css("color", "red");
                } else {
                    $("#bankruptcy_edit").hide();
                    $("#validation-errors_letter_bankruptcy_edit").html("<i class='fa fa-check' id='check_letter_bankruptcy_edit' style='color:green;'></i>");
                    $("#clear_letter_bankruptcy_edit").show();
                    $("#validation-errors_letter_bankruptcy_edit").show();
                    $("#letter_bankruptcy_edit").css("color", "green");
                    $("#letter_bankruptcy_url_edit").val(response.file);
                }
            }
        }).submit();
    });
});

//notice_agm_egm
$('body').delegate('#notice_agm_egm_edit', 'change', function () {
    $('#upload_notice_agm_egm_edit').ajaxForm({
        dataType: 'json',
        beforeSubmit: function () {
            $("#validation-notice_agm_egm_edit").hide().empty();
            return true;
        },
        success: function (response) {
            if (response.success == false) {
                var arr = response.errors;
                $.each(arr, function (index, value) {
                    if (value.length != 0) {
                        $("#validation-notice_agm_egm_edit").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                    }
                });
                $("#validation-notice_agm_egm_edit").show();
                $("#notice_agm_egm_edit").css("color", "red");
            } else {
                $("#btn_notice_agm_egm_edit").hide();
                $("#validation-notice_agm_egm_edit").html("<i class='fa fa-check' id='check_notice_agm_egm_edit' style='color:green;'></i>");
                $("#clear_notice_agm_egm_edit").show();
                $("#validation-notice_agm_egm_edit").show();
                $("#notice_agm_egm_edit").css("color", "green");
                $("#notice_agm_egm_url_edit").val(response.file);
            }
        }
    }).submit();
});



// ADD
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

// EDIT
function clearAuditFileEdit() {
    $("#audit_report_file_edit").val("");
    $("#audit_report_file_url_edit").val("");
    $("#audit_report_file_edit").css("color", "grey");
    $("#clear_audit_report_file_edit").hide();
    $("#check_audit_report_file_edit").hide();
}

function clearLetterIntegrityEdit() {
    $("#letter_integrity_edit").val("");
    $("#letter_integrity_url_edit").val("");
    $("#letter_integrity_edit").css("color", "grey");
    $("#clear_letter_integrity_edit").hide();
    $("#check_letter_integrity_edit").hide();
}

function clearLetterBankruptcyEdit() {
    $("#letter_bankruptcy_edit").val("");
    $("#letter_bankruptcy_url_edit").val("");
    $("#letter_bankruptcy_edit").css("color", "grey");
    $("#clear_letter_bankruptcy_edit").hide();
    $("#check_letter_bankruptcy_edit").hide();
}

function clearNoticeAgmEgmEdit() {
    $("#notice_agm_egm_edit").val("");
    $("#notice_agm_egm_url_edit").val("");
    $("#notice_agm_egm_edit").css("color", "grey");
    $("#clear_notice_agm_egm_edit").hide();
    $("#check_notice_agm_egm_edit").hide();
}

function clearMinutesAgmEgmEdit() {
    $("#minutes_agm_egm_edit").val("");
    $("#minutes_agm_egm_url_edit").val("");
    $("#minutes_agm_egm_edit").css("color", "grey");
    $("#clear_minutes_agm_egm_edit").hide();
    $("#check_minutes_agm_egm_edit").hide();
}

function clearMinutesAjkEdit() {
    $("#minutes_ajk_edit").val("");
    $("#minutes_ajk_url_edit").val("");
    $("#minutes_ajk_edit").css("color", "grey");
    $("#clear_minutes_ajk_edit").hide();
    $("#check_minutes_ajk_edit").hide();
}

function clearEligbleVoteEdit() {
    $("#eligible_vote_edit").val("");
    $("#eligible_vote_url_edit").val("");
    $("#eligible_vote_edit").css("color", "grey");
    $("#clear_eligible_vote_edit").hide();
    $("#check_eligible_vote_edit").hide();
}

function clearAttendMeetingEdit() {
    $("#attend_meeting_edit").val("");
    $("#attend_meeting_url_edit").val("");
    $("#attend_meeting_edit").css("color", "grey");
    $("#clear_attend_meeting_edit").hide();
    $("#check_attend_meeting_edit").hide();
}

function clearProksiEdit() {
    $("#proksi_edit").val("");
    $("#proksi_url_edit").val("");
    $("#proksi_edit").css("color", "grey");
    $("#clear_proksi_edit").hide();
    $("#check_proksi_edit").hide();
}

function clearAjkInfoEdit() {
    $("#ajk_info_edit").val("");
    $("#ajk_info_url_edit").val("");
    $("#ajk_info_edit").css("color", "grey");
    $("#clear_ajk_info_edit").hide();
    $("#check_ajk_info_edit").hide();
}

function clearIcEdit() {
    $("#ic_edit").val("");
    $("#ic_url_edit").val("");
    $("#ic_edit").css("color", "grey");
    $("#clear_ic_edit").hide();
    $("#check_ic_edit").hide();
}

function clearPurchaseAggrementEdit() {
    $("#purchase_aggrement_edit").val("");
    $("#purchase_aggrement_url_edit").val("");
    $("#purchase_aggrement_edit").css("color", "grey");
    $("#clear_purchase_aggrement_edit").hide();
    $("#check_purchase_aggrement_edit").hide();
}

function clearMaintenanceStatementEdit() {
    $("#maintenance_statement_edit").val("");
    $("#maintenance_statement_url_edit").val("");
    $("#maintenance_statement_edit").css("color", "grey");
    $("#clear_maintenance_statement_edit").hide();
    $("#check_maintenance_statement_edit").hide();
}

function clearIntegrityPledgeEdit() {
    $("#integrity_pledge_edit").val("");
    $("#integrity_pledge_url_edit").val("");
    $("#integrity_pledge_edit").css("color", "grey");
    $("#clear_integrity_pledge_edit").hide();
    $("#check_integrity_pledge_edit").hide();
}

function clearReportAuditedFinancialEdit() {
    $("#report_audited_financial_edit").val("");
    $("#report_audited_financial_url_edit").val("");
    $("#report_audited_financial_edit").css("color", "grey");
    $("#clear_report_audited_financial_edit").hide();
    $("#check_report_audited_financial_edit").hide();
}

function clearHouseRulesEdit() {
    $("#house_rules_edit").val("");
    $("#house_rules_url_edit").val("");
    $("#house_rules_edit").css("color", "grey");
    $("#clear_house_rules_edit").hide();
    $("#check_house_rules_edit").hide();
}