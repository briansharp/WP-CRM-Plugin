<?php if ($data instanceof stdClass) : ?>

<script type="text/javascript">		
	jQuery('.frm_datepicker').on("load", calendar_popup());
	function calendar_popup() {
		jQuery('.datepicker').datepicker({
			dateFormat : 'M dd yy'
		});
	};
	
	function set_selected(id){
		document.getElementById(id).onclick = function (e) {
			if (!e) {e = event;  }
			var target = e.target || e.srcElement;
			if ('equal' == target.className && 'button' == target.type) {
				var defaultValue = target.parentNode.parentNode.getElementsByTagName('SELECT')[0].selectedIndex;
				for (var i = 0, selects = this.getElementsByTagName('SELECT'), selectsLength = selects.length; i < selectsLength; ++i) {
					selects[i].selectedIndex = defaultValue;
				}
			}
		};
	}
</script>

	<?php
		global $wpdb; 		
		$id = $data->ticketid;		
		$q_string = "SELECT ev.id, ev.locationid, ev.customerid, cus.customername, ev.techid, ev.isremote, ev.timein, ev.timeout, ev.subject, ev.details, ev.requestedby, ev.isbillable, sup.supporttype, loc.customerlocation FROM {$wpdb->prefix}events AS ev ";
		$q_string .= " LEFT JOIN {$wpdb->prefix}customers AS cus ON ev.customerid = cus.id ";
		$q_string .= " LEFT JOIN {$wpdb->prefix}customerlocation AS loc ON ev.locationid = loc.id ";
		$q_string .= " LEFT JOIN {$wpdb->prefix}supporttype AS sup ON ev.supporttypeid = sup.id ";
		
		$row = $wpdb->get_row( "$q_string WHERE ev.id = $id" );		
		$bill_type =  $row->isbillable;
		$user_info = get_userdata($row->techid);
		$rem_type =  $row->isremote;
		$time_in = $row->timein;
		$time_out = $row->timeout;  
	?>
	
