<?php

use \LsmcdUserPanel\View\Model\ChangePasswordModel as ViewModel;

$user = $this->viewModel->getTplData(ViewModel::FLD_USER);
$server = $this->viewModel->getTplData(ViewModel::FLD_SERVER);
$message = $this->viewModel->getTplData(ViewModel::FLD_MESSAGE);
$done = $this->viewModel->getTplData(ViewModel::FLD_DONE);

?>

<div class="uk-container">
  <p class="uk-text-large uk-margin-large-bottom">
    Change Password on: <?php echo $server; ?>
  </p>
</div>

<div class="uk-container">
  <h2 class="uk-margin-bottom-remove ls-text-bold ls-text-slateblue">
    <i class="uk-icon uk-icon-question ls-text-skyblue">&nbsp;</i>
    User: <?php echo $user; ?>
  </h2>
  <hr class="uk-margin-top-remove uk-width-large-3-10 uk-width-medium-1-1
        uk-width-small-1-1 ls-border" />

  <?php if ( strlen($message) ): ?>

  <h2 class="uk-margin-bottom-remove ls-text-bold ls-text-slateblue">

    <?php if ( $done != '' ): ?>

    <img src="static/icons/success_icon.png" />

    <?php else: ?>

    <img src="static/icons/error_icon.png" />

    <?php endif; ?>

    &nbsp;<?php echo $message; ?>
  </h2>
  <hr class="uk-margin-top-remove uk-width-large-3-10 uk-width-medium-1-1
        uk-width-small-1-1 ls-border"/>

  <?php endif; ?>

  <div class="uk-text-muted uk-grid uk-margin-bottom">
    <div class="uk-width-large-2-3 uk-width-medium-1-1 uk-width-small-1-1
           uk-margin-bottom"
    >
      <p class="uk-margin-left">Enter new password:</p>
    </div>
    <div class="uk-width-large-1-3 uk-width-medium-1-1 uk-width-small-1-1
           uk-pull-1-10"
    >
      <input type="password" name="pwd1">
    </div>

    <div class="uk-width-large-2-3 uk-width-medium-1-1 uk-width-small-1-1
           uk-margin-bottom"
    >
      <p class="uk-margin-left">Enter password again:</p>
    </div>
    <div class="uk-width-large-1-3 uk-width-medium-1-1 uk-width-small-1-1
           uk-pull-1-10"
    >
      <input type="password" name="pwd2">
    </div>


    <div class="uk-width-large-2-3 uk-width-medium-1-1 uk-width-small-1-1
           uk-margin-bottom"
    >
      <p class="uk-margin-left">

        <?php if ($done == '') : ?>

        <button name="do" type="submit" value="NewPassword">Change Password</button>

        <?php else: ?>

        <button name="do" type="submit" value="main">OK</button>

        <?php endif; ?>

      </p>
    </div>
  </div>

</div>


