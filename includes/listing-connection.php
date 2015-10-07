<?php

// Web listing query code
	define("PB_WEBSERVICEENDPOINT"	, "http://spacestation.force.com/pba__WebserviceListingsQuery"); // Please enter your Propertybase webservice endpoint here
	define("PB_SECURITYTOKEN"		, "29bac54992830136725442d9c3c5ea086377d8065900263fb21986a539e72083"); // Please enter your security token here
	
	if (empty($_POST["bedrooms_from"])){
		$default_bedrooms_from = 1;
	}
		else{ 
		$default_bedrooms_from = $_POST["bedrooms_from"];
		}
		
	function getFromToParam($from,$to){
		
		if (empty($from) && empty($to)) return null;
	
		$p = '[';
		if (!empty($from)) $p .= $from;
		$p .= ';';
		if (!empty($to)) $p .= $to;
		$p .= ']';
				
		return $p;	 	
	}
	
	// if(isset($_POST['notify_box'])){ $notify = $_POST['notify_box']; }
	
	if(isset($_POST["reference"])){$reference 		= $_POST["reference"];}
	if(isset($_POST["price_from"])){$price_from 	= $_POST["price_from"];}
	if(isset($_POST["price_to"])){$price_to 		= $_POST["price_to"];}
	if(isset($_POST["size_from"])){$size_from 		= $_POST["size_from"];}
	if(isset($_POST["bedrooms_from"])){$bedrooms_from 	= $default_bedrooms_from ;}
	
	
	if(isset($_POST["page"])){$page 			= $_POST["page"];}
	
	if(isset($_POST["price_from"])){$priceParam 	= getFromToParam($price_from	,$price_to);}
	if(isset($_POST["size_from"])){$sizeParam 		= getFromToParam($size_from		,null);}
	$bedsParam 		= getFromToParam($default_bedrooms_from	,null);
	
	$doSearch = !(empty($reference) && empty($priceParam) && empty($sizeParam)&& empty($bedsParam)  );
	
	if(isset($_POST["page"])){if (!is_numeric($page) || $page < 0 ) $page = 0;}else{$page = 0;}
	
	$xmlResult		= null;
	$errorMessage 	= null;
	
	$reqArray = array("token" 			=> PB_SECURITYTOKEN,
					  "fields"			=> "name;pba__PropertyType__c;pba__ListingPrice_pb__c;pba__Bedrooms_pb__c;pba__FullBathrooms_pb__c;pba__totalarea_pb__c;pba__Description_pb__c;pba__Longitude_pb__c;pba__Latitude_pb__c;pba__Address_pb__c;",
					  "itemsperpage"	=> "1",
					  // "pba__Status__c"	=> "Available",
					  "page" 			=> $page ,
					  "id"				=> $_GET['id'],
					  // "orderby"			=> "pba__ListingPrice_pb__c;ASC",
					  "getvideos"		=> "true",
		              "debugmode"		=> "true"
					  );
	
	if (!empty($reference))		$reqArray["name"] 								= '%' . $reference . '%';
	if (!empty($priceParam))	$reqArray["pba__ListingPrice_pb__c"] 			= $priceParam;
	if (!empty($size_from)) 	$reqArray["pba__TotalArea_pb__c"] 				= $sizeParam;
	if (!empty($default_bedrooms_from)) $reqArray["pba__Bedrooms_pb__c"] 				= $bedsParam;
	
	$query 		= http_build_query($reqArray,'','&');
	//echo $query;
	$xmlResult 	= simplexml_load_file(PB_WEBSERVICEENDPOINT . "?" . $query);
	
	if (!empty($xmlResult->errorMessages->message)) {
		$errorMessage = 'Error: '.$xmlResult->errorMessages->message;
	} else {
		$debugMessages = 'Debug: '.$xmlResult->debugMessages->message;
		$previousPage 	= $page > 0 ? $page - 1 : null;
		$nextPage 		= ($xmlResult->listingsPerPage * ($page+1) < $xmlResult->numberOfListings) ? $page + 1 : null;
	}

	$DisplayQuery = $query;
	// $DisplayxmlResult = $xmlResult;
	// $errorMessage = 'error: '.$xmlResult->debugMessages;
	$DisplayDebug = $debugMessages;

?>