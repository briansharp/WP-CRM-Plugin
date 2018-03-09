<?php if ($data instanceof stdClass) : 
			
	// load recent events from database	
	global $wpdb; 
		
	//get page
	isset($data->page)?$pg=$data->page:$pg=1;
	
	//get filters
	$filters = "";
	if (isset($_POST['filters'])  && $_POST['filters'] != "-1" && $_POST['filters'] != ""){
		$filters = " WHERE ".$_POST['filters'];
		$filters = str_replace(":", " = ", $filters);
		$filters = str_replace(",", " AND ", $filters);
	}
	$data->filters = $filters;
	
	$lastpage = ($wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}events $filters" )) / 10;
	
	//figure offset
	$offset = ($pg - 1) . '0';
		
	// this will get the data from table
	$q_string  = "SELECT ev.id, ev.locationid, ev.customerid, cus.customername, ev.techid, ev.isremote, ev.timein, ev.timeout, ev.subject, ev.details, ev.requestedby, ev.isbillable, sup.supporttype, loc.customerlocation FROM {$wpdb->prefix}events AS ev ";
	$q_string .= " LEFT JOIN {$wpdb->prefix}customers AS cus ON ev.customerid = cus.id ";
	$q_string .= " LEFT JOIN {$wpdb->prefix}customerlocation AS loc ON ev.locationid = loc.id ";
	$q_string .= " LEFT JOIN {$wpdb->prefix}supporttype AS sup ON ev.supporttypeid = sup.id ";
	$q_string .= " $filters order by timein DESC Limit 10 OFFSET  $offset";
		
	$retrieve_data = $wpdb->get_results("$q_string");
	
		$imageUrl = cttPluginMain::getPluginUrl(). '/views/CustomerSupportLoggerPlugin/images/';
?>
	<style>
	#pagination #lbPrev{
		background-image:url('<?php echo $imageUrl ?>triangle-left.png'),
						 url('<?php echo $imageUrl ?>triangle-left.png');
		background-repeat:no-repeat, 
						  no-repeat;
		background-position:2px center, 18px center;				  
	}
	#pagination #lblPr{
		background-image:url('<?php echo $imageUrl ?>triangle-left.png');background-repeat:no-repeat;background-position:2px center;}
	#pagination #lbNext{
	background-image:url('<?php echo $imageUrl ?>triangle-right.png');background-repeat:no-repeat;background-position:right center;}
		
	#pagination #lbLast{
		background-image:url('<?php echo $imageUrl ?>triangle-right.png'),
						 url('<?php echo $imageUrl ?>triangle-right.png');
		background-repeat:no-repeat, 
						  no-repeat;
		background-position:55px center, 39px center;				  
	}	
	#pagination input {border:none;margin:3px 15px 0px 3px;border:1px solid silver; border-radius:3px;height:25px;}
		#pagination #lbPrev{padding-left:45px;}
		#pagination #lblPr{padding-left:29px;}
		#pagination #lbNext{padding-right:29px;}
		#pagination #lbLast{padding-right:45px;}
		
	</style>
	
	<article class="span8">
		<ul class="list-blog">
			<li>
				<h2>Tickets - As of<date> <?php echo date('F j, Y'); ?></date></h2>	<br>
				<div class="wrapper-extra">
					<div id="pagination" >
						<input type="button" id="lbPrev" value="first" <?php if ($pg == 1){echo 'disabled';} ?> onclick="crm_viewTickets(false,<?php echo $filters==''?'-1': "'".$_POST['filters']."'"  ?>)" />                            
						<input type="button" id="lblPr" value="prev" <?php if ($pg == 1){echo 'disabled';} ?> onclick="crm_viewTickets(false,<?php echo $filters==''?'-1':  "'".$_POST['filters']."'"  ?>, <?php echo $pg - 1 ; ?>)" />
						<input type="button" id="lbNext" value="next" <?php if (ceil($lastpage) == $pg){echo 'disabled';} ?> onclick="crm_viewTickets(false,<?php echo $filters==''?'-1':  "'".$_POST['filters']."'"  ?>, <?php echo $pg + 1 ; ?>)" />
						<input type="button" id="lbLast" value="last" <?php if (ceil($lastpage) == $pg){echo 'disabled';} ?> onclick="crm_viewTickets(false,<?php echo $filters==''?'-1':  "'".$_POST['filters']."'"  ?>, <?php echo ceil($lastpage)  ?>)" />
					</div>  
				</div>	
			</li>
