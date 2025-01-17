function getLanguageMessage(cookieName) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + cookieName + "=");
    var language = (parts.length == 2) ? parts.pop().split(";").shift() : '';
    var message;
    if (language === 'en-us') {
       message =  'Please select the event to delete';
    } else if (language === 'zh-cn') {
       message =  '请选择要删除的事件';
    } else if (language === 'zh-tw') {
       message =  '請點選要刪除的事件';
    } else {
      message =  'Please select the event to delete';
    }
   //alertify.alert(message);
}