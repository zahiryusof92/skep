<div class="form-group row">
    <div class="col-md-6">
        <label class="form-control-label">{{ trans('app.forms.status') }}</label>
    </div>
    <div class="col-md-6">
        <input type="text" class="form-control" readonly value="{{ $documentStatus ? trans('app.forms.' . ($documentStatus->status === 'approved' ? 'accepted' : $documentStatus->status)) : trans('app.forms.pending') }}"/>
    </div>
</div>
