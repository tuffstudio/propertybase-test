<?php


// Web to Prospect Code
	
	$Email = "";
	$Phone = "";
	
	$RunProcess=True;
	$nameErr = $emailErr = $genderErr = $websiteErr = "";
	$FirstName = $LastName = $email = $phone = $Comment = "";
	$NameVis = "none";
	$EmailVis = "none";
	$FormVis = "Block";
	$SuccessVis = "none";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (empty($_POST["c_LastName"])) {
		$nameErr = "Name is required";
		$NameVis = "block";
		$RunProcess = False;
	}


	if (empty($_POST["c_Email"])) {
	$emailErr = "Email is required";
		$EmailVis = "block";
		$RunProcess = false;
	} 
	else{

	$email = test_input($_POST["c_Email"]);
	// check if e-mail address is well-formed
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emailErr = "Invalid email format"; 
			$EmailVis = "block";
			$RunProcess = false;
		}
	}

	$FirstName = test_input($_POST["c_FirstName"]);
	$LastName = test_input($_POST["c_LastName"]);
	$Email = test_input($_POST["c_Email"]);
	$Phone = test_input($_POST["c_Phone"]);
	$Comment = test_input($_POST["r_Web_Comments__c"]);

	if($RunProcess){
		
		#ListingID = $_POST['id'];
		$url = 'https://spacestation.secure.force.com/services/apexrest/pba/webtoprospect/v1/';
		
		$body = array (
		'prospect' => array(
		  'token' => '29bac54992830136725442d9c3c5ea086377d8065900263fb21986a539e72083',
		  'contact' => array (
			'LeadSource' => 'Web',
			'FirstName' => $_POST["c_FirstName"],
			'LastName' => $_POST["c_LastName"],
			'Email' => $_POST["c_Email"],
			'Phone' => $_POST["c_Phone"],
			//'MailingStreet' => $_POST["c_MailingStreet"],
			//'MailingCity' => $_POST["c_MailingCity"],
			//'MailingCountry' => $_POST["c_MailingCountry"],
			'Description' => $_POST["r_Web_Comments__c"]     // **** Note: Last line of Array must not end with a comer
			
		  ), 
		  'request'=> array(
			//'Enquiry_URL__c' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],
			//'Project_Name__c' => 'Specify Project Name in Web Form',
			//'Web_Comments__c' => $_POST["r_Web_Comments__c"]
			'SystemWorkflowTrigger__c' => 'SendWelcomeEmail',
			'pba_ausfields__Suburb__c' => $_POST["r_Web_Comments__c"],
			'pba__ListingType__c' => 'Sale'			     // **** Note: Last line of Array must not end with a comer
		  ),
		  
		  'favoriteListings' => explode(';', $_POST['id']),
		  'ownerFields' => array('LastName'),
		  'requestFields' => array('Name'),
		  'contactFields' => array('Name')
		));

		
		$params = json_encode($body); 
		
		$curl = curl_init($url);
		
		// ******IMPORTANT*******
		// the following line is only there to make this example run without the need for importing SSL certificates
		// for production scripts, make sure to import current SSL certificates , otherwise there is the risk of man-in-the-middle attacks
		// more info can be found here:
		// http://ademar.name/blog/2006/04/curl-ssl-certificate-problem-v.html
		// http://curl.haxx.se/docs/sslcerts.html
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //REMOVE THIS LINE IN PRODUCTION ENVIRONMENTS AND MAKE SURE SSL CERTIFICATES ARE IMPORTED INSTEAD
		
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(  "Content-type: application/json"));
		
		$response = curl_exec($curl);
		
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		// EXCEPTION HANDLING
		// this usually happens if connection is impossible or if the datastructure is invalid, 
		// e.g. if a field does not exist in Propertybase or if the datatype is incorrect (like sending string for number field)
		if ( $status != 200 ) {
			$jsonResponse =  json_decode($response);
			if ($jsonResponse !== null && is_array($jsonResponse)) {
			  //typical format for generic Salesforce API error:
			  //[{"message":"Unknown field: pba.WebserviceProspectRest.ProspectData.data at [line:1, column:557]","errorCode":"JSON_PARSER_ERROR"}]
			  if (isset($jsonResponse[0]->{"message"}) && isset($jsonResponse[0]->{"errorCode"})) {
				
				echo "Json Submitted: " . $params. "<br/>\n";
				echo "Id of listing is " . $_POST['id']. "<br/>\n";
				echo "message: " . $jsonResponse[0]->{"message"} . "<br/>\n";
				echo "errorCode: " . $jsonResponse[0]->{"errorCode"} . "<br/>\n";
			  }
			}    
			die("Error: call to token URL $url failed with status $status, response $response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
		}

		curl_close($curl);
		
		$jsonResponse =  json_decode($response);
		if (isset($jsonResponse->{"errorMessage"})) {
		  // handle errors which could be caught by the application
		  // e.g. failed authentication or calling a plugin which throws an exception
		  echo "Json Submitted: " . $params. "<br/>\n";
			echo "Id of listing is " . $_POST['id']. "<br/>\n";
				
				die("Error: " . $jsonResponse->{"errorMessage"});
	
		}

		//echo "Id of listing is " . $_POST['id']. "<br/>\n";
		//header('location: success.php');
		$SuccessVis = "Block";
		$FormVis = "none";

		}
	}

		
	function test_input($data) {
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}



	
	
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
span.itemImage 	{ float: left; layout: block; width: 80px; height: 80px; margin-right: 5px; border: 1px solid #DDDDDD; padding: 2px; }
img.itemImage 	{ height: 80px; width: 80px;}
div#result 		{width: 580px;}
</style>


</head>

<body  link="#092A3D" vlink="#092A3D" alink="#092A3D">

  
                <div id="Regform" class="wpcf7">
                  <form id="main-wrapper" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="wpcf7-form" name="web2prospect" style="display:<?php echo $FormVis;?>" >
                    <p>First Name<br>
                      <input id="c_FirstName" maxlength="40" name="c_FirstName" size="30" type="text"  value="<?php echo $FirstName;?>" />
                    </p>
                    <p>Last Name*</br>
                      <input id="c_LastName" maxlength="40" name="c_LastName" size="30" type="text" value="<?php echo $LastName;?>" />
                    </p>
                    
                    <!-- Checks if the name has been entered in -->
                    <div id="NameError" runat="server"  style="display: block; color: red; display: <?php echo $NameVis;?>">
                      <div class="sfm_float_error_box" > <?php echo $nameErr;?>
                        <div class="sfm_close_box" style="position: absolute; right: 0px; top: 0px; border: 0px; padding: 5px 10px; margin: 0px;"></div>
                      </div>
                    </div>
                    <p>Telephone*<br>
                      <input id="c_Phone" maxlength="40" name="c_Phone" size="30" type="text" value="<?php echo $Phone;?>" />
                    </p>
                    <p>Email*<br>
                      <input id="c_Email" maxlength="40" name="c_Email" size="30" type="text" value="<?php echo $Email?>" />
                    </p>
                    
                    <!-- Checks if email has been entered and is in correct format -->
                    <div id="EmailError" style="display: block; color:red; display:<?php echo $EmailVis;?>">
                      <div class="sfm_float_error_box"> <?php echo  $emailErr;?>
                        <div class="sfm_close_box" style="position: absolute; right: 0px; top: 0px; border: 0px; padding: 5px 10px; margin: 0px;"></div>
                      </div>
                    </div>
                    <p>Your Message<br>
                      <span class="wpcf7-form-control-wrap your-message">
                      <textarea name="r_Web_Comments__c" id="r_Web_Comments__c" cols="40" rows="10" class="wpcf7-form-control wpcf7-textarea" aria-invalid="false" value="<?php echo $Comment;?>"></textarea>
                      </span> </p>
                    
                    <!-- Hidden information in form to populate data in Propertybase for project variables -->
                    
                    <input  name="id" type="hidden" value="<?=htmlentities($_GET['id'])?>" />
                    <p>
                      <input class="wpcf7-form-control wpcf7-submit" type="submit" value="Send" />
                    </p>
                  </form>
                </div>
                <div style="display: <?php echo $SuccessVis;?>">
                  <h2 style="font-family: Arial, sans-serif;">Thank You for your enquiry!</h2>
                  <span style="font-family: Arial">We will contact you shortly.</span> </div>
      



</body>

</html>