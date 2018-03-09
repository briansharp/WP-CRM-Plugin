
<div style="background-color:white; height:700px; text-align:center;" >
	<h3 style="padding-top:25px;" id="error_del" class="hide">Warning, cannot delete an item that is attached to an existing issue.<br>Redirecting</h3>
	<img src="../../../loading-circle.gif" style="padding-top:100px; height:110px; "  />
	<h3>&nbsp;&nbsp;Loading</h3>


<?php
/**
 * handles Event items in database
 *
 * @since 1.0.0
 * @author: Brian Sharp
 */
	  class cttPluginEvents{	
			//echo var_dump($_POST);		

		
		static function create_event(){			
			global $wpdb; 
			$tbl_events = $wpdb->prefix . "events";
	
			$wpdb->insert( 
				$tbl_events, 
				array( 
					"techid" => $_POST['sel_tech'], 
					"customerid" => $_POST['sel_customer'],  
					"locationid" => $_POST['sel_location'] , 
					"isremote" => $_POST['rb_service'] ,
					"supporttypeid" => $_POST['ddlSupType']  ,
					"timein" => date( 'Y-m-d H:i:s', strtotime($_POST['start_date'] . " " . $_POST['sel_hourin'] . ":" . $_POST['sel_minin'] . " " . $_POST['sel_ampmin'] ) ) ,
					"timeout" => date( 'Y-m-d H:i:s', strtotime($_POST['end_date'] . " " . $_POST['sel_hourout'] . ":" . $_POST['sel_minout'] . " " . $_POST['sel_ampmout'] ) ) ,
					"subject" => $_POST['txt_description'] ,
					"details" => $_POST['txt_resolution'] ,
					"requestedby" => $_POST['txt_contact']  ,
					"isbillable" => isset($_POST['cb_billable'])
				),
				array(
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%s', 
					'%s', 
					'%s', 
					'%s', 
					'%s', 
					'%d' 
				) 
			);
			?>					
				<script type="text/javascript">
				window.location="http://www.spaceportimaging.com/index.php/logs";
				</script>
			<?php
		}	
			
		static function delete_event(){
			global $wpdb; 
			$tbl_events = $wpdb->prefix . "events";
			if (array_key_exists('deleteevent', $_POST)){	
				$wpdb->delete( $tbl_events, array( "id" => $_POST['deleteevent'] ) );
			}
			?>					
				<script type="text/javascript">
				window.location="http://www.spaceportimaging.com/index.php/logs";
				</script>
			<?php
		}
		
		static  function update_event(){
			global $wpdb; 
			$tbl_events = $wpdb->prefix . "events";
			if (isset($_POST['updateevent'])){				
				$wpdb->update( 
					$tbl_events,  
					array( 
						"techid" => $_POST['sel_tech'], 
						"customerid" => $_POST['sel_customer'],  
						"locationid" => $_POST['sel_location'] , 
						"isremote" => $_POST['rb_service'] ,
						"supporttypeid" => $_POST['sel_suptype']  ,
						"timein" => date( 'Y-m-d H:i:s', strtotime($_POST['start_date'] . " " . $_POST['sel_hourin'] . ":" . $_POST['sel_minin'] . " " . $_POST['sel_ampmin'] ) ) ,
						"timeout" => date( 'Y-m-d H:i:s', strtotime($_POST['end_date'] . " " . $_POST['sel_hourout'] . ":" . $_POST['sel_minout'] . " " . $_POST['sel_ampmout'] ) ) ,
						"subject" => $_POST['txt_description'] ,
						"details" => $_POST['txt_resolution'] ,
						"requestedby" => $_POST['txt_contact']  ,
						"isbillable" => $_POST['rb_billable']
					),
					array( 'id' => $_POST['txt_id'] ), 
					array(
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%d' 
					)
				);
			}
			?>					
				<script type="text/javascript">
				window.location="http://www.spaceportimaging.com/index.php/logs";
				</script>
			<?php
		}				
			

		
			
	}	
?>

</div>