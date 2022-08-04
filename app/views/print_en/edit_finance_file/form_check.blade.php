<div class="row">    
    <div class="col-lg-12">
        <h6>{{ strtoupper(trans("app.forms.check")) }}</h6>
        <table class="table table-sm table-bordered" style="width: 100%">
            <tbody>
                <tr>
                    <th style="width: 20%">{{ trans("app.forms.date") }}</th>
                    <td style="width: 80%">{{ ($checkdata->date) ? date('d/m/Y', strtotime($checkdata->date)) : '' }}</td>
                </tr>
                <tr>
                    <th style="width: 20%">{{ trans("app.forms.name") }}</th>
                    <td style="width: 80%">{{ ($checkdata->name ? $checkdata->name : '') }}</td>
                </tr>
                <tr>
                    <th style="width: 20%">{{ trans("app.forms.position") }}</th>
                    <td style="width: 80%">{{ ($checkdata->position ? $checkdata->position : '') }}</td>
                </tr>
                <tr>
                    <th style="width: 20%">{{ trans("app.forms.status") }}</th>
                    <td style="width: 80%">{{ ($checkdata->is_active == 1 ? trans('app.forms.approved') : trans('app.forms.rejected')) }}</td>
                </tr>
                <tr>
                    <th style="width: 20%">{{ trans("app.forms.remarks") }}</th>
                    <td style="width: 80%">{{ ($checkdata->remarks ? $checkdata->remarks : '') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>