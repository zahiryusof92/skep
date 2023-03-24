@extends('layout.english_layout.default')

@section('content')
    <?php
    $company = Company::find(Auth::user()->company_id);
    ?>

    <div class="page-content-inner">
        <section class="panel panel-style">
            <div class="panel-heading">
                <h3>{{ $title }}</h3>
            </div>
            <div class="panel-body">
                <div class="invoice-block">

                    <div class="row">
                        <table width="100%">
                            <tr>
                                <td class="text-center">
                                    <h4 class="margin-bottom-0">
                                        <img src="{{ asset($company->image_url) }}" height="100px;" alt="">
                                    </h4>
                                </td>
                                <td>
                                    <h5 class="margin-bottom-10">
                                        {{ $company->name }}
                                    </h5>
                                    <h6 class="margin-bottom-0">
                                        {{ $title }}
                                    </h6>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <hr />

                    <section class="panel panel-pad">
                        <div class="row margin-top-30 margin-bottom-30">
                            <div class="col-lg-12">
                                <span style="font-size: 12px;">
                                    <b>
                                        {{ trans('app.forms.date') }}:
                                    </b>
                                </span>
                                &nbsp;
                                <input style="font-size: 12px;" id="date_from" data-column="0" type="text"
                                    class="form-control width-150 display-inline-block datetimepicker" placeholder="From" />
                                <span style="font-size: 12px;" class="margin-right-10">&nbsp; —</span>
                                <input style="font-size: 12px;" id="date_to" data-column="0" type="text"
                                    class="form-control width-150 display-inline-block datetimepicker" placeholder="To" />
                            </div>
                        </div>
                    </section>

                </div>
            </div>
        </section>
    </div>

    <script>
        $(document).ready(function() {
            oTable = $('#audit_trail').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('reporting.log.index') }}",
                    'data': function(data) {
                        var date_from = $('#date_from').val();
                        var date_to = $('#date_to').val();

                        $('#export_date_from').val(date_from);
                        $('#export_date_to').val(date_to);

                        // Append to data
                        data.date_from = date_from;
                        data.date_to = date_to;
                    }
                },
                "dom": '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                "order": [
                    [6, "desc"]
                ],
                "lengthMenu": [
                    [10, 25, 50],
                    [10, 25, 50]
                ],
                "pageLength": 25,
                "scrollX": true,
                "responsive": false,
                "columns": [{
                        data: 'company_id',
                        name: 'company.name'
                    },
                    {
                        data: 'file_id',
                        name: 'files.file_no'
                    },
                    {
                        data: 'module',
                        name: 'audit_trail.module'
                    },
                    {
                        data: 'remarks',
                        name: 'audit_trail.remarks'
                    },
                    {
                        data: 'role_name',
                        searchable: false
                    },
                    {
                        data: 'audit_by',
                        name: 'users.full_name'
                    },
                    {
                        data: 'created_at',
                        name: 'audit_trail.created_at'
                    },
                ],
                "fnDrawCallback": function(oSettings) {
                    $.unblockUI();
                }
            });
        });
    </script>
@endsection
