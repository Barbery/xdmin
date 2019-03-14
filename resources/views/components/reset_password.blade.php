<script type="text/x-template" id="reset-password-tpl">
    <form id="reset-password-form">
        <div class="form-group">
            <label class="control-label"> 原密码 </label>
            <input type="password" id="old_password" name="old_password" class="form-control" autocomplete="off" />
        </div>
        <div class="form-group">
            <label class="control-label"> 新密码 </label>
            <input type="password" id="new_password" name="new_password" class="form-control" autocomplete="off" />
        </div>
        <div class="form-group">
            <label class="control-label"> 确认新密码 </label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" autocomplete="off" />
        </div>
    </form>
</script>

@section('componentsJs')
    @parent

    <script>
        function resetPassword(obj) {
            var resetFormHtml = $('#reset-password-tpl').html();
            alertify.confirm('重置密码', resetFormHtml, function() {
                var oldPass = $('#old_password').val();
                var newPass = $('#new_password').val();
                var confirmPass = $('#confirm_password').val();

                var error = '';
                if (! oldPass) {
                    error = '请输入原密码';
                } else if (! newPass) {
                    error = '请输入新密码';
                } else if (newPass != confirmPass) {
                    error = '两次输入的密码不一致';
                }
                if (error) {
                    alertify.error(error);
                    return false;
                }

                request('{{ route('admin.reset_pass') }}', 'POST', {old_pass: oldPass, new_pass: newPass}, function (data) {
                    alertify.success('重置密码成功');
                });
            }, function() {}).set('transition', 'zoom').set('closable', false);
        }
    </script>
@endsection