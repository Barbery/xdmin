@section('componentsStyle')
    @parent

    <style>
        .upload-area {
            width: 200px;
        }

        .upload-area .thumbmail {
            width: 100%;
        }

        .upload-area .status-bar {
            text-align: center;
            color: white;
        }

        .upload-image-section .btn-group {
            margin-top: 5px;
        }
    </style>
@endsection

<div id="{{ $name }}" class="upload-image-section">
    <div class="upload-area">
        <img class="thumbmail"
             data-src="{{ asset(config('admin.default_image')) }}"
             src="{{ $value ? cdn($value) : asset(config('admin.default_image')) }}" />
        <input type="file" id="upload_image_{{ $name }}" name="upload_image_{{ $name }}" class="upload-input hide">
        <div class="status-bar"></div>
    </div>
    <input type="hidden" name="{{ $name }}" value="{{ $value }}" />
    <div class="btn-group">
        <button id="choose-btn-{{ $name }}" class="choose-btn btn btn-primary btn-xs">选择图片</button>
        <button class="del-btn btn btn-danger btn-xs">删除图片</button>
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
                uploadJq.find('input:hidden[name=\''+ name +'\']').val('');
                uploadJq.find('.thumbmail').attr('src', '');
                uploadJq.find('.thumbmail').attr('src', uploadJq.find('.thumbmail').data('src'))
            });

            initUpload(name);

            function initUpload(name) {
                var statusBar = uploadJq.find('.status-bar');
                var domain = '{{ config('services.qi_niu.cdn_host') }}';
                var btnId = 'choose-btn-' + name;
                var uptoken = '{{ getQiniuToken() }}';
                initQiNiuUpload(btnId, uptoken, domain, name, {
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

                            uploadJq.find('input:hidden[name="' + name + '"]').val(res.key);
                            uploadJq.find('.thumbmail').attr('src', domain + '/' + res.key);

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
