<?php
function getArrCount ($arr, $depth=1) { 
       if (!is_array($arr) || !$depth) return 0; 
          
      $res=count($arr); 
          
       foreach ($arr as $in_ar) 
          $res+=getArrCount($in_ar, $depth-1); 
       
       return $res; 
   }
$location = "#######";
global $sgwnc_cat;
global $sgwnc_freeform;
$cat  = trim(encrypt_decrypt('decrypt', $_POST['category']));
$freeForm = str_replace("%", "", trim($_POST['freeForm']));
if (strlen($freeForm)>0) {
	$cat=$freeForm;
	$sgwnc_freeform=true;
}
if ( strlen($cat) < 3) {
	echo 'Please enter at least 3 characters';
	return false;
}
echo $cat;
$sgwnc_cat=$cat;
if (!$sgwnc_freeform) {
	$data_string = json_encode(array('key' => '#######' , 'category' => $cat));
} else {
	$data_string = json_encode(array('key' => '#######' , 'query' => $cat));
}
$headers= array('Accept: application/json','Content-Type: application/json');
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://user.traxia.com/app/api/inventory');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$result = curl_exec($ch);
$code= curl_getinfo($ch,CURLINFO_HTTP_CODE);
$datas = json_decode($result,TRUE);

if(getArrCount($datas, 5)==1)
{
	echo "<table id='rounded-corner' border='0' class='paginated'>";
	echo "<thead><tr><th scope='col'>Product Name</th><th scope='col'>Location</th><th scope='col'>Price</th></tr></thead>";
	echo "<tbody><tr><td colspan=6 align='center'>";
	echo "<font color='#6c0a17'>Category searching is currently not working.  Please enter a search term in the box above, then press enter.</font>";
	echo "</td></tr></tbody></table>";
}
else {
	displayoutput($datas);
}
function displayoutput($datas)
{
		
	global $sgwnc_cat;
	global $sgwnc_freeform;
	echo "<div class='item_grid paginated'>";
	//echo "";
	//echo "<tbody>";
	
	foreach($datas as $data)
	{
		foreach ($data as  $keys) {
			$quantity=0;
			foreach($keys as $key => $value)
			{
				if($key=="name")
					$name = $value;
				else if($key=="consignorId")
					$consignorId = $value;
				/*else if($key=="images") {
					if (count($value)>0) $image = $value;
					else $image = '';
				}
				
				else if($key=="images")
					$featuredimage = $value;
					*/	
				else if($key=="images")
                {
                    foreach($value as $val)
                    {
                        $image = $val;
                        break;
                    }
                }
				/*
				else if($key=="images")
					$images = $array("$value");*/
				else if($key=="currentPrice")
					$cost = number_format($value/100, 2, '.', '');
				else if($key=="dateCreated")
					$dateCreated = $value;
				else if($key=="sku")
					$sku = $value;
				else if($key=="category")
					$cat2 = $value;
				else if($key=="status")
					$status = $value;
				else if($key=="quantity")
					$quantity=$value;
			}
			
			
			if($quantity!="0" && $status=="ACTIVE" && ($sgwnc_freeform || $cat2==$sgwnc_cat))
			{
				$items[] = (object) array('sku' => $sku, 'name' => $name, 'cost' => $cost,  'image' => $image, 'date' => $dateCreated);
			}
		}	
	}

	usort($items, "cmp");
	foreach($items as $item) {
			echo "<div class='sgc_single_item'>";
			//echo "<div class='sgc_single_item_image'>";
			//echo "<img src=" . $image . " />" ;
			//echo "</div>";
			echo "<div class='sgc_single_item_meta'><div class='sgc_single_item_title'><p>" .$item->name . "</p></div><div class='sgc_single_item_cost'><p>$" . $item->cost . "</p></div><div style='clear:both;'></div></div>";
			echo "</div>";
			
	}
	echo "</div><div class='page_navigation'></div>";
}
function cmp($a, $b)
{
	return strcmp($b->date, $a->date);
}

function getlocation()
{
	//if(trim(encrypt_decrypt('decrypt', $_POST['locations']))!="")
	//{
	//	if(trim(encrypt_decrypt('decrypt', $_POST['locations']))=="CYSUVJ4JL4CWT2C9UTQPGLYH")
			return "Haywood Road";
	//	else
		//	return "Walnut Street";
	//}
	//else
	//	return "Haywood Road";
}
function encrypt_decrypt($action, $string) {
   $output = false;

   $key = 'tvTEPukd';
   $iv = md5(md5($key));
   if( $action == 'encrypt' ) {
       $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, $iv);
       $output = base64_encode($output);
   }
   else if( $action == 'decrypt' ){
       $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, $iv);
       $output = rtrim($output, "");
   }
   return $output;
}
?>
