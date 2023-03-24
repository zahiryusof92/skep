@extends('layout.english_layout.print')

@section('content')

    <?php
    $company = Company::find(Auth::user()->company_id);
    ?>

    <table width="100%">
        <tr>
            <td>
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
            </td>
        </tr>

        <tr>
            <td>
                <hr />
            </td>
        </tr>

        <tr>
            <td>
                <table class="table table-sm table-bordered" style="width: 100%">
                    <tbody>
                        <tr>
                            <th style="width: 20%">{{ trans('app.forms.file_no') }}</th>
                            <td style="width: 80%">{{ $files->file_no }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('app.forms.strata') }}</th>
                            <td>{{ $files->strata->strataName() }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <table class="table table-sm table-bordered" style="width: 100%">
                    <thead>
                        <th style="width:30%;">{{ trans('app.forms.title') }}</th>
                        <th style="width:40%;">{{ trans('app.forms.assigned_to') }}</th>
                        <th style="width:30%;">{{ trans('app.forms.remarks') }}</th>
                    </thead>
                    <tbody>
                        @if ($files->file_movements->count() > 0)
                            @foreach ($files->file_movements as $model)
                                <tr>
                                    <td>
                                        {{ $model->title }}
                                    </td>
                                    <td>
                                        @if (!empty($model->assigned_to))
                                            <div class="row">
                                                <ul>
                                                    @foreach (unserialize($model->assigned_to) as $assigned)
                                                        <?php $user = User::find($assigned['value']); ?>
                                                        @if ($user)
                                                            <li>
                                                                {{ $user->full_name }} ({{ $assigned['created_at'] }})
                                                            </li>
                                                        @else
                                                            <li>
                                                                -
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $model->remarks }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">
                                    {{ trans('No data available') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
