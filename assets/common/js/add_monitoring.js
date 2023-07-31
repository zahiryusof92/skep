$(document).ready(function () {
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
    }).on('dp.change', function () {
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
    }).on('dp.change', function () {
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
    }).on('dp.change', function () {
        let currentDate = $(this).val().split('-');
        $("#audit_end").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
    });

    // agm_file
    $('body').delegate('#agm_file', 'change', function () {
        $('#upload_agm_file').ajaxForm({
            beforeSubmit: function () {
                $("#agm_file_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#agm_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button class='btn btn-xs btn-danger' onclick='clearAGMFile()'><i class='fa fa-times'></i></button>").show();
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
    $('body').delegate('#egm_file', 'change', function () {
        $('#upload_egm_file').ajaxForm({
            beforeSubmit: function () {
                $("#egm_file_error").empty().hide();
                return true;
            },
            success: function (result) {
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
    $('body').delegate('#minutes_meeting_file', 'change', function () {
        $('#upload_minutes_meeting_file').ajaxForm({
            beforeSubmit: function () {
                $("#minutes_meeting_file_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#minutes_meeting_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearMinutesMeetingFile()'><i class='fa fa-times'></i> </button>").show();
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
    $('body').delegate('#jmc_file', 'change', function () {
        $('#upload_jmc_file').ajaxForm({
            beforeSubmit: function () {
                $("#jmc_file_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#jmc_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearJMCFile()'><i class='fa fa-times'></i> </button>").show();
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
    $('body').delegate('#ic_file', 'change', function () {
        $('#upload_ic_file').ajaxForm({
            beforeSubmit: function () {
                $("#ic_file_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#ic_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearICFile()'><i class='fa fa-times'></i> </button>").show();
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
    $('body').delegate('#attendance_file', 'change', function () {
        $('#upload_attendance_file').ajaxForm({
            beforeSubmit: function () {
                $("#attendance_file_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#attendance_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearAttendanceFile()'><i class='fa fa-times'></i> </button>").show();
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
    $('body').delegate('#audited_financial_file', 'change', function () {
        $('#upload_audited_financial_file').ajaxForm({
            beforeSubmit: function () {
                $("#audited_financial_file_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#audited_financial_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearAuditedFinancialFile()'><i class='fa fa-times'></i> </button>").show();
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
    $('body').delegate('#audit_report_file', 'change', function () {
        $('#upload_audit_report_file').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#audit_report_file_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#audit_report_file_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearAuditFile()'><i class='fa fa-times'></i> </button>").show();
                    $("#audit_report_file_clear").show();
                    $("#audit_report_file").css("color", "green");
                    $("#audit_report_file_url").val(result.file);
                } else {
                    $("#audit_report_file_error").html("<span style='color:red;'><i>" + result.msg + "</i></span>").show();
                    $("#audit_report_file").css("color", "red");
                }
            },
        }).submit();
    });

    //upload letter integrity
    $('body').delegate('#letter_integrity', 'change', function () {
        $('#upload_letter_integrity').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#letter_integrity_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#letter_integrity_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearLetterIntegrity()'><i class='fa fa-times'></i> </button>").show();
                    $("#letter_integrity_clear").show();
                    $("#letter_integrity").css("color", "green");
                    $("#letter_integrity_url").val(result.file);
                } else {
                    $("#letter_integrity_error").html("<span style='color:red;'><i>" + result.msg + "</i></span>").show();
                    $("#letter_integrity").css("color", "red");
                }
            },
        }).submit();
    });

    //upload letter bankruptcy
    $('body').delegate('#letter_bankruptcy', 'change', function () {
        $('#upload_letter_bankruptcy').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#letter_bankruptcy_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#letter_bankruptcy_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearLetterBankruptcy()'><i class='fa fa-times'></i> </button>").show();
                    $("#letter_bankruptcy_clear").show();
                    $("#letter_bankruptcy").css("color", "green");
                    $("#letter_bankruptcy_url").val(result.file);
                } else {
                    $("#letter_bankruptcy_error").html("<span style='color:red;'><i>" + result.msg + "</i></span>").show();
                    $("#letter_bankruptcy").css("color", "red");
                }
            },
        }).submit();
    });

    //notice_agm_egm
    $('body').delegate('#notice_agm_egm', 'change', function () {
        $('#upload_notice_agm_egm').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#notice_agm_egm_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#notice_agm_egm_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearNoticeAgmEgm()'><i class='fa fa-times'></i> </button>").show();
                    $("#notice_agm_egm_clear").show();
                    $("#notice_agm_egm").css("color", "green");
                    $("#notice_agm_egm_url").val(result.file);
                } else {
                    var arr = result.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#notice_agm_egm_error").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
                        }
                    });
                    $("#notice_agm_egm_error").show();
                    $("#notice_agm_egm").css("color", "red");
                }
            },
        }).submit();
    });

    //minutes_agm_egm
    $('body').delegate('#minutes_agm_egm', 'change', function () {
        $('#upload_minutes_agm_egm').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#minutes_agm_egm_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#minutes_agm_egm_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearMinutesAgmEgm()'><i class='fa fa-times'></i> </button>").show();
                    $("#minutes_agm_egm_clear").show();
                    $("#minutes_agm_egm").css("color", "green");
                    $("#minutes_agm_egm_url").val(result.file);
                } else {
                    var arr = result.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#minutes_agm_egm_error").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
                        }
                    });
                    $("#minutes_agm_egm_error").show();
                    $("#minutes_agm_egm").css("color", "red");
                }
            },
        }).submit();
    });

    //minutes_ajk
    $('body').delegate('#minutes_ajk', 'change', function () {
        $('#upload_minutes_ajk').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#minutes_ajk_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#minutes_ajk_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearMinutesAjk()'><i class='fa fa-times'></i> </button>").show();
                    $("#minutes_ajk_clear").show();
                    $("#minutes_ajk").css("color", "green");
                    $("#minutes_ajk_url").val(result.file);
                } else {
                    var arr = result.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#minutes_ajk_error").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
                        }
                    });
                    $("#minutes_ajk_error").show();
                    $("#minutes_ajk").css("color", "red");
                }
            },
        }).submit();
    });

    //eligible_vote
    $('body').delegate('#eligible_vote', 'change', function () {
        $('#upload_eligible_vote').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#eligible_vote_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#eligible_vote_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearEligbleVote()'><i class='fa fa-times'></i> </button>").show();
                    $("#eligible_vote_clear").show();
                    $("#eligible_vote").css("color", "green");
                    $("#eligible_vote_url").val(result.file);
                } else {
                    var arr = result.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#eligible_vote_error").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
                        }
                    });
                    $("#eligible_vote_error").show();
                    $("#eligible_vote").css("color", "red");
                }
            },
        }).submit();
    });

    //attend_meeting
    $('body').delegate('#attend_meeting', 'change', function () {
        $('#upload_attend_meeting').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#attend_meeting_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#attend_meeting_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearAttendMeeting()'><i class='fa fa-times'></i> </button>").show();
                    $("#attend_meeting_clear").show();
                    $("#attend_meeting").css("color", "green");
                    $("#attend_meeting_url").val(result.file);
                } else {
                    var arr = result.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#attend_meeting_error").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
                        }
                    });
                    $("#attend_meeting_error").show();
                    $("#attend_meeting").css("color", "red");
                }
            },
        }).submit();
    });

    //proksi
    $('body').delegate('#proksi', 'change', function () {
        $('#upload_proksi').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#proksi_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#proksi_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearProksi()'><i class='fa fa-times'></i> </button>").show();
                    $("#proksi_clear").show();
                    $("#proksi").css("color", "green");
                    $("#proksi_url").val(result.file);
                } else {
                    var arr = result.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#proksi_error").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
                        }
                    });
                    $("#proksi_error").show();
                    $("#proksi").css("color", "red");
                }
            },
        }).submit();
    });

    //ajk_info
    $('body').delegate('#ajk_info', 'change', function () {
        $('#upload_ajk_info').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#ajk_info_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#ajk_info_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearAjkInfo()'><i class='fa fa-times'></i> </button>").show();
                    $("#ajk_info_clear").show();
                    $("#ajk_info").css("color", "green");
                    $("#ajk_info_url").val(result.file);
                } else {
                    var arr = result.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#ajk_info_error").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
                        }
                    });
                    $("#ajk_info_error").show();
                    $("#ajk_info").css("color", "red");
                }
            },
        }).submit();
    });

    //ic
    $('body').delegate('#ic', 'change', function () {
        $('#upload_ic').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#ic_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#ic_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearIc()'><i class='fa fa-times'></i> </button>").show();
                    $("#ic_clear").show();
                    $("#ic").css("color", "green");
                    $("#ic_url").val(result.file);
                } else {
                    var arr = result.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#ic_error").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
                        }
                    });
                    $("#ic_error").show();
                    $("#ic").css("color", "red");
                }
            },
        }).submit();
    });

    //purchase_aggrement
    $('body').delegate('#purchase_aggrement', 'change', function () {
        $('#upload_purchase_aggrement').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#purchase_aggrement_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#purchase_aggrement_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearPurchaseAggrement()'><i class='fa fa-times'></i> </button>").show();
                    $("#purchase_aggrement_clear").show();
                    $("#purchase_aggrement").css("color", "green");
                    $("#purchase_aggrement_url").val(result.file);
                } else {
                    var arr = result.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#purchase_aggrement_error").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
                        }
                    });
                    $("#purchase_aggrement_error").show();
                    $("#purchase_aggrement").css("color", "red");
                }
            },
        }).submit();
    });

    //strata_title
    $('body').delegate('#strata_title', 'change', function () {
        $('#upload_strata_title').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#strata_title_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#strata_title_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearStrataTitle()'><i class='fa fa-times'></i> </button>").show();
                    $("#strata_title_clear").show();
                    $("#strata_title").css("color", "green");
                    $("#strata_title_url").val(result.file);
                } else {
                    var arr = result.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#strata_title_error").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
                        }
                    });
                    $("#strata_title_error").show();
                    $("#strata_title").css("color", "red");
                }
            },
        }).submit();
    });

    //maintenance_statement
    $('body').delegate('#maintenance_statement', 'change', function () {
        $('#upload_maintenance_statement').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#maintenance_statement_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#maintenance_statement_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearMaintenanceStatement()'><i class='fa fa-times'></i> </button>").show();
                    $("#maintenance_statement_clear").show();
                    $("#maintenance_statement").css("color", "green");
                    $("#maintenance_statement_url").val(result.file);
                } else {
                    var arr = result.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#maintenance_statement_error").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
                        }
                    });
                    $("#maintenance_statement_error").show();
                    $("#maintenance_statement").css("color", "red");
                }
            },
        }).submit();
    });

    //integrity_pledge
    $('body').delegate('#integrity_pledge', 'change', function () {
        $('#upload_integrity_pledge').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#integrity_pledge_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#integrity_pledge_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearIntegrityPledge()'><i class='fa fa-times'></i> </button>").show();
                    $("#integrity_pledge_clear").show();
                    $("#integrity_pledge").css("color", "green");
                    $("#integrity_pledge_url").val(result.file);
                } else {
                    var arr = result.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#integrity_pledge_error").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
                        }
                    });
                    $("#integrity_pledge_error").show();
                    $("#integrity_pledge").css("color", "red");
                }
            },
        }).submit();
    });

    //report_audited_financial
    $('body').delegate('#report_audited_financial', 'change', function () {
        $('#upload_report_audited_financial').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#report_audited_financial_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#report_audited_financial_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearReportAuditedFinancial()'><i class='fa fa-times'></i> </button>").show();
                    $("#report_audited_financial_clear").show();
                    $("#report_audited_financial").css("color", "green");
                    $("#report_audited_financial_url").val(result.file);
                } else {
                    var arr = result.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#report_audited_financial_error").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
                        }
                    });
                    $("#report_audited_financial_error").show();
                    $("#report_audited_financial").css("color", "red");
                }
            },
        }).submit();
    });

    //house_rules
    $('body').delegate('#house_rules', 'change', function () {
        $('#upload_house_rules').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#house_rules_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#house_rules_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearHouseRules()'><i class='fa fa-times'></i> </button>").show();
                    $("#house_rules_clear").show();
                    $("#house_rules").css("color", "green");
                    $("#house_rules_url").val(result.file);
                } else {
                    var arr = result.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#house_rules_error").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
                        }
                    });
                    $("#house_rules_error").show();
                    $("#house_rules").css("color", "red");
                }
            },
        }).submit();
    });

    // OCR
    // notice_agm_egm_ocr
    $('body').delegate('#notice_agm_egm_ocr', 'change', function () {
        $('#upload_notice_agm_egm_ocr').ajaxForm({
            beforeSubmit: function () {
                $("#notice_agm_egm_ocr_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#notice_agm_egm_ocr_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearNoticeAgmEgmOcr()'><i class='fa fa-times'></i> </button>").show();
                    $("#notice_agm_egm_ocr_clear").show();
                    $("#notice_agm_egm_ocr").css("color", "green");
                    $("#notice_agm_egm_ocr_url").val(result.file);
                } else {
                    var arr = result.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#notice_agm_egm_ocr_error").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
                        }
                    });
                    $("#notice_agm_egm_ocr_error").show();
                    $("#notice_agm_egm_ocr").css("color", "red");
                }
            },
            dataType: 'json'
        }).submit();
    });

    // minutes_agm_egm_ocr
    $('body').delegate('#minutes_agm_egm_ocr', 'change', function () {
        $('#upload_minutes_agm_egm_ocr').ajaxForm({
            dataType: 'json',
            beforeSubmit: function () {
                $("#minutes_agm_egm_ocr_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#minutes_agm_egm_ocr_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearMinutesAgmEgmOcr()'><i class='fa fa-times'></i> </button>").show();
                    $("#minutes_agm_egm_ocr_clear").show();
                    $("#minutes_agm_egm_ocr").css("color", "green");
                    $("#minutes_agm_egm_ocr_url").val(result.file);
                } else {
                    var arr = result.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#minutes_agm_egm_ocr_error").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
                        }
                    });
                    $("#minutes_agm_egm_ocr_error").show();
                    $("#minutes_agm_egm_ocr").css("color", "red");
                }
            },
        }).submit();
    });

    // ajk_info_ocr
    $('body').delegate('#ajk_info_ocr', 'change', function () {
        $('#upload_ajk_info_ocr').ajaxForm({
            beforeSubmit: function () {
                $("#ajk_info_ocr_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#ajk_info_ocr_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearAjkInfoOcr()'><i class='fa fa-times'></i> </button>").show();
                    $("#ajk_info_ocr_clear").show();
                    $("#ajk_info_ocr").css("color", "green");
                    $("#ajk_info_ocr_url").val(result.file);
                } else {
                    var arr = result.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#ajk_info_ocr_error").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
                        }
                    });
                    $("#ajk_info_ocr_error").show();
                    $("#ajk_info_ocr").css("color", "red");
                }
            },
            dataType: 'json'
        }).submit();
    });

    // report_audited_financial_ocr
    $('body').delegate('#report_audited_financial_ocr', 'change', function () {
        $('#upload_report_audited_financial_ocr').ajaxForm({
            beforeSubmit: function () {
                $("#report_audited_financial_ocr_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#report_audited_financial_ocr_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearReportAuditedFinancialOcr()'><i class='fa fa-times'></i> </button>").show();
                    $("#report_audited_financial_ocr_clear").show();
                    $("#report_audited_financial_ocr").css("color", "green");
                    $("#report_audited_financial_ocr_url").val(result.file);
                } else {
                    var arr = result.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#report_audited_financial_ocr_error").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
                        }
                    });
                    $("#report_audited_financial_ocr_error").show();
                    $("#report_audited_financial_ocr").css("color", "red");
                }
            },
            dataType: 'json'
        }).submit();
    });

    // house_rules_ocr
    $('body').delegate('#house_rules_ocr', 'change', function () {
        $('#upload_house_rules_ocr').ajaxForm({
            beforeSubmit: function () {
                $("#house_rules_ocr_error").empty().hide();
                return true;
            },
            success: function (result) {
                if (result.success) {
                    $("#house_rules_ocr_error").html("<i class='fa fa-check' style='color:green;'></i>&nbsp;&nbsp;<button type='button' class='btn btn-xs btn-danger' onclick='clearHouseRulesOcr()'><i class='fa fa-times'></i> </button>").show();
                    $("#house_rules_ocr_clear").show();
                    $("#house_rules_ocr").css("color", "green");
                    $("#house_rules_ocr_url").val(result.file);
                } else {
                    var arr = result.errors;
                    $.each(arr, function (index, value) {
                        if (value.length != 0) {
                            $("#house_rules_ocr_error").append('<span style="color:red;font-style:italic;font-size:13px;">' + value + '<span>');
                        }
                    });
                    $("#house_rules_ocr_error").show();
                    $("#house_rules_ocr").css("color", "red");
                }
            },
            dataType: 'json'
        }).submit();
    });
});

