<?php
use \LsmcdUserPanel\View\Model\MainViewModel as ViewModel;

$pluginVersion = $this->viewModel->getTplData(ViewModel::FLD_PLUGIN_VER);
$errMsgs = $this->viewModel->getTplData(ViewModel::FLD_ERR_MSGS);
$succMsgs = $this->viewModel->getTplData(ViewModel::FLD_SUCC_MSGS);
$addr = $this->viewModel->getTplData(ViewModel::FLD_ADDR);
$user = $this->viewModel->getTplData(ViewModel::FLD_USER);
$dataByUser = $this->viewModel->getTplData(ViewModel::FLD_DATA_BY_USER);
$sasl = $this->viewModel->getTplData(ViewModel::FLD_SASL);

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
    Welcome to the User Manager for LSMCD (MemcacheD from LiteSpeed).
  </p>

</div>

<div class="uk-container">
  <h2 class="uk-margin-bottom-remove ls-text-bold ls-text-slateblue">
    <i class="uk-icon uk-icon-question ls-text-skyblue">&nbsp;</i>
    Who you are:
  </h2>
  <hr class="uk-margin-top-remove uk-width-large-3-10 uk-width-medium-1-1
        uk-width-small-1-1 ls-border" />
  <div class="uk-text-muted uk-margin-left">
    <p>
        You are currently logged in as user: <strong><?php echo $user; ?></strong><br>
        LSMCD server is currently set to: <strong><?php echo $addr; ?></strong><br>
        SASL security is enabled: <strong><?php echo $sasl?"YES":"NO"; ?></strong><br>
        Server is configured for User level security: <strong><?php echo $dataByUser?"YES":"NO"; ?></strong>
    </p>

  </div>
</div>

<div class="uk-container">
  <hr class="uk-margin-large-bottom ls-hr-dotted">
</div>
<div class="uk-container">
  <h2 class="uk-margin-bottom-remove ls-text-bold ls-text-slateblue">
    <i class="uk-icon uk-icon-folder-open ls-text-skyblue">&nbsp;Change Password</i>
  </h2>
  <hr class="uk-margin-top-remove uk-width-large-3-10 uk-width-medium-1-1
        uk-width-small-1-1 ls-border" />
  <div class="uk-text-muted uk-grid uk-margin-bottom">
    <div class="uk-width-large-2-3 uk-width-medium-1-1 uk-width-small-1-1
           uk-margin-bottom">
      <p class="uk-margin-left">
        Lets you set a new password for your LSMCD user
      </p>
    </div>
    <div class="uk-width-large-1-3 uk-width-medium-1-1 uk-width-small-1-1
         uk-pull-1-10">
        <?php echo $dataByUser ?
            '<button name="do" type="submit" value="ChangePassword">Change Password</button>' :
            'Requires User Security'; ?>
    </div>
  </div>
</div>

<div class="uk-container">
  <hr class="uk-margin-large-bottom ls-hr-dotted">
</div>
<div class="uk-container">
  <h2 class="uk-margin-bottom-remove ls-text-bold ls-text-slateblue">
    <i class="uk-icon uk-icon-folder-open ls-text-skyblue">&nbsp;Display Stats</i>
  </h2>
  <hr class="uk-margin-top-remove uk-width-large-3-10 uk-width-medium-1-1
        uk-width-small-1-1 ls-border" />
  <div class="uk-text-muted uk-grid uk-margin-bottom">
    <div class="uk-width-large-2-3 uk-width-medium-1-1 uk-width-small-1-1
           uk-margin-bottom">
      <p class="uk-margin-left">
        Lets you display statistics for the data you have stored in LSMCD.
      </p>
    </div>
    <div class="uk-width-large-1-3 uk-width-medium-1-1 uk-width-small-1-1
         uk-pull-1-10">
        <?php echo ((!$sasl) || $dataByUser) ?
            '<button name="do" type="submit" value="DisplayStats">Display Stats</button>' :
            'Requires User or No Security'; ?>
    </div>
  </div>
</div>

