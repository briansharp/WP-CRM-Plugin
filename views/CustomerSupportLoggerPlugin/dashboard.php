<?php if ($data instanceof stdClass) : ?>	
  	  		
	<?php
		$current_user = wp_get_current_user();	
		global $wpdb; 
		$tbl_customers = $wpdb->prefix . "customers";
		
		$thisuid = get_current_user_id();
		$imageUrl = cttPluginMain::getPluginUrl(). '/views/CustomerSupportLoggerPlugin/images/';

		$q_openUserEvents = "SELECT  ev.id, ev.ticketcreated, ev.subject, ev.subject, ev.requestedby, cus.customername FROM {$wpdb->prefix}events as ev " 		;
		$q_openUserEvents .= " LEFT JOIN {$wpdb->prefix}customers AS cus ON ev.customerid = cus.id  WHERE ev.timeout = 0 AND ev.techid= $thisuid ;";
		
		$countEvents = $wpdb->get_var( "SELECT count(*) FROM {$wpdb->prefix}events WHERE `timeout` = 0 ;" );
		$countUserEvents = $wpdb->get_var( "SELECT count(*) FROM {$wpdb->prefix}events WHERE `timeout` = 0 AND `techid` = $thisuid;" );
	?>
	
	<style>
		#crm_plugin article.dashboard ul.thumbnails li.thumbnail{background-color:#f5f5f5; border-radius:4px;  margin-bottom:15px; margin-top:15px;}
		#crm_plugin article.dashboard ul.thumbnails li.thumbnail div.block-thumbnail{margin:10px;}
		#crm_plugin article.dashboard ul.thumbnails li.thumbnail div.user-items{/*background: url('<?php //site_url(); ?>/wp-content/uploads/2016/07/dashboard_icons.png') no-repeat  center 20px;background-size: 60% ;*/}
		
		#crm_plugin article.dashboard ul.thumbnails li.thumbnail .alert{background-color: #fcf8e3; margin: 0px 0 12px 0 ; padding: 4px 6px;line-height:normal;}
		#crm_plugin article.update-ticket  input.alert{background-color: #fcf8e3; margin: 0px 0 12px 0 ; padding: 4px 6px;line-height:normal;}
		#crm_plugin article.update-ticket ul.thumbnails li.thumbnail #txt_description.alert{margin-bottom: 20px;line-height:24px;}
		


		#crm_plugin article.dashboard ul.thumbnails li.create-ticket input.init-ticket{margin-left:10px;}
		#crm_plugin article.dashboard ul.thumbnails li.manage-customers{}
		#crm_plugin article.dashboard ul.thumbnails li.run-reports{}
		
		
		#crm_plugin li.dashboard-links li.quick-links a{font-size:20px; text-decoration:none;line-height:40px;}
		#crm_plugin li.dashboard-links li.quick-links a:hover{text-decoration:underline; cursor:pointer;}
		#crm_plugin li.dashboard-links li.dashboard span{background: url('<?php echo $imageUrl ?>dashboard.svg') no-repeat  center ;background-size: 70%;overflow:visible;height:25px; width:40px;}
		#crm_plugin li.dashboard-links li.tickets span{background: url('<?php echo $imageUrl ?>ticket.svg') no-repeat  center ;background-size: 70%;overflow:visible;height:25px; width:40px;}
		#crm_plugin li.dashboard-links li.add-ticket span{background: url('<?php echo $imageUrl ?>ticket.png') no-repeat  center ;background-size: 70%;overflow:visible;height:25px; width:40px;}
		#crm_plugin li.dashboard-links li.customers-link span{background: url('<?php echo $imageUrl ?>customers.svg') no-repeat  center ;background-size: 70%;overflow:visible;height:25px; width:40px;}
		#crm_plugin li.dashboard-links li.support-categories span{background: url('<?php echo $imageUrl ?>supportcat.svg') no-repeat  center ;background-size: 70%;overflow:visible;height:25px; width:40px;}
		#crm_plugin li.dashboard-links li.users span{background: url('<?php echo $imageUrl ?>users.svg') no-repeat  center ;background-size: 70%;overflow:visible;height:25px; width:40px;}
		#crm_plugin li.dashboard-links li.reports span{background: url('<?php echo $imageUrl ?>reports.svg') no-repeat  center ;background-size: 70%;overflow:visible;height:25px; width:40px;}


		#crm_plugin li.user-items li.ticket a:hover{text-decoration:underline;}
		
		
		#crm_plugin li.user-items li.ticket span.ticket{background: url('<?php echo $imageUrl ?>ticket.svg') no-repeat  center ;background-size: 70%;overflow:visible;height:25px; width:40px;}
		#crm_plugin li.user-items li.ticket span.ticket-warning{background: url('<?php echo $imageUrl ?>ticket_warning.png') no-repeat  center ;background-size: 70%;overflow:visible;height:25px; width:40px;}
		#crm_plugin li.user-items li.ticket span.ticket-error{background: url('<?php echo $imageUrl ?>ticket_error.png') no-repeat  center ;background-size: 70%;overflow:visible;height:25px; width:40px;}
		
		#crm_plugin li.dashboard-links li.add-ticket span.ticket{background: url('<?php echo $imageUrl ?>ticket.png') no-repeat  center ;background-size: 70%;overflow:visible;height:25px; width:40px;}
		#crm_plugin li.user-items h4.open-ticket-count {color: #666 ; font-size:22px; }
		#crm_plugin li.user-items h4.open-ticket-count span{color:#843534 ; font-weight:bolder; }
		#crm_plugin li.user-items a:hover{cursor:pointer;}
		
		#crm_plugin li.user-items li.ticket {margin-left:12px;}
		#crm_plugin li.user-items h4 {margin-bottom:15px;}

		#crm_plugin li.user-items li.ticket{padding:7px 0 6px;}
		#crm_plugin li.user-items li.ticket a{padding:7px 0 6px;float:right; color:#9f9f9f;line-height: 20px;font-weight:bold; font-size:16px;text-decoration: none;}
		</style>
			
       <div class="row" id="crm_top">

             <section class="span12">
                <ul class="breadcrumb">
                    <li><a href="../">Home</a> <span class="divider">/</span></li>
                    <li class="active">Support Logs</li>
                </ul>
             </section>
          </div>
		  
          <div class="row">
			<article class="span12 dashboard">
			<h1><?php echo $current_user->user_login; ?>'s dashboard</h1><br>
			 <ul class="thumbnails">
			 
			 <!-- Logged in user's items -->
              <li class="thumbnail span4 user-items">
                <div style="height: 500px;position:relative;" class="block-thumbnail maxheight user-items" >
                  <h2 ><?php echo $countEvents ?>  Open Tickets</h2><br>

					<ul  class="open-ticket list">
					<?php 
						$retrieve_data = $wpdb->get_results( $q_openUserEvents );
						foreach ($retrieve_data as $row){ 
						
						     $now = time();
							 $your_date = strtotime($row->ticketcreated);
							 $datediff = $now - $your_date;
							 $datediff = floor($datediff/(60*60*24));
							 $errclass = "ticket";
							if ($datediff >=1) { $errclass= "ticket-warning";};							
							if ($datediff >=3) { $errclass= "ticket-error";};
							echo "<li class='ticket'><h4><span class='{$errclass}'></span><a onclick='crm_openEditTicket(true, $row->id)'> $row->requestedby of $row->customername:  ".stripslashes($row->subject)."</a></h4></li>";
					} ?>

				 </ul>
				<div class="row"><article class="span4"><div class="divider-1"></div></article></div>
				 
					<ul  class="open-ticket list list-blog"> <!--style="position:absolute; bottom:0px;"-->
						<li class='ticket'>
						<?php if ( $countUserEvents > 0) : ?>
							<h4>See All Tickets:</h4>
								<h4><span class='ticket'></span><a class="all-tickets" onclick='crm_viewTickets(true, "timeout:0,techid:<?php echo get_current_user_id() ?>" )'>All Open Tickets</a></h4>
						<?php else : ?>	
							<span class='ticket'></span><h4><a>You have no open tickets</a></h4>
						<?php endif; ?>
						</li>
					</ul>
					<br>
					
                  
                </div>
              </li>
			  
			  
			 <!-- Create a ticket -->
              <li class="thumbnail span4 create-ticket">
                <div style="height: 500px;" class="block-thumbnail maxheight">
                  <h2>Create a ticket</h2>
                  <form method="post" name="frm_initTicket" autocomplete="off"  >
					<!--article class="span4"-->				
						<label for="sel_customer" id="for_sel_customer">Customer:</label>
						<select id="sel_customer" name="sel_customer" >
							<option value="select">-Select-</option>
							<?php
								$retrieve_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}customers WHERE isactive=1;" );						
								foreach ($retrieve_data as $row){	
									echo "<option value='$row->id'  >$row->customername</option>";
								};
							?>
						</select>
						<label for="txt_contact" id="for_txt_contact">Requested By:</label>
						<input type="text" id="txt_contact" name="txt_contact">						
				
						<label for="assign_tech" id="for_assign_tech">Assign To:</label>
						<select id="assign_tech" name="assign_tech" >
							<option value="select">-Select-</option>
							<?php
								$retrieve_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}issupportmembers WHERE isactive=1;" );	
								foreach ($retrieve_data as $row){	
									$id = $row->userid;
									$user_info = get_userdata( $id );
									echo "<option value='$id' >$user_info->user_login</option>";
								}
							?>
						</select>

						<label for="ddlSupType" id="for_ddlSupType">Ticket Category:</label>
						<select id="ddlSupType" name="ddlSupType" >
							<option value="select">-Select-</option>
							<?php
								$retrieve_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}supporttype WHERE isactive=1;" );	
								foreach ($retrieve_data as $row){
									echo "<option value='$row->id'>$row->supporttype</option>";
								}
							?>
						</select>
						<br>				
					<!--/article--> 
					
					<div>
						<label for="txt_description" id="for_txt_description">Description: (Max 300 Characters)</label>
						<textarea id="txt_description" name="txt_description" MaxLength="300" ></textarea> 						
						<input onclick="jQuery(this).parents('form:first').submit(validateInitTicket() ? crm_initTicket : function(e){e.preventDefault()});" 
						type="submit" id="initticket" value="Create Ticket" class="btn-1 btn fright init-ticket" >						
					</div>
				</form>
                </div>
              </li>
			  
			 <!-- site links -->
              <li class="thumbnail span4 dashboard-links">
                <div style="height: 500px;" class="block-thumbnail maxheight" >
                  <h2>More settings</h2>
				  <ul class="list">
					  <li class="quick-links dashboard"><span></span><a onclick="crm_view('dashboard')">Dashboard</a></li>	
					  <li class="quick-links tickets"><span></span><a onclick="crm_view('default', true)">Ticket History</a></li>	
					  <li class="quick-links add-ticket"><span></span><a onclick="crm_view('createentry', true)">Add Ticket</a></li>	
					  <li class="quick-links customers-link"><span></span><a onclick="crm_view('customer', true)">Customers</a></li>	
					  <li class="quick-links support-categories"><span></span><a onclick="crm_view('supporttypes', true)">Ticket Categories</a></li>	
					  <li class="quick-links users"><span></span><a onclick="crm_view('users', true)">Users</a></li>	
					  <li class="quick-links reports"><span></span><a onclick="crm_view('reports', true)">Reports</a></li>
				 </ul>
                </div>
              </li>
			  
			 <!-- Manage Customers -->
              <!--li class="thumbnail span4 manage-customers">
                <div style="height: 500px;background: url('<?php //site_url(); ?>/wp-content/uploads/2016/07/Customers.png') no-repeat  center 40px;background-size: 60% ;" class="block-thumbnail maxheight" >
                  <span class="font14"><a href="#">Manage Customers</a></span>
				  <input onclick="crm_getView('customer', true)" style="display:block;margin:auto;margin-top:240px;" type="button" value="Manage Customers" class="btn btn-1">
                  <p style="padding:10px;margin:10px 0px;border: 1px solid silver;border-radius:4px;"><b>Create and modify customers</b><br>
				  <span class="caption">Tip: You can hide inactive customers from dropdown menus by un-checking 
				  the &ldquo;Enabled&rdquo; box from the Manage Customers panel.</span></p>
                </div>
              </li>
			  
			 <!-- Run reports -->
              <!--li class="thumbnail span4 run-reports">
                <div style="height: 500px;" class="block-thumbnail maxheight">
                  <figure class="img-polaroid"><img src="<?php site_url(); ?>/wp-content/uploads/temp/page-2-img-2.jpg" alt=""></figure>
                  <span class="font14"><a href="#">Service Name#3</a></span>
                  <p>Fusce euismod consequat ante. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Pellentesque sed dolor. Aliquam congue fermentum nisl.</p>
                </div>
              </li>
			  
			 <!-- Create a website post -->
              <!--li class="thumbnail span4">
                <div style="height: 500px;" class="block-thumbnail maxheight">
                  <figure class="img-polaroid"><img src="<?php site_url(); ?>/wp-content/uploads/temp/page-2-img-3.jpg" alt=""></figure>
                  <span class="font14"><a href="#">Service Name#4</a></span>
                  <p>Fusce euismod consequat ante. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Pellentesque sed dolor. Aliquam congue fermentum nisl.</p>
                </div>
              </li>
			  
			 <!-- Search Events -->
              <!--li class="thumbnail span4">
                <div style="height: 500px;" class="block-thumbnail maxheight">
                  <figure class="img-polaroid"><img src="<?php site_url(); ?>/wp-content/uploads/temp/page-2-img-4.jpg" alt=""></figure>
                  <span class="font14"><a href="#">Service Name#5</a></span>
                  <p>Fusce euismod consequat ante. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Pellentesque sed dolor. Aliquam congue fermentum nisl.</p>
                </div>
              </li>
             
          
			 <!-- end html from template -->
			 
			 
			 

</ul>
             </article>			
			 
 
			
		<!-- aside goes here -->
      </div><!-- end .row  -->	

      <div class="row"><article class="span12"><div class="divider-1"></div></article></div>

<?php endif; ?>

