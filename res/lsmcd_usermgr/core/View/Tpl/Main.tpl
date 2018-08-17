<?php

use \LsmcdUserPanel\View\Model\MainViewModel as ViewModel;

$pluginVersion = $this->viewModel->getTplData(ViewModel::FLD_PLUGIN_VER);
$addr = $this->viewModel->getTplData(ViewModel::FLD_ADDR);
$user = $this->viewModel->getTplData(ViewModel::FLD_USER);
$dataByUser = $this->viewModel->getTplData(ViewModel::FLD_DATA_BY_USER);
$sasl = $this->viewModel->getTplData(ViewModel::FLD_SASL);

?>

<div class="uk-container">
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
      You are currently logged in as user:
      <strong><?php echo $user; ?></strong>
      <br />
      LSMCD server is currently set to: <strong><?php echo $addr; ?></strong>
      <br />
      SASL security is enabled:
      <strong><?php echo ($sasl) ? "YES" : "NO"; ?></strong>
      <br />
      Server is configured for User level security:
      <strong><?php echo ($dataByUser) ? "YES" : "NO"; ?></strong>
    </p>
  </div>
</div>

<div class="uk-container">
  <hr class="uk-margin-large-bottom ls-hr-dotted">
</div>

<div class="uk-container">
  <h2 class="uk-margin-bottom-remove ls-text-bold ls-text-slateblue">
    <i class="uk-icon uk-icon-folder-open ls-text-skyblue">
      Change Password
    </i>
  </h2>
  <hr class="uk-margin-top-remove uk-width-large-3-10 uk-width-medium-1-1
        uk-width-small-1-1 ls-border" />
  <div class="uk-text-muted uk-grid uk-margin-bottom">
    <div class="uk-width-large-2-3 uk-width-medium-1-1 uk-width-small-1-1
           uk-margin-bottom"
    >
      <p class="uk-margin-left">
        Lets you set a new password for your LSMCD user.
      </p>
    </div>
    <div class="uk-width-large-1-3 uk-width-medium-1-1 uk-width-small-1-1
           uk-pull-1-10"
    >

      <?php if ( $dataByUser ) : ?>

      <button name="do" type="submit" value="ChangePassword">Change Password</button>

      <?php else : ?>

      Requires User Security

      <?php endif; ?>

    </div>
  </div>
</div>

<div class="uk-container">
  <hr class="uk-margin-large-bottom ls-hr-dotted">
</div>

<div class="uk-container">
  <h2 class="uk-margin-bottom-remove ls-text-bold ls-text-slateblue">
    <i class="uk-icon uk-icon-folder-open ls-text-skyblue">
      Display Stats
    </i>
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
           uk-pull-1-10"
    >

      <?php if ( !$sasl || $dataByUser ) : ?>

      <button name="do" type="submit" value="DisplayStats">Display Stats</button>

      <?php else : ?>

      Requires User or No Security

      <?php endif; ?>

    </div>
  </div>
</div>

