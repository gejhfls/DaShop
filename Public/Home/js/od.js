function setadd(tag){
    var consignee=$(tag).attr("consignee");
    var address=$(tag).attr("address");
    var tel=$(tag).attr("tel");
    var mobile=$(tag).attr("mobile");
    var email=$(tag).attr("email");
    var zipcode=$(tag).attr("zipcode");


    if(consignee!=""){
        $("#consignee").val(consignee);
        $("#address").val(address);
        $("#zipcode").val(zipcode);
        $("#tel").val(tel);
        $("#email").val(email);
        $("#mobile").val(mobile);
    }
}

function orderSubmit(){
    $("#lmsubmit").submit();
}