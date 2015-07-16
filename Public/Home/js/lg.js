$().ready(function(){
    error=[];
    url = window.location.host;
     rr="http://"+url+"/index.php/Home/Register/CheckCode";
    $('#lcode').blur(
        function() {
            var code = $(this).val();
            $.post(rr, {
                'code' : code
            }, function(data) {
                if (data == 0) {
                    error['lcode'] = 0;
                    $('#ltooltip5').attr('class',
                        'tooltip-info visible-inline success');
                    $('#ltooltip5').html("通过验证！");
                } else {
                    error['lcode'] = 1;
                    $('#ltooltip5').attr('class',
                        'tooltip-info visible-inline error');
                    $('#ltooltip5').html(data);
                }
            })
            return false;
        });
    $('#lsubmit1').click(function() {
        error['lusername']=0;
        error['lpassword']=0;
        if ($('#lusername').val() == '') {
            $('#ltooltip1').attr('class', 'tooltip-info visible-inline error');
            $('#ltooltip1').html("用户名不能为空!");
            error['lusername'] = 1;
        }
        if ($('#lpassword').val() == '') {
            $('#ltooltip2').attr('class', 'tooltip-info visible-inline error');
            $('#ltooltip2').html("密码不能为空!");
            error['lpassword'] = 1;
        }
        if ($('#lcode').val() == '') {
            $('#ltooltip5').attr('class', 'tooltip-info visible-inline error');
            $('#ltooltip5').html("验证码不能为空!");
            error['lcode'] = 1;
        }
        if (error['lusername'] == 1) {
            //var scroll_offset = $("#ltooltip1").offset(); // 如果用户名验证失败，屏幕会滚动到用户名的位置，让用户重新输入
            //$("body,html").animate({
            //    scrollTop : scroll_offset.top
            //    // 让body的scrollTop等于pos的top，就实现了滚动
            //}, 0);
            //alert("1");
            return false;
        } else if (error['lpassword'] == 1) {
            //var scroll_offset = $("#ltooltip2").offset(); // 如果用户名验证失败，屏幕会滚动到用户名的位置，让用户重新输入
            //$("body,html").animate({
            //    scrollTop : scroll_offset.top
            //    // 让body的scrollTop等于pos的top，就实现了滚动
            //}, 0);
            //alert("2");
            return false;
        }  else if (error['lcode'] == 1) {
            //var scroll_offset = $("#ltooltip5").offset(); // 如果用户名验证失败，屏幕会滚动到用户名的位置，让用户重新输入
            //$("body,html").animate({
            //    scrollTop : scroll_offset.top
            //    // 让body的scrollTop等于pos的top，就实现了滚动
            //}, 0);
            //alert("5");
            return false;
        } else {
            $('#lmsubmit').submit();
            return true;
        }
    });

})


