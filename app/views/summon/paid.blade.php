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

                @if(Auth::user()->isHR())
                    <div class="row padding-vertical-10">
                        <div class="col-lg-12 text-center">
                            <form>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ trans('app.forms.cob') }}</label>
                                            <select id="company" class="form-control select2">
                                                @if (count($cob) > 1)
                                                <option value="">{{ trans('app.forms.please_select') }}</option>
                                                @endif
                                                @foreach ($cob as $companies)
                                                <option value="{{ $companies->short_name }}">{{ $companies->name }} ({{ $companies->short_name }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <hr />
                @endif

                <div class="row padding-vertical-20">
                    <div class="col-lg-12">                    
                        <table class="table table-hover table-own table-striped" id="paid_table" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:15%;">{{ trans('app.summon.created_at') }}</th>
                                    <th style="width:10%;">{{ trans('app.summon.council') }}</th>
                                    <th style="width:10%;">{{ trans('app.summon.reference_no') }}</th>
                                    <th style="width:20%;">{{ trans('app.summon.attachment') }}</th>
                                    <th style="width:10%;">{{ trans('app.summon.paid_amount') }}</th>
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
        var oTable = $('#paid_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ URL::action('SummonController@paidListing') }}",
            lengthMenu: [[5, 10, 50, -1], [5, 10, 50, "All"]],
            pageLength: 10,
            order: [[0, "desc"]],
            responsive: true,
            columns: [
                {data: 'created_at', name: 'created_at'},
                {data: 'cob', name: 'company.short_name'},
                {data: 'reference_no', name: 'id'},
                {data: 'attachment', name: 'id'},
                {data: 'amount', name: 'amount'},
            ],
        });

        $('#company').on('change', function () {
            oTable.columns(1).search(this.value).draw();
        });
        
    });
</script>
@endsection