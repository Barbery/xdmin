<div id="{{ $id }}" class="upload-section">
    <div class="upload-area">
        <img class="thumbmail"
             data-src="{{ asset(config('admin.upload.default_image')) }}"
             src="{{ $value ? config('admin.upload.qi_niu.url') . $value : asset(config('admin.upload.default_image')) }}" />
        <input type="file" name="upload_image" class="upload-input hide">
        <div class="status-bar"></div>
    </div>
    <input class="upload-image-path" type="hidden" name="{{ $name }}" value="{{ $value or '' }}" />
    <div class="btn-group">
        <button id="choose-btn{{ $id }}" class="choose-btn btn btn-primary btn-xs">选择图片</button>
        <button class="del-btn btn btn-danger btn-xs"
                onclick="$('#{{ $id }} .status-bar').hide();
                         $('#{{ $id }} .upload-image-path').val('');
                         $('#{{ $id }} .thumbmail').attr('src', '');
                         $('#{{ $id }} .thumbmail').attr('src', $('#{{ $id }} .thumbmail').data('src'))">删除</button>
    </div>
</div>
@inject('uploadPresenter', 'Modules\Admin\Presenters\UploadPresenter')
<script src="{{ asset('admin-assets/plupload/moxie.min.js') }}"></script>
<script src="{{ asset('admin-assets/plupload/plupload.min.js') }}"></script>
<script src="{{ asset('admin-assets/qiniu/qiniu.min.js') }}"></script>
<script src="{{ asset('admin-assets/spark-md5.min.js') }}"></script>
<script>
    $(function() {
        var statusBar = $('#{{ $id }} .status-bar');
        var downloadDomain = '{{ config('admin.upload.qi_niu.url') }}';
        var uploadInput = $('#{{ $id }} .upload-input');

        var uploader = Qiniu.uploader({
            runtimes: 'html5,flash,html4',      // 上传模式，依次退化
            browse_button: 'choose-btn{{ $id }}',         // 上传选择的点选按钮，必需
            // 在初始化时，uptoken，uptoken_url，uptoken_func三个参数中必须有一个被设置
            // 切如果提供了多个，其优先级为uptoken > uptoken_url > uptoken_func
            // 其中uptoken是直接提供上传凭证，uptoken_url是提供了获取上传凭证的地址，如果需要定制获取uptoken的过程则可以设置uptoken_func
            uptoken : '{{ $uploadPresenter->getQiNiuUploadToken() }}', // uptoken是上传凭证，由其他程序生成
            // uptoken_url: '/uptoken',         // Ajax请求uptoken的Url，强烈建议设置（服务端提供）
            // uptoken_func: function(file){    // 在需要获取uptoken时，该方法会被调用
            //    // do something
            //    return uptoken;
            // },
            get_new_uptoken: false,             // 设置上传文件的时候是否每次都重新获取新的uptoken
            // downtoken_url: '/downtoken',
            // Ajax请求downToken的Url，私有空间时使用，JS-SDK将向该地址POST文件的key和domain，服务端返回的JSON必须包含url字段，url值为该文件的下载地址
            unique_names: true,              // 默认false，key为文件名。若开启该选项，JS-SDK会为每个文件自动生成key（文件名）
            save_key: false,                  // 默认false。若在服务端生成uptoken的上传策略中指定了sava_key，则开启，SDK在前端将不对key进行任何处理
            domain: downloadDomain,     // bucket域名，下载资源时用到，必需
            container: '{{ $id }}',             // 上传区域DOM ID，默认是browser_button的父元素
            max_file_size: '4mb',             // 最大文件体积限制
            flash_swf_url: '/public/admin-assets/plupload/Moxie.swf',  //引入flash，相对路径
            max_retries: 3,                     // 上传失败最大重试次数
            dragdrop: true,                     // 开启可拖曳上传
            drop_element: '{{ $id }}',          // 拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
            chunk_size: '4mb',                  // 分块上传时，每块的体积
            auto_start: true,                   // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
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

                    $('#{{ $id }} .upload-image-path').val(res.key);
                    $('#{{ $id }} .thumbmail').attr('src', domain + res.key);

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
                },
                'Key': function(up, file) {
                    // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                    // 该配置必须要在unique_names: false，save_key: false时才生效

                    var spark = new SparkMD5.ArrayBuffer();
                    var key = "images/" + spark.append(file).end();
                    // do something with key here
                    return key
                }
            }
        });
    });
</script>