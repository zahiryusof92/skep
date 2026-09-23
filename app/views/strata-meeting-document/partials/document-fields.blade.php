@foreach ($documents as $doc)
    <?php
        $isField = $doc['is_field'];
        $urlField = $doc['url_field'];
        $isChecked = $model ? (int) $model->{$isField} : 0;
        $urlVal = $model ? $model->{$urlField} : '';
    ?>
    <div class="form-group row strata-doc-row" data-slug="{{ $doc['id'] }}">
        <div class="col-md-6">
            <label class="form-control-label">{{ $doc['label'] }}</label>
        </div>
        <div class="col-md-2">
            <label class="radio-inline">
                <input type="radio" class="strata-doc-yesno" name="{{ $isField }}" value="1" data-slug="{{ $doc['id'] }}" {{ $isChecked ? 'checked' : '' }}>
                {{ trans('app.forms.yes') }}
            </label>
        </div>
        <div class="col-md-2">
            <label class="radio-inline">
                <input type="radio" class="strata-doc-yesno" name="{{ $isField }}" value="0" data-slug="{{ $doc['id'] }}" {{ !$isChecked ? 'checked' : '' }}>
                {{ trans('app.forms.no') }}
            </label>
        </div>
    </div>
    <div class="form-group row strata-doc-upload" id="upload_wrap_{{ $doc['id'] }}" style="{{ $isChecked ? '' : 'display:none;' }}">
        <div class="col-md-6">
            <label class="form-control-label">&nbsp;</label>
        </div>
        <div class="col-md-6">
            @if (!empty($urlVal))
                <div id="{{ $doc['id'] }}_download" style="margin-bottom: 8px;">
                    <a href="{{ asset($urlVal) }}" target="_blank" class="btn btn-xs btn-own">
                        <i class="icmn-file-download2"></i> {{ trans('app.forms.download') }}
                    </a>
                    &nbsp;
                    <button type="button" class="btn btn-xs btn-danger" onclick="clearStrataFile('{{ $doc['id'] }}')">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            @endif
            <input type="file" name="{{ $doc['id'] }}" id="{{ $doc['id'] }}" onchange="onStrataUpload(this)">
            @include('alert.feedback-ajax', ['field' => $urlField])
            <input type="hidden" id="{{ $urlField }}" name="{{ $urlField }}" value="{{ $urlVal }}">
        </div>
    </div>
@endforeach
