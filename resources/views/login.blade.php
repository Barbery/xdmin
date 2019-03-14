@extends('layouts.admin-login')

@section('content')
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="login-container">
                <div class="center">
                    <h1>
                        <i class="ace-icon fa fa-leaf green"></i>
                        <span class="red">{{ $appConfig['company_name'] ?? '' }}</span>
                        <span class="grey" id="id-text2">{{ $appConfig['appName'] ?? '管理后台' }}</span>
                    </h1>
                    <h4 class="blue" id="id-company-text">&copy; {{ $appConfig['company_name'] ?? '' }}</h4>
                </div>

                <div class="space-6"></div>

                <div class="position-relative">
                    <div id="login-box" class="login-box visible widget-box no-border">
                        <div class="widget-body">
                            <div class="widget-main">
                                <h4 class="header blue lighter bigger">
                                    <i class="ace-icon fa fa-coffee green"></i>
                                    请输入管理员账号密码进行登陆
                                </h4>

                                <div class="space-6"></div>

                                <form id="login-form" onsubmit="return false">
                                    <fieldset>
                                        <label class="block clearfix">
                                            <span class="block input-icon input-icon-right">
                                                <input type="text" class="form-control" name="{{ $username ?? 'username'}}" placeholder="请输入账号" value="{{ $remember_username or '' }}" />
                                                <i class="ace-icon fa fa-user"></i>
                                            </span>
                                        </label>

                                        <label class="block clearfix">
                                            <span class="block input-icon input-icon-right">
                                                <input type="password" class="form-control" name="password" placeholder="请输入您的密码" />
                                                <i class="ace-icon fa fa-lock"></i>
                                            </span>
                                        </label>

                                        <label class="block clearfix">
                                            <span class="block">
                                                <input style="width: 150px; display: inline;" type="text" class="form-control" name="captcha" placeholder="请输入验证码" />
                                                <img style="width: 120px;" id="captcha-img" src="/common/captcha" onclick="this.src+='?t='+Math.random();" alt="点击切换">
                                            </span>
                                        </label>

                                        <div class="space"></div>

                                        <div class="clearfix">
                                            <label class="inline">
                                                @if($username)
                                                    <input id="remember-username" type="checkbox" class="ace" name="remember_username" value="1" checked />
                                                @else
                                                    <input id="remember-username" type="checkbox" class="ace" name="remember_username" value="1" />
                                                @endif
                                                <span class="lbl"> 记住用户名</span>
                                            </label>

                                            <button id="login-btn" type="button" class="width-35 pull-right btn btn-sm btn-primary">
                                                <i class="ace-icon fa fa-key"></i>
                                                <span class="bigger-110">登录</span>
                                            </button>
                                        </div>

                                        <div class="space-4"></div>
                                    </fieldset>
                                </form>
                            </div><!-- /.widget-main -->
                        </div><!-- /.widget-body -->
                    </div><!-- /.login-box -->
                </div>
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
    <div class="row">
        <p style="align-content: center;text-align: center;">
            Copyright © 2018 • <a href="/">{{ $appConfig['company_name'] ?? '' }}</a>
        </p>
    </div>
<!-- basic scripts -->
</body>
@endsection

@section('inlineJs')
    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        function deleteAllCookies() {
            var cookies = document.cookie.split(";");

            for (var i = 0; i < cookies.length; i++) {
                var cookie = cookies[i];
                var eqPos = cookie.indexOf("=");
                var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
                document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
            }
        }

        jQuery(function($) {
            $(document).keydown(function(event){
                switch(event.keyCode) {
                    case 13:
                        $('#login-btn').trigger('click');
                }
            })

            $('#login-btn').click(function() {
                $('#login-btn').prop('disabled', true);

                var username = $('#login-form input[name="{{ $username }}"]').val();
                var password = $('#login-form input[name="password"]').val();
                var captcha = $('#login-form input[name="captcha"]').val();

                var error = '';
                if (! username) {
                    error = '账号不能为空';
                } else if (! password) {
                    error = '密码不能为空';
                } else if (! captcha) {
                    error = '验证码不能为空';
                }
                if (error) {
                    alertify.error(error);
                    $('#login-btn').prop('disabled', false);
                    return;
                }

                window.sessionStorage.clear();
                Operation.submit($(this), '/login', 'post', $('#login-form').serialize(), function(res) {
                    if (res.code == 0) {
                        alertify.success('登录成功');
                        location.href = '/';
                    }
                }, function() {
                    $('#captcha-img').trigger('click');
                })
            });

            deleteAllCookies();
        });


    </script>
@endsection
