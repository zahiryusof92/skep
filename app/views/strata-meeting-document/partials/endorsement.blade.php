<div class="form-group row">
    <div class="col-md-6">
        <label class="form-control-label">{{ trans('app.forms.status') }}</label>
    </div>
    <div class="col-md-6">
        <select name="status" class="form-control select2">
            <?php $st = $documentStatus ? $documentStatus->status : 'pending'; ?>
            <option value="pending" {{ $st === 'pending' ? 'selected' : '' }}>{{ trans('app.forms.pending') }}</option>
            <option value="accepted" {{ in_array($st, array('accepted', 'approved')) ? 'selected' : '' }}>{{ trans('app.forms.accepted') }}</option>
            <option value="rejected" {{ $st === 'rejected' ? 'selected' : '' }}>{{ trans('app.forms.rejected') }}</option>
        </select>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-6">
        <label class="form-control-label">{{ trans('app.forms.reason') }}</label>
    </div>
    <div class="col-md-6">
        <textarea class="form-control" name="reason" rows="3">{{ $documentStatus ? $documentStatus->reason : '' }}</textarea>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-6">
        <label class="form-control-label">{{ trans('app.forms.endorsed_by') }}</label>
    </div>
    <div class="col-md-6">
        <input type="text" class="form-control" name="endorsed_by" value="{{ $documentStatus ? $documentStatus->endorsed_by : '' }}"/>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-6">
        <label class="form-control-label">{{ trans('app.forms.endorsed_email') }}</label>
    </div>
    <div class="col-md-6">
        <input type="email" class="form-control" name="endorsed_email" value="{{ $documentStatus ? $documentStatus->endorsed_email : '' }}"/>
    </div>
</div>
<input type="hidden" id="endorsement_letter_url" name="endorsement_letter_url" value="{{ $documentStatus && $documentStatus->attachment ? $documentStatus->attachment : '' }}"/>