function clearEGMFile() {
    $("#egm_file").val("");
    $("#egm_file_url").val("");
    $("#egm_file").css("color", "grey");
    $("#egm_file_error").empty().hide();
}

function clearMinutesMeetingFile() {
    $("#minutes_meeting_file").val("");
    $("#minutes_meeting_file_url").val("");
    $("#minutes_meeting_file").css("color", "grey");
    $("#minutes_meeting_file_error").empty().hide();
}

function clearJMCFile() {
    $("#jmc_file").val("");
    $("#jmc_file_url").val("");
    $("#jmc_file").css("color", "grey");
    $("#jmc_file_error").empty().hide();
}

function clearICFile() {
    $("#ic_file").val("");
    $("#ic_file_url").val("");
    $("#ic_file").css("color", "grey");
    $("#ic_file_error").empty().hide();
}

function clearAttendanceFile() {
    $("#attendance_file").val("");
    $("#attendance_file_url").val("");
    $("#attendance_file").css("color", "grey");
    $("#attendance_file_error").empty().hide();
}

function clearAuditedFinancialFile() {
    $("#audited_financial_file").val("");
    $("#audited_financial_file_url").val("");
    $("#audited_financial_file").css("color", "grey");
    $("#audited_financial_file_error").empty().hide();
}

