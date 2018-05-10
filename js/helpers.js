function isEmail(email) {
    var emailRegex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return emailRegex.test(email);
}

function validateUrl(url) {
    var urlRegex = new RegExp("^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
    return urlRegex.test(url);
}

function hasNumber(String) {
    return /\d/.test(String);
}

function getFormObj(formid) {
    formdata = $("#" + formid).serializeArray();

    obj = new Object();
    for (i in formdata) {
        if (formdata[i].value !== "") {
            obj[formdata[i].name] = formdata[i].value;
        }
    }

    return obj;
}

function resetAllTextValues(id) {
  $("#"+id+"").find('input:text').val('');
  return false;
}

function usermessage(message, type) {
	var message = '<b class="message">'+message+'</b>';
    notif({
        msg: message,
        type: type,
        width:300,
        position: "center",
        fade: true
    });
}


function showLoadingDiv() {

    $(".modal-backdrop.loading.normal").remove();
    var html = "<div class=\"modal-backdrop loading normal\" style=\"position:fixed;z-index:3060;height:100%;top:0px;left:0px;bottom:0px;right:0px\"><span style='z-index:10;position:absolute;top:35%;left:45%;text-align;center'>\
    <h2 style='color:#fff;font-size:17px;margin:0px;line-height:0px'>\
    <img src='./assets/img/loading.gif'/></h2></span></div>";
    $("body").append(html);
}

function stopLoadingDiv() {
    $(".loading.normal").remove();
}


function addDivData(dataDiv,maxCount){

    for (var i = 0; i<=maxCount; i++)
    {
        if($("#"+dataDiv+i+"").is(':hidden'))
        {
            $("#"+dataDiv+i+"").show(500);
            return false;
        }
    }

}

function hideSingleDivData(divid, idnumber){
    if($("#"+divid+idnumber+"").is(':visible'))
    {
        resetAllTextValues(divid+idnumber+"");
        $("#"+divid+idnumber+"").hide(500);
        return false;
    }
    
}


function hidemultipleDivData(div){
    for(var i=1; i<=10; i++)
    {
        $("#"+div+i+"").hide();
    }
}


function showSingleDivData(divid, idnumber){
    if($("#"+divid+idnumber+"").is(':hidden'))
    {
        $("#"+divid+idnumber+"").show(500);
        return false;
    }
    
}