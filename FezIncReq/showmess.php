<?php

if (isset($_SESSION['ErrorMsg']))
{
	$ErrorMsg = $_SESSION['ErrorMsg'];
	unset($_SESSION['ErrorMsg']);
}
if (isset($ErrorMsg))
{
	echo '<br /><div class="ui-state-error">
	<p class="tCenter font3 padAll-narrow">
	' . nl2br($ErrorMsg) . '
	</p>
	</div><br />';
}

if (isset($_SESSION['SuccessMsg']))
{
	$msg = $_SESSION['SuccessMsg'];
	unset($_SESSION['SuccessMsg']);
}
if (isset($msg))
{
	echo '<br /><div class="ui-state-highlight">
	<p class="tCenter font3 padAll-narrow">
	' . nl2br($msg) . '
	</p>
	</div><br />';
}
?>