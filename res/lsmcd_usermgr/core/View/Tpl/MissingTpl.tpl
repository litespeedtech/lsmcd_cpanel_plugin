<?php

use \LsmcdUserPanel\View\Model\MissingTplViewModel as ViewModel;

$msg = $this->viewModel->getTplData(ViewModel::FLD_MSG);

?>

<div>
  <p>
    <?php echo $msg; ?>
  </p>
</div>
