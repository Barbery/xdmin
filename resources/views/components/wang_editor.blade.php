@section('componentsLink')
    @parent

    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/wang_editor/css/wangEditor.min.css') }}">
@endsection

<textarea id="{{ $name }}" name="{{ $name }}">{!! isset($value) ? $value : '' !!}</textarea>

@section('componentsScript')
    @parent

    <script src="{{ asset('admin-assets/wang_editor/js/wangEditor.js') }}"></script>
    @if(isset($qiniu_upload))
        <script src="{{ asset('admin-assets/plupload/moxie.min.js') }}"></script>
        <script src="{{ asset('admin-assets/plupload/plupload.min.js') }}"></script>
        <script src="{{ asset('admin-assets/qiniu/qiniu.min.js') }}"></script>
    @endif
@endsection

@section('componentsJs')
    @parent

    <script>
        $(function () {
            var name = '{{ $name }}';
            // 生成编辑器
            var editor = new wangEditor(name);
            @if(isset($qiniu_upload))
                editor.config.customUpload = true;  // 设置自定义上传的开关
                editor.config.customUploadInit = initQiNiu;  // 配置自定义上传初始化事件，uploadInit方法在上面定义了
            @endif
            editor.create();

            // 初始化七牛上传
            function initQiNiu() {
                // this 即 editor 对象
                var editor = this;
                // 触发选择文件的按钮的id
                var btnId = editor.customUploadBtnId;
                // 触发选择文件的按钮的父容器的id
                var containerId = editor.customUploadContainerId;
                var domain = '{{ config('admin.upload.qi_niu.url') }}';

                @inject('uploadService', 'Modules\Admin\Services\UploadService')
                var uptoken = '{{ $uploadService->getQiNiuUploadToken() }}';
                initQiNiuUpload(btnId, uptoken, domain, containerId, {
                    init: {
                        'FilesAdded': function (up, files) {
                            plupload.each(files, function (file) {
                            });
                        },
                        'BeforeUpload': function (up, file) {
                        },
                        'UploadProgress': function (up, file) {
                            // 显示进度条
                            editor.showUploadProgress(file.percent);
                        },
                        'FileUploaded': function (up, file, info) {
                            var domain = up.getOption('domain');
                            var res = $.parseJSON(info);
                            var sourceLink = domain + res.key; //获取上传成功后的文件的Url

                            // 插入图片到editor
                            editor.command(null, 'insertHtml', '<img src="' + sourceLink + '" style="max-width:100%;"/>')
                        },
                        'Error': function (up, err, errTip) {
                        },
                        'UploadComplete': function () {
                            // 隐藏进度条
                            editor.hideUploadProgress();
                        }

                    }
                });
            }
        });
    </script>
@endsection