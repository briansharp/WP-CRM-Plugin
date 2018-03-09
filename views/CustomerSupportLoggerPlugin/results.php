<?php if ($data instanceof stdClass) : ?> 
	<article class="span8">
		<ul class="list-blog">
	<?php
		//dr:date range; sd:start date; ed:end date; cust:customer; tech:tech; st:supType; sb:sort by; sd:sort direction; bill:bill
		   
		$thisday = date("d");
		$thismonth = date("m");
		$thisyear = date("Y");
		$edate = date("Y-m-d");
		$edate = date('Y-m-d', strtotime($edate . "+1 days"));
		   
		$daterange = $_POST['dr'];
		switch ($daterange) {
			case "Specific Dates":
				$sdate = date("Y-m-d", strtotime($_POST['sd'] ));
				$edate = date("Y-m-d", strtotime($_POST['ed'] ));
				$edate = date('Y-m-d', strtotime($edate . "+1 days"));
				break;
			case "This Week":
				$sdate  = date("Y-m-d", strtotime("last Monday"));
				break;
			case "Last Week":
				$sdate  = date("Y-m-d", strtotime("last Monday -1 week"));
				$edate = (date('D') === 'Mon' ? date("Y-m-d") : date("Y-m-d", strtotime("last Monday")));			
				break;
			case "This Month":
				$sdate = $thisyear . '-' . $thismonth . '-01';
				break;
			case "Last Month":
				$sdate = date("Y-m-j", strtotime("first day of previous month"));
				$edate = $thisyear . '-' . $thismonth . '-01';//for some reason it has to be an extra day.. so this line works better than the former
				break;
			case "This Year":
				$sdate = $thisyear . '-01-01';
				break;
			case "Last Year":
				$sdate = ($thisyear - 1) . '-01-01';
				$edate = ($thisyear) . '-01-01';
				break;
			case "Last_16-EOM":
				$sdate = date("Y-m-j", strtotime("first day of previous month"));
				$sdate = date('Y-m-d', strtotime($sdate . "+15 days"));
				$edate = $thisyear . '-' . $thismonth . '-01';
				break;
			case "This_1-15":
				$sdate = $thisyear . '-' . $thismonth . '-01';
				$edate = $thisyear . '-' . $thismonth . '-16';
				break;
			case "All Time":
				break;   
			default: 
				break;   			
		} 
				
				
		$cust_id = $_POST['cust'];
		$tech_id = $_POST['tech'];
		$support_id = $_POST['st'];
		$sortby = $_POST['ob'];
		$sortdirection = $_POST['od'];
		$billable = $_POST['bill'];
		$remote = $_POST['rem'];
		$groupby = $_POST['gb'];
		$ss = $_POST['ss'];		
		
		global $wpdb;
		$tbl_events = $wpdb->prefix . "events";
		$tbl_customerlocation = $wpdb->prefix . "customerlocation";
		$tbl_customers = $wpdb->prefix . "customers";
		$tbl_supportmembers = $wpdb->prefix . "issupportmembers";
		$tbl_supporttype = $wpdb->prefix . "supporttype";	
		
		$q_string = "SELECT ev.id, ev.techid, ev.isremote, ev.timein, ev.timeout, ev.subject, ev.details, ev.requestedby, ev.isbillable, cus.customername, sup.supporttype, loc.customerlocation FROM $tbl_events AS ev ";
		$q_string .= " LEFT JOIN $tbl_customers AS cus ON ev.customerid = cus.id ";
		$q_string .= " LEFT JOIN $tbl_customerlocation AS loc ON ev.locationid = loc.id ";
		$q_string .= " LEFT JOIN $tbl_supporttype AS sup ON ev.supporttypeid = sup.id ";
			
			
		$q_hasval = false;
		$q_where = " 1 ";
			
		$q_dr = "";
		if (isset($sdate) && $sdate != "") {$q_dr = " ev.timein >= '" . $sdate . "' AND ev.timeout <= '" . $edate ."' "; }
		if ($q_dr != ""){ $q_hasval = True; $q_where = $q_dr;}
	
		$q_tec = "";
		if ($tech_id !== "-1" ) {$q_tec = " ev.techid = " . $tech_id ;}
		if ($q_tec !== "" && !$q_hasval){ $q_hasval = True; $q_where = $q_tec;}
		elseif ($q_tec  !== "" && $q_hasval){$q_where .= " AND " . $q_tec;}		

		$q_customer = "";	
		if ($cust_id !== "-1" ) {$q_customer = " ev.customerid = " . $cust_id ;}
		if ($q_customer !== "" && !$q_hasval){ $q_hasval = True; $q_where = $q_customer;}
		elseif ($q_customer !== "" && $q_hasval){$q_where .= " AND " . $q_customer;}
		
		$q_sup = "";	
		if ($support_id !== "-1") {$q_sup = " ev.supporttypeid = " . $support_id ;}
		if ($q_sup !== "" && !$q_hasval){ $q_hasval = True; $q_where = $q_sup;}
		elseif ($q_sup !== "" && $q_hasval){$q_where .= " AND " . $q_sup;}
			
		$q_billable;
		if ($billable == "b") { $q_billable = " ev.isbillable = 1 ";}
		if ($billable == "n") {$q_billable = " ev.isbillable = 0 ";}
		if ($billable != 'a' && !$q_hasval){ $q_hasval = True; $q_where = $q_billable;}
		elseif ($billable != 'a' && $q_hasval) {$q_where .= " AND " . $q_billable;}
		
		$q_location = "";
		if ($remote == "rem") {$q_location = " ev.isremote = 1 ";} 
		if ($remote == "ons") {$q_location = " ev.isremote = 0 ";}
		if ($q_location !== "" && !$q_hasval){ $q_hasval = True; $q_where = $q_location;}
		elseif ($q_location !== "" && $q_hasval){$q_where .= " AND " . $q_location;}
		
		$q_ss = "";	
		if (isset($ss) && $ss !== "") {$q_ss = " `details` LIKE '%" . $ss  . "%' ";}
		if ($q_ss !== "" && !$q_hasval){ $q_hasval = True; $q_where = $q_ss;}
		elseif ($q_ss !== "" && $q_hasval){$q_where .= " AND " . $q_ss;}
			
			
		if($q_hasval) {$q_string .= " WHERE " . $q_where;}  
		
		//add group by
		$group_result;	
		switch ($groupby){
			case "Customer":
				$group_result = " cus.customername ";   //  LoadEventsByCustomer(dt)
				break;
			case "Technician":
				$group_result = " ev.techid ";       //  LoadEventsOrderByFK(dt, Technicians.GetNames(), "technicianName")
				break;
			case "Support Type":
				$group_result = " sup.supporttype "; //  LoadEventsOrderByFK(dt, Support.GetNames(), "supportType")
				break;
			case "Billable":
				$group_result = " ev.isbillable ";   //  LoadEventsOrderByFK(dt, myList, "Billable")
				break;
			case "Remote | Onsite":
				$group_result = " ev.isremote ";     //  LoadEventsOrderByFK(dt, myList, "Remote")
				break;
		}
		$q_string .=  " ORDER BY " . $group_result;	

		//add ORDER BY
		$sort_result;
		switch ($sortby){
			case "Customer":
				$sort_result = " cus.customername ";
				break;
			case "Date":
				$sort_result = " ev.timein ";
				break;
			case "Technician":
				$sort_result = " ev.techid ";
				break;
			case "Support Type":
				$sort_result = " sup.supporttype ";
				break;
			case "Billable":
				$sort_result = " ev.isbillable ";
				break;
		}
		$q_string .= ", " . $sort_result;				

		//add sort direction
		if ($sortdirection == "Ascending") {$q_string .= " ASC ";} 
		else {$result = $q_string .= " DESC ";}

			
		// this will get the data from your table
		$retrieve_data = $wpdb->get_results( $q_string );
		
		//  count rows and display message if 0
		if ($wpdb->num_rows == '0'){
			echo "<h2>No results for date range: </h2> <em style='color:#304F68;'><h2>" . $sdate . "&nbsp;&nbsp; through &nbsp;&nbsp;" . date('Y-m-d', strtotime($edate . "-1 days")) . "</h2></em>";
		}
		
		
		$previous_row;
		$totalgroupminutes = 0;
		$overallgroupminutes = 0;
		
					
		function formattimebymins($tmins){
			if ($tmins == 0) {return '0 Mins';}
			$resulttime = "";
			$thours = gethours($tmins); 
			$tmins =  remainingminutes($tmins);
			if (($thours >= 2) && ($tmins == 0)){$resulttime = $thours . " Hrs ";} 
			elseif (($thours >= 2) && ($tmins > 0)){$resulttime = $thours . "h ";} 
			elseif ($thours == 1){$resulttime = "1 Hr "; }
			if (($tmins != 0) && ($thours == 0)){$resulttime .= $tmins . " Mins "; }
			elseif (($tmins != 0) && ($thours != 0)){$resulttime .= $tmins . "m "; }
			return $resulttime;
		}
		function gethours( $tmins){			 
			return floor($tmins/60);
		}
		function remainingminutes( $tmins){
			$remainingmins = $tmins - gethours($tmins)*60; 
			return  $remainingmins + ((15 - ($remainingmins % 15)) % 15);
		}
				
		foreach ($retrieve_data as $row){ 
			$eventid = $row->id;
			$user_info = get_userdata($row->techid);
			$un = $user_info->first_name . " ". $user_info->last_name;
			$time_in = $row->timein;
			$time_out = $row->timeout;
			$f_time_in = date('g:i a \- D, M jS, Y', strtotime($time_in));
			$f_time_in = str_replace( '12:00 pm','Noon &nbsp;', $f_time_in);
			$f_time_out = date('g:i a \- D, M jS, Y', strtotime($time_out));
			$f_time_out = str_replace( '12:00 pm','Noon &nbsp;', $f_time_out);
			$remotetype = "onsite";
			$remotetype2 = "Onsite";
			if ($row->isremote == true){
				$remotetype = "remotely";
				$remotetype2 = "Remote";
			}
			$billabletype = "billable";
			if (!$row->isbillable){$billabletype = "Non-Billable";}
			$cusname = $row->customername;
			$subject = stripslashes($row->subject);
			$details = stripslashes($row->details);		
		
			$finaltime = "";
			$a =  date("Y-m-d H:i:s", strtotime("$row->timein"));
			$b =  date("Y-m-d H:i:s", strtotime("$row->timeout"));
			$start_date = new DateTime($a,new DateTimeZone('Pacific/Nauru'));
			$end_date = new DateTime($b, new DateTimeZone('Pacific/Nauru'));
			$interval = $start_date->diff($end_date);
			$hours   = $interval->format('%h'); 
			$minutes = $interval->format('%i');
			$diff_minutes = ($hours * 60 + $minutes);
			$finalhours = floor($diff_minutes/60);
			$finalminutes = remainingminutes($diff_minutes); 
			if ($finalminutes == 60){$finalhours += 1; $finalminutes = 0;};
			$finaltime = formattimebymins($finalminutes + $finalhours*60);	
			  
			$overallgroupminutes += $finalminutes + $finalhours*60;

			
			
		switch ($groupby){
			case "Customer":
				$field_name = 'customername';
				$field_title = $row->$field_name;
				break;
			case "Technician":
				$field_name = 'techid';
				$field_title = $un;
				break;
			case "Support Type":
				$field_name = 'supporttype';
				$field_title = $row->$field_name;
				break;
		}		
				
		if (!isset($previous_row)){
			?>	<li><div class="wrapper-extra">
					<div class="badge" style="width:85px;"><span id="<?php echo $row->$field_name . '">' . $finaltime ?> </span>Total</div><h2 style="margin-top: 10px;"><?php echo $field_title; ?></h2>				
				</div>`</li>
			<?php
			$totalgroupminutes = $finalminutes + $finalhours*60 ;
		}
		elseif ($row->$field_name == $previous_row->$field_name){
			$totalgroupminutes += $finalminutes + $finalhours*60 ;
			?>
			<!--set last group heading (total hours)..  this is only here for last loop if there are more than 1 item in last group -->
			<script type="text/javascript">
				document.getElementById("<?php echo $previous_row->$field_name; ?>").innerHTML = '<?php echo formattimebymins($totalgroupminutes) ; ?>';
			</script>
			<?php
		}
		elseif ($row->$field_name != $previous_row->$field_name){
			$timeresult = formattimebymins($totalgroupminutes);
		?>	
			<!--set last group heading (total hours) -->
			<script type="text/javascript">
				document.getElementById("<?php echo $previous_row->$field_name; ?>").innerHTML = '<?php echo $timeresult;  ?>';
			</script>					
			<!--create new heading badge (total hours) -->
			<li style="margin-top:20px; border-top:1px solid silver;">
				<div class="wrapper-extra">
					<div class="badge" style="width:80px;"><span id="<?php echo $row->$field_name; ?>"><?php echo $finaltime; ?> </span>Total</div><h2 style="margin-top: 10px;"><?php echo $field_title; ?></h2>				
			</div></li>					
		<?php
		    $totalgroupminutes = $finalminutes + $finalhours*60;
		  }
		?>

		<li>	
			<div class="wrapper-extra">
				<div class="extra-wrap">
					<span class="font14">					   		
					<time class="badge time-span-badge" datetime=""><?php echo "<span> $finaltime </span>" ?></time>
						<?php echo "$cusname: <span style='color:#494B82;text-decoration:underline;'><em>$remotetype2</em></span> " . $row->supporttype . " issue" ?> 				
					</span>
					<div class="fleft"><span class="dis-block">Handled by <a ><?php echo  $user_info->first_name . " ". $user_info->last_name; ?></a></span></div>
				</div>
			</div>
			<div class="block-blog">
				<figure class="img-polaroid">
					 <?php echo get_avatar( $user_info, 50 ); ?>
				</figure>
				<div class="extra-wrap">
					<p>
						  &nbsp;&nbsp;<strong>&bull;</strong> Starting: <em style="color:#304F68";"><?php echo $f_time_in; ?></em><br>
						  &nbsp;&nbsp;<strong>&bull;</strong> Ending:&nbsp; <em style="color:#304F68";"><?php echo $f_time_out; ?></em></strong> 	
					</p>						   
					<p style="margin-left:10px;"><strong>&bull; &nbsp;details:&nbsp;</strong>&nbsp;<?php echo $details; ?> &nbsp;&nbsp;
					<a class="edit-event" onclick="crm_openEditTicket(false, <?php echo $eventid; ?> )">Edit this event</a></p>
				</div>
			</div>
		</li>
	
	<?php
			$previous_row = $row;
		}		
	?>
	
	</ul>
</article>
<?php endif; ?>