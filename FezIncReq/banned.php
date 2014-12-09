<?php
if ($banned == '1')
{

// GET BAN REASON
	?><table class="alert"><tr><td><b>You have been Banned!</td></tr>
		<tr><td class="alertinner" align="center">It appears you have been banned for not following the TOS.
				<br><br>
				If you think this action was unfair, please contact the site administrator where they will investigate this case further.
			</td>
		</tr>
	</table><br />
	<?php
	include_once Fez\LAYOUT_ROOT . 'footer.php';
	die();
}
?>