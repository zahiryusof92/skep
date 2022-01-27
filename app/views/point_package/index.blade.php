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
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">

                        <div class="margin-bottom-30">
                            <a href="{{ route('pointPackage.create') }}" class="btn btn-own">
                                {{ trans('app.point_package.add_package') }}
                            </a>
                        </div>

                        <table class="table table-hover nowrap table-own table-striped" id="point_package_table" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:45%;">{{ trans('app.point_package.name') }}</th>
                                    <th style="width:20%;">{{ trans('app.point_package.points') }}</th>
                                    <th style="width:20%;">{{ trans('app.point_package.price') }}</th>
                                    <th style="width:15%;">{{ trans('app.forms.action') }}</th>
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
    $(document).ready(function () {
        $('#point_package_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('pointPackage.index') }}",
            lengthMenu: [[5, 10, 50], [5, 10, 50]],
            pageLength: 10,
            order: [[0, "asc"]],
            responsive: true,
            columns: [
                {data: 'name', name: 'name'},
                {data: 'points', name: 'points'},
                {data: 'price', name: 'price'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [{"targets": -1, "className": "text-center"}]
        });
    });

    function inactive(id) {
        $.ajax({
            url: "{{ url('pointPackage/inactive') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function (data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>", function () {
                        location.reload();
                    });
                } else {
                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                }
            }
        });
    }

    function active(id) {
        $.ajax({
            url: "{{ url('pointPackage/active') }}",
            type: "POST",
            data: {
                id: id
            },
            success: function (data) {
                if (data.trim() == "true") {
                    bootbox.alert("<span style='color:green;'>{{ trans('app.successes.statuses.update') }}</span>", function () {
                        location.reload();
                    });
                } else {
                    bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                }
            }
        });
    }

    $('body').on('click', '.confirm-delete', function (e) {
        e.preventDefault();
        let formId = $(this).data('id');

        swal({
            title: "{{ trans('app.confirmation.are_you_sure') }}",
            text: "{{ trans('app.confirmation.no_recover_file') }}",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            cancelButtonClass: "btn-default",
            confirmButtonText: "Delete",
            closeOnConfirm: true
        }, function () {
            $('#' + formId).submit();
        });
    });
</script>
@endsection