@extends('layout.english_layout.default')

@section('content')
<div class="page-content-inner">
    <section class="panel panel-style">
        <div class="panel-heading">
            <h3>{{ $title }}</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h6>{{ trans('app.forms.file_no') }}: {{ $file->file_no }}</h6>
                    <div id="update_files_lists">

                        @include('page_en.nav.cob_file', ['files' => $file])

                        <div class="tab-content padding-vertical-20">
                            <div class="tab-pane active" id="" role="tabpanel">

                                <section class="panel panel-pad">

                                    <div class="row padding-vertical-20">
                                        <div class="col-lg-12">
                                            <h4>{{ trans('app.forms.fixed_deposit') }}</h4>

                                            <!-- Form Start -->
                                            <form>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label style="color: red; font-style: italic;">
                                                                * {{ trans('app.forms.mandatory_fields') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>
                                            <!-- Form End -->

                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="button" class="btn btn-own" id="submit_button">
                                            {{ trans('app.forms.submit') }}
                                        </button>
                                        @if ($file->is_active != 2)
                                        <button type="button" class="btn btn-default" id="cancel_button"
                                            onclick="window.location ='{{ URL::action('AdminController@fileList') }}'">
                                            {{ trans('app.forms.cancel') }}
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-default" id="cancel_button"
                                            onclick="window.location ='{{ URL::action('AdminController@fileListBeforeVP') }}'">
                                            {{ trans('app.forms.cancel') }}
                                        </button>
                                        @endif
                                    </div>

                                </section>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Page Scripts --> 
<script>
    var changes = false;
    
    $('input, textarea, select').on('keypress change input', function () {
        changes = true;
    });

    $(window).on('beforeunload', function () {
        if (changes) {
            return "{{ trans('app.confirmation.want_to_leave') }}";
        }
    });
</script>
<!-- End Page Scripts-->
@endsection