<?php

/**
 * Expects: $d['do']
 */

$headInfo = <<<EOF
<link rel="stylesheet" type="text/css" href="static/litespeed-custom.min.css">
<link rel="stylesheet" type="text/css" href="static/fontawesome-webfont.woff2">
EOF;

$title='<img src="static/icons/lsmcd_usermgr.png"> User Management of LSMCD';

$cpanelHeader = $this->cpanel->header($title);
$newHeader = str_replace('</head>', "{$headInfo}</head>", $cpanelHeader);

print $newHeader;

?>

<div id="lsmcd-container" class="uk-margin-large-bottom">
  <form name="lsmcdform">