function clearAuditFile() {
    $("#audit_report_file").val("");
    $("#audit_report_file_url").val("");
    $("#audit_report_file").css("color", "grey");
    $("#audit_report_file_error").empty().hide();
}

function clearLetterIntegrity() {
    $("#letter_integrity").val("");
    $("#letter_integrity_url").val("");
    $("#letter_integrity").css("color", "grey");
    $("#letter_integrity_error").empty().hide();
}

function clearLetterBankruptcy() {
    $("#letter_bankruptcy").val("");
    $("#letter_bankruptcy_url").val("");
    $("#letter_bankruptcy").css("color", "grey");
    $("#letter_bankruptcy_error").empty().hide();
}

function clearNoticeAgmEgm() {
    $("#notice_agm_egm").val("");
    $("#notice_agm_egm_url").val("");
    $("#notice_agm_egm").css("color", "grey");
    $("#notice_agm_egm_error").empty().hide();
}

function clearMinutesAgmEgm() {
    $("#minutes_agm_egm").val("");
    $("#minutes_agm_egm_url").val("");
    $("#minutes_agm_egm").css("color", "grey");
    $("#minutes_agm_egm_error").empty().hide();
}

function clearMinutesAjk() {
    $("#minutes_ajk").val("");
    $("#minutes_ajk_url").val("");
    $("#minutes_ajk").css("color", "grey");
    $("#minutes_ajk_error").empty().hide();
}

