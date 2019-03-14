@section('componentsStyle')
    @parent

    <style>
        .upload-file-section .file-view {
            display: inline-block;
            margin-left: 10px;
            margin-top: 5px;
        }
    </style>
@endsection

<div id="{{ $name }}" class="upload-file-section">
    <div class="upload-area" style="border: 0px;">
        <input type="file" name="upload_file_{{ $name }}" class="upload-input hide">
        <div class="status-bar"></div>
    </div>
    <input class="upload-file-path" type="hidden" name="{{ $name }}" value="{{ $value or '' }}" />
    <div class="btn-group">
        <button id="choose-btn-{{ $name }}" class="choose-btn btn btn-primary btn-xs">选择文件</button>
        <button class="del-btn btn btn-danger btn-xs">删除文件</button>
        <h4 class="file-view">
            @if($value)
                <a target="target" href="{{ config('admin.upload.qi_niu.url') . $value }}">查看文件</a>
            @else
                请上传文件
            @endif
        </h4>
    </div>
</div>

@section('componentsScript')
    @parent

    <script src="{{ asset('admin-assets/plupload/moxie.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plupload/plupload.min.js') }}"></script>
    <script src="{{ asset('admin-assets/qiniu/qiniu.min.js') }}"></script>
@endsection

@section('componentsJs')
    @parent

    <script>
        $(function () {
            var name = '{{ $name }}';
            var uploadJq = $('#' + name);
            uploadJq.find('.del-btn').click(function () {
                uploadJq.find('.status-bar').hide();
                uploadJq.find('.upload-file-path').val('');
                uploadJq.find('.file-view').html('请上传文件');
            });

            initUpload(name);

            function initUpload(name) {
                var statusBar = uploadJq.find('.status-bar');
                var domain = '{{ config('admin.cdn_url') }}';
                var btnId = 'choose-btn-' + name;
                var uptoken = '{{ getQiniuToken() }}';
                initQiNiuUpload(btnId, uptoken, domain, name, {
                    max_file_size: '20mb',
                    init: {
                        'FilesAdded': function(up, files) {
                            plupload.each(files, function(file) {

                            });
                        },
                        'BeforeUpload': function(up, file) {
                            statusBar.show();
                            statusBar.html('正在上传...');
                            statusBar.css('backgroundColor', 'grey');
                        },
                        'UploadProgress': function(up, file) {
                            // 每个文件上传时，处理相关的事情
                        },
                        'FileUploaded': function(up, file, info) {
                            var domain = up.getOption('domain');
                            var res = $.parseJSON(info);

                            uploadJq.find('.upload-file-path').val(res.key);
                            uploadJq.find('.file-view').html('<a target="target" href="' + domain + res.key + '">查看文件</a>');

                            statusBar.html('上传成功');
                            statusBar.css('backgroundColor', 'green');
                            setTimeout(function () {
                                statusBar.hide();
                            }, 1500);
                        },
                        'Error': function(up, err, errTip) {
                            statusBar.html(errTip);
                            statusBar.css('backgroundColor', 'red');
                        },
                        'UploadComplete': function() {
                            //队列文件处理完毕后，处理相关的事情
                        }
                    }
                });
            }
        })
    </script>
@endsection
