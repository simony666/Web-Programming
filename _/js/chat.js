var sender = "<div class=\"message sender\"><span> %msg% </span></div>";
var receiver = "<div class=\"message\"><span> %msg% </span></div>";
var update = false;
var msgcount = 0;

$('.chatuser').click(function(){
    chat = $(this).children()[0].innerHTML;
    $('.chatname')[0].innerHTML = chat;
    updatemsg(chat);
    update = true;
});

function scrollToBottom(){
  $('#message-container').scrollTop($('#message-container').prop('scrollHeight') - $('#message-container').height());
}

$('#send-button').click(()=>{
  var text = $('#message-input').val();
  var chat = $('.chatname')[0].innerHTML;
  if (text == ""){
    return;
  }
  if (sendmsg(chat,text)){
    $('#message-input').val('');
  }
})

$(document).keypress(function(event) {
  //$('#send-button').keypress(function(event) {
    var keycode = event.keyCode || event.which;
    if(keycode == '13') {
      var text = $('#message-input').val();
      var chat = $('.chatname')[0].innerHTML;

      if (text == '' || chat == "Please Select A Chat"){
        return;
      }
      if (sendmsg(chat,text)){
        $('#message-input').val('');
      }
    }
  });

function sendmsg(chat, text){
  addmsg(chat,text);

  if($('.message').length <= 0){  
    updatemsg(chat);
  }
  var message = sender.replace('%msg%', text);
  $('#message-container').append(message);
  scrollToBottom();
  return true;
}

function updatemsg(chat){
  $.post( "/_/liveChat.php", { chat: chat, function: "getmsg" } ).done((data)=>{
    console.log(data)
    var data = $.parseJSON(data);
    if(data.message.length > 0){
      update = true;
      $('#message-container').empty();
      for(i=0;i<data.message.length;i++){
        var msgg = $.parseJSON(data.message[i]);
        if (msgg.sender == 0){
          var message = receiver.replace('%msg%', msgg.text);
        }else{
          var message = sender.replace('%msg%', msgg.text);
        }
        $('#message-container').append(message);
      }
      if (msgcount != data.message.length){
        scrollToBottom();
        msgcount = data.message.length;
      }
    }
  })
}

function addmsg(chat, msg){
    console.log("addmsg",chat,msg);
  $.post( "/_/liveChat.php", { chat: chat, function: "insertmsg", message: msg, sender:"1"} ).done((data)=>{
    console.log(data);
  });
}


setInterval(()=>{
  if(update){
    updatemsg($('.chatname')[0].innerHTML);
  }
},1000);