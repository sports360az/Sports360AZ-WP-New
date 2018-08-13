<div class="wrap">
	<h2>Manage Players
		<a href='<?php echo admin_url( "admin.php?page=ult_video_player_admin&action=add_new" ); ?>' class='add-new-h2'>Add New</a>
	</h2>
	
	<table class='players-table wp-list-table widefat fixed'>
		<thead>
			<tr>
				<th width='5%'>ID</th>
				<th width='50%'>Name</th>
				<th width='30%'>Actions</th>
				<th width='20%'>Shortcode</th>						
			</tr>
		</thead>
		<tbody>
			<?php 
				
				$ult_players = get_option("ult_players");

				if (count($ult_players) == 0) {
					echo '<tr>'.
							 '<td colspan="100%">No players found.</td>'.
						 '</tr>';
				} else {
					$player_display_name;
					foreach ($ult_players as $ult_player) {
						$player_display_name = $ult_player["name"];
						if(!$player_display_name) {
							$player_display_name = 'Player #' . $ult_player["id"] . ' (no name)';
						}
						echo '<tr>'.
								'<td>' . $ult_player["id"] . '</td>'.								
								'<td>' . '<a href="' . admin_url('admin.php?page=ult_video_player_admin&action=edit&playerId=' . $ult_player["id"]) . '" title="Edit">'.$player_display_name.'</a>' . '</td>'.
								'<td>' . '<a href="' . admin_url('admin.php?page=ult_video_player_admin&action=edit&playerId=' . $ult_player["id"]) . '" title="Edit this player">Edit</a> | '.									  
										 '<a href="' . admin_url('admin.php?page=ult_video_player_admin&action=delete&playerId='  . $ult_player["id"]) . '" title="Delete player permanently" >Delete</a> | '.
										 '<a href="' . admin_url('admin.php?page=ult_video_player_admin&action=duplicate&playerId='  . $ult_player["id"]) . '" title="Duplicate player" >Duplicate</a>'.
								'</td>'.
								'<td>[ultimate_video_player  id="' . $ult_player["id"] . '"]</td>'.															
							'</tr>';
					}
				}
			?>
		</tbody>		 
	</table>

	<p>			
		<a class='button-primary' href='<?php echo admin_url( "admin.php?page=ult_video_player_admin&action=add_new" ); ?>'>Create New Player</a>       
	</p>    
	
	<p></p>
</div>