function clearEligbleVote() {
    $("#eligible_vote").val("");
    $("#eligible_vote_url").val("");
    $("#eligible_vote").css("color", "grey");
    $("#eligible_vote_error").empty().hide();
}

function clearAttendMeeting() {
    $("#attend_meeting").val("");
    $("#attend_meeting_url").val("");
    $("#attend_meeting").css("color", "grey");
    $("#attend_meeting_error").empty().hide();
}

function clearProksi() {
    $("#proksi").val("");
    $("#proksi_url").val("");
    $("#proksi").css("color", "grey");
    $("#proksi_error").empty().hide();
}

function clearAjkInfo() {
    $("#ajk_info").val("");
    $("#ajk_info_url").val("");
    $("#ajk_info").css("color", "grey");
    $("#ajk_info_error").empty().hide();
}

function clearIc() {
    $("#ic").val("");
    $("#ic_url").val("");
    $("#ic").css("color", "grey");
    $("#ic_error").empty().hide();
}

function clearPurchaseAggrement() {
    $("#purchase_aggrement").val("");
    $("#purchase_aggrement_url").val("");
    $("#purchase_aggrement").css("color", "grey");
    $("#purchase_aggrement_error").empty().hide();
}

function clearStrataTitle() {
    $("#strata_title").val("");
    $("#strata_title_url").val("");
    $("#strata_title").css("color", "grey");
    $("#strata_title_error").empty().hide();
}

