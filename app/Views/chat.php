<?php foreach($detail_chat as $dc){ ?>
    <?php if($dc->user == $group){ ?>
    <div class="chat-message d-flex">
        <div class="chat-message-content is-me">
        <?php if($dc->type == 'image'){ ?>
            <img src="<?= $dc->message ?>" width="256px" alt="">
        <?php }else{ ?>
            <p><?= $dc->message; ?></p>
        <?php } ?>
        <p class="chat-time-content"><?= date('d F Y, H:i', $dc->date) ?></p>
        </div>
    </div>
    <?php } else {  ?>
    <div class="chat-message d-flex">
        <div class="chat-message-content">
        <?php if($dc->type == 'image'){ ?>
            <img src="<?= $dc->message ?>" width="256px" alt="">
        <?php }else{ ?>
            <p><?= $dc->message; ?></p>
        <?php } ?>
        <p class="chat-time-content"><?= date('d F Y, H:i', $dc->date) ?></p>
        </div>
    </div>
<?php }}?>   