<?php
	foreach ($retrieve_data as $row){
	$id = $row->id;
	$billable_type =  $row->isbillable ? "billable" : "non-billable";
	$user_info = get_userdata($row->techid);
	$user_name = $user_info->first_name . " ". $user_info->last_name;
	$remote_type =  $row->isremote ? "Remotely:" : "On Site:";
	$time_in =  $row->timein;

	$month = date('M', strtotime($time_in));
	$my_day = date('j', strtotime($time_in));
	$year = date('Y', strtotime($time_in));

	$a =  date("Y-m-d H:i:s", strtotime("$row->timein"));
	$b =  date("Y-m-d H:i:s", strtotime("$row->timeout"));
	$start_date = new DateTime($a,new DateTimeZone('Pacific/Nauru'));
	$end_date = new DateTime($b, new DateTimeZone('Pacific/Nauru'));
	$interval = $start_date->diff($end_date);
	$hours   = $interval->format('%h'); 
	$minutes = $interval->format('%i');
	$diff_minutes = ($hours * 60 + $minutes);
	$diff_quarterhour = $diff_minutes + ($diff_minutes % 15);
	$finalhours = floor($diff_minutes/60);
	$finalminutes = $diff_minutes - $finalhours*60; 
	$finalminutes = $finalminutes + ((15 - ($finalminutes % 15)) % 15);
	if ($finalminutes == 60){$finalhours += 1; $finalminutes = 0;};
	$finaltime = "";
	if ($finalhours >= 1){$finaltime = $finalhours . "h ";}
	elseif ($finalminutes > 0){$finaltime .= $finalminutes . "m";}
	else {$finaltime = "0 mins";}		
?>			
			<li>	
				<div class="options" id="delete_<?php echo $id ?>">
					<h3>Confirm Delete?</h3>
					<input type="button" value="No, Just Kidding" class="btn btn-1" onclick="hideOptions('<?php echo $id ?>');" />
					<form method="post">
						<input onclick="jQuery(this).parents('form:first').submit(crm_delTicket(<?php echo $filters==''||$filters=='-1'? "{$id}, {$pg}, '-1'": "{$id}, {$pg}, '{$_POST['filters']}'"  ?>))" type="submit" id="btnDelCus" value="Yes, Delete Event" class="btn btn-1" >
						<input type="hidden" name="deleteevent" value="<?php echo $id ?>" >
					</form>
				</div>
				<div class="datarow" id="row_<?php echo $id ?>" >
				  <div class="wrapper-extra">
					<time class="badge" datetime="<?php echo $time_in ?>"><?php 
					 echo date('Y')==$year?"<span> $my_day </span> $month":"<span> $month </span> $year" ?>
					 </time>
					<time class="badge time-span-badge" datetime=""><?php echo "<span> $finaltime </span>" ?></time>
					<div class="extra-wrap">
						<span class="font14">
							<?php echo stripslashes($row->requestedby).", of $row->customername, contacted us regarding a $billable_type $row->supporttype issue. ";   ?> 				
						</span>
						<div class="fleft">
						  <span class="dis-block">Handled by <strong><a href="#"><?php echo $user_name ?></a></strong></span> 
						</div>
					  </div>
				   </div>
				   <div class="block-blog">
					 <figure class="img-polaroid">
						 <?php  echo get_avatar( $user_info, 100 ); ?>
					 </figure>
					 <div class="extra-wrap">
						<p>
						<?php echo "Addressed <strong>$remote_type </strong><br>
						  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>&bull;</strong> Starting: ".date(' g:i a \o\n D, M jS, Y', strtotime($time_in))."<br>
						  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>&bull;</strong> Ending:&nbsp; ".date(' g:i a \o\n D, M jS, Y', strtotime($row->timeout))."</strong> " ?>	
					   </p>
					   
					   <div><strong>Here's the issue:</strong>
						<p style="margin-left:100px;"><?php echo stripslashes($row->subject) ?></p>
					   </div>

					   <div><strong>This is how we fixed it:</strong>
						 <p><?php echo stripslashes($row->details) ?></p>
					   </div>
						
						<span class="fright">
							<input type="button" id="btn_delete" class="btn btn-1" value="Delete Event" onclick="showOptions('<?php echo $id ?>');" />
							&nbsp;&nbsp;
							<input type="button" class="btn btn-1 fright" value="Edit This Event" 
								onclick='crm_openEditTicket(false, <?php echo $row->id ?>)' />
						</span>		
					 </div>
				   </div>
			   </div>	
			</li>
<?php 
	}
?>
			<li>
				<div class="wrapper-extra">		
					<div id="pagination" >
						<input type="button" id="lbPrev" value="first" <?php if ($pg == 1){echo 'disabled';} ?> onclick="crm_viewTickets(false,<?php echo $filters==''?'-1': "'".$_POST['filters']."'"  ?>)" />                            
						<input type="button" id="lblPr" value="prev" <?php if ($pg == 1){echo 'disabled';} ?> onclick="crm_viewTickets(false,<?php echo $filters==''?'-1':  "'".$_POST['filters']."'"  ?>, <?php echo $pg - 1 ; ?>)" />
						<input type="button" id="lbNext" value="next" <?php if (ceil($lastpage) == $pg){echo 'disabled';} ?> onclick="crm_viewTickets(false,<?php echo $filters==''?'-1':  "'".$_POST['filters']."'"  ?>, <?php echo $pg + 1 ; ?>)" />
						<input type="button" id="lbLast" value="last" <?php if (ceil($lastpage) == $pg){echo 'disabled';} ?> onclick="crm_viewTickets(false,<?php echo $filters==''?'-1':  "'".$_POST['filters']."'"  ?>, <?php echo ceil($lastpage)  ?>)" />
					</div> 
				</div>	
			</li>

		</ul>
	</article>
<?php endif; ?>

