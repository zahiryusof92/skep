@if (Session::has('success'))
<script>
    bootbox.alert("<span style='color:green;'>{{ Session::get('success') }}</span>");
</script>
@endif

@if (Session::has('error'))
<script>
    bootbox.alert("<span style='color:red;'>{{ Session::get('error') }}</span>");
</script>
@endif

@if (Session::has('delete'))
<script>
    swal({
        title: "{{ trans('app.successes.deleted_title') }}",
        text: "{{ trans('app.successes.deleted_text_file') }}",
        type: "success",
        confirmButtonClass: "btn-success",
        closeOnConfirm: false
    });
</script>
@endif