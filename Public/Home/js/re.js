$().ready(function(){
    var rurl="http://"+window.location.host+"/index.php/Home/";
    error=[];
    $('#username').blur(
        function() {
            var username = $(this).val();
            $.post(rurl+'Register/CheckName', {
                'user_name' : username//前一个username需要跟UserModel对应，即跟数据库字段对应
            }, function(data) {
                if (data == 0) {
                    error['username'] = 0;
                    $('#tooltip1').attr('class',
                        'tooltip-info visible-inline success');
                    $('#tooltip1').html("恭喜，用户名可注册！");
                } else {
                    error['username'] = 1;
                    $('#tooltip1').attr('class',
                        'tooltip-info visible-inline error');
                    $('#tooltip1').html(data);
                }
            })
            return false;
        });
    $('#remail').blur(
        function() {
            var email = $(this).val();
            $.post(rurl+'Register/CheckEmail', {
                'email' : email//前一个username需要跟UserModel对应，即跟数据库字段对应
            }, function(data) {
                if (data == 0) {
                    error['email'] = 0;
                    $('#tooltip4').attr('class',
                        'tooltip-info visible-inline success');
                    $('#tooltip4').html("恭喜，邮箱可注册！");
                } else {
                    error['email'] = 1;
                    $('#tooltip4').attr('class',
                        'tooltip-info visible-inline error');
                    $('#tooltip4').html(data);
                }
            })
            return false;
        });
    $('#password').blur(
        function() {
            var pass = $(this).val();
            $.post(rurl+'Register/CheckPass', {
                'password' : pass//前一个username需要跟UserModel对应，即跟数据库字段对应
            }, function(data) {
                if (data == 0) {
                    error['email'] = 0;
                    $('#tooltip2').attr('class',
                        'tooltip-info visible-inline success');
                    $('#tooltip2').html("格式正确！");
                } else {
                    error['email'] = 1;
                    $('#tooltip2').attr('class',
                        'tooltip-info visible-inline error');
                    $('#tooltip2').html(data);
                }
            })
            return false;
        });
    $('#check').blur(
        function() {
            var pass = $('#password').val();
            var rpass = $(this).val();
            $.post(rurl+'Register/CheckRPass', {
                'password' : pass,
                'rpassword' : rpass//前一个username需要跟UserModel对应，即跟数据库字段对应
            }, function(data) {
                if (data == 0) {
                    error['check'] = 0;
                    $('#tooltip3').attr('class',
                        'tooltip-info visible-inline success');
                    $('#tooltip3').html("通过验证！");
                } else {
                    error['check'] = 1;
                    $('#tooltip3').attr('class',
                        'tooltip-info visible-inline error');
                    $('#tooltip3').html(data);
                }
            })
            return false;
        });
    $('#code').blur(
        function() {
            var code = $(this).val();
            $.post(rurl+'Register/CheckCode', {
                'code' : code
            }, function(data) {
                if (data == 0) {
                    error['code'] = 0;
                    $('#tooltip5').attr('class',
                        'tooltip-info visible-inline success');
                    $('#tooltip5').html("通过验证！");
                } else {
                    error['code'] = 1;
                    $('#tooltip5').attr('class',
                        'tooltip-info visible-inline error');
                    $('#tooltip5').html(data);
                }
            })
            return false;
        });

    $('#submit1').click(function() {
        if ($('#username').val() == '') {
            $('#tooltip1').attr('class', 'tooltip-info visible-inline error');
            $('#tooltip1').html("用户名不能为空!");
            error['username'] = 1;
        }
        if ($('#password').val() == '') {
            $('#tooltip2').attr('class', 'tooltip-info visible-inline error');
            $('#tooltip2').html("密码不能为空!");
            error['password'] = 1;
        }
        if ($('#check').val() == '') {
            $('#tooltip3').attr('class', 'tooltip-info visible-inline error');
            $('#tooltip3').html("确认密码不能为空!");
            error['check'] = 1;
        }
        if ($('#email').val() == '') {
            $('#tooltip4').attr('class', 'tooltip-info visible-inline error');
            $('#tooltip4').html("邮箱不能为空!");
            error['email'] = 1;
        }
        if ($('#code').val() == '') {
            $('#tooltip5').attr('class', 'tooltip-info visible-inline error');
            $('#tooltip5').html("验证码不能为空!");
            error['code'] = 1;
        }
        if (error['username'] == 1) {
            var scroll_offset = $("#tooltip1").offset(); // 如果用户名验证失败，屏幕会滚动到用户名的位置，让用户重新输入
            $("body,html").animate({
                scrollTop : scroll_offset.top
                // 让body的scrollTop等于pos的top，就实现了滚动
            }, 0);
            //alert("1");
            return false;
        } else if (error['password'] == 1) {
            var scroll_offset = $("#tooltip2").offset(); // 如果用户名验证失败，屏幕会滚动到用户名的位置，让用户重新输入
            $("body,html").animate({
                scrollTop : scroll_offset.top
                // 让body的scrollTop等于pos的top，就实现了滚动
            }, 0);
            //alert("2");
            return false;
        } else if (error['check'] == 1) {
            var scroll_offset = $("#tooltip3").offset(); // 如果用户名验证失败，屏幕会滚动到用户名的位置，让用户重新输入
            $("body,html").animate({
                scrollTop : scroll_offset.top
                // 让body的scrollTop等于pos的top，就实现了滚动
            }, 0);
            //alert("3");
            return false;
        } else if (error['email'] == 1) {
            var scroll_offset = $("#tooltip4").offset(); // 如果用户名验证失败，屏幕会滚动到用户名的位置，让用户重新输入
            $("body,html").animate({
                scrollTop : scroll_offset.top
                // 让body的scrollTop等于pos的top，就实现了滚动
            }, 0);
            //alert("4");
            return false;
        } else if (error['code'] == 1) {
            var scroll_offset = $("#tooltip5").offset(); // 如果用户名验证失败，屏幕会滚动到用户名的位置，让用户重新输入
            $("body,html").animate({
                scrollTop : scroll_offset.top
                // 让body的scrollTop等于pos的top，就实现了滚动
            }, 0);
            //alert("5");
            return false;
        } else {
            $('#msubmit').submit();
            return true;
        }
    });
})
