@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>
        <div class="panel-body">
            <section class="panel panel-pad">
                <div class="row padding-vertical-20">
                    <div class="col-lg-12">
                        
                        <table class="table table-hover nowrap table-own table-striped" id="price" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:30%;">{{ trans('app.forms.cob') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.category') }}</th>
                                    <th style="width:35%;">{{ trans('app.forms.type') }}</th> 
                                    <th style="width:20%;">{{ trans('app.forms.price') }} (RM)</th>                                    
                                    <th style="width:5%;">{{ trans('app.forms.action') }}</th>
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
    var oTable;
    $(document).ready(function () {
        oTable = $('#price').DataTable({
            "sAjaxSource": "{{ URL::action('EServicePriceController@index') }}",
            "lengthMenu": [[10, 25, 50], [10, 25, 50]],
            "order": [[ 0, "asc" ], [ 1, "asc" ]],
            "responsive": true
        });
    });
</script>

@stop