<article class="span8 update-ticket">
	<ul class="list-blog"> 	

	<h2>Update Ticket:</h2>			

	<li class=" add-entry">	
	  <form method="post" name="frm_updateevent" id="frm_updateevent"  class="frm_datepicker">
		<div id="innerContent" style=" text-align:left;" class="fleft span4">									  
			<input name="txt_id" type="hidden" value="<?php echo $id ?>" />			
			<label for="sel_tech">Support Member</label>
			<select name="sel_tech" id="sel_tech" >
				<?php
					$sm_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}issupportmembers WHERE isactive=1;" );
					foreach ($sm_data as $r){
						$smid = $r->userid;
						$userlogin = get_userdata($smid)->user_login;
						echo "<option value='$smid' id='$smid' ".($user_info->user_login != $userlogin ?'':' selected')." >$userlogin</option>";
					}
				?>
				<script type="text/javascript">
					set_selected('sel_tech');
				</script>
			</select>

			<label for="sel_customer">Customer:</label>
			<select id="sel_customer" name="sel_customer" onchange="setlocations();" >
				<?php
					$retrieve_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}customers WHERE isactive=1;" );						
					foreach ($retrieve_data as $r){	
						$cid = $r->id;
						echo "<option value='$cid' id='$cid'".($cid!= $row->customerid?'':' selected').">$r->customername</option>";
					}
				?>					
			</select>
			<label for="sel_location">Customer Location:</label>
			<select id="sel_location" name="sel_location">
				<?php							
					$retrieve_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}customerlocation;" );						
					foreach ($retrieve_data as $r){	
						$cl = $r->customerlocation;
						echo "<option value='$r->customerid' class='show' ".($row->customerlocation==$cl?'selected':'').">$cl</option>";
					}
				?>
			</select>
			<script type="text/javascript">setlocations();</script>	  

			<label for="rb_service">Onsite | Remote:	</label>	
			<input type="radio" name="rb_service" class="add-options" value="0" <?php echo $rem_type || empty($rem_type)?"checked":"";?> > Onsite
			<input type="radio" name="rb_service" class="add-options" value="1" <?php echo !$rem_type && !empty($rem_type)?"checked":"";?>  > Remote<br><br>
			
			<label for="sel_suptype">Support Category</label>
			<select name="sel_suptype" id="sel_suptype" >
				<?php			
					$retrieve_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}supporttype  WHERE isactive=1" );
					foreach ($retrieve_data as $r){	
						echo "<option value='$r->id' id='$r->id' ".($r->supporttype==$row->supporttype?'selected':'')." >$r->supporttype</option>";
					}
				?>		
				<script type="text/javascript">
					set_selected('sel_suptype');
				</script>			      
			</select>
						
			<div id="panelin" class="pin" >
				<label for="start_date">Time In:</label>
				<div id="timein" class="time-picker">
					<?php 
						$hourin = date('g', strtotime($time_in));
						$min = date('i', strtotime($time_in));
						$ain = date('a', strtotime($time_in));
					?>					                       
					<input type="text" id="start_date" class="datepicker" name="start_date" value="<?php echo $time_in==0?"":date('M j Y', strtotime($time_in)); ?>">
					<select id="sel_hourin" name="sel_hourin" >
						<?php
							echo $time_in==0? "<option value='' selected></option>":"";
							for($i=1; $i<=12; $i++){
								 echo "<option value='$i' ".($time_in!=0 && $hourin==$i?"selected":"").">$i</option>";
							}
						?>
					</select>
					<select id="sel_minin" name="sel_minin" >
						<?php
							echo $time_in==0? "<option value='' selected></option>":"";
							for($i=0; $i<=55; $i+=5){
								echo "<option value=".sprintf('%02d', $i)." ".($time_in!=0 && $min==$i?"selected":"").">".sprintf('%02d', $i)."</option>";
							}
						?>		
					</select>
					<select id="sel_ampmin" name="sel_ampmin" >
						<option value=''></option>
						<option value="am" <?php echo $ain=='am'?'selected':""; ?>>am</option>
						<option value="pm" <?php echo $ain=='pm'?'selected':""; ?>>pm</option>
					</select>						
				</div> 
			</div>
			
			<div id="panelout" class="pout">
				<label for="end_date">Time Out:</label>
				<div id="timeout" class="time-picker">	
					<?php 
						$hourout = date('g', strtotime($time_out));
						$mout = date('i', strtotime($time_out));
						$aout = date('a', strtotime($time_out));
					?>
					<input type="text" id="end_date" name="end_date" class="datepicker" value="<?php echo $time_out==0?"":date('M j Y', strtotime($time_out));?>">
					<select id="sel_hourout" name="sel_hourout">
						<option value=''></option>
						<?php
							echo $time_out==0? "<option value='' selected></option>":"";
							for($i=1; $i<=12; $i++){
								echo "<option value='$i' ".($time_out!=0 && $hourout==$i?"selected":"").">$i</option>";
							}
						?>
						<script type="text/javascript">
							set_selected('sel_hourout');
						</script>
					</select>
					<select id="sel_minout" name="sel_minout" >
						<option value=''></option>
						<?php
							echo $time_out==0? "<option value='' selected></option>":"";
							for($i=0; $i<=55; $i+=5){ 
								echo "<option value=".sprintf('%02d', $i)." ".($time_out!=0 && $mout==$i?"selected":"").">".sprintf('%02d', $i)."</option>";
							}
						?>
					</select>
					<select id="sel_ampmout" name="sel_ampmout" >
						<option value=''></option>
						<option value="am" <?php echo $aout=='am'?'selected':''; ?>>am</option>
						<option value="pm" <?php echo $aout=='pm'?'selected':''; ?>>pm</option>
						<script type="text/javascript">
							set_selected('sel_ampmout');
						</script>
					</select>
				</div>
			</div>
			
			<label for="txt_contact">Requested By:</label>
			<input type="text" name="txt_contact" id="txt_contact" value="<?php echo $row->requestedby ?>" >

			<label for="rb_billable">Billable:	</label>	
			<input type="radio" name="rb_billable" class="add-options" value="1" <?php echo $bill_type||empty($bill_type) ? "checked":"" ?> > Billable
			<input type="radio" name="rb_billable" class="add-options" value="0" <?php echo !$bill_type && !empty($bill_type)?"checked":"";?> > Not Billable<br><br>

		</div><!--end inner content-->
		<div class="span6">
			<label for="txt_description">Issue Description:</label>                            
			<textarea name="txt_description" id="txt_description" style="width:inherit;height:120px;"><?php echo stripslashes($row->subject) ?></textarea>			
			<label for="txt_resolution">Resolution:</label>                            
			<textarea name="txt_resolution" id="txt_resolution"  style="width:inherit;height:120px;" ><?php echo stripslashes($row->details) ?></textarea> 					  
			<br><br>			
			<span class="fright">
				<input onclick="jQuery(this).parents('form:first').submit(validateUpdateEventForm() ? crm_updTicket : function(e){e.preventDefault()});" 
					type="submit" id="btn_update" value="Save Changes" name="updateevent" class="btn btn-1" >
			</span>
			<br>                 
		</div>
	  </form>
	</li>
	</ul>
</article>
			
<?php endif; ?>
