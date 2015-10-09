<?php 

header('X-Frame-Options: GOFORIT'); 

include 'includes/listing-connection.php' 

?>

<head>
<title>Propertybase Australia</title>
<meta name="author" content="Rod Gilbody"/>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flickity/1.1.1/flickity.css" media="screen">
<link rel="stylesheet" href="css/px-video.css" />
<link rel="stylesheet" type="text/css" href="css/pb-style.css">

<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script> -->
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="http://imagesloaded.desandro.com/imagesloaded.pkgd.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/flickity/1.1.1/flickity.pkgd.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

<html>

</head>

<body>
<!-- VARS -->
<script>
  var lat;
  var lng;
  var video_url;
  var youtube_url;
  var external;
</script>
 <!-- BEGIN ERROR -->
    <?php if (!empty($errorMessage) || !empty($DisplayQuery)|| !empty($DisplayxmlResult) ): ?>
    <div id="error">
      <?php echo $errorMessage; ?>
      <br>
      <?php echo $DisplayDebug ?>
      <br>
      <?php echo $DisplayQuery; ?>
      <br>
      <br>
      <?php echo $DisplayxmlResult; ?>    
      <br>
      <br>
    </div>
    <?php else: ?>
    <?php endif;?>  
  
  <!-- END ERROR -->
