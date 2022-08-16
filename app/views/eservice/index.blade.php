@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>

        <div class="panel-body">
            <div class="widget widget-four background-transparent">
                @if (!empty($options))
                <div class="row">
                    @foreach ($options as $option)
                    <div class="col-xl-3 col-lg-6 col-sm-6 col-xs-12">
                        <a href="{{ route('eservice.create', $option['id']) }}">
                            <div class="step-block">
                                <div class="step-desc">
                                    <span class="step-title">
                                        {{ $option['text'] }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>
    <!-- End  -->
</div>

<!-- Page Scripts -->
<script>

</script>
@endsection