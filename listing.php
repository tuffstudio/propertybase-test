<?php 
  include 'includes/listing-connection.php'; 
?>

<html>

<head>
<title>Propertybase Australia</title>
<meta name="author" content="Rod Gilbody"/>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flickity/1.1.1/flickity.css" media="screen">
<link rel="stylesheet" href="css/px-video.css" />
<link rel="stylesheet" type="text/css" href="css/pb-style.css">
<link rel="stylesheet" type="text/css" href="css/fontello.css">

<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="http://imagesloaded.desandro.com/imagesloaded.pkgd.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/flickity/1.1.1/flickity.pkgd.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

</head>

<body>

<!-- JS VARS -->
<script>
  var lat;
  var lng;
  var video_url;
  var youtube_url;
  var external;
  var epc;
  var floorplan;
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
   
<?php 
 // VARS
 
  // ini_set('memory_limit','256M');

  $mainTitle = $xmlResult->listings->listing->data->name;
  $listing_type = $xmlResult->listings->listing->data->pba__listingtype__c;
  $tenure = $xmlResult->listings->listing->data->tenure__c;
  $floorplan = false;
  $epc = false;

 ?>
      <div id="result">

                  <h3 class="mainTitle"><?php echo  $mainTitle; ?></h3>   
                  
                  <div class="single_view_navigation">
                    <ul>
                      <li><a id="single_view_nav_gallery" href="#">Gallery</a></li>
                      <li><a id="single_view_nav_floorplan" href="#">Floor Plan</a></li>
                       <li><a id="single_view_nav_epc" href="#">EPC</a></li>
                      <li><a id="single_view_nav_map" href="#">Map</a></li>
                      <li><a id="single_view_nav_video" href="#">Video</a></li>
                    </ul>
                  </div>
				  
                  <div class="single_view_media">

                    <!-- BEGIN GALLERY -->
                      <?php foreach ($xmlResult->listings->listing as $item): ?> <!-- begin foreach $item -->
                        
                        <?php if ($item->media->images->image != null && count($item->media->images->image) > 0): ?> <!-- begin if images not empty -->
                        
                        <div class="ls-wp-fullwidth-container" style="height: 500px;">  
                          <div class="gallery">

                           
                          
                            <?php $i = 0; foreach ($item->media->images->image as $image): ?> <!-- begin foreach $image -->
                            
                                
                                <?php if ($image->tags == 'Interior' || $image->tags == 'Exterior' || $image->tags == '' ) {?><!-- exclude floorplan from gallery flow -->
                                  <div class="gallery-cell">
                                    <img class="itemImage" src="<?php echo $image->baseurl . "/" . $image->filename; ?>"/>
                                    <?php $i++; ?>
                                  </div>
                                <?php } ?>

                            <?php endforeach; ?> <!-- end foreach $image -->

                          </div>
                        <!-- BEGIN THUMBNAILS -->
        
                                    <div class="ls-thumbnail-wrapper" style="position:fixed; z-index:50; bottom:50px; height:60px; width:auto; background: rgba(255,255,255,0.1); visibility: visible;">
                                        <div class="ls-thumbnail">
                                            <div class="ls-thumbnail-inner">
                                                <div class="ls-thumbnail-slide-container">
                                                    <div class="ls-thumbnail-slide" style="height: 60px; margin-left: 0px;">
          
                                                          
                                                          <?php $i = 0; foreach ($item->media->images->image as $image): ?>
                                                            
                                                            <!-- exclude floorplan from gallery flow -->
                                                            <?php if ($image->tags == 'Interior' || $image->tags == 'Exterior' || $image->tags == '' ) {?>
                                                              
                                                              <a class="ls-thumb-<?php echo $i; ?>" href="#" style="width: 100px; height: 60px;">
        
                                                                <img src="includes/show_image.php?file=<?php echo urldecode($image->url); ?>" /> 
                                                                
                                                                <?php $i++; ?>
                                                              
                                                              </a>
                                                          
                                                            <?php } ?>
        
                                                      <?php endforeach; ?>
        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <!-- END THUMBNAILS -->
                          </div>  
                        <?php endif; ?> <!-- begin if images not empty -->

                    <!-- END GALLERY -->

                            
                    
                    <!-- BEGIN FLOORPLAN -->
                    <div id="floorplan_container">
                      <?php foreach ($xmlResult->listings->listing as $item): ?>
                        
                        <!-- if images not empty -->
                        <?php if ($item->media->images->image != null && count($item->media->images->image) > 0): ?>
                         <?php $i = 0; foreach ($item->media->images->image as $image): ?>
                          <?php if ($image->tags == 'Floorplan Quick (JPG)') {?>
                                <img style="max-height:700px; margin-left:200px;" class="itemImage" src="<?php echo $image[$i]->baseurl . "/" . $image[$i]->filename; ?>"/>
                          <?php $floorplan=true; } ?>
                         <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- if documents not empty -->
                        <?php if ($item->media->documents->document != null && count($item->media->documents->document) > 0){ ?>
                          <?php $i = 0; foreach ($item->media->documents->document as $document): ?>
                              <!-- if floorplan PDF       -->
                              <?php if ($document->tags == 'Floorplan Enhanced (PDF)') {?>
                              <br>
                                <a href="<?php echo $document[$i]->url; ?>" target="_blank">Download Floor Plan PDF</a>
                              <?php $floorplan=true; } ?>
                              
                              <?php $i++; ?>
                          <?php endforeach; ?>
                        <?php } ?>
                        <!-- end if documents not empty -->

                      <?php endforeach; ?>
                    </div>
                    <!-- END FLOORPLAN -->

                     <!-- BEGIN EPC -->
                    <div id="epc_container">
                      <?php foreach ($xmlResult->listings->listing as $item): ?>
                        
                        <!-- if images not empty -->
                        <?php if ($item->media->images->image != null && count($item->media->images->image) > 0): ?>
                         <?php $i = 0; foreach ($item->media->images->image as $image): ?>
                          <?php if ($image->tags == 'EPC') {?>
                                <img style="max-height:700px; margin-left:200px;" class="itemImage" src="<?php echo $image[$i]->baseurl . "/" . $image[$i]->filename; ?>"/>
                          <?php $epc=true; } ?>
                         <?php endforeach; ?>
                        <?php endif; ?>

                      <?php endforeach; ?>
                    </div>
                    <!-- END EPC -->
                    
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
               <p><?php echo $item->data->tenure__c; ?></p>
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
              <ul class="info_panel_nav">

                      <!-- if documents not empty -->
                        <?php if ($item->media->documents->document != null && count($item->media->documents->document) > 0){ ?>
                          <?php $i = 0; foreach ($item->media->documents->document as $document): ?>
                              <!-- if Brochure PDF -->
                              <?php if ($document->tags == 'Brochure') {?>
                                 <li><a href="<?php echo $document[$i]->url; ?>">PRINT</a></li>
                              <?php } ?>
                              <?php $i++; ?>
                          <?php endforeach; ?>
                        <?php } ?>
                      <!-- end if documents not empty -->
               
                <li><a href="#">SAVE</a></li>
                <li><a href="#">SHARE</a></li>
              </ul>
            <br>
            <p><?php echo $item->data->pba__description_pb__c; ?> </p>
                
            </div> <!-- // end panel -->  
            <div class="panel half_width">  
                        <ul class="itemFacts">
                          <li><h4>FAST FACTS</h4></li>
                          <br>
                          <li class="itemFact">Type: <?php echo  $item->data->pba__propertytype__c; ?></li>
                          
                          <!-- IF RENT -->
                          <?php if($listing_type == 'Rent'){ ?>
                          <li class="itemFact">Weekly Rent: <?php $weekly = number_format((float)$item->data->weekly_rent__c); $monthly = number_format((float)($weekly * 52)/12); echo  '&#163;'.$weekly.' (&#163;'.$monthly.'/month)'; ?></li>
                          <?php } ?>
                          <!-- IF RENT END -->
                          
                          <!-- IF LEASHOLD -->
                          <?php if( $tenure == 'Leasehold' ){ ?>
                          <li class="itemFact">Years Remaining: <?php echo  $item->data->years_remaining_leasehold_only__c; ?></li>
                          <?php } ?>
                          <!-- IF LEASHOLD END-->

                          <!-- IF SALE -->
                          <?php if($listing_type == 'Sale'){ ?>
                          <li class="itemFact">Price: &#163;<?php echo number_format((float) $item->data->pba__listingprice_pb__c); ?></li>
                          <?php } ?>
                          <!-- IF SALE END-->
                          
                          <br>
                          <?php if(!empty($item->data->ff_award__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-award"></i><?php echo $item->data->ff_award__c; ?></li>
                          <?php } ?>
                           <?php if(!empty($item->data->ff_bottle__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-beaker"></i><?php echo $item->data->ff_bottle__c; ?></li>
                          <?php } ?>
                           <?php if(!empty($item->data->ff_brick__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-tag"></i><?php echo $item->data->ff_brick__c; ?></li>
                          <?php } ?>
                           <?php if(!empty($item->data->ff_built__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-thumbs-up"></i><?php echo $item->data->ff_built__c; ?></li>
                          <?php } ?>
                           <?php if(!empty($item->data->ff_champagne__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-wine"></i><?php echo $item->data->ff_champagne__c; ?></li>
                          <?php } ?>
                           <?php if(!empty($item->data->ff_desiner__c)){ ?>
                            <li class="itemFact"><i class="demo-icon icon-user"></i><?php echo $item->data->ff_desiner__c; ?></li>
                          <?php } ?>
                          <br>

                          <li class="itemFact">Room list: <?php echo  $item->data->room_list__c; ?></li>
                          <li class="itemFact">Local Authority: <?php echo  $item->data->local_authority__c; ?></li>
                          <li class="itemFact">Council Tax Band: <?php echo  $item->data->council_tax_band__c; ?></li>
                          <li class="itemFact">Beds: <?php echo  $item->data->pba__bedrooms_pb__c; ?></li>
                          <li class="itemFact">Baths: <?php echo  $item->data->pba__fullbathrooms_pb__c; ?></li>
                          <li class="itemFact">Sq.ft: <?php echo  number_format((float) $item->data->pba__totalarea_pb__c); ?></li>
                          <br>
                          <li class="itemFact"><em>lat & long: <?php echo  $item->data->pba__latitude_pb__c .' | '. $item->data->pba__longitude_pb__c; ?></em></li>
                          <li class="itemFact"><em>Video: <a href="<?php echo $item->media->videos->video->url; ?>"><?php echo $item->media->videos->video->title; ?></a></em> </li>  
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
                  floorplan = <?php echo ("'".$floorplan."';");  ?>
                  epc = <?php echo ("'".$epc."';");  ?>
                </script>
       <?php } ?>

                 <?php endforeach; ?>
                <?php } ?>
                <!-- END ELSE EMPTY RESULT -->

<script src="js/px-video.js"></script>
<script type="text/javascript" src="js/single_view.js"></script>
</body>
</html>