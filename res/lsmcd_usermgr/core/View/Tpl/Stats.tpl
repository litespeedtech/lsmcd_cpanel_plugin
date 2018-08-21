<?php

use \LsmcdUserPanel\View\Model\StatsModel as ViewModel;

$stats = $this->viewModel->getTplData(ViewModel::FLD_STATS);
$user = $this->viewModel->getTplData(ViewModel::FLD_USER);
$server = $this->viewModel->getTplData(ViewModel::FLD_SERVER);
$errMsg = $this->viewModel->getTplData(ViewModel::FLD_ERR_MSG);

if ( !empty($errMsg) ) :

?>

<p class="uk-margin-bottom-remove ls-text-bold">
  <img src="static/icons/error_icon.png" alt="error icon" /> &nbsp;<?php echo $errMsg; ?>
</p>

<?php else: ?>

<div class="uk-container">
  <p class="uk-text-large uk-margin-large-bottom">
    Stats for Server: <?php echo $server; ?>
  </p>
</div>

<div class="uk-container">
  <h2 class="uk-margin-bottom-remove ls-text-bold ls-text-slateblue">
    <i class="uk-icon uk-icon-question ls-text-skyblue">&nbsp;</i>
    User: <?php echo $user; ?>
  </h2>
  <hr class="uk-margin-top-remove uk-width-large-3-10 uk-width-medium-1-1
        uk-width-small-1-1 ls-border" />
  <div class="uk-table">

      <?php

      $didTitle = FALSE;
      $didTable = FALSE;

      foreach ( $stats as &$line ) :
          $titleValue = explode(':', $line);

          if ( count($titleValue) != 2 ) :

              if ( $didTitle != FALSE ) {
                  continue;
              }

      ?>

      <p>
        Unexpected data (count: <?php echo count($titleValue); ?>
        line: <?php echo $line; ?>):
        <br />

        <?php print_r($stats); ?>

      </p>

      <?php

              break;
          endif;

          if ( $didTitle == FALSE ) :
              $didTitle = TRUE;

      ?>

      <table>
        <tr>
          <th>Variable</th>
          <th>Value</th>
        </tr>

        <?php

              $didTable = TRUE;
          endif;

        ?>

        <tr>
          <td><?php echo $titleValue[0]; ?></td>
          <td><?php echo $titleValue[1]; ?></td>
        </tr>

      <?php

      endforeach;

      if ( $didTable == TRUE ) :

      ?>

      </table>

      <?php endif; ?>

  </div>
</div>

<?php endif; ?>

<button name="do" type="submit" value="main"
        class="uk-button uk-button-muted uk-margin uk-margin-large
          uk-width-medium-1-10 uk-width-small-1-5"
>
  Back
</button>
