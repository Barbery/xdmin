<div class="form-actions">
    <div class="col-sm-offset-4">
        <button id="save-btn" type="button" class="btn btn-sm btn-success">
            <i class="ace-icon fa fa-floppy-o icon-on-right bigger-110"></i>
            保存
        </button>
        @if (empty($hideCancel))
            <a class="btn btn-sm btn-warning" href="{{ route($cancelUri ?? "{$route}.index") }}">
                <i class="ace-icon fa fa-times icon-on-right bigger-110"></i>
                取消
            </a>
        @endif
    </div>
</div>
