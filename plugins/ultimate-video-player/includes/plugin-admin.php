<?php
	$ult_players = get_option("ult_players");

	if(!$ult_players){
		$ult_players = array();
		add_option("ult_players", $ult_players);
	}

	function read_ult_video_player_admin_init(){

	}
	add_action("admin_init", "read_ult_video_player_admin_init");
	
	function read_ult_video_player_admin_menu(){
		add_options_page("Video Player Admin", "Ultimate Video Player", "manage_options", "ult_video_player_admin", "ult_video_player_admin"); 
	}
	add_action("admin_menu", "read_ult_video_player_admin_menu");
	
	//options page
	function ult_video_player_admin()
    {
		$current_action = "";
		// handle action from url
		if (isset($_GET['action']) ) {
			$current_action = $_GET['action'];
		}

		$ult_players = get_option("ult_players");
		if (isset($_GET['playerId']) )
		{
			$current_id = $_GET['playerId'];
			$ult_player = $ult_players[$current_id];
			$videos = $ult_player["videos"];
		}
		// $current_id = $_GET['playerId'];
		// $ult_player = $ult_players[$current_id];
		// $videos = $ult_player["videos"];
		
		// if(isset($current_action))
			// trace("isset");
		// else 
			// trace("notset");
		switch( $current_action ) {
		
			case 'edit':
				include("edit-player.php");
				break;
				
			case 'delete':
				//delete vplayer with id from url
				unset($ult_players[$current_id]);
				update_option("ult_players", $ult_players);
				include("players.php");
				break;
			case 'duplicate':
				$highest_id = 0;
				foreach ($ult_players as $flipbook) {
					$flipbook_id = $flipbook["id"];
					if($flipbook_id > $highest_id) {
						$highest_id = $flipbook_id;
					}
				}
				$new_id = $highest_id + 1;
				$ult_players[$new_id] = $ult_players[$current_id];
				$ult_players[$new_id]["id"] = $new_id;
				$ult_players[$new_id]["name"] = $ult_players[$current_id]["name"]." (copy)";
				update_option("ult_players", $ult_players);
				include("players.php");
				break;	
			case 'add_new':
				//generate ID 
				$new_id = 0;
				$highest_id = 0;
				foreach ($ult_players as $ult_player) {
					$player_id = $ult_player["id"];
					if($player_id > $highest_id) {
						$highest_id = $player_id;
					}
				}
				$current_id = $highest_id + 1;
				//create new vplayer 
				$vplayer = array(	'id' => $current_id, 
										"name" => "Player " . $current_id,
										"videos" => array()
						);
				$ult_players[$current_id] = $vplayer;
				update_option("ult_players", $ult_players);
				include("edit-player.php");
				break;
				
			case 'save_settings':
				$new = array_merge($ult_player, $_POST);
				$ult_players[$current_id] = $new;
				//reset indexes because of sortable videos can be rearranged
				$oldvideos = $ult_players[$current_id]["videos"];
				$newvideos = array();
				$index = 0;
				foreach($oldvideos as $p){
					$newvideos[$index] = $p;
					$index++;
				}
				$ult_players[$current_id]["videos"] = $newvideos;

				//convert values to boolean and integer where needed
				$formatted = array_map("ult_cast", $ult_players[$current_id]);
				// stripslashes($players[$current_id]["embedCode"]);
					// trace($players[$current_id]["embedCode"]);
					// trace (stripslashes($players[$current_id]["embedCode"]));
				
				$ult_players[$current_id] = $formatted;
				//for each video
				for($i = 0; $i < count($ult_players[$current_id]["videos"]); $i++){
					$p = $ult_players[$current_id]["videos"][$i];
				}
				update_option("ult_players", $ult_players);
				include("edit-player.php");
				break;

			default:
				include("players.php");
				break;
				
		}
    }
	
	function ult_cast($n)
	{
		if($n === "true") {
			return true;
		}else if ($n === "false"){
			return false;
		}else if(is_numeric($n)){
			// return (int)$n;
			return floatval($n);
		}else{
			return $n;
		}
	}