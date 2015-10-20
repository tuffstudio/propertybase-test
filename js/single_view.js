//VARS

var gallery_view = $('.ls-wp-fullwidth-container');
var floorplan_view = $('#floorplan_container');
var epc_view = $('#epc_container');
var map_view = $('#map_container');
var video_view = $('#myvid');
// var single_view_divs = $('.single_view_media > div');
var description_view = $('.single_view_info.description');
var arrange_view = $('.single_view_info.arrange');

var video1;

// GOOGLE MAP
function init_map(){
	var myOptions = {
	    zoom:16,
	    center:new google.maps.LatLng(lat,lng),
	    mapTypeId: google.maps.MapTypeId.ROADMAP,
	    featureType: "all",
	    elementType: "all"
	};

	map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
	marker = new google.maps.Marker({
  	map: map,
  	position: new google.maps.LatLng(lat,lng)
});

	infowindow = new google.maps.InfoWindow({content:"<b>"+property_name+"</b><br/>"+property_address });
	google.maps.event.addListener(marker, "click", function(){
		infowindow.open(map,marker);});
		infowindow.open(map,marker);}

///////////// hideItem / showItem
function hideItem(items, opacity)
	{
		for(var i=0; i<items.length; i++)
			{
				if(opacity)
				{
					items[i].css({opacity: 0.0, visibility: "hidden"}).animate({opacity: 0}, 200);
					
				}
				else
				{
					items[i].fadeOut(200);
				}
			}
	}
 
function showItem(items, opacity)
	{
		for(var i=0; i<items.length; i++)
		{
			if(opacity)
			{
				items[i].css({opacity: 0.0, visibility: "visible"}).animate({opacity: 1}, 200)
			}
			else
			{
				items[i].fadeIn(300);
			}
		}
	}

// Initialize Unversal Player
function Initialize_Unversal_Player() 
	{
	
		// inject html structure
		if(external == 'false') 
			{
				'use strict';
				$('#myvid').html('<div class="px-video-img-captions-container"><div class="px-video-captions hide"></div><div class="px-video-wrapper"><video poster="img/poster.jpg" class="px-video" controls ><source src='+video_url+' type="video/mp4" /><div><a href=' + video_url + '><img src="img/poster.jpg" width="640" height="360" alt="download video" /></a></div></video></div></div><div class="px-video-controls"></div>');
		
				//init UVP
				video1 = new InitPxVideo({
				"videoId": "myvid",
				"captionsOnDefault": false,
				"seekInterval": 20,
				"videoTitle": "Ind.ie Launch",
				"debug": true
				});
		
				//Autoplay video after init - optional
				playVid();
		
			}
		else if(external === 'true') 
			{
				$('#myvid').html('<iframe width="100%" height="500" src="' + youtube_url + '"></iframe>');
			}
	}

function playVid() {
	obj.movie.play();
	obj.btnPlay.className = "px-video-play hide";
	obj.btnPause.className = "px-video-pause px-video-show-inline";
	obj.btnPause.focus();
}

function pauseVid() {
	obj.movie.pause(); 
   	obj.btnPlay.className = "px-video-play px-video-show-inline";
	obj.btnPause.className = "px-video-pause hide";
	obj.btnPlay.focus();
}

// DOCUMENT READY - 2st
	$(document).ready(function() {
		
	//LAYOUT CHANGE BUTTONS LOGIC

	$('.single_view_navigation ul li').on('click','a',function(event){
		event.preventDefault();
		event.stopPropagation();
		switch( $(this).attr('id') ) {
    		case 'single_view_nav_map': 
    			
    			// if video player has been initialised
    		    if(video1 != undefined){  pauseVid(); }

    		    // if lat & lng defined load map
    		    if(lat != undefined && lng != undefined){

    		    	if( $('#gmap_canvas').is(':empty') ){
    		    		init_map();
    		    	}
    		    		map_view.css('z-index', '0');
    		    		hideItem([gallery_view,video_view,floorplan_view,epc_view]);
    		    		showItem([map_view]);

					}else{
						alert('no longitude and latitude');
					}

    		    break;
    		
    		case 'single_view_nav_gallery':
    			
    			// if video player has been initialised
    		    if(video1 != undefined){ pauseVid(); }

    		     	hideItem([map_view,video_view,floorplan_view,epc_view]);
					showItem([gallery_view]);

    		    break;

    		case 'single_view_nav_floorplan':
    			
    			// if video player has been initialised
    		    if(video1 != undefined){ pauseVid(); }

    		    	if(floorplan){
    		     		hideItem([map_view,video_view,gallery_view,epc_view]);
				 		showItem([floorplan_view]);
				 	}else{
				 		alert('no Floorplan');
				 	}

    		    break;

    		case 'single_view_nav_epc':
    			
    			// if video player has been initialised
    		    if(video1 != undefined){ pauseVid(); }

    		    	if(epc)
    		    	{
    		    		hideItem([map_view,video_view,gallery_view,floorplan_view]);
						showItem([epc_view]);
					}
					else
					{
						alert('no EPC image');
					}


    		    break;
    		
    		case 'single_view_nav_video':
    				
    				// if video hasn't been initialised yet -> do it
    				if(video_url == undefined){
    					alert('no video url');
    				}
    				else if(video1 == undefined && video_url != undefined){ 
    		    		Initialize_Unversal_Player();
    		    	}
    		    	// else video has been initialised and is paused now -> resume video playing
    		    	else
    		    	{
    		    		playVid();
    		    	}

    		    	if( video_url != undefined ){
    		    		hideItem([map_view,gallery_view,floorplan_view,epc_view]);
						showItem([video_view]);
					}
    		    break;
    		
    		default:
        		// default code block
		}
	})

		// ARRANGE VIEWING BUTTON
		$('.arrange_viewing_button.open').on('click',function(event){
			event.preventDefault(); 
			hideItem([description_view],true);
			showItem([arrange_view],true);
	
		})

		$('.arrange_viewing_button.close').on('click',function(event){
			event.preventDefault(); 
			hideItem([arrange_view],true);
			showItem([description_view],true);
		})

		// MAP CHECKBOXES
		$('.map_points_of_interest').on('change', 'input[type=checkbox]', function(event) {
			event.preventDefault();
			/* Act on the event */

			if( $(this).is(':checked') ){
				alert($(this).val()+" wasn't checked");
			}else{
				alert($(this).val()+" was checked");
			}

		});
	
	});

// WINDOW LOAD - 3rd
	$(window).load(function(){

   		// Initialize flickity
   		$('.gallery').flickity({
  			contain: false,
  			imagesLoaded: true,
  			lazyLoad: true
		});

	});


