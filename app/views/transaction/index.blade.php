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
                        <table class="table table-hover table-own table-striped" id="summon_table" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:15%;">{{ trans('app.transaction.created_at') }}</th>
                                    <th style="width:10%;">{{ trans('app.transaction.reference_no') }}</th>
                                    <th style="width:10%;">{{ trans('app.transaction.pay_for') }}</th>
                                    <th style="width:20%;">{{ trans('app.transaction.user') }}</th>
                                    <th style="width:20%;">{{ trans('app.transaction.amount') }}</th>
                                    <th style="width:20%;">{{ trans('app.transaction.status') }}</th>                               
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
    <!-- End -->
</div>

<script>
    $(document).ready(function () {
        $('#summon_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('transaction/get') }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 10,
            order: [[0, "desc"]],
            responsive: true,
            columns: [
                {data: 'created_at', name: 'created_at'},
                {data: 'reference_no', name: 'reference_no'},
                {data: 'pay_for', name: 'pay_for'},
                {data: 'user', name: 'user'},
                {data: 'amount', name: 'amount'},
                {data: 'status', name: 'status'},
            ],
            'columnDefs': [
                {"targets": 2, "className": "text-center"},
                {"targets": 3, "className": "text-center"},
                {"targets": 4, "className": "text-center"},
                {"targets": 5, "className": "text-center"},
            ]
        });
    });

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
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: true
        }, function () {
            $('#' + formId).submit();
        });
    });
</script>
@endsection