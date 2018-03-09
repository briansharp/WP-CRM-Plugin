<?php if ($data instanceof stdClass) : 
global $wpdb; ?>  

<script>
	jQuery(document).ready(function() {
		jQuery('.datepicker-reports').datepicker({
			dateFormat : 'M dd yy'
		});
	});	
</script>
	
	
	<article class="span8">
		<ul class="list-blog">
		<li>
			<h2>Run Reports - <date><?php echo date('F j, Y'); ?></date></h2>
		</li>
		<li class="reports ">				
			
			<h3>What would you like to search for?</h3>
			<article class="span4">
				<section class="clearfix">
					<label for="sel_daterange">Date Range:</label>
					<select id="sel_daterange" onchange="toggleDateFields()" >
						<option>Specific Dates</option>
						<option>This Week</option>
						<option>Last Week</option>
						<option selected>This Month</option>
						<option>Last Month</option>
						<option>This Year</option>
						<option>Last Year</option>
						<option>All Time</option>
					</select>
					<div class="datepicker span2">
							<label for="start_date">Begin Date:</label>
							<input type="text" class="datepicker-reports" id="start_date" name="date_range" disabled >
					</div>
					<div class="datepicker span2">
							<label for="end_date">End Date:</label>                                                                
							<input type="text" class="datepicker-reports" id="end_date" name="date_range" disabled >  
					</div>
				</section>
				<section class="rbGroup clearfix">		
					<div id="BillableGroup" class="rb-group">
					  <span class="sel-group">
						<label for="rb_billable">Billable</label>
						<input type="radio" id="rb_billable"  name="g1" >
					  </span>
					  <span class="sel-group">
						<label for="rb_non_billable">Not Billable</label>
						<input type="radio" id="rb_non_billable" name="g1" >				
					  </span>
					  <span class="sel-group">
						<label for="rb_billable_both">Both</label>
						<input type="radio" id="rb_billable_both" name="g1"  checked >				
					  </span>
					</div>
				</section>       
			</article>
			<article class="span3">
				<section class="clearfix">
					<label for="sel_customer">Customer:</label>	
					<select id="sel_customer">
						<option value="-1" >All</option>
						<?php							
							$tbl_cust = $wpdb->prefix . "customers";
							$retrieve_data = $wpdb->get_results( "SELECT * FROM $tbl_cust;" );						
							foreach ($retrieve_data as $row){	
								$id = $row->id;
								$st = $row->customername;
								echo "<option value='$id'>$st</option>";
							}
						?>
					</select>
					<label for="sel_support">Support Type:</label>	
					<select id="sel_support">
						<option value="-1" >All</option>
						<?php
							$retrieve_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}supporttype;" );	
							foreach ($retrieve_data as $row){	
								$id = $row->id;
								$st = $row->supporttype;
								echo "<option value='$id'>$st</option>";
							}
						?>
					</select>
					<label for="sel_technician">Technician:</label>
					<select id="sel_technician">
						<option value="-1" >All</option>
						<?php
							global $wpdb; 
							$tbl_sm = $wpdb->prefix . "issupportmembers";
							$retrieve_data = $wpdb->get_results( "SELECT * FROM $tbl_sm;" );	
							foreach ($retrieve_data as $row){	
								$id = $row->userid;
								$user_info = get_userdata( $id );
								$un = $user_info->user_login;
								echo "<option value='$id'>$un</option>";
							}
						?>
					</select>				
				</section>
				<section class="rbGroup">
					<div id="RemoteGroup" class="rb-group">
					  <span class="sel-group">
						<label for="rbRemote" >Remote</label>
						<input type="radio" id="rbRemote" name="g2" >
					  </span>
					  <span class="sel-group">
						<label for="rbOnsite">Onsite</label>
						<input type="radio" id="rbOnsite" name="g2" >		
					  </span>
					  <span class="sel-group">
						<label for="rbAllLoc">Both</label>
						<input type="radio" id="rbAllLoc" name="g2" checked >		
					  </span>
					</div>	
				</section>
				
			</article>
			
			<div class="row"><article class="span7"><div class="divider-2"></div></article></div> 
			
			<h3>How would you like your search results sorted?</h3>	
			<article class="span4">	  
				<section> 
					<div class="sel-group span3">    
						<label for="ddlSortBy">Sort By:</label>
						<select id="ddlSortBy" >
							<option>Customer</option>
							<option Selected="selected">Date</option>
							<option>Technician</option>
							<option>Support Type</option>
							<option>Billable</option>                                    
						</select>
					</div>                             
					
					<div class="sel-group span3">
						<label for="ddlSortDirection">Sort Direction:</label>  
						<select id="ddlSortDirection">
							<option Selected="selected">Ascending</option>
							<option>Descending</option>
						</select>  
					</div>	
				</section>
			</article>
			<article class="span3">
				<section>
					<div class="sel-group span3 clearfix">						
						<label for="ddlGroupBy">Group By:</label>
						<select id="ddlGroupBy">
							<option selected="selected">Customer</option>
							<option>Technician</option>
							<option>Support Type</option>                                   
						</select>
					</div>
					<div class="sel-group span3 fright search-btn"  ><br />
						<input type="button" value="Reset" class="btn btn-1" onclick="crm_view(reports)" />
						<input type="button" value="Search" class="btn btn-1" id="btnSearch" 
							onclick="jQuery(this).parents('form:first').submit(crm_view('results&' + search()))" />
					</div>
				</section>                    
			</article>
		</li>

		<div class="row"><article class="span12"><div class="divider-2"></div></article></div>

<?php endif; ?>

