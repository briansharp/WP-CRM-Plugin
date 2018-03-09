<div style="background-color:white; height:700px; text-align:center;" >
	<h3 style="padding-top:25px;" id="error_del" class="hide">Warning, cannot delete an item that is attached to an existing issue.<br>Redirecting</h3>
	<img src="../../../loading-circle.gif" style="padding-top:100px; height:110px; "  />
	<h3>&nbsp;&nbsp;Loading</h3>


<?php
/**
 * handles creating, updating and deleting support categories
 *
 * @since 1.0.0
 * @author: Brian Sharp
 */
	  class cttPluginSupport{	

		static  function add_support(){
			global $wpdb; 
			$tbl_sup = $wpdb->prefix . "supporttype";
			if (isset($_POST['addsupport'])){				
				$wpdb->insert( 
					$tbl_sup,  
					array( 
						"supporttype" => $_POST['newsupportitem'],  
						"isactive" => true
					),
					array(
						'%s',
						'%d'
					)
				);
			}
			?>
			<script type="text/javascript">
				window.location="http://www.spaceportimaging.com/index.php/logs/?logaction=SupportTypes";
			</script>
			<?php

		}	
			
		static function delete_support(){
			global $wpdb; 
			$tbl_sup = $wpdb->prefix . "supporttype";
			$tbl_events = $wpdb->prefix . "events";	
			$rowcount = $wpdb->get_var('SELECT count(*) FROM ' . $tbl_events . ' WHERE supporttypeid = ' . $_POST["supportid"]);
			if (array_key_exists('deletesupport', $_POST) && ($rowcount>0)){
				?>	
				<script type="text/javascript">
					document.getElementById("error_del").className="";  //show error
					//sleep(5000); //show error wait 3 seconds for the user to see it
					window.location="http://www.spaceportimaging.com/index.php/logs/?logaction=SupportTypes";//redirect to the page they came from
				</script>
				<?php				
			} elseif (array_key_exists('deletesupport', $_POST)) {
				$wpdb->delete( $tbl_sup, array( "id" => $_POST['supportid'] ) );
				?>
				<script type="text/javascript">
					window.location="http://www.spaceportimaging.com/index.php/logs/?logaction=SupportTypes";//redirect to the page they came from
				</script>
				<?php
			}

		}
		
		static  function update_support(){			
			global $wpdb; 
			$tbl_sup = $wpdb->prefix . "supporttype";
			$activechecked = false;
			if (isset($_POST['supportisactive'])){$activechecked = true;}
			if (isset($_POST['updatesupport'])){				
				$wpdb->update( 
					$tbl_sup,  
					array( 
						"supporttype" => $_POST['txt_support'],  
						"isactive" => $activechecked
					),
					array( 'id' => $_POST['supportid'] ), 
					array(
						'%s',
						'%d'
					)
				);
			}
			?>
			<script type="text/javascript">
				window.location="http://www.spaceportimaging.com/index.php/logs/?logaction=SupportTypes";
			</script>
			<?php
		}			
			
	}	
?>

</div>