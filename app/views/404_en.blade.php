@extends('layout.english_layout.loginlayout')

@section('content')

<div class="page-content-inner">

    <!-- Page 404 -->
    <div class="single-page-block">
        <div class="margin-auto text-center max-width-500">
            <h1>{{$title}}</h1>
            <h6>{{ trans('app.page404.body') }}</h6>
            <button class="btn btn-own" onclick="window.history.go(-1); return false;">{{ trans('app.page404.action') }}</button>
        </div>
    </div>
    <!-- End Page 404 -->

</div>

@stop
