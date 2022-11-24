<?= $this->extend('template2') ?>

<?= $this->section('content') ?>

    <div class="container" id="vapp">
      <div class="bg-grey">
        <div class="py-5 px-5 chat-padding">
          <div class="card card-shadow radius-15">
            <div class="card-body px-4">
              <div class="row">
                <div class="col-lg-4 left-chat">
                  <nav class="nav flex-column nav-list-chat">
                    <?php foreach ($list_chat as $lc) { ?>
                      <?php if(base64_encode($lc->project_id) == $idlist){ ?>
                        <a href="<?= base_url('chat/').'?83rc2kp='.base64_encode($lc->project_id) ?>" class="nav-link active">
                      <?php }else{ ?>
                        <a href="<?= base_url('chat/').'?83rc2kp='.base64_encode($lc->project_id) ?>" class="nav-link">
                      <?php } ?>
                        <div class="chat-img">
                          <div class="chat-img-inner">
                            <img style="object-fit: contain !important;" src="<?= base_url('/main/images/logo-mitrarenov.png') ?>" alt="">
                          </div>
                        </div>
                        <div class="chat-content">
                          <p class="chat-name">Admin - Mitrarenov </p>
                          <p class="chat-sort"><?= substr($lc->message, 0, 30)."..."; ?></p>
                        </div>
                        <div class="chat-time">
                          <?= $lc->tanggal ?>
                        </div>
                      </a>
                    <?php } ?>
                  </nav>

                </div>
                <div class="col-lg-8 right-chat">
                  <div class="tab-content">
                    <div class="tab-pane fade show active" id="chat1">
                      <div class="chat-detail">
                        <?php if(!empty($list_chat)): ?>
                        <div class="chat-header">
                          <div class="chat-img">
                            <div class="chat-img-inner">
                              <img style="object-fit: contain !important;" src="<?= base_url('/main/images/logo-mitrarenov.png') ?>" alt="">
                            </div>
                          </div>
                          <div class="chat-content">
                            <p class="chat-name">Admin - Mitrarenov</p>
                          </div>
                        </div>
                        <?php endif; ?>     
                        <div class="chat-body" id="chat_body">
                              <?php if(empty($list_chat)): ?>
                                <h3>Anda belum memliki projek.</h3>
                              <?php endif; ?>     
                        </div> 
                        <?php if(!empty($list_chat)): ?>
                        <form action="<?= base_url('chat-kirim') ?>" method="post">
                          <div class="chat-footer align-items-center">
                            <div class="w-100 pr-3">
                              <input type="hidden" class="form-control" id="idprojekpesan" name="idcht" value="<?= $idlist; ?>">
                              <input type="text" class="form-control" id="inputpesan" name="cht" placeholder="Tulis Pesan Disini..">
                            </div>
                            <div class="chat-action">
                              <a href="#">
                                <i class="ico ico-plus"></i>
                              </a>
                            </div>
                            <div class="chat-action">
                              
                            <label for="mySubmit" class="btn" ><i class="ico ico-paperline"></i></label>
                            <input id="mySubmit" id="sendpesan" type="submit" hidden />                        
                            </div>
                          </div>
                        </form>
                        <?php endif; ?>     
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
 
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
  $(document).ready(function() {
    var b =  GetURLParameter("83rc2kp");
    setInterval(() => {
      dataChat(b);
      var objDiv = document.getElementById("chat_body");
      objDiv.scrollTop = objDiv.scrollHeight;
     
        // console.log(b);
    }, 5000);
    $(document).on('click', '#sendpesan', function() {
        var id = $('#idprojekpesan').val();
        var pesan = $('#inputpesan').val();
        $.post('<?php echo site_url('chat-kirim') ?>', {
            id: id,
            pesan: pesan,
        }, function(data) {
            if (data != "" || data != null) {
                $('#inputpesan').val('');
                $('#idprojekpesan').val(id);
                $('#chat_body').html('');
                $('#chat_body').html(data);
            }
        });
    });
    
  });
  function GetURLParameter(sParam)
  {
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
  }
  function dataChat(b) {
      $.post('<?php echo site_url('Chat/chat') ?>', {
          id: b
      }, function(data) {
          if (data != "" || data != null) {              
              $('#chat_body').html('');
              $('#chat_body').html(data);
          }
      });
  }       
  
  
              
</script>
<?= $this->endSection() ?>
