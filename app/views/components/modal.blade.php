
<div class="modal fade modal-size-medium" id="{{ $modal_id }}" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">@yield('modal_title')</h4>
            </div>
            <form id="{{ $modal_form_id }}" class="form-horizontal" method="POST" action="">
                <div class="modal-body">
                    @yield('modal_content')
                </div>
                <div class="modal-footer">
                    @yield('modal_extra_attributes')
                    <button type="button" id="modal_cancel_button" class="btn" data-dismiss="modal">
                        {{ trans('app.forms.close') }}
                    </button>
                    @if($show_submit)
                    <button type="submit" id="modal_submit_button" class="btn btn-own">
                        @yield('modal_submit_text')
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@yield('modal_script')