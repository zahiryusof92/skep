<div class="row padding-vertical-10">    
    <div class="col-lg-12">
        <h6><u>{{ trans("app.forms.before_change") }}</u></h6>
        <fieldset disabled>
            <div class="form-group row">
                <div class="col-md-6">
                    <label>{{ trans("app.forms.date") }}</label>
                    <input name="lbl-date" class="form-control form-control-sm" type="text" value="{{ ($checkOldData->date) ? date('d/m/Y', strtotime($checkOldData->date)) : '' }}">
                </div>
                <div class="col-md-6">
                    <label>{{ trans('app.forms.name') }}</label>
                    <input name="lbl-name" class="form-control form-control-sm" type="text" value="{{ $checkOldData->name }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label>{{ trans("app.forms.position") }}</label>
                    <input name="lbl-position" class="form-control form-control-sm" type="text" value="{{ $checkOldData->position }}">
                </div>
                <div class="col-md-6">
                    <label>{{ trans('app.forms.admin_status') }}</label>
                    <select name="lbl-status" class="form-control form-control-sm">
                        <option value="">{{ trans('app.forms.please_select') }}</option>
                        @foreach($adminStatus as $key => $status) 
                            <option value="{{ $key }}" {{ $checkOldData->is_active == $key ? "selected" : "" }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <label>{{ trans('app.forms.remarks') }}</label>
                    <textarea name="lbl-remarks" id="remarks" rows="5" class="form-control form-control-sm" >{{ $checkOldData->remarks }}</textarea>
                </div>
            </div>
        </fieldset>
    </div>
</div>
<hr>

