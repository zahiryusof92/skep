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
                    <form class="form-horizontal" method="POST" action="{{ route('vendors.store') }}">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('name') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.directory.vendors.name') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.directory.vendors.name') }}" id="name" name="name" value="{{ Input::old('name') }}"/>
                                    @if($errors->has('name'))
                                    <span class="help-block text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group {{ $errors->has('address') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.directory.vendors.address') }}</label>
                                    <textarea id="address" name="address" rows="4" class="form-control" placeholder="{{ trans('app.directory.vendors.address') }}">{{ Input::old('address') }}</textarea>
                                    @if($errors->has('address'))
                                    <span class="help-block text-danger">{{ $errors->first('address') }}</span>
                                    @endif
                                </div>
                            </div>                            
                        </div>

                        @if (Auth::user()->getAdmin())
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group {{ $errors->has('council') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.directory.vendors.council') }}</label>
                                    <small class="text-muted">{{ trans('app.directory.vendors.council_help') }}</small>
                                    <select class="form-control select2" id="council" name="council[]" multiple="">                               
                                        @foreach ($council as $id => $value)                                        
                                        <option value="{{ $id }}" {{ (is_array(Input::old('council')) && in_array($id, Input::old('council'))) ? ' selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('council'))
                                    <span class="help-block text-danger">{{ $errors->first('council') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @else
                        <input type="hidden" name="council[]" value="{{ Auth::user()->company_id }}"/>
                        @endif

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group {{ $errors->has('rating') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.directory.vendors.rating') }}</label>
                                    <small class="text-muted">{{ trans('app.directory.vendors.rating_help') }}</small>
                                    <input type="number" class="form-control" placeholder="{{ trans('app.directory.vendors.rating') }}" id="rating" name="rating" value="{{ Input::old('rating') }}"/>
                                    @if($errors->has('rating'))
                                    <span class="help-block text-danger">{{ $errors->first('rating') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group {{ $errors->has('remarks') ? 'has-danger' : '' }}">
                                    <label class="form-control-label">{{ trans('app.directory.vendors.remarks') }}</label>
                                    <textarea id="remarks" name="remarks" rows="5" class="form-control" placeholder="{{ trans('app.directory.vendors.remarks') }}">{{ Input::old('remarks') }}</textarea>
                                    @if($errors->has('remarks'))
                                    <span class="help-block text-danger">{{ $errors->first('remarks') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if (AccessGroup::hasInsert(58))
                        <div class="form-actions">
                            <button type="submit" class="btn btn-own" id="submit_button">{{ trans('app.forms.submit') }}</button>
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location = '{{ route('vendors.index') }}'">{{ trans('app.forms.cancel') }}</button>
                        </div>
                        @endif
                        
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- End -->
</div>
@endsection