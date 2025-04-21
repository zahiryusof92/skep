@if(!empty($model))
    @foreach (unserialize($model['description']) as $key => $val)
        @if(!str_contains($key, '_url'))
        <div class="form-group row">
            <div class="col-md-6">
                <label class="form-control-label">{{$questions[$key]['label']}}</label>
            </div>
            <div class="col-md-2">
                <input type="radio" id="{{$key}}" name="{{$key}}" value="1" {{ $val ? "checked" : "" }}> {{ trans("app.forms.yes") }}
            </div>
            <div class="col-md-2">
                <input type="radio" id="{{$key}}" name="{{$key}}" value="0" {{ !$val ? "checked" : "" }}> {{ trans("app.forms.no") }}
            </div>
        </div>
        @else
        <?php 
            $key_name = str_replace("_url", "", $key);
        ?>
        <div class="form-group row">
            <div class="col-md-6">
                <label class="form-control-label">&nbsp;</label>
            </div>
            <div class="col-md-6">
                @if ($val != "")
                <div id="{{ $key_name }}_download">
                    <a href="{{ asset($val) }}" target="_blank">
                        <button type="button" class="btn btn-xs btn-own" data-toggle="tooltip" data-placement="bottom" title="Download File">
                            <i class="icmn-file-download2"></i> {{ trans('app.forms.download') }}
                        </button>
                    </a>
                    &nbsp;
                    <button type="button" id="clear_{{ $key_name }}" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete File" onclick="clearFile(this)">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                @else
                <div>
                    <input type="file" name="{{ $key_name }}" id="{{ $key_name }}" onChange="onUpload(this)">
                </div>
                @endif
                @include('alert.feedback-ajax', ['field' => $key_name . "_url"])
            </div>
        </div>
        <input hidden id="{{ $key_name }}_url" name="{{ $key_name }}_url" value="{{$val}}">
        @endif
    @endforeach
@else
    @if(count($questions) > 0)
        @foreach ($questions as $key => $question)
            <div class="form-group row">
                <div class="col-md-6">
                    <label class="form-control-label">{{$question['label']}}</label>
                </div>
                <div class="col-md-2">
                    <input type="radio" id="{{$question['field'][0]}}" name="{{$question['field'][0]}}" value="1"> {{ trans("app.forms.yes") }}
                </div>
                <div class="col-md-2">
                    <input type="radio" id="{{$question['field'][0]}}" name="{{$question['field'][0]}}" value="0" checked> {{ trans("app.forms.no") }}
                </div>
            </div>
            <form id="upload_{{$question['field'][1]}}" enctype="multipart/form-data" method="post" action="{{ route('agm-minute.fileUpload') }}" autocomplete="off">
                <div class="form-group row">
                    <div class="col-md-6">
                        <label class="form-control-label">&nbsp;</label>
                    </div>
                    <div class="col-md-6">
                        <input type="file" name="{{$question['field'][1]}}" id="{{$question['field'][1]}}" onChange="onUpload(this)">
                        @include('alert.feedback-ajax', ['field' => $question['field'][1] . "_url"])
                    </div>
                </div>
            </form>
            <input hidden id="{{$question['field'][1]}}_url" name="{{$question['field'][1]}}_url">
        @endforeach
    @endif
@endif
<script>
    function onUpload(e) {
        let id = e.getAttribute('id');
        let data = new FormData();
        if(e.files.length > 0) {
            data.append(id, e.files[0]);
        }
        data.append("type", $("#type").val());
        data.append("agm_type", $("#agm_type").val());
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "{{ route('agm-minute.fileUpload') }}",
            data: data,
            async: true,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            beforeSubmit: function() {
                $("#"+ id + "_url_error").hide().empty();
                return true;
            },
            success: function(response) {
                if (response.success == false) {
                    var arr = response.errors;
                    $.each(arr, function(index, value) {
                        if (value.length != 0) {
                            $("#"+ id + "_url_error").html(value);
                        }
                    });
                    $("#"+ id + "_url_error").show();
                    $("#"+ id).css("color", "red");
                } else {
                    $("#" + id + "_url_error").html("<button id='clear_" + id + "' class='btn btn-xs btn-danger' onclick='clearFile(this)'><i class='fa fa-times'></i> </button>&nbsp;&nbsp;<i class='fa fa-check' style='color:green;'></i>");
                    $("#clear_" + id).show();
                    $("#" + id + "_url_error").show();
                    $("#" + id).css("color", "green");
                    $("#" + id + "_url").val(response.file);
                }
            }
        });
    }

    function clearFile(e) {
        let id = e.getAttribute('id');
        id = id.replace("clear_", "");
        $("#" + id).val("");
        $("#" + id + "_url").val("");
        $("#" + id).css("color", "");
        $("#" + id + "_url_error").empty().hide();
        $("#" + id + "_download").html('<input type="file" name="' + id + '" id="' + id + '" onChange="onUpload(this)">');
    }
</script>