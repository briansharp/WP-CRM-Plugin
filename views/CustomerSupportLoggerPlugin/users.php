<?php if ($data instanceof stdClass && $data->security == true) : ?>
<article class="span8">
	<ul class="list-blog">  
	
		<li><h2 class="">Users - As of <date><?php echo date('F j, Y'); ?></date></h2></li>


<?php
// arguments
$args = array(
	'meta_query' => array(
		'relation' => 'OR',
			array(
				'role' => 'Administrator',
			),
			array(
				'role' => 'Subscriber' 
			)		
		)		
);

// The Query
$user_query = new WP_User_Query( $args );

// User Loop
		if ( ! empty( $user_query->results ) ) {
			foreach ( $user_query->results as $user ) {
				?>
				<li class="customers">		
					<ul>
						<form method="post" name="frm_edituser"  autocomplete="off">
							<input type="text" value="<?php echo $user->display_name ?>" disabled >&nbsp;&nbsp;
							<input type="button" class="btn " value="Edit User"  onclick="crm_getView('edituser', false, '1', '<?php echo $user->ID ?>')">						 
						 </form>
					 </ul>
				 </li>
				<?php
			}
		} else {
			echo 'No users found.';
		}
		?>

</ul>
</article>
<?php endif; ?>
