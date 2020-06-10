@extends('layout.default')

@section('content')

{{$welcomeText}}
{{$name}}


@foreach($customers as $customer)

    {{$customer->name}} - {{$customer->age}}<br />

@endforeach


@stop