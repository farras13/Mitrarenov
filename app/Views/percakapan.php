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
                            <img src="<?= base_url('public/main/images/article-sd.jpg') ?>" alt="">
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
                        <div class="chat-header">
                          <div class="chat-img">
                            <div class="chat-img-inner">
                              <img src="<?= base_url('public/main/images/article-sd.jpg') ?>" alt="">
                            </div>
                          </div>
                          <div class="chat-content">
                            <p class="chat-name">Admin - Mitrarenov</p>
                          </div>
                        </div>
                        <div class="chat-body">
                          <?php foreach($detail_chat as $dc){ ?>
                            <?php if($dc->user == $group){ ?>
                            <div class="chat-message d-flex">
                              <div class="chat-message-content is-me">
                                <p><?= $dc->message; ?></p>
                                <p class="chat-time-content"><?= date('d F Y, H:i', $dc->date) ?></p>
                              </div>
                            </div>
                            <?php } else {  ?>
                            <div class="chat-message d-flex">
                              <div class="chat-message-content">
                                <p><?= $dc->message; ?></p>
                                <p class="chat-time-content"><?= date('d F Y, H:i', $dc->date) ?></p>
                              </div>
                            </div>
                          <?php }}?>                          
                        </div>
                        <form action="<?= base_url('chat-kirim') ?>" method="post">
                          <div class="chat-footer align-items-center">
                            <div class="w-100 pr-3">
                              <input type="hidden" class="form-control" name="idcht" value="<?= $idlist; ?>">
                              <input type="text" class="form-control" name="cht" placeholder="Tulis Pesan Disini..">
                            </div>
                            <div class="chat-action">
                              <a href="#">
                                <i class="ico ico-plus"></i>
                              </a>
                            </div>
                            <div class="chat-action">
                            <label for="mySubmit" class="btn"><i class="ico ico-paperline"></i></label>
                            <input id="mySubmit" type="submit" hidden />                        
                            </div>
                          </div>
                        </form>
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