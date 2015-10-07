<?php
$SuccessVis = "none";
$reference="";
$size_from = "";
$price_to = "";
$price_from = "";
$reference = "";

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

// http://spacestation.force.com/pba__WebserviceListingsQuery
// token=29bac54992830136725442d9c3c5ea086377d8065900263fb21986a539e72083
// fields=
// name;
// pba__ListingPrice_pb__c;
// pba__description_pb__c;
// pba__Bedrooms_pb__c;
// pba__FullBathrooms_pb__c;
// pba__totalarea_pb__c&
// pba__ListingPrice_pb__c=[1000000;1500000]&
// pba__totalarea_pb__c=[2500;]&
// orderby=pba__ListingPrice_pb__c;
// ASC&
// getvideos=true&
// itemsperpage=25&
// page=0

	$reqArray = array("token" 			=> PB_SECURITYTOKEN,
					  // "fields"			=> "ID;name;pba__ListingPrice_pb__c;pba__PropertyType__c;pba__Bedrooms_pb__c;pba__TotalArea_pb__c;pba__Description_pb__c",
					  "fields"			=> "ID;name;pba__ListingPrice_pb__c;pba__description_pb__c;pba__Bedrooms_pb__c;pba__FullBathrooms_pb__c;pba__totalarea_pb__c;pba__Description_pb__c",
		              "itemsperpage"	=> "25",
					  //"External_Listing__c" => "true",
		              "page" 			=> $page ,
		              "orderby"			=> "pba__ListingPrice_pb__c;ASC",
		              "getvideos"		=> "true",
		              "debugmode"		=> "true"
		              );

	if (!empty($reference))		$reqArray["name"] 								= '%' . $reference . '%';
	if (!empty($priceParam))	$reqArray["pba__ListingPrice_pb__c"] 			= $priceParam;
	if (!empty($size_from)) 	$reqArray["pba__TotalArea_pb__c"] 				= $sizeParam;
	if (!empty($default_bedrooms_from)) $reqArray["pba__Bedrooms_pb__c"] 				= $bedsParam;
	
	$query 		= http_build_query($reqArray,'','&');

	$xmlResult 	= simplexml_load_file(PB_WEBSERVICEENDPOINT . "?" . $query);
	
	if (!empty($xmlResult->Error)) {
		$errorMessage = $xmlResult->Error;
	} else {
		$previousPage 	= $page > 0 ? $page - 1 : null;
		$nextPage 		= ($xmlResult->listingsPerPage * ($page+1) < $xmlResult->numberOfListings) ? $page + 1 : null;
	}
	
	//$DisplayQuery = $query;
	// $DisplayxmlResult = $xmlResult;
	
?>

<head>

<title>Propertybase Australia</title>

<meta name="author" content="Rod Gilbody"/>

