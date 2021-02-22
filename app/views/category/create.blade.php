@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-with-borders">
        <div class="panel-heading">
            <h3>{{$title}}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">

                    @include('alert.bootbox')

                    <form class="form-horizontal" method="POST" action="{{ route('category.store') }}">

                        <div class="row">
                            <div class="col-lg-12">
                                <label class="form-control-label"><span style="color: red;">* {{ trans('app.forms.mandatory_fields') }}</span></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('description') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.forms.category') }}</label>
                                    <input type="text" class="form-control" id="description" name="description" placeholder="{{ trans('app.forms.category') }}" value="{{ Input::old('description') }}">
                                    @include('alert.feedback', ['field' => 'description'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('is_active') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.forms.status') }}</label>
                                    <select id="is_active" name="is_active" class="form-control">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        <option value="1" {{ Input::old('is_active') == '1' ? 'selected' : '' }}>{{ trans('app.forms.active') }}</option>
                                        <option value="0" {{ Input::old('is_active') == '0' ? 'selected' : '' }}>{{ trans('app.forms.inactive') }}</option>
                                    </select>
                                    @include('alert.feedback', ['field' => 'is_active'])
                                </div>
                            </div>
                        </div>

                        @if (Auth::user()->isSuperadmin())
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-info table-bordered table-sm" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 80%">{{ trans('app.summon.category.cob') }}</th>
                                            <th class="text-center" style="width: 20%">{{ trans('app.summon.category.amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($cob)
                                        <?php $count = 0; ?>
                                        @foreach ($cob as $council)                                        
                                        <tr>
                                            <td class="text-left">
                                                <input type="hidden" name="company[]" value="{{ $council->id }}"/>{{ $council->name }}                                                
                                            </td>
                                            <td>
                                                <div class="margin-bottom-0 form-group {{ $errors->has('amount.' . $count) ? 'has-danger' : '' }}">
                                                    <input type="text" class="form-control form-control-sm text-right number-only" name="amount[]" value="{{ (Input::old('amount.' . $count) ? Input::old('amount.' . $count) : '') }}"/>
                                                    @include('alert.feedback', ['field' => 'amount.' . $count])
                                                </div>
                                            </td>
                                        </tr>
                                        <?php $count++; ?>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        <div class="form-actions">
                            @if (AccessGroup::hasInsert(12))
                            <button type="submit" class="btn btn-primary" id="submit_button">{{ trans('app.forms.save') }}</button>
                            @endif
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location ='{{ route('category.index') }}'">{{ trans('app.forms.cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- End -->
</div>

<script>
    $('.number-only').keyup(function () {
        this.value = this.value.replace(/[^0-9\.]/g, '');
    });
</script>
@endsection