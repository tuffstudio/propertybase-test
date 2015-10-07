<?php

// VARS
$SuccessVis = "none";
$reference="";
// $size_from = "";
$price_to = 0;
$price_from = 0;
$reference = "";
// my vars
$orderby = ""; //*
$itemsperpage ="";//- 20
$recordtypes ="sale;rent";//= 
$propertytype ="";//+
$propertystatus ="";

// SECURE CONNECTION SETUP
define("PB_WEBSERVICEENDPOINT"	, "http://spacestation.force.com/pba__WebserviceListingsQuery"); // Please enter your Propertybase webservice endpoint here
define("PB_SECURITYTOKEN"		, "29bac54992830136725442d9c3c5ea086377d8065900263fb21986a539e72083"); // Please enter your security token here

// GET FROM TO PARAM FUNCTION
function getFromToParam($from,$to){
	
	if (empty($from) && empty($to)) return null;

	$p = '[';
	if (!empty($from)) $p .= $from;
	$p .= ';';
	if (!empty($to)) $p .= $to;
	$p .= ']';
		 	
	return $p;	 	
}

// SET DEDAULTS FOR SELECTED FILTERS
	
	// BEDROOMS
	if (empty($_POST["bedrooms_from"])){ 					// if variable is empty
		$default_bedrooms_from = 1; 	 					// apply default value
	}else{ 								 					// otherwise 
		$default_bedrooms_from = $_POST["bedrooms_from"]; 	// retrieve POSTEed value
	}
	// BATHROOMS
	if (empty($_POST["bathrooms_from"])){ 					
		$default_bathrooms_from = 1; 	 					
	}else{ 								 					
		$default_bathrooms_from = $_POST["bathrooms_from"]; 	
	}
	// ORDER BY
	if (empty($_POST["orderby"])){
		$default_orderby = ""; //* pba__ListingPrice_pb__c;ASC
	}else{ 
		$default_orderby = $_POST["orderby"];
	}
	// PER PAGE
	if (empty($_POST["itemsperpage"])){
		$default_perpage = "20"; //-
	}else{ 
		$default_perpage = $_POST["itemsperpage"];
	}
	//RECORDTYPES
	if (empty($_POST["recordtypes"])){
		$default_recordtypes = "sale;rent"; //=
	}else{ 
		$default_recordtypes = $_POST["recordtypes"];
	}
	//PROPERTYTYPE
	if (empty($_POST["propertytype"])){
		$default_propertytype = ""; //=
	}else{ 
		$default_propertytype = $_POST["propertytype"];
	}
	//PROPERTYSTATUS
	if (empty($_POST["propertystatus"])){
		$default_propertystatus = ""; //=
	}else{ 
		$default_propertystatus = $_POST["propertystatus"];
	}

// if(isset($_POST['notify_box'])){ $notify = $_POST['notify_box']; }

// if there's variable in query and is not empty
if(isset($_POST["reference"])){$reference 				= $_POST["reference"];}
if(isset($_POST["price_from"])){$price_from 			= $_POST["price_from"];}
if(isset($_POST["price_to"])){$price_to 				= $_POST["price_to"];}
// if(isset($_POST["size_from"])){$size_from 				= $_POST["size_from"];}
if(isset($_POST["bedrooms_from"])){$bedrooms_from 		= $default_bedrooms_from ;}
if(isset($_POST["bathrooms_from"])){$bathrooms_from 	= $default_bathrooms_from ;}
if(isset($_POST["orderby"])){$orderby 					= $default_orderby ;} //*
if(isset($_POST["itemsperpage"])){$itemsperpage 		= $default_perpage ;} //-
if(isset($_POST["recordtypes"])){$recordtypes 			= $default_recordtypes ;} //=
if(isset($_POST["propertytype"])){$propertytype 		= $default_propertytype;} //+
if(isset($_POST["propertystatus"])){$propertystatus 	= $default_propertystatus;} //p
if(isset($_POST["page"])){$page 						= $_POST["page"];}


if(isset($_POST["price_from"])){$priceParam 	= getFromToParam($price_from	,$price_to);}
// if(isset($_POST["size_from"])){$sizeParam 		= getFromToParam($size_from		,null);} //*
$bedsParam 		= getFromToParam($default_bedrooms_from	,null);
$bathsParam 	= getFromToParam($default_bathrooms_from ,null);

$doSearch = !(empty($reference) && empty($priceParam) && empty($sizeParam)&& empty($bedsParam) );

if(isset($_POST["page"])){if (!is_numeric($page) || $page < 0 ) $page = 0;}else{$page = 0;}

$xmlResult		= null;
$errorMessage 	= null;

// fields:
// name;
// pba__ListingPrice_pb__c;
// pba__description_pb__c;
// pba__Bedrooms_pb__c;
// pba__FullBathrooms_pb__c;
// pba__totalarea_pb__c
// pba__ListingPrice_pb__c=[1000000;1500000]
// pba__totalarea_pb__c=[2500;]
// orderby=pba__ListingPrice_pb__c;ASC
// getvideos=true
// itemsperpage=25
// page=0


/////////////// QUERY ARRAY ///////////////
	$reqArray = array("token" 			=> PB_SECURITYTOKEN,
					  "fields"			=> "ID;name;pba__ListingType__c;pba__PropertyType__c;pba__Status__c;pba__ListingPrice_pb__c;pba__description_pb__c;pba__Bedrooms_pb__c;pba__FullBathrooms_pb__c;pba__totalarea_pb__c;",
		              "page" 			=> $page ,
		              "getvideos"		=> "true",
		              "debugmode"		=> "true"

		              );
// add FILTERS to QUERY ARRAY
	if (!empty($reference))		$reqArray["name"] 								= '%' . $reference . '%';
	if (!empty($priceParam))	$reqArray["pba__ListingPrice_pb__c"] 			= $priceParam;
	// if (!empty($size_from)) 	$reqArray["pba__TotalArea_pb__c"] 				= $sizeParam;
	if (!empty($default_bedrooms_from)) $reqArray["pba__Bedrooms_pb__c"] 		= $bedsParam;
	if (!empty($default_bathrooms_from)) $reqArray["pba__FullBathrooms_pb__c"] 	= $bathsParam;
	if (!empty($default_orderby)) $reqArray["orderby"] 							= $orderby; //*
	if (!empty($default_perpage)) $reqArray["itemsperpage"] 					= $itemsperpage; //-
	if (!empty($default_recordtypes)) $reqArray["recordtypes"] 					= $recordtypes; //=
	if (!empty($default_propertytype)) $reqArray["pba__PropertyType__c"] 		= $default_propertytype; //+
	if (!empty($default_propertystatus)) $reqArray["pba__status__c"] 		= $default_propertystatus; //+
	// $reqArray["pba__status__c"] = "Sold";

// BUILD HTTP QUERY STRING
	$query 		= http_build_query($reqArray,'','&');
// RETURN XML RESULT
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