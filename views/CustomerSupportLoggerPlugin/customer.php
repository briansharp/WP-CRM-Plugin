<?php if ($data instanceof stdClass && $data->security == true) : ?>

<article class="span8">
	<ul class="list-blog">  		
		<li><h2 class="">Customers - As of <date><?php echo date('F j, Y'); ?></date></h2></li>
		
		<li class="add-customer">
		  <form method="post" name="frm_newcustomer" autocomplete="off">
			<label for="txt_newcustomer">Add New Customer</label>
			<input type="text" name="txt_newcustomer"  class="input" placeholder="Customer Name" >
			<input type="text" name="txt_newlocation" placeholder="Location (ie. Main Office)" >
			<input onclick="jQuery(this).parents('form:first').submit(crm_createCustomer)" type="submit" value="Create Customer" class="btn btn-1" >
		  </form>
		</li>
		<li><h2>&nbsp;&nbsp;&nbsp;&nbsp;Customers</h2></li>
		<?php							
			global $wpdb; 
			$tbl_cust = $wpdb->prefix . "customers";
			$retrieve_data = $wpdb->get_results( "SELECT * FROM $tbl_cust ORDER BY customername ASC;" );				
				
			foreach ($retrieve_data as $row){	
				$id = $row->id;
				$st = $row->customername;
				$active =  $row->isactive;
		?>	
		<li class="customers">		
			<ul>
				<form method="post" name="frm_editcustomer" autocomplete="off">
					<input class="customer-name" type="text" name="txt_customer" id="txt_<?php echo $id;?>" value="<?php echo $st;?>"  disabled><br>
					<input class="cbx" type="checkbox" name="customerisactive" id="cb_<?php echo $id;?>" value="<?php if ($row->isactive) {echo 'active';}?>" <?php if ($row->isactive) {echo 'checked';}?> disabled> Enabled &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input onclick="crm_getView('customer')" class="submit hideme" type="button" value="Cancel" id="cancel_<?php echo $id;?>" >
					<input onclick="jQuery(this).parents('form:first').submit(crm_rnmCustomer)" class="submit hideme" type="submit" value="Save" id="save_<?php echo $id;?>">
					<input onclick="jQuery(this).parents('form:first').submit(crm_delCustomer)" class="submit hideme" type="submit" value="Confirm Delete" id="confirmdelete_<?php echo $id;?>" >
					<input class="submit" type="button" name="rename_customer" value="edit" id="edit_<?php echo $id;?>" onclick="edit_click('<?php echo $id;?>')">
					<input class="submit" type="button" name="delete_customer" value="Delete" id="delete_<?php echo $id;?>" onclick="delete_click('<?php echo $id;?>')">
					<input type="hidden" name="customerid" value="<?php echo $id;?>">	
				</form>
					<br>
					<p class="location-item-title">Locations:</p>
					<?php							
						//get locations 
						$tbl_loc = $wpdb->prefix . "customerlocation";
						$retrieve_data_loc = $wpdb->get_results( "SELECT * FROM $tbl_loc WHERE customerid=$id;" );								
						foreach ($retrieve_data_loc as $row2){	
							$lid = $row2->id;
							$cl = $row2->customerlocation;
					?>
					<form method="post" name="frm_editlocation" autocomplete="off">							
					  <li class="location-item">
						<input class="txt" type="text" name="txt_location_li" id="txtlocation_<?php echo $lid;?>" value="<?php echo $cl;?>"  disabled>	&nbsp;
						<input onclick="jQuery(this).parents('form:first').submit(crm_delLocation)"  class="submit del-loc" type="submit" value="Remove " id="confirmdeletelocation_<?php echo $lid;?>"  >
						<input class="submit del-loc hideme" type="button" value="Cancel" id="cancelrename_<?php echo $lid;?>" onclick="crm_getView('customer')">
						<input class="submit del-loc" type="button" value="Rename " id="renamelocation_<?php echo $lid;?>"  onclick="renameloc_click('<?php echo $lid;?>')">
						<input onclick="jQuery(this).parents('form:first').submit(crm_rnmLocation)"  class="submit del-loc hideme" type="submit" name="updatelocation" value="Save New Name" id="confirmrenamelocation_<?php echo $lid;?>"  >
						<input type="hidden" name="locationid" value="<?php echo $lid;?>">
						<input type="hidden" name="customerid" value="<?php echo $id;?>">
					  </li>
					</form>  

				<?php } ?>

				  <form method="post"  name="frm_createlocation" id="frm_createlocation<?php echo $id;?>" autocomplete="off">
					<li class="location-item">
						<input class="txt" type="text" name="newlocation_li" >	&nbsp;
						<input type="hidden" name="parentid" value="<?php echo $id;?>">	
						<input onclick="jQuery(this).parents('form:first').submit(crm_createLocation)" class="submit del-loc"  type="submit" name="createlocation" value="Add location" >
					</li>
				  </form>
				  <div id="feedback<?php echo $id;?>"></div>
			</ul>	
		</li>		
		<?php } ?>
	</ul>
</article>
<?php endif; ?>