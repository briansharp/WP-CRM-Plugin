<?php if ($data instanceof stdClass) : 
	echo '<div id="crm_plugin">';
	if ($data->view == "dashboard.php"){
		echo cttPluginMain::getView('CustomerSupportLoggerPlugin' . DIRECTORY_SEPARATOR . $data->view, $data);
	} else{
?>			

    <div class="row" id="crm_top">
		<?php echo cttPluginMain::getView('CustomerSupportLoggerPlugin' . DIRECTORY_SEPARATOR . 'pagetop.php', $data); ?>

		<div  class="crm-inner-content">
			<?php echo cttPluginMain::getView('CustomerSupportLoggerPlugin' . DIRECTORY_SEPARATOR . $data->view, $data); ?>
		</div>			

				
		<?php echo cttPluginMain::getView('CustomerSupportLoggerPlugin' . DIRECTORY_SEPARATOR . 'sidebar.php', $data); ?>	 	
    </div><!-- end .row  -->	

    <div class="row"><article class="span12"><div class="divider-1"></div></article></div>
</div>
<?php } endif; ?>