<div id="container_single_view">
  
  <!-- BEGIN EMPTY RESULT -->
  
    <?php if ($doSearch  && ($xmlResult == null || count($xmlResult->listings->listing) == 0)){ ?>
      <div id="noresult"> 
        no listings found: 
      </div>
    <?php }else{ ?>
  <!-- END EMPTY RESULT --> 

    <!-- BEGIN RESULT -->
                
      <div id="result">

                  <h3 class="mainTitle"><?php echo  $xmlResult->listings->listing->data->name; ?></h3>   
                  
                  <div class="single_view_navigation">
                    <ul>
                      <li><a id="single_view_nav_gallery" href="#">Gallery</a></li>
                      <li><a id="single_view_nav_map" href="#">Map</a></li>
                      <li><a id="single_view_nav_video" href="#">Video</a></li>
                    </ul>
                  </div>
				  
                  <div class="single_view_media">

                    <!-- BEGIN GALLERY -->
                      <?php foreach ($xmlResult->listings->listing as $item): ?>
                        <?php if ($item->media->images->image != null && count($item->media->images->image) > 0): ?>
                          <div class="gallery">
                            <?php $i = 0; foreach ($item->media->images->image as $image): ?>
                            <div class="gallery-cell">
                              <img class="itemImage" src="<?php echo $item->media->images->image[$i]->baseurl . "/" . $item->media->images->image[$i]->filename; ?>"/>
                              <?php $i++; ?>
                             </div> 
                            <?php endforeach; ?>
                          </div>
                      <?php endif; ?>
                    <!-- END GALLERY -->

                    <!-- BEGIN MAP -->
                     
                      <div id="map_container">
                        <div id="gmap_canvas" style="height:500px;width:100%;"></div>
                            <style>#gmap_canvas img{max-width:none!important;background:none!important; }</style>
                        </div>
                      <script type="text/javascript"> 
                       
                        function init_map(){
                          var myOptions = {
                            zoom:14,
                            center:new google.maps.LatLng(lat,lng),
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                          };

                            map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
                            marker = new google.maps.Marker({
                              map: map,
                              position: new google.maps.LatLng(lat,lng)
                            });

                            infowindow = new google.maps.InfoWindow({content:"<b><?php echo  $xmlResult->listings->listing->data->name; ?></b><br/><?php echo  $xmlResult->listings->listing->data->pba__address_pb__c; ?>" });
                            google.maps.event.addListener(marker, "click", function(){
                              infowindow.open(map,marker);});
                              infowindow.open(map,marker);}

                        // check if latituee and longitude exists and init map is both does
                         <?php if ( !empty($item->data->pba__latitude_pb__c) && !empty($item->data->pba__longitude_pb__c) ){ ?> 
                            lat =  <?php echo  $item->data->pba__latitude_pb__c; ?>;
                            lng =  <?php echo  $item->data->pba__longitude_pb__c; ?>;     
                            google.maps.event.addDomListener(window, 'load', init_map);
                         <?php } ?>   

                      </script>
                    <!-- END MAP -->

                    <!-- BEGIN VIDEO -->
                    <div class="px-video-container" id="myvid">

                  
                    </div><!-- end video container -->
                    
                    <!-- END VIDEO -->
                   
					       </div> <!-- // end single_view_media -->

          <div class="single_view_info arrange">
            <div class="single_view_arrange_header">
                <p>REQUEST THE VIEWING OF</p>
               <h3 class="itemFact"><?php echo  $item->data->name; ?></h3>
                <em>Please fill in the fields marked with *</em>
               <a class="arrange_viewing_button beige angle_edges_button close" href="#">X</a>
            </div><!-- // end single_view_info_header -->
            <div class="panel full_width">  
                        <form action="/">
                           <fieldset>
                             <label for="name">Name</label>
                             <input type="text" id="name" class="form-text" />
                             <p class="form-help">This is help text under the form field.</p>
                           </fieldset>
                           
                           <fieldset>
                             <label for="email">Email</label>
                             <input type="email" id="email" class="form-text" />
                           </fieldset>

                           <fieldset class="form-actions">
                             <input type="submit" value="Submit" />
                           </fieldset>
                        </form>     
            </div> <!-- // end panel -->  
          </div> <!-- // end single_view_info  --> 

          <div class="single_view_info description">
            <div class="single_view_info_header">
               <h3 class="itemFact"><?php echo  $item->data->name; ?></h3>
               <strong>&#163;<?php echo number_format((float) $item->data->pba__listingprice_pb__c); ?></strong>
               <p> 
               <?php
                $listing_bedrooms = $item->data->pba__bedrooms_pb__c;
                if( $listing_bedrooms > 1 ){
                  echo  $item->data->pba__bedrooms_pb__c.' Bedrooms'; 
                }else{
                  echo  $item->data->pba__bedrooms_pb__c.' Bedroom'; 
                }
                ?>
               </p>
               <a href="#" class="arrange_viewing_button beige angle_edges_button open">ARRANGE A VIEWING</a>
            </div><!-- // end single_view_info_header -->
            <div class="panel half_width" style="height:250px; overflow-y: auto;">  
            <br>
            <p><?php echo $item->data->pba__description_pb__c; ?> </p>
                        <!-- <ul class="itemFacts">
                          <li class="itemFact">Price: <?php echo number_format((float) $item->data->pba__listingprice_pb__c); ?></li>
                          <li class="itemFact">Beds: <?php echo  $item->data->pba__bedrooms_pb__c; ?></li>
                          <li class="itemFact">Type: <?php echo  $item->data->pba__propertytype__c; ?></li>
                          <li class="itemFact">Sq.ft: <?php echo  number_format((float) $item->data->pba__totalarea_pb__c); ?></li>
                          <li class="itemFact"><?php echo substr($item->data->pba__description_pb__c,0,400); if( strlen($item->data->pba__description_pb__c)>400) echo '...' ; ?></li>
					             </ul> -->
            </div> <!-- // end panel -->  
            <div class="panel half_width">  
                        <ul class="itemFacts">
                          <li><h4>FAST FACTS</h4></li>
                          <li class="itemFact">Id: <?php echo  $item->data->id; ?></li>
                          <li class="itemFact">Room list: <?php echo  $item->data->room_list__c; ?></li>
                          <li class="itemFact">Council Tax Band: <?php echo  $item->data->council_tax_band__c; ?></li>
                          <li class="itemFact">Local Authority: <?php echo  $item->data->local_authority__c; ?></li>
                          <li class="itemFact">Years Remaining: <?php echo  $item->data->years_remaining_leasehold_only__c; ?></li>
                          <li class="itemFact">Price: $<?php echo number_format((float) $item->data->pba__listingprice_pb__c); ?></li>
                          <li class="itemFact">Beds: <?php echo  $item->data->pba__bedrooms_pb__c; ?></li>
                          <li class="itemFact">Type: <?php echo  $item->data->pba__propertytype__c; ?></li>
                          <li class="itemFact">Sq.ft: <?php echo  number_format((float) $item->data->pba__totalarea_pb__c); ?></li>
                          <li class="itemFact">lat & long: <?php echo  $item->data->pba__latitude_pb__c .' | '. $item->data->pba__longitude_pb__c; ?></li>
                          <li class="itemFact">Video: <a href="<?php echo $item->media->videos->video->url; ?>"><?php echo $item->media->videos->video->title; ?></a> </li>  
                       </ul>
            </div> <!-- // end panel --> 
          </div> <!-- // end single_view_info  --> 

           

      </div>
      <!-- END RESULT -->
       <!-- if video url create javascript variable with it -->
       <?php if( !empty($item->media->videos->video->url) ){ ?>
                <script type="text/javascript">
                  video_url = <?php echo ("'".$item->media->videos->video->url."';");  ?>
                  // youtube_url = video_url.replace("watch?v=", "v/");
                  youtube_url = video_url;
                  external = <?php echo ("'".$item->media->videos->video->external."';");  ?>
                </script>
       <?php } ?>

                 <?php endforeach; ?>
                <?php } ?>
                <!-- END ELSE EMPTY RESULT -->

<script src="js/px-video.js"></script>
<script type="text/javascript" src="js/single_view.js"></script>
</body>
</html>