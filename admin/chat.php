<?php 
    include ('../_/_base.php');
    get_user($user->id,true);

    $stm = $db->query("SELECT DISTINCT `chat` FROM `live_chat`;");
    $chatroom = $stm->fetchAll();
    $chats = array();
    foreach ($chatroom as $c){
        $us = $c->chat;
        $stm1 = $db->prepare("SELECT * FROM `live_chat` WHERE `chat` = ? ORDER BY `id` DESC LIMIT 1;");
        $stm1->execute([$us]);
        foreach($stm1->fetchAll() as $c){
            $ch = new stdClass();
            $ch->user = $us;
            $ch->text = $c->text;
            $chats[]=  $ch;
        }
    }

    $chat = $chats[0]->user;
    include ('../_/layout/admin/header.php');
?>
<link rel="stylesheet" href="../_/css/chat.css">
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row mt-4">
                <div class="">
                    <div class="row">
                        <div class="leftpanel col" style="background-color: antiquewhite;">
                            <?php
                            foreach ($chats as $c ){
                                echo "<div class=\"chatuser\"><span class=\"chatusername\" >".$c->user."</span><br><span class=\"lastchat\">".$c->text."</span></div>";
                            }
                            ?>
                        </div>
                        <div class="rightpanel col">
                            <div class="user-container">
                                <h3 class="chatname">Please Select A Chat</h3>
                            </div>
                            <div id="message-container">
                                
                            </div>
                            

                            <div class="input">
                                <input type="text" name="send" id="message-input">
                                <input type="button" id="send-button" value="Send">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../_/js/chat.js"></script>
<?php include ('../_/layout/admin/footer.php');  ?>