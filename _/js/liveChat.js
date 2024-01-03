var sender = "<div class=\"message sender\"><span> %msg% </span></div>";
var receiver = "<div class=\"message\"><span> %msg% </span></div>";
var update = false;
var msgcount = 0;

$('#chatbtn').click(function(){
  $('#chat-box').addClass('visible');
  scrollToBottom()
});

$('.fa-window-close').click(function(){
  $('#chat-box').removeClass('visible');
});

function scrollToBottom(){
  $('.message-container').scrollTop($('.message-container').prop('scrollHeight') - $('.message-container').height());
}

$('#send-button').click(()=>{
  var text = $('#message-input').val();
  if (text == ""){
    return;
  }
  if (sendmsg(text)){
    $('#message-input').val('');
  }
})

$(document).keypress(function(event) {
//$('#send-button').keypress(function(event) {
  var keycode = event.keyCode || event.which;
  if(keycode == '13') {
      if ($('#chat-box').hasClass('visible')){
        var text = $('#message-input').val();
        if (text == ""){
          return;
        }
        if (sendmsg(text)){
          $('#message-input').val('');
        }
      }
  }
});

function sendmsg(text){
  addmsg($.cookie("Chat"),text);
  if($('.message').length <= 0){
    updatemsg($.cookie("Chat"))
  }
  var message = sender.replace('%msg%', text);
  $('.message-container').append(message);
  scrollToBottom();
  return true;
}

function updatemsg(chat){
  $.post( "/_/liveChat.php", { chat: chat, function: "getmsg" } ).done((data)=>{
    var data = $.parseJSON(data);
    if(data.message.length > 0){
      update = true;
      $('.message-container').empty();
      for(i=0;i<data.message.length;i++){
        var msgg = $.parseJSON(data.message[i]);
        if (msgg.sender == 0){
          var message = sender.replace('%msg%', msgg.text);
        }else{
          var message = receiver.replace('%msg%', msgg.text);
        }
        $('.message-container').append(message);
      }
      if (msgcount != data.message.length){
        scrollToBottom();
        msgcount = data.message.length;
      }
    }
  })
}

function addmsg(chat, msg){
  $.post( "/_/liveChat.php", { chat: chat, function: "insertmsg", message: msg, sender:"0"} ).done((data)=>{
    console.log(data);
  });
}
function updateChat(chat, newChat){
    $.post( "/_/liveChat.php", { chat: chat, function: "updateChat", newChat: newChat} ).done((data)=>{
      console.log(data);
    });
  }

updatemsg($.cookie("Chat"));
setInterval(()=>{
  if(update){
    updatemsg($.cookie("Chat"));
  }
},1000);