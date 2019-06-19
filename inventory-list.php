<?php
/*
Plugin Name: Traxia Inventory List
Plugin URI: none
Description: Inventory Plugin
Version: 1.0
Author URI: none
*/

/* Note that the hello_world doesnt have brackets.*/
//add_action('init','inventory_list');
add_action( 'wp_enqueue_scripts', 'prefix_add_my_stylesheet' );

function prefix_add_my_stylesheet() {
        // Respects SSL, Style.css is relative to the current file
        wp_register_style( 'prefix-style', plugins_url('style.css', __FILE__) );
        wp_enqueue_style( 'prefix-style' );
		wp_register_style( 'prefix-style1', plugins_url('simplePagination.css', __FILE__) );
        wp_enqueue_style( 'prefix-style1' );
		wp_register_style( 'prefix-style2', plugins_url('chosen.css', __FILE__) );
        wp_enqueue_style( 'prefix-style2' );
		
		
    }



function inventory_list()
{
	//wp_deregister_script('jquery');
	wp_enqueue_script( 'jquery-js', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js');
	//wp_enqueue_script( 'jquery-js', 'http://secondgear.crookedtreecreative.com/wp-includes/js/jquery/jquery.js');
	//wp_register_script( 'jquery3.2.1', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js' );
	//wp_add_inline_script( 'jquery3.2.1', 'var jQuery3_2_1 = $.noConflict(true);' );
	//wp_enqueue_script( 'jquery-js', array('jquery') );
	
	//wp_enqueue_script('inventory_list1-js', get_template_directory_uri() .'/jquery.simplePagination.js', array('jquery'), null, true);
	//wp_enqueue_script('nventory_cho-js', get_template_directory_uri() .'/chosen.jquery.js', array('jquery'), null, true);
	//wp_enqueue_script('inventory_list-js', get_template_directory_uri() .'/global.js', array('jquery'), null, true);

	wp_enqueue_script( 'inventory_list1-js', plugins_url( '/jquery.simplePagination.js', __FILE__ )  );
	wp_enqueue_script( 'inventory_cho-js', plugins_url( '/chosen.jquery.js', __FILE__ )  );
	wp_enqueue_script( 'inventory_list-js', plugins_url( '/global.js', __FILE__ ) );
	
	
	
	displayoutput();
}	
function bind_category()
{
	$categories_array=array("APPAREL - HATS AND BELTS", "APPAREL - KID'S", "APPAREL - MEN'S", "APPAREL - WOMEN'S", "BIKING - BIKE SHOES", "BIKING - HITCH-MOUNT RACKS", "BIKING - HELMETS", "BIKING - HYBRID BIKES", "BIKING - KID'S BIKES", "BIKING - MEN'S APPAREL", "BIKING - MOUNTAIN BIKES", "BIKING - PARTS AND ACCESSORIES", "BIKING - REAR-MOUNT RACKS", "BIKING - ROAD BIKES", "BIKING - WOMEN'S APPAREL","BOOKS", "CAMPING - BACKPACKS", "CAMPING - COOKING", "CAMPING - DAYPACKS", "CAMPING - GEAR AND ACCESSORIES", "CAMPING - HAMMOCKS", "CAMPING - KID CARRIERS", "CAMPING - SLEEPING BAGS", "CAMPING - SLEEP PADS", "CAMPING - TENTS", "CAMPING - WATER FILTERS", "CLIMBING - GEAR AND ACCESSORIES", "CLIMBING - CLIMBING SHOES", "DISC GOLF", "FOOD", "FOOTWEAR - KID'S SHOES", "FOOTWEAR - MEN'S SHOES", "FOOTWEAR - WOMEN'S SHOES", "FOOTWEAR - SOCKS", "HYDRATION", "KNIVES AND TOOLS", "MAPS", "OPTICS AND GADGETS", "OUTERWEAR - HATS AND GLOVES", "OUTERWEAR - KID'S", "OUTERWEAR - MEN'S", "OUTERWEAR - WOMEN'S", "PADDLING - CANOES", "PADDLING - GEAR AND ACCESSORIES", "PADDLING - KAYAKS", "PADDLING - PADDLEBOARDS", "PADDLING - PFDS", "PADDLING - TUBES", "PET ACCESSORIES", "STICKERS", "SUNGLASSES", "TRAVEL - BAGS", "TRAVEL - GEAR AND ACCESSORIES", "UNCATEGORIZED", "WINTER SPORTS - GEAR AND ACCESSORIES", "WINTER SPORTS - SNOWBOARDS", "WINTER SPORTS - SNOWBOARD BOOTS");
	return $categories_array;
}
function displayoutput()
{?>
	<input type='hidden' value='<?php echo plugins_url( '/jsonpost.php', __FILE__ ) ?>' id='jsonposturl' />
	<input type='hidden' value='<?php echo plugins_url( '/ajax-loader.gif', __FILE__ ) ?>' id='ajaimageurl' />
	<div style='font:normal 12px Verdana;color:#6c0a17;'><b>Category : </b><select data-placeholder="category" id='category' class="chosen-select" tabindex="2"> 
	<?php
	foreach (bind_category() as $category) {
		if($_GET['query']==$category)
			echo "<option value='" . list_encrypt_decrypt('encrypt',$category) ."' selected>" . $category . "</option>";
		else
			echo "<option value='" . list_encrypt_decrypt('encrypt',$category) ."'>" . $category . "</option>";
	}?>
	</select>
		<span style="margin-left: 20px;">
			Or enter a search term here:
			<input id="freeSearch" size="20" value="">
		</span>
	</div>
	
	<div id="results"></div>
	
	
<?php
}
function list_encrypt_decrypt($action, $string) {
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

