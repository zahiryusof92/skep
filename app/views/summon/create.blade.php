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

                    @include('alert.bootbox')

                    <form class="form-horizontal" method="POST" action="{{ route('summon.store') }}" enctype="multipart/form-data" novalidate="">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label style="color: red; font-style: italic;">* {{ trans('app.forms.mandatory_fields') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label">{{ trans('app.forms.category') }}</label>
                                    <input type="hidden" class="form-control" id="category" name="category" value="{{ $category->id }}"/>
                                    <input type="text" class="form-control" value="{{ $category->description }}" readonly=""/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('unit_no') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.unit_no') }}</label>
                                    <select class="form-control select2" id="unit_no" name="unit_no">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @if ($unit_no)
                                        @foreach ($unit_no as $value => $name)
                                        <option value="{{ $value }}" {{ (Input::old('unit_no') && Input::old('unit_no') == $value) ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @include('alert.feedback', ['field' => 'unit_no'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('name') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.name') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.summon.name') }}" id="name" name="name" value="{{ Input::old('name') }}"/>
                                    @include('alert.feedback', ['field' => 'name'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('ic_no') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.ic_no') }}</label>
                                    <input type="text" class="form-control" placeholder="{{ trans('app.summon.ic_no') }}" id="ic_no" name="ic_no" value="{{ Input::old('ic_no') }}"/>
                                    @include('alert.feedback', ['field' => 'ic_no'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('phone_no') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.phone_no') }}</label>
                                    <input type="tel" class="form-control" placeholder="{{ trans('app.summon.phone_no') }}" id="phone_no" name="phone_no" value="{{ Input::old('phone_no') }}"/>
                                    @include('alert.feedback', ['field' => 'phone_no'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('email') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.email') }}</label>
                                    <input type="email" class="form-control" placeholder="{{ trans('app.summon.email') }}" id="email" name="email" value="{{ Input::old('email') }}"/>
                                    @include('alert.feedback', ['field' => 'email'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('address') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.address') }}</label>
                                    <textarea class="form-control" placeholder="{{ trans('app.summon.address') }}" id="address" name="address" rows="3">{{ Input::old('address') }}</textarea>
                                    @include('alert.feedback', ['field' => 'address'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('mailing_address') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.mailing_address') }}</label>
                                    <textarea class="form-control" placeholder="{{ trans('app.summon.mailing_address') }}" id="mailing_address" name="mailing_address" rows="3">{{ Input::old('mailing_address') }}</textarea>
                                    @include('alert.feedback', ['field' => 'mailing_address'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('duration_overdue') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.duration_overdue') }}</label>
                                    <select class="form-control select2" id="duration_overdue" name="duration_overdue">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @if ($durationOverdue)
                                        @foreach ($durationOverdue as $value => $name)
                                        <option value="{{ $value }}" {{ Input::old('duration_overdue') == $value ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @include('alert.feedback', ['field' => 'duration_overdue'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group {{ $errors->has('total_overdue') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.total_overdue') }}</label>
                                    <input type="currency" class="form-control" placeholder="0.00" id="total_overdue" name="total_overdue" value="{{ Input::old('total_overdue') }}">
                                    @include('alert.feedback', ['field' => 'total_overdue'])
                                </div>
                            </div>
                        </div>

                        @if ($type == Summon::LETTER_OF_DEMAND)
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ $errors->has('lawyer') ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.lawyer') }}</label>
                                    <select class="form-control select2" id="lawyer" name="lawyer">
                                        <option value="">{{ trans('app.forms.please_select') }}</option>
                                        @if ($lawyer)
                                        @foreach ($lawyer as $lawyers)
                                        <option value="{{ $lawyers->id }}" {{ (Input::old('lawyer') && Input::old('lawyer') == $lawyers->id) ? 'selected' : '' }}>{{ $lawyers->full_name }} ({{ $lawyers->username }})</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @include('alert.feedback', ['field' => 'lawyer'])
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ ($errors->has('attachment1')) ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.attachment1') }}</label>
                                    <input type="file" class="form-control-file" id="attachment1" name="attachment1">
                                    <small class="text-help muted">{{ trans('app.summon.attachment_help') }}</small><br/>
                                    @include('alert.feedback', ['field' => 'attachment1'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ ($errors->has('attachment2')) ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.attachment2') }}</label>
                                    <input type="file" class="form-control-file" id="attachment2" name="attachment2">
                                    <small class="text-help muted">{{ trans('app.summon.attachment_help') }}</small><br/>
                                    @include('alert.feedback', ['field' => 'attachment2'])
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group {{ ($errors->has('attachment3')) ? 'has-danger' : '' }}">
                                    <label class="form-control-label"><span style="color: red;">*</span> {{ trans('app.summon.attachment3') }}</label>
                                    <input type="file" class="form-control-file" id="attachment3" name="attachment3">
                                    <small class="text-help muted">{{ trans('app.summon.attachment_help') }}</small><br/>
                                    @include('alert.feedback', ['field' => 'attachment3'])
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <input type="hidden" id="buyer" name="buyer" value="{{ Input::old('buyer') }}"/>
                            <input type="hidden" id="type" name="type" value="{{ $type }}"/>
                            <button type="submit" class="btn btn-own" id="submit_button">{{ trans('app.forms.submit') }}</button>
                            <button type="button" class="btn btn-default" id="cancel_button" onclick="window.location = '{{ route('summon.index') }}'">{{ trans('app.forms.cancel') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- End -->
</div>

<script>
    $(document).on("change", "#unit_no", function () {
        var id = $(this).val();

        $.ajax({
            type: "POST",
            url: "{{ url('summon/purchaser') }}",
            data: {
                "id": id
            },
            dataType: "json",
            success: function (result) {
//                console.log(result);
                $("#buyer").val(result.id);
                $("#name").val(result.name);
                $("#ic_no").val(result.ic_no);
                $("#phone_no").val(result.phone_no);
                $("#email").val(result.email);
                $("#address").val(result.address);
                $("#mailing_address").val(result.mailing_address);
            }
        });
    });

    // Jquery Dependency
    $("input[type='currency']").on({
        keyup: function () {
            formatCurrency($(this));
        },
        blur: function () {
            formatCurrency($(this), "blur");
        }
    });

    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "");
    }

    function formatCurrency(input, blur) {
        // appends $ to value, validates decimal side
        // and puts cursor back in right position.

        // get input value
        var input_val = input.val();

        // don't validate empty input
        if (input_val === "") {
            return;
        }

        // original length
        var original_len = input_val.length;

        // initial caret position 
        var caret_pos = input.prop("selectionStart");

        // check for decimal
        if (input_val.indexOf(".") >= 0) {

            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(".");

            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            // add commas to left side of number
            left_side = formatNumber(left_side);

            // validate right side
            right_side = formatNumber(right_side);

            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                right_side += "00";
            }

            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);

            // join number by .
            input_val = left_side + "." + right_side;

        } else {
            // no decimal entered
            // add commas to number
            // remove all non-digits
            input_val = formatNumber(input_val);

            // final formatting
            if (blur === "blur") {
                input_val += ".00";
            }
        }

        // send updated string to input
        input.val(input_val);

        // put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }
</script>
@endsection