<style type="text/css">
a:hover	{color: #3999ca;}

a:link {text-decoration:none;}    /* unvisited link */
a:visited {text-decoration:none;} /* visited link */
a:hover {text-decoration:none;}   /* mouse over link */
a:active {text-decoration:none;}  /* selected link */


</style>
<style>
<!--
-->
</style>


<script type="text/javascript">
// Popup window code
function newPopup(url) {
	popupWindow = window.open(
		url,'popUpWindow','height=900,width=900,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes')
}
</script>


    <script type="text/javascript">
	
	
    function checkSubmit() {
        
        // Please enter all ID's of your Multi-Select-Fields into this array.
        multiPicklistFields = new Array("pba__Request__c.pba__PropertyType__c","pba__Request__c.View__c");
        
        // Enter the name of your form here. DEFAULT: web2prospect
        var formName = "web2prospect";
        
        // PLEASE DONT'T CHANGE THE JAVASCRIPT-CODE AFTER THIS LINE
        for (var y=0; y < multiPicklistFields.length; y++) {
            var string = "";
            var field = multiPicklistFields[y];
            for (var i=0; i < document.forms[formName].elements[field].length; i++) {
                if (document.forms[formName].elements[field][i].checked) {
                    string += document.forms[formName].elements[field][i].value + "; ";
                }
            }
            string = string.substr(0, string.length - 2);
            field = document.createElement("input");
            field.type = "hidden";
            field.name = multiPicklistFields[y];
            field.value = string;
            document.forms[formName].appendChild(field);
        }
        return true;
    }
    </script>

<html>

<style>
body			{ font-family: Helvetica, Arial, sans-serif; font-size: 0.91em; line-height: 1.5em;}
span.label      {color:#FFF;}
p.white         {color:#FFF;}
td 				{vertical-align: top;border-right: 1px dotted; padding: 0 10px;}
ul.result 		{ margin: 0; padding: 0; }
li.itemFact 	{ list-style-type: none; margin: 0; padding: 0;}
li.item 		{ list-style-type: none; margin: 0; padding: 0; border-bottom: 1px solid #ccc; padding-top: 1em;}
span.itemImage 	{ float: left; layout: block; width: 100px; height: 100px; margin-right: 5px; border: 1px solid #DDDDDD; padding: 2px; }
img.itemImage 	{ height: 100px; width: 100px;}
ul.itemFacts 	{height: 120px; }
div#result 		{width: 580px;}
</style>


</head>

<body bgcolor="#092A3D" link="#092A3D" vlink="#092A3D" alink="#092A3D">

<!-- BEGIN ERROR -->
<?php if (!empty($errorMessage) || !empty($DisplayQuery)|| !empty($DisplayxmlResult) ): ?>
	<div id="error" style="color: white">
		<?php echo $errorMessage;?>
		<br>
		<br>
		<?php echo $DisplayQuery;?>
		<br>
		<br>
		<?php echo $DisplayxmlResult;?>		
		<br>
		<br>
	</div>
<?php else: ?>
<?php endif;?>	

<!-- END ERROR -->

<div align="center">
  

  <table border="0" cellpadding="1" cellspacing="0" style="border-collapse: collapse; " bordercolor="#FFFFFF" width=100% height="250"id="AutoNumber1" bgcolor="#FFFFFF" >
    <tr>
      <td bgcolor="#092A3D" style="border-style: none; border-width: none" >&nbsp;</td>
          
      <td width="960" bgcolor="#092A3D" style="border-style: none; border-width: none; color: #FFF;" >
     
            
                
<!-- BEGIN SEARCHFORM -->
<p><br>
</p>
<h2>Simple Property Search</h2>
<div id="searchform">
	<form method="post" id="theForm">
	
	<input type="hidden" name="page" value="0">

	<table>
		<tr>
			<td>
				<span class="label">Property Address</span><br/>
				<input type="text" name="reference" value="<?php echo $reference;?>" style="width: 170px;">
			</td>
			<td>
				<span class="label">Price from</span><br/>
				<select name="price_from">
					<option value="0">all</option>
					<option value="1000">1000</option>
					<option value="3000">3000</option>
					<option value="5000">5000</option>
					<option value="10000">10000</option>
				</select>
				<br>
				<span class="label">Price to<br>
				</span>
				<!-- <input type="text" name="price_to"   value="<?php echo $price_to;?>" style="width: 100px;"> -->
				<select name="price_to">
					<option value="0">all</option>
					<option value="50000">50k</option>
					<option value="200000">200k</option>
					<option value="500000">500k</option>
					<option value="800000">800k</option>
				</select>
			</td>
			<td>
				<span class="label">Sqm from</span><br/>
				<input type="text" name="size_from" value="<?php echo $size_from;?>" style="width: 80px;">
			</td>		
			<td>
				<span class="label">Min. bedrooms</span><br/>
				<select class="input" name="bedrooms_from" Selected="<?php echo $bedrooms_from;?>" size="1">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5+</option>
				</select>
			</td>
			
			<td><input class="formButton" value="Search" type="submit">			  <br>
			  <br>
			  <img src="images/PBlogo.png" width="auto" height="62">
			</td>
		</tr>
	</table>
	</form>
</div>
   
      <td bgcolor="#092A3D"style="border-style: none; border-width: none" >&nbsp;</td>
              
    </tr>
      </table>
      
         <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; " bordercolor="#FFFFFF" width=100% id="AutoNumber1" bgcolor="#FFFFFF" >
<tr>
    
          <td style="border-style: none; border-width: none" >&nbsp;</td>
          
      <td  width= 100% valign="top" style="border-style: none; border-width: none">
        <div style="border-left-style: solid; border-left-width: 0; padding-left: 4">
          
          <table border="0" cellpadding="4" cellspacing="0" style="font-family: Verdana, Geneva, sans-serif; border-collapse: collapse" width="100%" id="AutoNumber2" bordercolorlight="#FFFFFF" bordercolordark="#FFFFFF">
   <tr>
                <td style="border-style: none; border-width: none" >&nbsp;</td>

              <td width="650" height="500" valign="top" class="t1" td>
       
<!-- END SEARCHFORM -->



	
	<!-- BEGIN EMPTY RESULT -->
	<?php if ($doSearch  && ($xmlResult == null || count($xmlResult->listings->listing) == 0)): ?>
		<div id="noresult">
			no listings found: </div>
	<?php else: ?>
		<!-- END EMPTY RESULT -->
		
		<!-- BEGIN RESULT -->
		<div id="result">
			<ul class="result">
			<?php foreach ($xmlResult->listings->listing as $item): ?>
			<li class="item">

				<span class="itemImage">
					<?php if ($item->media->images->image != null && count($item->media->images->image) > 0): ?>
					<a href= "JavaScript:newPopup('<?php echo $item->media->images->image[0]->baseurl . "/" . $item->media->images->image[0]->filename; ?> ');"> 
                    <img class="itemImage" src="<?php echo $item->media->images->image[0]->baseurl . "/thumbnail/" . $item->media->images->image[0]->filename; ?>"/></a>
					<?php endif; ?>
				</span>
					
				<ul class="itemFacts">
				
					<li class="itemFact"><strong>   
					
					<a href= "listing.php?id=<?php echo  $item->data->id; ?> "><?php echo  $item->data->name; ?></strong> </a>
					
					
					
					</li>
					<li class="itemFact">Price: $<?php echo number_format((float) $item->data->pba__listingprice_pb__c); ?></li>			
				  <li class="itemFact">Beds: <?php echo  $item->data->pba__bedrooms_pb__c; ?></li>			
					<li class="itemFact">Type: <?php echo  $item->data->pba__propertytype__c; ?></li>			
					<li class="itemFact">Sqm: <?php echo  number_format((float) $item->data->pba__totalarea_pb__c); ?></li>	
				</ul>
									
			</li>
			<?php endforeach; ?>
			</ul>
			
			<div id="paging">
			
				<?php if($nextPage !== null):?>
					<div style="float: right;">
					<form method="post">
						<input type="hidden" name="page" value="<?php echo $nextPage;?>">
						<input type="hidden" name="reference" value="<?php echo $reference;?>">
						<input type="hidden" name="price_from" value="<?php echo $price_from;?>">
						<input type="hidden" name="price_to" value="<?php echo $price_to;?>">
						<input type="hidden" name="size_from" value="<?php echo $size_from;?>">
						<input type="hidden" name="bedrooms_from" value="<?php echo $bedrooms_from;?>">						
						<input class="formButton" value="Next Page >>" type="submit">					
					</form>
					</div>
				<?php endif;?>				
			
				<?php if($previousPage !== null):?>
					<form method="post">
						<input type="hidden" name="page" value="<?php echo $previousPage;?>">
						<input type="hidden" name="reference" value="<?php echo $reference;?>">
						<input type="hidden" name="price_from" value="<?php echo $price_from;?>">
						<input type="hidden" name="price_to" value="<?php echo $price_to;?>">
						<input type="hidden" name="size_from" value="<?php echo $size_from;?>">
						<input type="hidden" name="bedrooms_from" value="<?php echo $bedrooms_from;?>">					
						<input class="formButton" value="<< Previous Page" type="submit">					
					</form>
				<?php endif;?>
				
			</div>
		</div>
		<!-- END RESULT -->
	<!-- END ELSE EMPTY RESULT -->
	
<?php endif; ?>
<!-- END ELSE ERROR -->
<td  width="250" style="border-style: none; border-width: none" >&nbsp;
                <div id="Regform" class="wpcf7">
				
				
				<iframe src="form.php" width="350" height="600" scrolling="no" style="overflow:hidden; margin-top:-4px; margin-left:-4px; border:none;"></iframe>
				
				</td>
              <td style="border-style: none; border-width: none" >&nbsp;</td>
            </tr>
                       
          </table>
        </div>
      </td>
      
           
            
    </tr>
  </table>
</div>

</div>

</body>

</html>