<?php
require('./_base.php');
$func = req('function');
if (!isset($func)){
    echo '{"status":400,"error":"No Function Provided"}';
    return;
}

$chat = req('chat');

if ($func =="getmsg"){
    if (!isset($chat)){
        echo '{"status":400,"error":"Required Args is empty -> chat"}';
        return;
    }
    echo getmsg($chat);

}else if($func =="insertmsg"){
    $sender = req('sender');
    $msg = req('message');
    if (!isset($chat) || !isset($sender) || !isset($msg)){

        echo '{"status":400,"error":"Required Args is empty"}';
        return;
    }
    echo insertmsg($chat,$sender,$msg);
}else if($func =="updateChat"){
    $nChat = req('newChat');
    if (!isset($chat) || !isset($nChat)){

        echo '{"status":400,"error":"Required Args is empty"}';
        return;
    }
    echo updateChat($chat,$nChat);
}else{
    echo '{"status":400,"error":"No Such Function"}';
    return;
}

/*
getmsg(chat)
get message from database
chat int(10)
 */
function getmsg($chat){
    global $db;
    $msgs = array();
    $stmt = $db->prepare("SELECT * FROM live_chat WHERE chat = ?");
    $result = $stmt->execute([$chat]);
    $res = $stmt->fetchAll();

    foreach ($res as $row){
        $msgs[] = "{\"sender\":\"$row->sender\",\"text\":\"$row->text\"}";
    }
    $result = json_encode($msgs);
    return '{"status":200,"chat":"'.$chat.'","message":'.$result.'}';
}

/*
getmsg(chat)
insert message to database
chat int(10)
sender int(1) 0:customer 1:Staff
messages longtext
 */
function insertmsg($chat,$sender,$messages){
    global $db;
    $stmt = $db->prepare("INSERT INTO `live_chat` (chat, sender, text) VALUES (?, ?, ?)");
    $result = $stmt->execute([$chat, $sender, $messages]);
    return '{"status":200,"msg":"Record Added"}';
}

function updateChat($chat,$newChat){
    global $db;
    $stmt = $db->prepare("UPDATE `live_chat` set `chat` = ? WHERE `chat` = ?");
    $result = $stmt->execute([$newChat, $chat]);
    return '{"status":200,"msg":"Chat ID Updated"}';
}
?>