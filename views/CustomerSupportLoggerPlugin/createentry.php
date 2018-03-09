<?php if ($data instanceof stdClass && $data->security == true) : 



global $wpdb; 
?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#start_date').datepicker({
			dateFormat : 'M dd yy'
		});
	});
	jQuery(document).ready(function() {
		jQuery('#end_date').datepicker({
			dateFormat : 'M dd yy'
		});
	});
</script>

<article class="span8">
	<ul class="list-blog">  
 
		<li>
			<h2>New Ticket - <date><?php echo date('F j, Y'); ?></date></h2>					
		</li>				
		
		<li class="add-entry">
			<form method="post" name="frm_createevent" onsubmit="return validateForm()" >
				<article class="span4">				
                    <label for="sel_customer">Customer:</label>
                    <select id="sel_customer" name="sel_customer" onchange="setlocations();" >
						<?php							
							$tbl_cust = $wpdb->prefix . "customers";
							$retrieve_data = $wpdb->get_results( "SELECT * FROM $tbl_cust WHERE isactive=1;" );						
							foreach ($retrieve_data as $row){	
								$id = $row->id;
								$st = $row->customername;
								echo "<option value='$id'  >$st</option>";
							}
						?>					
					</select>

                    <label for="sel_location">Customer Location:</label>
                    <select id="sel_location" name="sel_location">
						<?php							
							$tbl_loc = $wpdb->prefix . "customerlocation";
							$retrieve_data = $wpdb->get_results( "SELECT * FROM $tbl_loc;" );						
							foreach ($retrieve_data as $row){	
								$id = $row->customerid;
								$st = $row->customerlocation;
								echo "<option value='$id' class='show'>$st</option>";
							}
						?>	
						<option value=""><option>
					</select>
					<script>setlocations();</script>
                    
                    <label for="txt_contact">Customer Contact:</label>
                    <input type="text" id="txt_contact" name="txt_contact">
					
				</article>
				<article class="span3">
				
                    <label for="sel_tech">Technician Name:</label>
                    <select id="sel_tech" name="sel_tech" >
						<?php 
							$tbl_sm = $wpdb->prefix . "issupportmembers";
							$retrieve_data = $wpdb->get_results( "SELECT * FROM $tbl_sm WHERE isactive=1;" );	
							foreach ($retrieve_data as $row){	
								$id = $row->userid;
								$user_info = get_userdata( $id );
								$un = $user_info->user_login;
								echo "<option value='$id' ";
								if ($id == get_current_user_id()) {echo 'selected';}
								echo ">$un</option>";
							}
						?>
					</select>

                    <label for="ddlSupType">Support Type:</label>
                    <select id="ddlSupType" name="ddlSupType" >
						<?php
							$tbl_supporttype = $wpdb->prefix . "supporttype";
							$retrieve_data = $wpdb->get_results( "SELECT * FROM $tbl_supporttype WHERE isactive=1;" );	
							foreach ($retrieve_data as $row){	
								$id = $row->id;
								$st = $row->supporttype;
								echo "<option value='$id'>$st</option>";
							}
						?>
					</select>

                    <label for="rb_service">Onsite | Remote:	</label>	
					<input type="radio" name="rb_service" class="add-options" value="0" checked > Onsite
                    <input type="radio" name="rb_service" class="add-options" value="1"   > Remote<br><br>
 
                    <label for="cb_billable">Billable:	</label>
                    <input type="checkbox" name="cb_billable" value="Yes" class="add-options billable" checked  >Billable<br>
            
				</article> 
				
				<div class="row"><article class="span4"><div class="divider-2"></div></article></div>
				
                <div id="panelin" class="pin" >
					<label for="start_date">Time In:</label>
                    <div id="time-in" class="time-picker">
					                       
						<input type="text" id="start_date" name="start_date" value="<?php echo date('M d Y') ?>">
						<select id="sel_hourin" name="sel_hourin" >						
						<?php
							for($i=1; $i<=12; $i++){ echo "<option value=".sprintf($i).">".sprintf($i)."</option>";}
						?>
						</select>
                        <select id="sel_minin" name="sel_minin" >
						<?php
							for($i=0; $i<=55; $i+=5){ echo "<option value=".sprintf('%02d', $i).">".sprintf('%02d', $i)."</option>";}
						?>
						</select>
                        <select id="sel_ampmin" name="sel_ampmin" >
                            <option>am</option>
                            <option>pm</option>
                        </select>						
                    </div> 
                </div>
				
				<div id="panelout" class="pout">
					<label for="end_date">Time Out:</label>
                    <div id="time-Out" class="time-picker">					                       
						<input type="text" id="end_date" name="end_date" value="<?php echo date('M d Y') ?>">
						<select id="sel_hourout" name="sel_hourout">						
						<?php
							for($i=1; $i<=12; $i++){ echo "<option value=".sprintf($i).">".sprintf($i)."</option>";}
						?>
						</select>
                        <select id="sel_minout" name="sel_minout" >
						<?php
							for($i=0; $i<=55; $i+=5){ echo "<option value=".sprintf('%02d', $i).">".sprintf('%02d', $i)."</option>";}
						?>
						</select>
                        <select id="sel_ampmout" name="sel_ampmout" >
                            <option>am</option>
                            <option>pm</option>
                        </select>
                    </div>
                </div>
    					
				<div class="row"><article class="span7"><div class="divider-2"></div></article></div>
				
                <div>
                    <label for="txt_description">Issue Description: (Max 300 Characters)</label>
                    <textarea id="txt_description" name="txt_description" MaxLength="300" ></textarea> 

					<label for="txt_resolution">How did this issue get fixed?</label>
					<textarea id="txt_resolution" name="txt_resolution" ></textarea><br>
					
					<input type="submit" id="btn_save" value="Save Event" name="createevent"class="btn-1 btn fright" >
					<input type="button" value="Cancel" class="btn-1 btn fright">
					
                </div>
			</form>
		</li>
	</ul>
</article>

<?php endif; ?>