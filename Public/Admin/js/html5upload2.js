/**
 * User: zhy
 * Date: 14-6-16
 * Time: 下午11:06
 */
var ZhangHongyang = {};
ZhangHongyang.html5upload = (function ()
{
    var _ID_UPLOAD_BOX = "uploadBox";
    var _CLASS_PROGRESS = "progress";
    var _CLASS_PERCENTAGE = "percentage";

    var _tip_no_drag = "将文件拖拽至此区域，即可上传！";
    var _tip_drag_over = "释放鼠标立即上传！";

    var _uploadEle = null;

    /**
     * 初始化对象与事件
     * @private
     */
    function _init()
    {
        _uploadEle = document.getElementById(_ID_UPLOAD_BOX);
        _uploadEle.ondragenter = _onDragEnter;
        _uploadEle.ondragover = _onDragOver;
        _uploadEle.ondragleave = _onDragLeave;
        _uploadEle.ondrop = _onDrop;
        _setStatusNoDrag();

    };


    /**
     * 正在拖拽状态
     * @private
     */
    function _setDragOverStatus()
    {
        if (_checkContatinsElements())return;
        _uploadEle.innerText = _tip_drag_over;
        _uploadEle.style.border = "2px dashed #777";
        $(_uploadEle).css({lineHeight: $(_uploadEle).height() + "px"});
    }

    /**
     * 初始化状态
     * @private
     */
    function _setStatusNoDrag()
    {
        if (_checkContatinsElements())return;
        _uploadEle.innerText = _tip_no_drag;
        _uploadEle.style.border = "2px dashed #777";
        $(_uploadEle).css({lineHeight: $(_uploadEle).height() + "px"});
    }

    /**
     * 上传文件
     * @private
     */
    function _setDropStatus()
    {

        if (_checkContatinsElements())return;
        _uploadEle.innerText = "";
        _uploadEle.style.border = "1px solid #444";
        $(_uploadEle).css({lineHeight: "1em"});
        $(_uploadEle).append("<ul></ul>");

    };


    /**
     * 判断是否已经上传文件了
     * @private
     */
    function _checkContatinsElements()
    {
        return !!$(_uploadEle).find("li").size();

    }
    /**
     * 当ondragenter触发
     * @private
     */
    function _onDragEnter(ev)
    {
        _setDragOverStatus();
    }
    /**
     * 当ondargmove触发
     * @private
     */
    function _onDragOver(ev)
    {
        //ondragover中必须组织事件的默认行为，默认地，无法将数据/元素放置到其他元素中。
        ev.preventDefault();

    }
    /**
     * 当dragleave触发
     * @private
     */
    function _onDragLeave(ev)
    {
        _setStatusNoDrag();
    }

    /**
     * ondrop触发
     * @private
     */
    function _onDrop(ev)
    {
        //drop 事件的默认行为是以链接形式打开，所以也需要阻止其默认行为。
        ev.preventDefault();
        _setDropStatus();

        //拿到拖入的文件
        var files = ev.dataTransfer.files;
        var len = files.length;
        for (var i = 0; i < len; i++)
        {
            //页面上显示需要上传的文件
            _showUploadFile(files[i]);
        }
    }
    /**
     * 页面上显示需要上传的文件
     * @private
     */
    function _showUploadFile(file)
    {
        var reader = new FileReader();
        console.log(file)
//        console.log(reader);

        //判断文件类型
        if (file.type.match(/image*/))
        {
            reader.onload = function (e)
            {
                var formData = new FormData();

                var li = $("#template ul").clone();
                var img = li.find("img");
                var progress = li.find(".progress");
                var percentage = li.find(".percentage");
                percentage.text("0%");
                img.attr("src", e.target.result);
                $("ul", $(_uploadEle)).append(li);
                $(_uploadEle).find("li").size() == 10 && $(_uploadEle).width(($(_uploadEle).width() + 8) + "px").css("overflow", "auto");
                formData.append("uploadFile", file);

                //上传文件到服务器
                _uploadToServer(formData, li, progress, percentage);

            };
            reader.readAsDataURL(file);
        }
        else
        {
            console.log("此" + file.name + "不是图片文件！");
        }
    }

    /**
     * 上传文件到服务器
     * @private
     */
    function _uploadToServer(formData, li, progress, percentage)
    {
        var goodsid=$("#gid").attr('title');
        formData.append('goods_id',goodsid);
        var xhr = new XMLHttpRequest();
        var url="http://"+window.location.host+"/index.php/Admin/Gallery/addOK";
        //alert(url);
        xhr.open("POST",'localhost', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest', 'Content-Type', 'multipart/form-data;');

        //HTML5新增的API，存储了上传过程中的信息
        xhr.upload.onprogress = function (e)
        {
            var percent = 0;
            if (e.lengthComputable)
            {
                //更新页面显示效果
                percent =Number((100 * e.loaded / e.total).fixed(2));
                progress.height(percent );
                percentage.text(percent + "%");
                percent >= 100 && li.addClass("done");
            }
        };
        xhr.send(formData);
    }


    //把init方法公布出去
    return{
        init: _init }


})();
