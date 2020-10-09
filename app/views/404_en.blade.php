@extends('layout.english_layout.loginlayout')

@section('content')

<div class="page-content-inner">

    <!-- Page 404 -->
    <div class="single-page-block">
        <div class="margin-auto text-center max-width-500">
            <h1>{{$title}}</h1>
            <h6>{{ trans('app.page404.body') }}</h6>
            <a class="btn btn-primary" href="{{ URL::action('AdminController@home') }}">{{ trans('app.page404.action') }}</a>
        </div>
    </div>
    <!-- End Page 404 -->

</div>

@stop
