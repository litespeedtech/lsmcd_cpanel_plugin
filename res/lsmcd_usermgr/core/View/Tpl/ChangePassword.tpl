<?php
use \LsmcdUserPanel\View\Model\ChangePasswordModel as ViewModel;

$user = $this->viewModel->getTplData(ViewModel::FLD_USER);
$server = $this->viewModel->getTplData(ViewModel::FLD_SERVER);
$message = $this->viewModel->getTplData(ViewModel::FLD_MESSAGE);
$done = $this->viewModel->getTplData(ViewModel::FLD_DONE);
$pwd1Disabled = $this->viewModel->getTplData(ViewModel::FLD_PWD1DISABLED);
$pwd2Disabled = $this->viewModel->getTplData(ViewModel::FLD_PWD2DISABLED);
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
    <?php echo 'Change Password on: ' . $server; ?>
  </p>

</div>

<div class="uk-container">
  <h2 class="uk-margin-bottom-remove ls-text-bold ls-text-slateblue">
    <i class="uk-icon uk-icon-question ls-text-skyblue">&nbsp;</i>
    <?php 
    echo 'User: ' . $user; 
    echo '<hr class="uk-margin-top-remove uk-width-large-3-10 ' .
         'uk-width-medium-1-1 uk-width-small-1-1 ls-border" />';
    if (strlen($message))
    {
        echo '<br>';
        if ($done != '')
            echo '   <img src="static/icons/success_icon.png">';
        else        
            echo '   <img src="static/icons/error_icon.png">';
            
        echo '  ' . $message;
        echo '  <hr class="uk-margin-top-remove uk-width-large-3-10 ' .
             'uk-width-medium-1-1 uk-width-small-1-1 ls-border" />';
    }
    ?>
  </h2>
  <div class="uk-text-muted uk-grid uk-margin-bottom">
    <div class="uk-width-large-2-3 uk-width-medium-1-1 uk-width-small-1-1
           uk-margin-bottom">
      <p class="uk-margin-left">
        Enter new password:
      </p>
    </div>
    <div class="uk-width-large-1-3 uk-width-medium-1-1 uk-width-small-1-1
         uk-pull-1-10">
        <input type="password" name="pwd1"<?php echo $pwd1Disabled ?> >
    </div>
      
    <div class="uk-width-large-2-3 uk-width-medium-1-1 uk-width-small-1-1
           uk-margin-bottom">
      <p class="uk-margin-left">
        Enter password again:
      </p>
    </div>
    <div class="uk-width-large-1-3 uk-width-medium-1-1 uk-width-small-1-1
         uk-pull-1-10">
        <input type="password" name="pwd2" <?php echo $pwd2Disabled ?> >
    </div>
      
      <br>
      
    <div class="uk-width-large-2-3 uk-width-medium-1-1 uk-width-small-1-1
           uk-margin-bottom">
      <p class="uk-margin-left">
        <?php
        if ($done == '')
            echo '<button name="do" type="submit" formmethod="post" value="NewPassword">Change Password</button>';
        else
            echo '<button name="do" type="submit" value="main">Ok</button>';
        ?>
      </p>
    </div>
  </div>
  
</div>


