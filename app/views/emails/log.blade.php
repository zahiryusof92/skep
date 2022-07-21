@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{ trans('app.forms.email_log') }}</h3>
        </div>
        <div class="panel-body">
            
            @include('alert.bootbox')
            
            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">

                        <table class="table table-hover table-own table-striped" id="email_log_table" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:20%;">{{ trans('app.forms.name') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.file_no') }}</th>
                                    <th style="width:20%;">{{ trans('app.forms.strata') }}</th>
                                    <th style="width:30%;">{{ trans('app.forms.description') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.created_at') }}</th>
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
        $('#email_log_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('email_log.index') }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 10,
            order: [[4, "desc"]],
            responsive: true,
            columns: [
                {data: 'user_id', name: 'users.full_name'},
                {data: 'file_no', name: 'files.file_no'},
                {data: 'strata', name: 'strata.name'},
                {data: 'description', name: 'email_logs.description'},
                {data: 'created_at', name: 'email_logs.created_at'},
            ],
        });
    });
</script>
@endsection