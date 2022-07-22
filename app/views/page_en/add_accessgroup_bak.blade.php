@extends('layout.english_layout.default')

@section('content')

<div class="page-content-inner">
    <section class="panel">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <form id="add_access_group">
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
                                    <label class="form-label"><span style="color: red; font-style: italic;">*</span> {{ trans('app.forms.name') }}</label>
                                    <input id="description" class="form-control" placeholder="{{ trans('app.forms.name') }}" type="text">
                                    <div id="description_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table margin-bottom-0" border="0">
                                    <thead>
                                        <tr>
                                            <th width="600" class="text-center">{{ trans('app.forms.description') }}</th>
                                            <th width="100" class="text-center">{{ trans('app.forms.access') }}</th>
                                            <th width="100" class="text-center">{{ trans('app.forms.insert') }}</th>
                                            <th width="100" class="text-center">{{ trans('app.forms.update') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="4"><h6 class="margin-bottom-0">COB Maintenance</h6></th>
                                        </tr>
                                        <tr>
                                            <td>
                                                COB File Prefix
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_cob_prefix" name="accessgroup" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="insert_cob_prefix" name="accessgroup" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="update_cob_prefix" name="accessgroup" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Add COB File
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_add_cob" name="accessgroup" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="insert_add_cob" name="accessgroup" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center"></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                COB File List
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_cob_list" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center"></td>
                                            <td scope="row" class="text-center">
                                               <input type="checkbox" id="update_cob_list" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="4"><h6 class="margin-top-20 margin-bottom-0">Administration</h6></th>
                                        </tr>
                                        <tr>
                                            <td>
                                                Edit Organization Profile
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_edit_company" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center"></td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="update_edit_company" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Access Group Management
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_group_management" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="insert_group_management" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="update_group_management" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                User Management
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_user_management" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="insert_user_management" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="update_user_management" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Memo Maintenance
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_memo" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="insert_memo" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="update_memo" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="4"><h6 class="margin-top-20 margin-bottom-0">Master Setup</h6></th>
                                        </tr>
                                        <tr>
                                            <td>
                                                Area
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_area" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="insert_area" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="update_area" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                City
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_city" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="insert_city" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="update_city" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Category
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_category" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="insert_category" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="update_category" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Land Title
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_land_title" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="insert_land_title" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="update_land_title" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Developer
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_developer" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="insert_developer" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="update_developer" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Agent
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_agent" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="insert_agent" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="update_agent" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Parliament
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_parliament" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="insert_parliament" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="update_parliament" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                DUN
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_dun" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="insert_dun" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="update_dun" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Park
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_park" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="insert_park" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="update_park" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Memo Type
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_memo_type" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="insert_memo_type" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="update_memo_type" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Designation
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_designation" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="insert_designation" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="update_designation" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Unit of Measure
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_unit_measure" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="insert_unit_measure" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="update_unit_measure" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                {{ trans('Liquidator') }}
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_liquidator" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="insert_liquidator" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="update_liquidator" value="0"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="4"><h6 class="margin-top-20 margin-bottom-0">Reporting</h6></th>
                                        </tr>
                                        <tr>
                                            <td>
                                                Audit Trail
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_audit_trail" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center"></td>
                                            <td scope="row" class="text-center"></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                File By Location
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_file_location" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center"></td>
                                            <td scope="row" class="text-center"></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Rating Summary
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_rating_summary" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center"></td>
                                            <td scope="row" class="text-center"></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Management Summary
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_management_summary" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center"></td>
                                            <td scope="row" class="text-center"></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                COB File / Management (%)
                                            </td>
                                            <td scope="row" class="text-center">
                                                <input type="checkbox" id="access_cob_management" value="0"/>
                                            </td>
                                            <td scope="row" class="text-center"></td>
                                            <td scope="row" class="text-center"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><span style="color: red;">*</span> {{ trans('app.forms.admin_status') }}</label>
                                    <select id="is_active" class="form-control">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        <option value="1">{{ trans('app.forms.active') }}</option>
                                        <option value="0">{{ trans('app.forms.inactive') }}</option>
                                    </select>
                                    <div id="is_active_error" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('app.forms.remarks') }}</label>
                                    <textarea class="form-control" rows="3" id="remarks"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-own" id="submit_button" onclick="addAccessGroup()">{{ trans('app.forms.save') }}</button>
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ URL::action("AdminController@accessGroup") }}'">{{ trans('app.forms.cancel') }}</button>
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

    $(document).ready(function(){
        $("input[type=checkbox]").change(function() {
            var access_cob_prefix = $("#access_cob_prefix").is(":checked") ? 1:0;
            $("#access_cob_prefix").val(access_cob_prefix);
        });
    });

    function addAccessGroup() {
        $("#loading").css("display", "inline-block");

        var description = $("#description").val(),
                access_cob_prefix = $("#access_cob_prefix").val(),
                insert_cob_prefix = $("#insert_cob_prefix").val(),
                update_cob_prefix = $("#update_cob_prefix").val(),
                access_add_cob = $("#access_add_cob").val(),
                insert_add_cob = $("#insert_add_cob").val(),
                access_cob_list = $("#access_cob_list").val(),
                update_cob_list = $("#update_cob_list").val(),
                access_edit_company = $("#access_edit_company").val(),
                update_edit_company = $("#update_edit_company").val(),
                access_group_management = $("#access_group_management").val(),
                insert_group_management = $("#insert_group_management").val(),
                update_group_management = $("#update_group_management").val(),
                access_user_management = $("#access_user_management").val(),
                insert_user_management = $("#insert_user_management").val(),
                update_user_management = $("#update_user_management").val(),
                access_memo = $("#access_memo").val(),
                insert_memo = $("#insert_memo").val(),
                update_memo = $("#update_memo").val(),
                access_area = $("#access_area").val(),
                insert_area = $("#insert_area").val(),
                update_area = $("#update_area").val(),
                access_city = $("#access_city").val(),
                insert_city = $("#insert_city").val(),
                update_city = $("#update_city").val(),
                access_category = $("#access_category").val(),
                insert_category = $("#insert_category").val(),
                update_category = $("#update_category").val(),
                access_land_title = $("#access_land_title").val(),
                insert_land_title = $("#insert_land_title").val(),
                update_land_title = $("#update_land_title").val(),
                access_developer = $("#access_developer").val(),
                insert_developer = $("#insert_developer").val(),
                update_developer = $("#update_developer").val(),
                access_agent = $("#access_agent").val(),
                insert_agent = $("#insert_agent").val(),
                update_agent = $("#update_agent").val(),
                access_parliament = $("#access_parliament").val(),
                insert_parliament = $("#insert_parliament").val(),
                update_parliament = $("#update_parliament").val(),
                access_dun = $("#access_dun").val(),
                insert_dun = $("#insert_dun").val(),
                update_dun = $("#update_dun").val(),
                access_park = $("#access_park").val(),
                insert_park = $("#insert_park").val(),
                update_park = $("#update_park").val(),
                access_memo_type = $("#access_memo_type").val(),
                insert_memo_type = $("#insert_memo_type").val(),
                update_memo_type = $("#update_memo_type").val(),
                access_designation = $("#access_designation").val(),
                insert_designation = $("#insert_designation").val(),
                update_designation = $("#update_designation").val(),
                access_unit_measure = $("#access_unit_measure").val(),
                insert_unit_measure = $("#insert_unit_measure").val(),
                update_unit_measure = $("#update_unit_measure").val(),
                access_liquidator = $("#access_liquidator").val(),
                insert_liquidator = $("#insert_liquidator").val(),
                update_liquidator = $("#update_liquidator").val(),
                access_audit_trail = $("#access_audit_trail").val(),
                access_file_location = $("#access_file_location").val(),
                access_rating_summary = $("#access_rating_summary").val(),
                access_management_summary = $("#access_management_summary").val(),
                access_cob_management = $("#access_cob_management").val(),
                remarks = $("#remarks").val(),
                is_active = $("#is_active").val();

        var error = 0;

        if (description.trim() == "") {
            $("#description_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.required", ["attribute"=>"Name"]) }}</span>');
            $("#description_error").css("display", "block");
            error = 1;
        }

        if (is_active.trim() == "") {
            $("#is_active_error").html('<span style="color:red;font-style:italic;font-size:13px;">{{ trans("app.errors.select", ["attribute"=>"Status"]) }}</span>');
            $("#is_active_error").css("display", "block");
            error = 1;
        }

        if (error == 0) {
            $.ajax({
                url: "{{ URL::action('AdminController@submitAccessGroup') }}",
                type: "POST",
                data: {
                    description: description,
                    access_cob_prefix: access_cob_prefix,
                    insert_cob_prefix: insert_cob_prefix,
                    update_cob_prefix: update_cob_prefix,
                    access_add_cob: access_add_cob,
                    insert_add_cob: insert_add_cob,
                    access_cob_list: access_cob_list,
                    update_cob_list: update_cob_list,
                    access_edit_company: access_edit_company,
                    update_edit_company: update_edit_company,
                    access_group_management: access_group_management,
                    insert_group_management: insert_group_management,
                    update_group_management: update_group_management,
                    access_user_management: access_user_management,
                    insert_user_management: insert_user_management,
                    update_user_management: update_user_management,
                    access_memo: access_memo,
                    insert_memo: insert_memo,
                    update_memo: update_memo,
                    access_area: access_area,
                    insert_area: insert_area,
                    update_area: update_area,
                    access_city: access_city,
                    insert_city: insert_city,
                    update_city: update_city,
                    access_category: access_category,
                    insert_category: insert_category,
                    update_category: update_category,
                    access_land_title: access_land_title,
                    insert_land_title: insert_land_title,
                    update_land_title: update_land_title,
                    access_developer: access_developer,
                    insert_developer: insert_developer,
                    update_developer: update_developer,
                    access_agent: access_agent,
                    insert_agent: insert_agent,
                    update_agent: update_agent,
                    access_parliament: access_parliament,
                    insert_parliament: insert_parliament,
                    update_parliament: update_parliament,
                    access_dun: access_dun,
                    insert_dun: insert_dun,
                    update_dun: update_dun,
                    access_park: access_park,
                    insert_park: insert_park,
                    update_park: update_park,
                    access_memo_type: access_memo_type,
                    insert_memo_type: insert_memo_type,
                    update_memo_type: update_memo_type,
                    access_designation: access_designation,
                    insert_designation: insert_designation,
                    update_designation: update_designation,
                    access_unit_measure: access_unit_measure,
                    insert_unit_measure: insert_unit_measure,
                    update_unit_measure: update_unit_measure,
                    access_liquidator: access_liquidator,
                    insert_liquidator: insert_liquidator,
                    update_liquidator: update_liquidator,
                    access_audit_trail: access_audit_trail,
                    access_file_location: access_file_location,
                    access_rating_summary: access_rating_summary,
                    access_management_summary: access_management_summary,
                    access_cob_management: access_cob_management,
                    remarks: remarks,
                    is_active: is_active

                },
                success: function (data) {
                    $("#loading").css("display", "none");
                    $("#submit_button").removeAttr("disabled");
                    $("#cancel_button").removeAttr("disabled");
                    if (data.trim() == "true") {
                        bootbox.alert("<span style='color:green;'>{{ trans('app.successes.access_group.store') }}</span>", function () {
                            window.location = '{{URL::action("AdminController@accessGroup") }}';
                        });
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        }
    }
</script>
<!-- End Page Scripts-->

@stop
