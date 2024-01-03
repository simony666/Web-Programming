<?php
$chat =$_COOKIE['Chat']??null;
if (!isset($chat)){
    if ($user){
        echo "<script>setCookie('Chat', $user->id, 86400, '/');</script>";
    }else{
        echo "<script>setCookie('Chat', uniqid(), 86400, '/');</script>";
    }
}else{
    if ($user){
        if($user->id != $chat){
            echo "<script>$(()=>{updateChat('$chat','$user->id');});</script>";
            echo "<script>setCookie('Chat', $user->id, 86400, '/');</script>";
        }
    }
}
?>
    <link rel="stylesheet" href="/_/css/liveChat.css">
</head>
<body>
    
    <div class="livechat">
        <div id="chatbtn">
        <i class="fa-solid fa-headset fa-beat"></i>
        </div>
        <div id="chat-box">    
            <div id="message-box">
                <div class="title"><span>Live chat session</span><i class="fa fa-window-close" aria-hidden="true"></i></div>
                <div class="message-container">
                </div>
            </div>
            <div class="typearea">
                <input type="text" id="message-input" placeholder="Type your message here...">
                <button id="send-button">Send</button>
            </div>
            
        </div>
    </div>
      
</body>
<script src="/_/js/liveChat.js"></script>
</html>