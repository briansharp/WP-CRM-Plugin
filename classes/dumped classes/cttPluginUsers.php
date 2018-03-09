
<div style="background-color:white; height:700px; text-align:center;" >
	<h3 style="padding-top:25px;" id="error_del" class="hide">Warning, cannot delete an item that is attached to an existing issue.<br>Redirecting</h3>
	<img src="../../../loading-circle.gif" style="padding-top:100px; height:110px; "  />
	<h3>&nbsp;&nbsp;Loading</h3>


<?php
/**
 * handles  user secururity and deleting users
 *
 * @since 1.0.0
 * @author: Brian Sharp
 */
	  class cttPluginUsers{	  
		static function ctt_create_user($uid){			
			global $wpdb; 
			$tbl_users = $wpdb->prefix . "issupportmembers";	
			$wpdb->insert( 
				$tbl_users, 
				array( 
					"userid" =>  $uid, 
					"isactive" => true,  
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
			
		
		static  function update_user(){
			$uid = $_POST['userid'];
			
			global $wpdb; 
			$tbl_users = $wpdb->prefix . "issupportmembers";
			
						
			$a = false;
			if (isset($_POST['isactive'])){$a = true;}
			$b = false;
			if (isset($_POST['editusers'])){$b = true;}
			$c = false;
			if (isset($_POST['editsupportcategores'])){$c = true;}
			$d = false;
			if (isset($_POST['editcustomers'])){$d = true;}
			$e = false;
			if (isset($_POST['superuser'])){$e = true;}
			$f = false;
			if (isset($_POST['editevents'])){$f = true;}
			$g = false;
			if (isset($_POST['viewreports'])){$g = true;}
			
			
			if (isset($_POST['updateuser'])){				
				$wpdb->update( 
					$tbl_users,  
					array( 
					"isactive" => $a,
					"editusers" => $b,
					"editsupportcategores" => $c,
					"editcustomers" => $d,  
					"superuser" => $e, 
					"editevents" =>  $f, 
					"viewreports" =>  $g
					),
					array( 'userid' => $_POST['userid'] ), 
					array(
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
			?>					
				<script type="text/javascript">
				  window.location="http://www.spaceportimaging.com/index.php/logs/?logaction=PluginUsers";
				</script>
			<?php
		}				
			

		
			
	}	
?>

</div>