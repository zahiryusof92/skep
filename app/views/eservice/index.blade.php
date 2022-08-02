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
                            <a href="{{ route('eservice.create') }}" class="btn btn-own">
                                {{ trans('app.buttons.add_new_application') }}
                            </a>
                        </div>
                        
                        <table class="table table-hover nowrap table-own table-striped" id="epks_table" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:20%;">{{ trans('app.forms.file_no') }}</th>
                                    <th style="width:30%;">{{ trans('app.forms.name') }}</th>
                                    <th style="width:30%;">{{ trans('app.forms.address') }}</th>
                                    <th style="width:5%;">{{ trans('app.forms.status') }}</th>
                                    <th style="width:5%;">{{ trans('app.forms.created_at') }}</th>
                                    <th style="width:10%;">{{ trans('app.forms.action') }}</th>
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
    
</script>
@endsection