function clearMaintenanceStatement() {
    $("#maintenance_statement").val("");
    $("#maintenance_statement_url").val("");
    $("#maintenance_statement").css("color", "grey");
    $("#maintenance_statement_error").empty().hide();
}

function clearIntegrityPledge() {
    $("#integrity_pledge").val("");
    $("#integrity_pledge_url").val("");
    $("#integrity_pledge").css("color", "grey");
    $("#integrity_pledge_error").empty().hide();
}

function clearReportAuditedFinancial() {
    $("#report_audited_financial").val("");
    $("#report_audited_financial_url").val("");
    $("#report_audited_financial").css("color", "grey");
    $("#report_audited_financial_error").empty().hide();
}

function clearHouseRules() {
    $("#house_rules").val("");
    $("#house_rules_url").val("");
    $("#house_rules").css("color", "grey");
    $("#house_rules_error").empty().hide();
}

// OCR
function clearNoticeAgmEgmOcr() {
    $("#notice_agm_egm_ocr").val("");
    $("#notice_agm_egm_ocr_url").val("");
    $("#notice_agm_egm_ocr").css("color", "grey");
    $("#notice_agm_egm_ocr_error").hide();
}

function clearMinutesAgmEgmOcr() {
    $("#minutes_agm_egm_ocr").val("");
    $("#minutes_agm_egm_ocr_url").val("");
    $("#minutes_agm_egm_ocr").css("color", "grey");
    $("#minutes_agm_egm_ocr_error").hide();
}

function clearAjkInfoOcr() {
    $("#ajk_info_ocr").val("");
    $("#ajk_info_ocr_url").val("");
    $("#ajk_info_ocr").css("color", "grey");
    $("#ajk_info_ocr_error").hide();
}

function clearReportAuditedFinancialOcr() {
    $("#report_audited_financial_ocr").val("");
    $("#report_audited_financial_ocr_url").val("");
    $("#report_audited_financial_ocr").css("color", "grey");
    $("#report_audited_financial_ocr_error").hide();
}

function clearHouseRulesOcr() {
    $("#house_rules_ocr").val("");
    $("#house_rules_ocr_url").val("");
    $("#house_rules_ocr").css("color", "grey");
    $("#house_rules_ocr_error").hide();
}