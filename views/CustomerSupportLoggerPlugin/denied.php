<?php if ($data instanceof stdClass) : ?>
<?php echo cttPluginMain::getView('CustomerSupportLoggerPlugin' . DIRECTORY_SEPARATOR . 'pagetop.php', $data); 


 
?>

	
		<li><h2>Permission Denied - Please see Administrator</h2></li>



<?php echo cttPluginMain::getView('CustomerSupportLoggerPlugin' . DIRECTORY_SEPARATOR . 'pagebottom.php', $data); ?>


<?php
if ( is_user_logged_in() ) {
?>
<script>
window.location="http://www.spaceportimaging.com/index.php/logs/?logaction=events";
</script>
<?php
} else {
?>
<script>
window.location="http://www.spaceportimaging.com/";
</script>
<?php
}
?>


<?php endif; ?>
