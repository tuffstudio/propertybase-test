<?php include 'includes/connection.php' ?>

<html>
	<head>

	<title>Propertybase</title>

	<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.2/masonry.pkgd.min.js"></script>
	<script type="text/javascript" src="http://imagesloaded.desandro.com/imagesloaded.pkgd.min.js"></script>
	<script type="text/javascript" src="http://jquery-list-grid.ssdtutorials.com/js/cookie.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.5.18/webfont.js"></script>

	<link rel="stylesheet" type="text/css" href="css/pb-style.css">

	
	</head>

<body>

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

	<div id="container_multiple_view">
	<!-- BEGIN SEARCHFORM -->
		<h2>Simple Property Search</h2>
		<div id="searchform">
	<form method="post" id="theForm">

		<!-- RECORD TYPES -->
				<fieldset id="record-types">
					<input type="radio" name="recordtypes" id="all-types" value="" checked="checked">
					<label for="all-types">ALL</label>
					<br>
					<input type="radio" name="recordtypes" id="sale" value="sale"> 
					<label for="sale">SALE</label> 				
					<br>
  					<input type="radio" name="recordtypes" id="rent" value="rent">
  					<label for="rent">RENT</label> 		
  				</fieldset>

  			<fieldset id="parameters">
				<input type="text" name="reference" placeholder="Property Address" value="<?php echo $reference;?>" style="width: 170px;">
				<br>
				<input class="slider" id="slider1" type="range" min="0" max="100" value="0" oninput="showValue(value, 1);" onchange="showValue(value, 1);" />
				<!-- // DETECT CURRENT RECORD TYPE -->
				
				<!-- <?php echo "<script> console.log('type: ' ".$recordtypes.") </script>"; ?> -->
				
				<!-- //////// if SALE or ALL /////////// -->
				<?php if(isset($recordtypes) && ($recordtypes == 'sale' ||  $recordtypes == 'sale;rent') ){ ?>
				<select name="price_from">
					<option value="0">MIN PRICE</option>
					<option value="1000">1000</option>
					<option value="3000">3000</option>
					<option value="5000">5000</option>
					<option value="10000">10000</option>
				</select>

				<select name="price_to">
					<option value="0">MAX PRICE</option>
					<option value="50000">50k</option>
					<option value="200000">200k</option>
					<option value="500000">500k</option>
					<option value="800000">800k</option>
				</select>
				<br>
				<?php } ?>
				<!-- //////// end if SALE or ALL /////////// -->
				<select name="propertytype">
					<option value="">Type</option>
					<option value="Apartment">Apartment</option>
					<option value="Commercial">Commercial</option>
					<option value="Condo">Condo</option>
					<option value="Detached">Detached</option>
					<option value="End Terrace">End Terrace</option>
					<option value="House">House</option>
					<option value="Loft">Loft</option>
					<option value="Maisonette">Maisonette</option>
					<option value="Multi Family">Multi Family</option>
					<option value="Other">Other</option>
					<option value="Penthouse">Penthouse</option>
					<option value="Single Family">Single Family</option>
					<option value="Studio">Studio</option>
					<option value="Terraced">Terraced</option>
					<option value="Townhouse">Townhouse</option>
					<option value="Villa">Villa</option>
				</select>
		
			<!-- <fieldset> -->
				<!-- <span class="label">Min. bedrooms</span><em> <?php echo $_POST["bedrooms_from"] ?></em><br/> -->
				<select name="bedrooms_from">
					<option value="1">Bedrooms</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5+</option>
				</select>
			<!-- </fieldset> -->
			<br>
			<select name="bathrooms_from">
					<option value="1">Bathrooms</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5+</option>
				</select>
			<!-- <input class="formButton" value="Search" type="submit"> -->
			<!-- <input type="text" name="size_from" placeholder="Sqm from" value="<?php echo $size_from;?>" style="width: 80px;"> -->
			<!-- <select name="size_from">
					<option value="0">Sq.ft</option>
					<option value="500">500</option>
					<option value="1000">1000</option>
					<option value="1500">1500</option>
					<option value="2000">2000+</option>
				</select> -->

				<div class="filter__submit">
						<a href="#" class="js-clear-all-filters">Clear Filters</a>
						<!-- <button type="submit" class="js-run-pb-search">See Results</button> -->
				</div>
			
		</fieldset>

		<fieldset id="bottomFilters">
				<fieldset id="property-status">
					<input type="radio" name="propertystatus" id="all-status" value="" checked>
					<label for="all-status">All</label>
					
					<input type="radio" name="propertystatus" id="active" value="Active">
					<label for="active">Active</label>  				
  					<!-- //////// if SALE or ALL /////////// -->
				<?php if(isset($recordtypes) && ($recordtypes == 'sale' ||  $recordtypes == 'sale;rent') ){ ?>
  					<input type="radio" name="propertystatus" id="sold" value="Sold">
  					<label for="sold">Sold</label>	
  				<?php } ?>	
  					<input type="radio" name="propertystatus" id="blocked" value="Blocked">
  					<label for="blocked">Blocked</label>	
  				<!-- //////// if not SALE or ALL = RENT /////////// -->
				<?php if(isset($recordtypes) && ($recordtypes != 'sale' ||  $recordtypes == 'sale;rent') ){ ?>	
  					<input type="radio" name="propertystatus" id="rented" value="Rented">
  					<label for="rented">Rented</label>	
  				<?php } ?>		
  					<input type="radio" name="propertystatus" id="archived" value="Archived">
  					<label for="archived">Archived</label>	
  					
  					<input type="radio" name="propertystatus" id="in-preparation" value="In Preparation">
  					<label for="in-preparation">In Preparation</label>	
  					
  					<input type="radio" name="propertystatus" id="in-acquisition" value="In Acquisition">	
  					<label for="in-acquisition">In Acquisition</label>
  					
  					<input type="radio" name="propertystatus" id="reserved" value="Reserved">		
  					<label for="reserved">Reserved</label>
  				</fieldset>


				<select name="orderby">
					<option value="">Order By:</option>
					<option value="CreatedDate;DESC">Created Date DESC</option>
					<option value="CreatedDate;ASC">Created Date ASC</option>
					<option value="pba__ListingPrice_pb__c;DESC">Price DESC</option>
					<option value="pba__ListingPrice_pb__c;ASC">Price ASC</option>
					<option value="pba__Bedrooms_pb__c;DESC">Bedrooms DESC</option>
					<option value="pba__Bedrooms_pb__c;ASC">Bedrooms ASC</option>
				</select>

				<select name="itemsperpage">
					<option value="20">20</option>
					<option value="30">30</option>
					<option value="50">50</option>
				</select>
		</fieldset>		
	</form>
		</div>
		<div class="view">
			<a id="list" href="#"> List View </a><span>|<span>
    		<a id="grid" href="#"> Grid View </a>
		</div>
	<!-- END SEARCHFORM -->      
             
	
	<!-- BEGIN EMPTY RESULT -->
	<?php if ($doSearch  && ($xmlResult == null || count($xmlResult->listings->listing) == 0)){ ?>
		<div id="noresult">
			no listings found: 
		</div>
	<?php }else{ ?>
		<!-- END EMPTY RESULT -->
		
		
		<!-- PAGINATION -->
			<div id="paging">
			
				<?php if($nextPage !== null):?>
					<form method="post">
						<input type="hidden" name="page" value="<?php echo $nextPage;?>">
						<input type="hidden" name="reference" value="<?php echo $reference;?>">
						<input type="hidden" name="price_from" value="<?php echo $price_from;?>">
						<input type="hidden" name="price_to" value="<?php echo $price_to;?>">
						<!-- <input type="hidden" name="size_from" value="<?php echo $size_from;?>"> -->
						<input type="hidden" name="bedrooms_from" value="<?php echo $bedrooms_from;?>">	
						<input type="hidden" name="bathrooms_from" value="<?php echo $bathrooms_from;?>">	
						<input type="hidden" name="orderby" value="<?php echo $orderby;?>">	
						<input type="hidden" name="itemsperpage" value="<?php echo $itemsperpage;?>">
						<input type="hidden" name="recordtypes" value="<?php echo $recordtypes;?>">	
						<input type="hidden" name="propertytype" value="<?php echo $propertytype;?>">
						<input type="hidden" name="propertystatus" value="<?php echo $propertystatus;?>">						
						<input class="formButton" value="Next Page >>" type="submit">					
					</form>
				<?php endif;?>				
			
				<?php if($previousPage !== null):?>
					<form method="post">
						<input type="hidden" name="page" value="<?php echo $previousPage;?>">
						<input type="hidden" name="reference" value="<?php echo $reference;?>">
						<input type="hidden" name="price_from" value="<?php echo $price_from;?>">
						<input type="hidden" name="price_to" value="<?php echo $price_to;?>">
						<!-- <input type="hidden" name="size_from" value="<?php echo $size_from;?>"> -->
						<input type="hidden" name="bedrooms_from" value="<?php echo $bedrooms_from;?>">
						<input type="hidden" name="bathrooms_from" value="<?php echo $bathrooms_from;?>">	
						<input type="hidden" name="orderby" value="<?php echo $orderby;?>">
						<input type="hidden" name="itemsperpage" value="<?php echo $itemsperpage;?>">
						<input type="hidden" name="recordtypes" value="<?php echo $recordtypes;?>">		
						<input type="hidden" name="propertytype" value="<?php echo $propertytype;?>">
						<input type="hidden" name="propertystatus" value="<?php echo $propertystatus;?>">					
						<input class="formButton" value="<< Previous Page" type="submit">					
					</form>
				<?php endif;?>
				<br>
			</div>
			<!-- END PAGINATION -->
		<!-- BEGIN RESULT -->
		<div id="result">

			<div id="results">
				<?php foreach ($xmlResult->listings->listing as $item): ?>
    				<div class="post <?php echo $item->data->pba__status__c; ?>">
    					<h1><a href="listing.php?id=<?php echo  $item->data->id; ?> "><?php echo  $item->data->name; ?></a></h1>
    					<p><?php echo $item->data->pba__status__c; ?></p>
    					<?php if ($item->media->images->image != null && count($item->media->images->image) > 0){ ?>
    				    <div class="thumbnail">
    				    	<a href= "listing.php?id=<?php echo  $item->data->id; ?>"> 
    				    	<img src="<?php echo $item->media->images->image[0]->baseurl . "/thumbnail/" . $item->media->images->image[0]->filename; ?>"></a>
    				    </div>
    				    <?php }; ?>
    				    
    				    <ul>
							<li class="itemFact">Price: $<?php echo number_format((float) $item->data->pba__listingprice_pb__c); ?></li>			
							<li class="itemFact">Beds: <?php echo  $item->data->pba__bedrooms_pb__c; ?></li>	
							<li class="itemFact">Baths: <?php echo  $item->data->pba__fullbathrooms_pb__c; ?></li>			
							<li class="itemFact">Type: <?php echo  $item->data->pba__propertytype__c; ?></li>			
							<li class="itemFact">Sq.ft: <?php echo  number_format((float) $item->data->pba__totalarea_pb__c); ?></li>	
    				    </ul>
    				</div>
    			<?php endforeach; ?>
			</div>

			
		</div>
		<!-- END RESULT -->
	
	<?php }; ?>
	<!-- END ELSE ERROR -->


        </div>
      </td>
      
           
            

	</div>

	</div>

