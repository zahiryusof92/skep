@extends('layout.english_layout.default')

@section('content')

<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>
                {{ $title }}
            </h3>
        </div>

        <div class="panel-body">
            <section class="panel panel-with-borders">
                <div class="panel-heading">
                    <h5>
                        {{ trans('Search result for') }}:
                        {{ $keyword }}
                    </h5>
                </div>
                <div class="panel-body">
                    @if (count($results) > 0)
                    @foreach ($results as $result)
                    <section class="panel">
                        <a href="{{ $result['url'] }}" target="_blank">
                            <div class="panel-heading">
                                <h5>
                                    {{ $result['type'] }}
                                </h5>
                            </div>
                            <div class="panel-body">
                                <span>
                                    {{ $result['text'] }}
                                </span>
                            </div>
                        </a>
                    </section>
                    @endforeach
                    @else
                    <section class="panel">
                        <div class="panel-heading">
                            <div class="panel-body">
                                <span>
                                    {{ trans('No result found') }}
                                </span>
                            </div>
                    </section>
                    @endif
                </div>
                <div class="panel-footer">
                    <span>
                        {{ trans('Found') . ' ' . count($results) . ' ' . trans('results') }}
                    </span>
                </div>
            </section>
        </div>
    </section>
</div>

@endsection