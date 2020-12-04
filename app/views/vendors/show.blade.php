@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <dl class="row">
                        <dt class="col-lg-3">
                            {{ trans('app.directory.vendors.name') }}
                        </dt>
                        <dd class="col-lg-9">
                            {{ $model->name }}
                        </dd>
                        <dt class="col-lg-3">
                            {{ trans('app.directory.vendors.address') }}
                        </dt>
                        <dd class="col-lg-9">
                            {{ $model->address }}
                        </dd>
                        <dt class="col-lg-3">
                            {{ trans('app.directory.vendors.council') }}
                        </dt>
                        <dd class="col-lg-9">
                            <?php
                            $council_id = json_decode($model->company_id);
                            $company = Company::whereIn('id', $council_id)->orderBy('name', 'asc')->get();
                            foreach ($company as $cob) {
                                $council[] = $cob->name;
                            }
                            ?>
                            {{ implode('<br/>', $council) }}
                        </dd>
                        <dt class="col-lg-3">
                            {{ trans('app.directory.vendors.rating') }}
                        </dt>
                        <dd class="col-lg-9">
                            @if ($model->rating)
                            @for ($x = 1; $x <= $model->rating; $x++)
                            <span class="fa fa-star star-checked"></span>
                            @endfor
                            @endif
                        </dd>
                        <dt class="col-lg-3">
                            {{ trans('app.directory.vendors.remarks') }}
                        </dt>
                        <dd class="col-lg-9">
                            {{ $model->remarks }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </section>
    <!-- End -->
</div>
@endsection