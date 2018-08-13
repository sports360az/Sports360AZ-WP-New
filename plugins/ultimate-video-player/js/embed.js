(function($) {
	$(document).ready(function(){
	
		var videoplayers = $(".videoplayer");
		
		$.each(videoplayers, function(){
			var id = $(this).attr('id');
			var options = $(this).find("#options").html();
			
			var json_str = options.replace(/&quot;/g, '"');
			
			json_str = json_str.replace(/“/g, '"');
			json_str = json_str.replace(/”/g, '"');
			json_str = json_str.replace(/″/g, '"');
			json_str = json_str.replace(/„/g, '"');
			
			options = jQuery.parseJSON(json_str);

			if(options.responsive){
				var vp_Container = $(this).css({
					width: "100%",
					height: options.videoPlayerHeight
				});
			}
			else{
				var vp_Container = $(this).css({
					width: options.videoPlayerWidth,
					height: options.videoPlayerHeight
				});
			}
            //if(options.playerShadow){
                //vp_Container = $(this).css({
                    //boxShadow: "0px 4px 7px rgba(0,0,0,.6)"
					/* direction:"ltr" */
                //});
            //}
			vp_Container = $(this).css({direction:"ltr"/*, position:"relative",width:"100%"*/});
			/*switch(options.playerDirection) {
					case 'Left to right (default)':
						vp_Container = $(this).css({
							direction:"ltr" 
						});
						break;
					case 'Right to left (eastern countries)':
						vp_Container = $(this).css({
							direction:"ltr" 
						});
						break;
			}*/
			/*switch(options.playerShadow) {
					case 'Effect1':
						vp_Container.addClass("ult_vp_effect1");
						break;
					case 'Effect2':
						vp_Container.addClass("ult_vp_effect2");
						break;
					case 'Effect3':
						vp_Container.addClass("ult_vp_effect3");
						break;
					case 'Effect4':
						vp_Container.addClass("ult_vp_effect4");
						break;
					case 'Effect5':
						vp_Container.addClass("ult_vp_effect5");
						break;
					case 'Effect6':
						vp_Container.addClass("ult_vp_effect6");
						break;
					case 'Off':
					
						break;
				}*/

			// console.log("vp_Container:",vp_Container)
			
			/*switch(options.mode){
				case "normal":
					options.lightBox = false;
					vp_Container
						.css("position","relative")
						.css("height",String(options.height)+"px")
					break;
				case "lightbox":
					options.lightBox = true;
					var img = $('<img></img>').attr('src', options.lightboxThumbnailUrl ).appendTo(vp_Container);
					break;
				case "fullscreen":
					options.lightBox = false;
					vp_Container
						.appendTo('body')
						.css("position","fixed")
						.css("top","0")
						.css("bottom","0")
						.css("left","0")
						.css("right","0")
						;
					break;
			}*/
			vp_Container.Video(options);
		})
	});
}(jQuery));