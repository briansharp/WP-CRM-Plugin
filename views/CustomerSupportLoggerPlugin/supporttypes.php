<?php if ($data instanceof stdClass && $data->security == true) : ?>
<article class="span8">
	<ul class="list-blog">  
		<li>	        
			<h2 class="">Support Categories - As of <date><?php echo date('F j, Y'); ?></date></h2>
		</li>	
		<li id="innerContent">			  
			  <ul>
				<li>	
					<form method="post" name="frm_addsupport" action="<?php plugin_dir_path( __FILE__ ) . '../../classes/cttPluginEvents.php' ?>">
						<label for="tbx_newcategory">Add New Category</label>
						<input type="text" name="newsupportitem" id="tbx_newcategory"  class="txt_input" >
						<input onclick="jQuery(this).parents('form:first').submit(crm_addSupport)" type="submit" name="addsupport" value="Create New Item" class="btn btn-1" >
					</form>					
					<div class="row"><article class="span8"><div class="divider-2"></div></article></div> 
				</li>
			  </ul>			  
			  <ul>
				<li><h2>Categories</h2></li>
				<style type="text/css">
				  .hideme {display:none;}			
				</style>

				<?php
				global $wpdb; 
				$tbl_supporttype = $wpdb->prefix . "supporttype";
				$retrieve_data = $wpdb->get_results( "SELECT * FROM $tbl_supporttype ORDER BY id DESC;" );	
				foreach ($retrieve_data as $row){	
					$id = $row->id;
					$st = $row->supporttype;
				?>			
				<li>	
					<form method="post" name="frm_editsupport">
						<input type="text" name="txt_support" id="txt_<?php echo $id;?>" value="<?php echo $st;?>"  disabled>	&nbsp;
						<input type="checkbox" name="supportisactive" id="cb_<?php echo $id;?>" value="<?php if ($row->isactive) {echo 'active';}?>" <?php if ($row->isactive) {echo 'checked';}?> disabled> Enabled &nbsp;&nbsp;
						<input onclick="crm_getView('supporttypes')" type="button" value="Cancel" id="cancel_<?php echo $id;?>" class="hideme" >
						<input onclick="jQuery(this).parents('form:first').submit(crm_rnmSupport)"type="submit" name="updatesupport" value="Save" id="save_<?php echo $id;?>"  class="hideme">
						<input onclick="jQuery(this).parents('form:first').submit(crm_delSupport)" type="submit" name="deletesupport" value="Confirm Delete" id="confirmdelete_<?php echo $id;?>"  class="hideme">
						<input type="button" name="rename_support" value="edit" id="edit_<?php echo $id;?>" onclick="support_edit_click('<?php echo $id;?>')">
						<input type="button" name="delete_support" value="Delete" id="delete_<?php echo $id;?>" onclick="support_delete_click('<?php echo $id;?>')">
						<input type="hidden" name="supportid" value="<?php echo $id;?>">
					</form>
				</li>		
				<?php		
				}
				?>
					<div class="row"><article class=8"><div class="divider-2"></div></article></div> 
			  </ul>	
		</li><!--end inner content-->
	</ul>
</article>
<?php endif; ?>
    
    
