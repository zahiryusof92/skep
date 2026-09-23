@if (!empty($grouped))
    @if (!empty($grouped['before_mandatory']))
        <hr/>
        <h4 class="margin-bottom-20">{{ trans('app.strata_meeting_documents.before_meeting') }} — {{ trans('app.strata_meeting_documents.mandatory') }}</h4>
        @include('strata-meeting-document.partials.document-fields', array('documents' => $grouped['before_mandatory'], 'model' => $model))
    @endif
    @if (!empty($grouped['before_additional']))
        <hr/>
        <h4 class="margin-bottom-20">{{ trans('app.strata_meeting_documents.before_meeting') }} — {{ trans('app.strata_meeting_documents.additional') }}</h4>
        @include('strata-meeting-document.partials.document-fields', array('documents' => $grouped['before_additional'], 'model' => $model))
    @endif
    @if (!empty($grouped['after_mandatory']))
        <hr/>
        <h4 class="margin-bottom-20">{{ trans('app.strata_meeting_documents.after_meeting') }} — {{ trans('app.strata_meeting_documents.mandatory') }}</h4>
        @include('strata-meeting-document.partials.document-fields', array('documents' => $grouped['after_mandatory'], 'model' => $model))
    @endif
    @if (!empty($grouped['after_additional']))
        <hr/>
        <h4 class="margin-bottom-20">{{ trans('app.strata_meeting_documents.after_meeting') }} — {{ trans('app.strata_meeting_documents.additional') }}</h4>
        @include('strata-meeting-document.partials.document-fields', array('documents' => $grouped['after_additional'], 'model' => $model))
    @endif
@endif

<script>
    function onStrataUpload(el) {
        var id = el.getAttribute('id');
        var data = new FormData();
        if (el.files.length > 0) {
            data.append(id, el.files[0]);
        }
        data.append('type', $('#type').val());
        data.append('agm_type', $('#agm_type').val());
        $.blockUI({ message: '{{ trans("app.confirmation.please_wait") }}' });
        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            url: "{{ route('strata-meeting-document.fileUpload') }}",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                if (response.success) {
                    $('#' + id + '_url').val(response.file);
                } else {
                    bootbox.alert(response.message || 'Upload failed');
                    $(el).val('');
                }
            },
            error: function () {
                bootbox.alert("<span style='color:red;'>{{ trans('app.errors.occurred') }}</span>");
                $(el).val('');
            },
            complete: function () {
                $.unblockUI();
            }
        });
    }

    function clearStrataFile(slug) {
        $('#' + slug + '_url').val('');
        $('#' + slug).val('');
        $('#' + slug + '_download').remove();
    }

    function toggleStrataDocUpload(slug, show) {
        var $wrap = $('#upload_wrap_' + slug);
        if (show) {
            $wrap.slideDown(150);
        } else {
            $wrap.slideUp(150);
            clearStrataFile(slug);
        }
    }

    $(document).off('change.strataDoc', '.strata-doc-yesno').on('change.strataDoc', '.strata-doc-yesno', function () {
        toggleStrataDocUpload($(this).data('slug'), $(this).val() === '1');
    });
</script>
