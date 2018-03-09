<?php if ($data instanceof stdClass) : ?>	
<script type="text-javascript">
		function search_events(){
			alert(loc);	
			var loc = document.getElementById('searchstring').value;
			var url = "localhost/index.php/Logs/?logaction=Results&dr=All%20Time&sd=undefined&ed=undefined&cust=-1&tech=-1&st=-1&ob=Date&od=Ascending&bill=a&rem=all&gb=Customer&ss="
			window.location= url + loc;
		}
</script>

	  <aside id="secondary" class="span4 ">
		<?php 
			//check the user id.  If it's dede logged in, we show quick report links here.  Otherwise we check again below and list them later.
			$user_ID = get_current_user_id(); 
			if ($user_ID == 8){
		?>
            <h2 class="h2indent-1">Quick Reports</h2>
            <ul class="list">
                <li><span></span><a href='../Logs?logaction=Results&dr=This_1-15&cust=-1&tech=-1&st=-1&ob=Date&od=Ascending&bill=b&rem=all&gb=Customer'   class='url'>This Month - 1st through 15th </a>
                <li><span></span><a href='../Logs?logaction=Results&dr=Last_16-EOM&cust=-1&tech=-1&st=-1&ob=Date&od=Ascending&bill=b&rem=all&gb=Customer'  class='url'>Last Month - 16th through EOM </a>
                <!--li><span></span><a href='../Logs?logaction=Results&dr=Last%20Month&cust=-1&tech=-1&st=-1&ob=Date&od=Ascending&bill=b&rem=all&gb=Customer'  class='url'>Last Month's Billable Events</a> 
                <li><span></span><a href='../Logs?logaction=Results&dr=This%20Month&cust=-1&tech=-1&st=-1&ob=Date&od=Ascending&bill=b&rem=all&gb=Customer'  class='url'>This Month's Billable Events</a--> 
            </ul>			
			<hr />				
		<?php				
			}
		?> 

	   <h2 class="h2indent-1">What to do?</h2>		
		  <ul class="list">
			  <li><span></span><a onclick="crm_view('dashboard', true)">dashboard</a></li>	
			  <li><span></span><a onclick="crm_view('default')">Ticket History</a></li>	
			  <li><span></span><a onclick="crm_view('createentry')">Add Ticket</a></li>	
			  <li><span></span><a onclick="crm_view('customer')">Customers</a></li>	
			  <li><span></span><a onclick="crm_view('supporttypes')">Ticket Categories</a></li>	
			  <li><span></span><a onclick="crm_view('users')">Users</a></li>	
			  <li><span></span><a onclick="crm_view('reports')">Reports</a></li>
	     </ul>
	    <hr />            
            <h2 class="h2indent-1">Search Events</h2>	
            <div class="extra-wrap">	
                <label for="side-search">Search Events</label>
                <input type="text" value="searchstring" placeholder="search" id="side-search" >
                <input type="button" value="Search" onclick="search_events()">	
            </div>
		<?php 
			$user_ID = get_current_user_id(); 
			if ($user_ID != 8){
		?>			
			<hr />	
            <h2 class="h2indent-1">Quick Reports</h2>
            <ul class="list">
                <li><span></span><a onclick="crm_view('results&dr=This_1-15&cust=-1&tech=-1&st=-1&ob=Date&od=Ascending&bill=b&rem=all&gb=Customer')"  class='url'>This Month - 1st through 15th </a>
                <li><span></span><a onclick="crm_view('results&dr=Last_16-EOM&cust=-1&tech=-1&st=-1&ob=Date&od=Ascending&bill=b&rem=all&gb=Customer')"  class='url'>Last Month - 16th through EOM </a>
                <!--li><span></span><a href='../Logs?logaction=Results&dr=Last%20Month&cust=-1&tech=-1&st=-1&ob=Date&od=Ascending&bill=b&rem=all&gb=Customer'  class='url'>Last Month's Billable Events</a> 
                <li><span></span><a href='../Logs?logaction=Results&dr=This%20Month&cust=-1&tech=-1&st=-1&ob=Date&od=Ascending&bill=b&rem=all&gb=Customer'  class='url'>This Month's Billable Events</a--> 
            </ul>			
		<?php				
			}
		?>          
       </aside><!-- end .sidebar  -->
<?php endif; ?>

