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
                @if (Auth::user()->isJMB())
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">
                        <a class="btn btn-own-outline margin-bottom-25" href="{{ route('summon.create', Summon::LETTER_OF_REMINDER) }}">
                            {{ trans('app.summon.letter_of_reminder') }}
                        </a> 
                        <a class="btn btn-success-outline margin-bottom-25 margin-left-10" href="{{ route('summon.create', Summon::LETTER_OF_DEMAND) }}">
                            {{ trans('app.summon.letter_of_demand') }}
                        </a> 
                    </div>
                </div>
                @endif

                <div class="row padding-vertical-20">
                    <div class="col-lg-12">                    
                        <table class="table table-hover table-own table-striped" id="summon_table" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:15%;">{{ trans('app.summon.created_at') }}</th>
                                    <th style="width:10%;">{{ trans('app.summon.unit_no') }}</th>
                                    <th style="width:20%;">{{ trans('app.summon.name') }}</th>
                                    <th style="width:15%;">{{ trans('app.summon.phone_no') }}</th>
                                    <th style="width:15%;">{{ trans('app.summon.type') }}</th>
                                    <th style="width:10%;">{{ trans('app.summon.status') }}</th>                                
                                    <th style="width:15%; text-align: center;">{{ trans('app.summon.action') }}</th>
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
            ajax: "{{ route('summon.index') }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 10,
            order: [[0, "desc"]],
            responsive: true,
            columns: [
                {data: 'created_at', name: 'created_at'},
                {data: 'unit_no', name: 'unit_no'},
                {data: 'name', name: 'name'},
                {data: 'phone_no', name: 'phone_no'},
                {data: 'type', name: 'type'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            'columnDefs': [{"targets": -1, "className": "text-center"}]
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