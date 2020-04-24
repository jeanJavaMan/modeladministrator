<div class="modal fade show" id="{{$id ?? 'modal-primary'}}" style="{{$style ?? 'display: none; padding-right: 17px;'}}" aria-modal="true">
    <div class="modal-dialog {{$modalDialogClass ?? ''}}">
        <div class="modal-content {{$class ?? "bg-primary"}}">
            <div class="modal-header">
                <h4 class="modal-title">{{$title ?? ""}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                {{$slot}}
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">{{__("modeladminlang::default.button_close")}}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
