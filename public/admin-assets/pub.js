// alertify设置
initAlertifySetting();

// ajax 设置
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    statusCode: {401: function() {
        window.location.href = '/';
    }, 422: function(XMLHttpRequest) {
        var fieldErrors = XMLHttpRequest.responseJSON.data;
        for (var field in fieldErrors) {
            for (var k in fieldErrors[field]) {
                alertify.error(fieldErrors[field][k]);
                return false;
            }
        }
    }},
    error: function(XMLHttpRequest, textStatus, errorThrown) {
        if (XMLHttpRequest.status != 422) {
            alertify.error(XMLHttpRequest.responseJSON.msg);
        }
    }
});

function request(url, method, data, successCallback, errorCallback) {
    $.ajax({
        url: url,
        method: method,
        data: data,
        dataType: 'json',
        success: function (data) {
            if (successCallback) {
                successCallback(data);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            if (XMLHttpRequest.status != 422) {
                alertify.error(XMLHttpRequest.responseJSON.msg);
            }

            if (errorCallback) {
                errorCallback(XMLHttpRequest, textStatus, errorThrown);
            }
        }
    });
}

function initAlertifySetting() {
    alertify.defaults.glossary.ok = '确定';
    alertify.defaults.glossary.cancel = '取消';
    alertify.defaults.transition = 'zoom';
    alertify.defaults.closable = true;
    alertify.set('notifier', 'position', 'top-right');
    alertify.set('notifier', 'delay', 2);
}

function initQiNiuUpload(btnId, uptoken, domain, containerId, options) {
    var defaultOptions = {
        runtimes: 'html5,flash,html4',      // 上传模式，依次退化
        browse_button: btnId,         // 上传选择的点选按钮，必需
        // 在初始化时，uptoken，uptoken_url，uptoken_func三个参数中必须有一个被设置
        // 切如果提供了多个，其优先级为uptoken > uptoken_url > uptoken_func
        // 其中uptoken是直接提供上传凭证，uptoken_url是提供了获取上传凭证的地址，如果需要定制获取uptoken的过程则可以设置uptoken_func
        uptoken: uptoken, // uptoken是上传凭证，由其他程序生成
        // uptoken_url: '/uptoken',         // Ajax请求uptoken的Url，强烈建议设置（服务端提供）
        // uptoken_func: function(file){    // 在需要获取uptoken时，该方法会被调用
        //    // do something
        //    return uptoken;
        // },
        get_new_uptoken: false,             // 设置上传文件的时候是否每次都重新获取新的uptoken
        // downtoken_url: '/downtoken',
        // Ajax请求downToken的Url，私有空间时使用，JS-SDK将向该地址POST文件的key和domain，服务端返回的JSON必须包含url字段，url值为该文件的下载地址
        unique_names: true,
        save_key: false,
        domain: domain,     // bucket域名，下载资源时用到，必需
        container: containerId,             // 上传区域DOM ID，默认是browser_button的父元素
        max_file_size: '4mb',             // 最大文件体积限制
        flash_swf_url: '/public/admin-assets/plupload/Moxie.swf',  //引入flash，相对路径
        max_retries: 3,                     // 上传失败最大重试次数
        dragdrop: true,                     // 开启可拖曳上传
        drop_element: containerId,          // 拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
        chunk_size: '4mb',                  // 分块上传时，每块的体积
        auto_start: true,                   // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
        init: {
            'FilesAdded': function(up, files) {
                plupload.each(files, function(file) {

                });
            },
            'BeforeUpload': function(up, file) {
            },
            'UploadProgress': function(up, file) {
                // 每个文件上传时，处理相关的事情
            },
            'FileUploaded': function(up, file, info) {
            },
            'Error': function(up, err, errTip) {
            },
            'UploadComplete': function() {
                //队列文件处理完毕后，处理相关的事情
            },
            'Key': function(up, file) {
                // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                // 该配置必须要在unique_names: false，save_key: false时才生效
            }
        }
    };

    var options = $.extend(defaultOptions, options);
    Qiniu.uploader(options);
}

var Operation = {
    refreshTable: function () {
        $('#search-btn').trigger('click');
    },
    submit: function (btnJqObj, url, method, data, successCallback, errorCallback) {
        var btnHtml = btnJqObj.html();
        var btnLabel = btnJqObj.text();
        var loadingHtml = '<i class="ace-icon fa fa-spinner fa-spin blue bigger-125"></i>' + btnLabel;

        btnJqObj.html(loadingHtml);
        btnJqObj.prop('disabled', true);
        btnJqObj.addClass('disabled');
        $.ajax({
            url: url,
            method: method,
            data: data,
            dataType: 'json',
            success: function (data) {
                if (successCallback) {
                    successCallback(data);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (XMLHttpRequest.status != 422) {
                    alertify.error(XMLHttpRequest.responseJSON.msg);
                }
                btnJqObj.html(btnHtml);
                btnJqObj.prop('disabled', false);
                btnJqObj.removeClass('disabled');

                if (errorCallback) {
                    errorCallback(XMLHttpRequest, textStatus, errorThrown);
                }
            }
        });
    },
    confirm_submit: function (confirmMsg, btnJqObj, url, method, data, successCallback, errorCallback) {
        alertify.confirm('确认操作', confirmMsg, function() {
            Operation.submit(btnJqObj, url, method, data, successCallback, errorCallback);
        }, function(){});
    },
    refreshTable: function () {
        $('#search-btn').trigger('click');
    },
    setSessionCache: function (key, val) {
        if (window.sessionStorage) {
            sessionStorage[key] = val;
        }
    },
    getSessionCache: function (key) {
        if (sessionStorage[key] !== undefined) {
            return sessionStorage[key];
        }

        return null;
    }
}