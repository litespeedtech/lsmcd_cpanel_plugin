<?php
use \LsmcdUserPanel\View\Model\StatsModel as ViewModel;

$stats = $this->viewModel->getTplData(ViewModel::FLD_STATS);
$user = $this->viewModel->getTplData(ViewModel::FLD_USER);
$server = $this->viewModel->getTplData(ViewModel::FLD_SERVER);

?>

<div class="uk-container">
  <p class="uk-text-large uk-margin-large-bottom">
    <?php echo 'Stats for Server: ' . $server; ?>
  </p>
</div>

<div class="uk-container">
  <h2 class="uk-margin-bottom-remove ls-text-bold ls-text-slateblue">
    <i class="uk-icon uk-icon-question ls-text-skyblue">&nbsp;</i>
    <?php echo 'User: ' . $user; ?>
  </h2>
  <hr class="uk-margin-top-remove uk-width-large-3-10 uk-width-medium-1-1
        uk-width-small-1-1 ls-border" />
  <div class="uk-table">
    <p>
        <?php
        $didTitle = FALSE;
        $didTable = FALSE;
        foreach ($stats as &$line)
        {
            $titleValue = explode(':', $line);
            if (count($titleValue) != 2)
            {
                if ($didTitle == FALSE)
                {
                    echo 'Unexpected data (count: ' . count($titleValue) .
                         ' line: ' . $line . '):<br>';
                    print_r($stats);
                    break;
                }
                continue; // Ignore it.
            }
            if ($didTitle == FALSE)
            {
                $didTitle = TRUE;

                echo '<table>' .
                     '  <tr>' .
                     '    <th>Variable</th>' .
                     '    <th>Value</th>' .
                     '  </tr>';
                $didTable = TRUE;
            }
            echo '  <tr>' .
                 '    <td>' . $titleValue[0] . '</td>' .
                 '    <td>' . $titleValue[1] . '</td>' .
                 '  </tr>';
        }
        if ($didTable == TRUE)
        {
            echo '</table>';
        }
        ?>
    </p>
  </div>
</div>


