<?php
	require_once(dirname(__FILE__)."/class.sraAuth.php");
	
	$sra = new SRAConnect("http://www.studentradio.org.uk/api/v1");

	var_dump($sra->get_stations());
	if($_POST) {
		echo $sra->login($_POST['username'], $_POST['password']);
}
if ($sra->loggedin===true):
	echo "<pre>";
	echo var_dump($sra->get_user_by_auth());
	echo "</pre>";

endif;
if ($sra->loggedin === false):
?>
<form method="post">
	<input name="username" />
	<input name="password" type="password" />
	<input type="submit" />
</form>

<?php
else:
	echo "You are already logged in";
endif;

var_dump($_COOKIE);