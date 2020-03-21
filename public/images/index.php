<?php
error_reporting( error_reporting() & ~E_NOTICE );
$page=htmlentities($_GET['page']);

if (empty($page)) {
	echo '<script language="javascript">alert("ERROR!!! Wrong page address.");document.location="https://'.$_SERVER['HTTP_HOST'].'"</script>';
}
?>