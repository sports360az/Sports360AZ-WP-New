<div class='wrap'>
	<div id='videoplayer-admin'>
		<a href="admin.php?page=ult_video_player_admin" class="back-to-list-link">&larr; <?php _e('Back to players list', 'player'); ?></a>
		<h2 id="edit-player-text">Edit player
		<?php
			if (isset($_GET['playerId']) && $_GET['playerId'] > -1) {
				echo ' ' . $_GET['playerId'];
			}
		?>
		</h2>
		<form method="post" enctype="multipart/form-data" action="admin.php?page=ult_video_player_admin&action=save_settings&playerId=<?php _e($current_id)?>">
			<p class="submit"><input type="submit" name="submit" id="submit" class="button save-button button-primary" value="Save Changes"></p>
			<div class="metabox-holder">
				<div class="meta-box-sortables ui-sortable">
				
					<div class="column-left">
				
				
						<div class="postbox">
							<div class="handlediv" title="Click to toggle"></div>
							<h3 class="hndle">
								<span id="sortable-title">Options</span>
							</h3>
							<div class="inside">
								<table class="form-table" id="player-options-table">
									<tbody/>
								</table>
							</div>
						</div>
						
						<!---<div class="postbox float-left-vimeo">
							<div class="handlediv" title="Click to toggle"></div>
							<h3 class="hndle">
								<span id="sortable-title">Vimeo</span>
							</h3>
							<div class="inside">
								<table class="form-table" id="player-options-table-vimeo">
									<tbody/>
								</table>
							</div>
						</div>--->
						
					</div>
					
					<div class="column-right">
					
						<div class="postbox">
							<div class="handlediv" title="Click to toggle"></div>
							<h3 class="hndle">
								<span id="sortable-title">Videos</span>
							</h3>
							<div class="inside">
								<div>
									<div class="ui-sortable sortable-videos-body">
										<div id="videos-container" class="ui-sortable sortable-videos-container">
												<table class="form-table" id="player-options-table-right">
													<tbody/>
												</table>
										</div>
										<div><a id="add-new-video-button" class="alignleft button-primary " href='#'>Add New Video</a></div>
									</div>
								</div>
							</div>
						</div>
						
					</div>
					
				</div>
			</div>
			<p class="submit"><input type="submit" name="submit" id="submit" class="button save-button button-primary" value="Save Changes"></p>
		</form>
	</div>
</div>
<?php 
wp_enqueue_media();
wp_enqueue_script("readvideo_player_admin", plugins_url()."/ultimate-video-player/js/plugin_admin.js", array('jquery','jquery-ui-sortable','jquery-ui-resizable','jquery-ui-selectable','jquery-ui-tabs' ),ULT_VIDEO_PLAYER_VERSION);
wp_enqueue_style( 'readvideo_player_admin_css', plugins_url()."/ultimate-video-player/css/player-admin.css",array(), ULT_VIDEO_PLAYER_VERSION );
wp_enqueue_style( 'jquery-ui-style', plugins_url()."/ultimate-video-player/css/jquery-ui.css",array(), ULT_VIDEO_PLAYER_VERSION );
//pass $players to javascript
wp_localize_script( 'readvideo_player_admin', 'options', json_encode($ult_players[$current_id]) );

// echo json_encode($ult_players[$current_id]); 