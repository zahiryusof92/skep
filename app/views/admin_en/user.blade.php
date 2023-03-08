@extends('layout.english_layout.default')

@section('content')

    <?php
    $insert_permission = 0;
    $update_permission = 0;
    
    foreach ($user_permission as $permission) {
        if ($permission->submodule_id == 6) {
            $insert_permission = $permission->insert_permission;
            $update_permission = $permission->update_permission;
        }
    }
    ?>

    <div class="page-content-inner">
        <section class="panel panel-style">
            <div class="panel-heading">
                <h3>{{ $title }}</h3>
            </div>
            <div class="panel-body">
                <section class="panel panel-pad">

                    <?php if ($insert_permission == 1) { ?>
                    <div class="row padding-vertical-20">
                        <div class="col-md-6">
                            <button onclick="window.location = '{{ URL::action('AdminController@addUser') }}'" type="button"
                                class="btn btn-own">
                                {{ trans('app.buttons.add_user') }}
                            </button>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="row padding-vertical-20">
                        <div class="col-lg-12 text-center">
                            <form>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>{{ trans('app.forms.cob') }}</label>
                                            <select id="company" class="form-control select2">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                @if ($cob)
                                                    @foreach ($cob as $companies)
                                                        <option value="{{ $companies->name }}">
                                                            {{ $companies->name }} ({{ $companies->short_name }})
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>{{ trans('app.forms.role') }}</label>
                                            <select id="role" class="form-control select2">
                                                <option value="">
                                                    {{ trans('app.forms.please_select') }}
                                                </option>
                                                @if ($role)
                                                    @foreach ($role as $name)
                                                        <option value="{{ $name }}">
                                                            {{ $name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <hr />

                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-hover table-own table-striped" id="userlist_datatable" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width:10%;">{{ trans('app.forms.username') }}</th>
                                        <th style="width:15%;">{{ trans('app.forms.full_name') }}</th>
                                        <th style="width:15%;">{{ trans('app.forms.email') }}</th>
                                        <th style="width:20%;">{{ trans('app.forms.cob') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.access_group') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.is_active') }}</th>
                                        <th style="width:10%;">{{ trans('app.forms.status') }}</th>
                                        <?php if ($update_permission == 1) { ?>
                                        <th style="width:10%;">{{ trans('app.forms.action') }}</th>
                                        <?php } ?>
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
        <!-- End  -->
    </div>

    <!-- Page Scripts -->
    <script>
        $(document).ready(function() {
            var oTable = $('#userlist_datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ URL::action('AdminController@getUser') }}",
                columns: [{
                        data: 'username',
                        name: 'users.username'
                    },
                    {
                        data: 'full_name',
                        name: 'users.full_name'
                    },
                    {
                        data: 'email',
                        name: 'users.email'
                    },
                    {
                        data: 'council',
                        name: 'company.name'
                    },
                    {
                        data: 'role',
                        name: 'role.name'
                    },
                    {
                        data: 'is_active',
                        name: 'users.is_active'
                    },
                    {
                        data: 'status',
                        name: 'users.status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                lengthMenu: [
                    [10, 25, 50],
                    [10, 25, 50]
                ],
                order: [
                    [0, "asc"]
                ],
                responsive: false
            });

            $('#company').on('change', function() {
                oTable.columns(3).search(this.value).draw();
            });

            $('#role').on('change', function() {
                oTable.columns(4).search(this.value).draw();
            });
        });

        function inactiveUser(id) {
            $.ajax({
                url: "{{ URL::action('AdminController@inactiveUser') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data) {
                    if (data.trim() == "true") {
                        bootbox.alert(
                            "<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>",
                            function() {
                                window.location = "{{ URL::action('AdminController@user') }}";
                            });
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        }

        function activeUser(id) {
            $.ajax({
                url: "{{ URL::action('AdminController@activeUser') }}",
                type: "POST",
                data: {
                    id: id
                },
                success: function(data) {
                    if (data.trim() == "true") {
                        bootbox.alert(
                            "<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>",
                            function() {
                                window.location = "{{ URL::action('AdminController@user') }}";
                            });
                    } else {
                        bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                    }
                }
            });
        }

        function deleteUser(id) {
            swal({
                    title: "{{ trans('app.confirmation.are_you_sure') }}",
                    text: "{{ trans('app.confirmation.no_recover_file') }}",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    cancelButtonClass: "btn-default",
                    confirmButtonText: "Delete",
                    closeOnConfirm: true
                },
                function() {
                    $.ajax({
                        url: "{{ URL::action('AdminController@deleteUser') }}",
                        type: "POST",
                        data: {
                            id: id
                        },
                        success: function(data) {
                            if (data.trim() == "true") {
                                swal({
                                    title: "{{ trans('app.successes.deleted_title') }}",
                                    text: "{{ trans('app.successes.users.destroy') }}",
                                    type: "success",
                                    confirmButtonClass: "btn-success",
                                    closeOnConfirm: false
                                });
                                location.reload();
                            } else {
                                bootbox.alert(
                                    "<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>"
                                );
                            }
                        }
                    });
                });
        }
    </script>

@stop
