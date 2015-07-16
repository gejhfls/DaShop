
$(resizeimg);
$(window).resize(resizeimg);
function resizeimg(){
    var a=$('.goodsimg').width();
    $('.goodsimg').css('height',a*1.2);
    a=$('.tbgroup').width();
    $('.tbgroup').css('height',a*1.5);
}

function resetimg(tag){
    var img=tag.firstChild.nextSibling;
    var url=img.getAttribute('src');
    $('#big').attr('src',url);
    $('.cloud-room').attr('url',$(img).attr('big'));
}
function aaaa(a){
    alert("aaa");
}

function onlyNum() {
    if(!(event.keyCode==46)&&!(event.keyCode==8)&&!(event.keyCode==37)&&!(event.keyCode==39))
        if(!((event.keyCode>=48&&event.keyCode<=57)||(event.keyCode>=96&&event.keyCode<=105)))
            event.returnValue=false;
}

function addq(){
    var num= Number($('.text_box').val())+1;
    $('.text_box').val(num);
}
function dropq(){
    var num= Number($('.text_box').val())-1;
    if(num<=0)num=0;
    $('.text_box').val(num);
}

function addtocart(tag){

    var urlb = window.location.host;
    var url = "http://"+urlb+"/index.php/Home/Cart/add"
    $.post(url, $("#addform").serialize(), function(data) {

        if(data=='success'){
            var offset = $("#cartModel").offset();
            var proOffset = $("#addcar").offset();

            var urlimg="http://"+urlb+"/Public/Home/images/shopcar.png";
            var flyer = $('<img class="u-flyer" src="'+urlimg+'">');
            flyer.css({ "position": "absolute", "top": proOffset.top, "left": proOffset.left+50 ,"z-index":99999,"width":'40px'});
            $("body").append(flyer);
            if(getWinWidth()>500)
               var width=getWinWidth()-90;
            else
                var width=getWinWidth()-40;
            flyer.animate({
                left:width,
                //left:offset.left+15,
                top:offset.top+15,
                opacity:'0.5',
                height:'15px',
                width:'15px'
            },1000,function(){
                flyer.remove();
                updateCart();
                $('#cartmsg').show().fadeOut(1500);
            })
        }
    })

}

function getWinWidth(){
    var windowWidth, windowHeight;
    if (self.innerHeight) { // all except Explorer
        if (document.documentElement.clientWidth) {
            windowWidth = document.documentElement.clientWidth;
        } else {
            windowWidth = self.innerWidth;
        }
        windowHeight = self.innerHeight;
    } else {
        if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
            windowWidth = document.documentElement.clientWidth;
            windowHeight = document.documentElement.clientHeight;
        } else {
            if (document.body) { // other Explorers
                windowWidth = document.body.clientWidth;
                windowHeight = document.body.clientHeight;
            }
        }
    }
    return windowWidth;
}

function addfav(tag,animate){

    var url = 'http://'+window.location.host+'/index.php/Home/Favorite/add';
    $goods_id=$(tag).attr('goodsid')
    $.post(url,{'goods_id':$goods_id},function(data){

        if(data=='success'){
            var urlb = window.location.host;
            if(animate){
                var offset = $(".userModel").offset();
                var proOffset = $(tag).offset();
                var urlimg="http://"+urlb+"/Public/Home/images/fav.png";
                var flyer = $('<img class="u-flyer" src="'+urlimg+'">');
                flyer.css({ "position": "absolute", "top": proOffset.top, "left": proOffset.left+50 ,"z-index":99999});
                $("body").append(flyer);
                if(getWinWidth()>500)
                    var width=getWinWidth()-90;
                else
                    var width=getWinWidth()-40;
                flyer.animate({
                    left:width,
                    //left:offset.left+15,
                    top:offset.top+15,
                    opacity:'0.5',
                    height:'15px',
                    width:'15px'
                },1000,function(){
                    flyer.remove();
                })
            }else {
                var msg = $(tag).next();
                $(msg).show().fadeOut(1500);
            }
        }
        if(data=='error')
            $("#myModal").modal('toggle');
    })
}

function updateCart(){
    var url = 'http://'+window.location.host+'/index.php/Home/Cart/getSum';
    $.post(url,function(data){
        $("#cartPrice").html(data);
    })
}