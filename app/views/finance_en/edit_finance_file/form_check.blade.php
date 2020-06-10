<div class="row">
    <div class="col-lg-12">

        <h6>{{ trans("app.forms.check") }}</h6>

        <div class="form-group row">
            <div class="col-md-6">
                <label><span style="color: red;">*</span> {{ trans("app.forms.date") }}</label>
                <input id="date" class="form-control form-control-sm" type="text" placeholder="{{ trans("app.forms.date") }}" value="{{ ($checkdata->date) ? date('d/m/Y', strtotime($checkdata->date)) : '' }}">
                <input type="hidden" name="date" id="mirror_date" value="{{ $checkdata->date }}">
                <div id="date_err" style="display:none;"></div>
            </div>
            <div class="col-md-6">
                <label><span style="color: red;">*</span> {{ trans('app.forms.name') }}</label>
                <input name="name" id="name" class="form-control form-control-sm" type="text" placeholder="{{ trans('app.forms.name') }}" value="{{ $checkdata->name }}">
                <div id="name_err" style="display:none;"></div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <label><span style="color: red;">*</span> {{ trans("app.forms.position") }}</label>
                <input name="position" id="position" class="form-control form-control-sm" type="text" placeholder="{{ trans("app.forms.position") }}" value="{{ $checkdata->position }}">
                <div id="position_err" style="display:none;"></div>
            </div>
            <div class="col-md-6">
                <label><span style="color: red;">*</span> {{ trans('app.forms.admin_status') }}</label>
                <select name="is_active" id="is_active" class="form-control form-control-sm">
                    <option value="">{{ trans('app.forms.please_select') }}</option>
                    <option value="1" {{ ($checkdata->is_active == 1) ? 'selected' : '' }}>{{ trans('app.forms.active') }}</option>
                    <option value="0" {{ ($checkdata->is_active == 0) ? 'selected' : '' }}>{{ trans('app.forms.inactive') }}</option>
                </select>
                <div id="is_active_err" style="display:none;"></div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-12">
                <label>{{ trans('app.forms.remarks') }}</label>
                <textarea name="remarks" id="remarks" rows="5" class="form-control form-control-sm" placeholder="{{ trans('app.forms.remarks') }}">{{ $checkdata->remarks }}</textarea>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#date").datetimepicker({
            widgetPositioning: {
                horizontal: 'left'
            },
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            },
            format: 'DD/MM/YYYY'
        }).on('dp.change', function () {
            let currentDate = $(this).val().split('/');
            $("#mirror_date").val(`${currentDate[2]}-${currentDate[1]}-${currentDate[0]}`);
        });
    });
</script>
