<?php if ($data instanceof stdClass && $data->security == true) : 

 //other preliminary variables
 global $wpdb; 
 
 //user info
 $uid = $data->uid;
 $user_info = get_userdata($uid);
 $un = $user_info->first_name . " " . $user_info->last_name;
 
 //user rights info 
 $tbl_sup = $wpdb->prefix . "issupportmembers";
 

//first check if user exists...if not creat user with zeros across
$useradded = $wpdb->get_var("SELECT COUNT(*) FROM $tbl_sup WHERE userid=$uid");
if($useradded == 0)
{
$tbl_users = $wpdb->prefix . "issupportmembers";	
	$wpdb->insert( 
		$tbl_users, 
		array( 
			"userid" =>  $uid, 
			"isactive" => 0,  
			"superuser" => 0, 
			"editusers" => 0,
			"editsupportcategores" => 0,
			"editcustomers" => 0,
			"editevents" =>  0,
			"viewreports" =>  0,
			"viewevents" => 0
		),				
		array(
			'%d',
			'%d',
			'%d',
			'%d',
			'%d', 
			'%d',
			'%d',
			'%d', 
			'%d' 
		) 
	);
}			
 $row = $wpdb->get_row( "SELECT * FROM $tbl_sup WHERE userid= $uid;" );
 
?>
<style>
	.user-edit input{margin-bottom:20px;vertical-align:baseline;}
	.user-edit input.btn{float:right; margin-right:10px; width:130px;}
</style>

<article class="span8">
	<ul class="list-blog">  	
		<li><h2><?php echo $un ?> - <date><?php echo date('F j, Y'); ?></date></h2></li>

		<li class="user-edit">		
			<ul>
				<form method="post" name="frm_edituser"  autocomplete="off">
					<input type="checkbox" name="isactive" value ="<?php echo $row->isactive; ?>" <?php if ($row->isactive) {echo 'checked';}; ?>	> User is active <br>
					<input type="checkbox" name="superuser" <?php if ($row->superuser) {echo "checked";} ?>	> Super User <br>
					<input type="checkbox" name="editusers" <?php if ($row->editusers) {echo "checked";} ?>	> Edit Users <br>
					<input type="checkbox" name="editsupportcategores" <?php if ($row->editsupportcategores) {echo "checked";} ?>	> Edit support categories <br>
					<input type="checkbox" name="editcustomers" <?php if ($row->editcustomers) {echo "checked";} ?>	> Edit Customers <br>
					<input type="checkbox" name="editevents" <?php if ($row->editevents) {echo "checked";} ?>	> Edit Events <br>
					<input type="checkbox" name="viewreports" <?php if ($row->viewreports) {echo "checked";} ?>	> View Reports <br>	<br>
					<input onclick="jQuery(this).parents('form:first').submit(crm_rnmUser)" type="submit" class="btn btn-1" name="updateuser" value="Save" >	
					<input type="hidden" name="userid" value="<?php echo $uid; ?>" >
					<input type="button" class="btn btn-1" value="Cancel" onclick="crm_getView('users')" >
				 </form>
			 </ul>
		 </li>
	</ul>
</article>


<?php endif; ?>
