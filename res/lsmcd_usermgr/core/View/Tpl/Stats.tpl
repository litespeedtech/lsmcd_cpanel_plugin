<?php
use \LsmcdUserPanel\View\Model\MainViewModel as ViewModel;

$stats = $this->viewModel->getTplData(ViewModel::FLD_STATS);
$user = $this->viewModel->getTplData(ViewModel::FLD_USER);
$server = $this->viewModel->getTplData(ViewModel::FLD_SERVER);

?>

<div class="uk-container">

  <?php

  $errMsgCnt = count($errMsgs);
  $succMsgCnt = count($succMsgs);

  if ( $errMsgCnt > 0 || $succMsgCnt > 0 ) {
      $msgsDisplay = 'initial';
  }
  else {
      $msgsDisplay = 'none';
  }

  ?>

  <div id="display-msgs" style="display:<?php echo $msgsDisplay; ?>;">
      
    <button class="accordion accordion-error" type="button"
            style="display: <?php echo ($errMsgCnt > 0) ? 'initial' : 'none'; ?>">
      Error Messages
      <span id ="errMsgCnt" class="badge errMsg-badge">
        <?php echo $errMsgCnt; ?>
      </span>
    </button>
    <div class="panel panel-error">

      <?php

      $d = array(
          'id' => 'errMsgs',
          'msgs' => $errMsgs,
          'class' => 'scrollable',
      );
      $this->loadTplBlock('DivMsgBox.tpl', $d);

      ?>

    </div>

    <button class="accordion accordion-success" type="button"
            style="display: <?php echo ($succMsgCnt > 0) ? 'initial' : 'none'; ?>">
      Success Messages
      <span id="succMsgCnt" class="badge succMsg-badge">
        <?php echo $succMsgCnt; ?>
      </span>
    </button>
    <div class="panel panel-success">

      <?php

      $d = array(
          'id' => 'succMsgs',
          'msgs' => $succMsgs,
          'class' => 'scrollable',
      );
      $this->loadTplBlock('DivMsgBox.tpl', $d);

      ?>

    </div>
  </div>

  <p class="uk-text-large uk-margin-large-bottom">
    Display Stats
  </p>

</div>

<div class="uk-container">
  <h2 class="uk-margin-bottom-remove ls-text-bold ls-text-slateblue">
    <i class="uk-icon uk-icon-question ls-text-skyblue">&nbsp;</i>
    <?php echo 'Server: ' . $server . ' for: ' . $user; ?>
  </h2>
  <hr class="uk-margin-top-remove uk-width-large-3-10 uk-width-medium-1-1
        uk-width-small-1-1 ls-border" />
  <div class="uk-text-muted uk-margin-left">
    <p>
        <?php print_r($stats); ?>
    </p>

  </div>
</div>