<script type="text/javascript">

//CHANGE SELECT OPTION FILTERS BASED ON CURRENT SELECTION	
    $("select[name='price_from'] option[value='"+<?php Print($price_from)  ?>+"']").prop('selected', true);
    $("select[name='price_to'] option[value='"+<?php Print($price_to) ?>+"']").prop('selected', true);
    $("select[name='bedrooms_from'] option[value='"+<?php Print($bedrooms_from) ?>+"']").prop('selected', true);
    $("select[name='bathrooms_from'] option[value='"+<?php Print($bathrooms_from) ?>+"']").prop('selected', true);
    // $("select[name='size_from'] option[value='"+<?php Print($size_from) ?>+"']").prop('selected', true);
    $("select[name='itemsperpage'] option[value='"+<?php Print($itemsperpage) ?>+"']").prop('selected', true);
    <?php if( $default_recordtypes == "sale;rent" ){ ?>
     $("input[name='recordtypes']").first().prop('checked', true);
    <?php }else{ ?>
     $("input[name='recordtypes'][value=<?php echo("'".$recordtypes."'"); ?>]").prop('checked', true);
     <?php } ?>
  
    $("select[name='orderby'] option[value=<?php echo("'".$orderby."'"); ?>]").prop('selected', true);
    <?php 
    	if (!empty($default_propertytype)) echo( "$(\"select[name='propertytype'] option[value='".$default_propertytype."']\").prop('selected', true);");
    	if (!empty($default_propertystatus)) echo( "$(\"input[name='propertystatus'][value='".$default_propertystatus."']\").prop('checked', true);"); 
    ?>
    


</script>
	<script src="js/main.js"></script>
</body>

</html>


