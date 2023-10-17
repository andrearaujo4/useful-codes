/* facebook pixel no header */
add_action('wp_head', 'addFacebookPixel',10);
function addFacebookPixel(){
?>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '974054933361015');
fbq('track', 'PageView');

</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=974054933361015&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->



<!-- Global site tag (gtag.js) - Google Ads: 10848186333 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-XXXXXXXXXX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-XXXXXXXX',  { ' allow_enhanced_conversions':true });
</script>



<?php 
};

//view_content facebook pixel
add_action( 'woocommerce_after_single_product', 'viewContentFB' );
function viewContentFB() {
	global $product;
	
	$prodId = $product->get_id();
$categories = get_the_terms($prodId, 'product_cat');	
		$brand = get_the_terms($prodId,'pwb-brand');
    ?>

   <script>
fbq('track', 'ViewContent', {
  content_name: ' <?php echo $product->get_name();?>',
  content_category: '<?php echo implode(', ', array_column($categories, 'name')) ?>'.replace(/\&amp\;/gi, "&"),
  content_ids: ['wc_post_id_<?php echo $product->get_id() ?>'],
  content_type: 'product',
  value: <?php echo $product->get_price(); ?>,
  currency: 'EUR',
	brand: '<?php echo str_replace("'","",$brand[0]->name); ?>'
 });

gtag('event','view_item', {
  'value': <?php echo $product->get_price(); ?>,
'ecomm_prodid': 'wc_post_id_<?php echo $product->get_id() ?>',
'ecomm_pagetype': 'product',
	'ecomm_totalvalue': <?php echo $product->get_price(); ?>,
	'ecomm_category': '<?php echo implode(', ', array_column($categories, 'name')) ?>'.replace(/\&amp\;/gi, "&"),
//	'send_to': 'AW-XXXXXXXXXX',
  'items': [
    {
      'id': 'wc_post_id_<?php echo $product->get_id() ?>',
      'google_business_vertical': 'retail',
	'brand':'<?php echo str_replace("'","",$brand[0]->name); ?>',
	'quantity': 1,
	'price': <?php echo $product->get_price(); ?>,
    }
  ]
});


<?php //dados para add to cart//?>
window.dataLayer = window.dataLayer || [];
window.dataLayer.push({
  'content_name': ' <?php echo $product->get_name();?>',
  'content_category': '<?php echo implode(', ', array_column($categories, 'name')) ?>'.replace(/\&amp\;/gi, "&"),
  'content_ids': 'wc_post_id_<?php echo $product->get_id() ?>',
  'content_type': 'product',
  'value': <?php echo $product->get_price(); ?>,
  'currency': 'EUR',
'brand': '<?php echo str_replace("'","",$brand[0]->name); ?>'
 });	   
</script>



    <?php
}

/*
//add to cart facebook pixel
add_action( 'woocommerce_after_add_to_cart_button', 'addToCartFB' );
function addToCartFB() {
global $product;
$categories = get_the_terms($product->get_id(), 'product_cat');	
    ?>

   <script>

fbq('track', 'AddToCart', {
  content_name: ' <?php echo $product->get_name();?>',
  content_category: '<?php echo implode(', ', array_column($categories, 'name')) ?>',
  content_ids: ['wc_post_id_<?php echo $product->get_id() ?>'],
  content_type: 'product',
  value: <?php echo $product->get_price(); ?>,
  currency: 'EUR'
 });


</script>
<?php
}
*/


add_action('woocommerce_after_checkout_form', 'InitiateCheckoutFB' );

function InitiateCheckoutFB() { 
	
	
	
global $woocommerce;
global $product;
$items = $woocommerce->cart->get_cart();
$totalCart = $woocommerce->cart->cart_contents_total;
$totalCartNumItems = $woocommerce->cart->cart_contents_count;

$idsProducts = array();	
$prices = array();
$names	 = array();
$categoriesArr	 = array();
$brands	 = array();
	
foreach($items as $item => $values) { 
	
$productObject = wc_get_product( $values['product_id'] );
	
	$idProduct = 'wc_post_id_' . $values['product_id'];
	$idProductObject = $values['product_id'];
	$price = $values['line_subtotal'];
	$name =  $productObject->get_title();
	$categories = get_the_terms($idProductObject, 'product_cat');
	$brand = str_replace("'","",get_the_terms($idProductObject,'pwb-brand')[0]->name);

array_push($idsProducts,$idProduct);
array_push($prices,$price);
array_push($names,$name);
array_push($brands,$brand);	
array_push($categoriesArr,array_column($categories, 'name'));	
		
}

    ?>

<script>
	
fbq('track', 'InitiateCheckout',
  {
    content_ids: <?php echo json_encode($idsProducts);?>,
    num_items: <?php echo $totalCartNumItems;?>,
	value: <?php echo $totalCart;?>,
	content_category: <?php echo json_encode($categoriesArr);?>,
	content_type: 'product',
	content_name: <?php echo json_encode($names);?>,
	currency: 'EUR',
	brands: <?php echo json_encode($brands);?>,
  }
);	


gtag('event', 'begin_checkout', {
  "items": [<?php 
	$itemsConversion = array();
		for ($x = 0; $x <= count($idsProducts) - 1; $x++) {
			echo '{';
			echo '"id": "' . $idsProducts[$x] .'",';
			echo '"brand": "' . $brands[$x].'",';	
			echo "'category': " . json_encode($categoriesArr[$x]);
			echo '}';
			if ($x < count($idsProducts) - 1) {
				echo ',';
		}
		}	
	?>]	
});
</script>



<?php
   }


//envia o dataLayer para tracking na e evento purchase no facebook
add_action('woocommerce_thankyou', 'thankyouDataLayer', 10, 1);
function thankyouDataLayer( $order_id ) {
	
    if ( ! $order_id )
        return;

    // Allow code execution only once 
if( ! get_post_meta( $order_id, '_thankyou_action_done', true ) ) {

        // Get an instance of the WC_Order object
        $order = wc_get_order( $order_id );

 $totalOrder = $order->get_total(); 
 $totalOrderNumItems =  $order->get_item_count();
$billing_email  = $order->get_billing_email();
$billing_phone  = $order->get_billing_phone();
$billing_first_name = $order->get_billing_first_name();
$billing_last_name  = $order->get_billing_last_name();
$billing_address_1  = $order->get_billing_address_1();
$billing_address_2  = $order->get_billing_address_2();
$billing_city       = $order->get_billing_city();
$billing_state      = $order->get_billing_state();
$billing_postcode   = $order->get_billing_postcode();
$billing_country    = $order->get_billing_country();




// Initialize arrays to store product data
$idsProducts = array();
$namesProducts = array();
$pricesProducts = array();
$categoriesArr = array();
$brands = array();
$quantities = array();

// Loop through order items
foreach ($order->get_items() as $item_id => $item) {
    
    // Get the product object
    $product = $item->get_product();
    
    $itemQuantity = $item->get_quantity();
    $skuProduct = 'wc_post_id_' . $product->get_id();
    $skuProductObject = $product->get_parent_id();
    $nameProduct = $item->get_name();
    $priceProduct = $product->get_price();
    $finalPrice = $priceProduct * $itemQuantity;
    $brand = str_replace("'", "", get_the_terms($skuProductObject, 'pwb-brand')[0]->name);

    // Get product categories, check if it's an array before using array_column
    $categories = get_the_terms($skuProductObject, 'product_cat');
    if (is_array($categories)) {
        $categoriesNames = array_column($categories, 'name');
    } else {
        $categoriesNames = array(); // Handle the case where $categories is not an array
    }

    // Push data into respective arrays
    array_push($idsProducts, $skuProduct);
    array_push($namesProducts, $nameProduct);
    array_push($pricesProducts, $finalPrice);
    array_push($brands, $brand);
    array_push($quantities, $itemQuantity);
    array_push($categoriesArr, $categoriesNames);
}

// Flag the action as done (to avoid repetitions on reload, for example)
$order->update_meta_data('_thankyou_action_done', true);
$order->save();

    ?>
<script> 


fbq('track', 'Purchase',
{
    content_ids: <?php echo json_encode($idsProducts);?>,
    num_items: <?php echo $totalOrderNumItems;?>,
    value: <?php echo $totalOrder;?>,
    content_category: <?php echo json_encode($categoriesArr);?>,
    content_type: 'product',
    content_name: <?php echo json_encode($namesProducts);?>,
    currency: 'EUR',
	brand: <?php echo json_encode($brands);?>,
  }
);  
	

gtag('set', 'user_data', {
	"email": '<?php echo $billing_email; ?>',
	"phone_number": '<?php echo '+351' . $billing_phone; ?>',

	"address": {

	"first_name": '<?php echo $billing_first_name; ?>',

	"last_name": '<?php echo $billing_last_name; ?>',

	"street": '<?php echo $billing_address_1 . ' ' . $billing_address_2; ?>',

	"city": '<?php echo $billing_city; ?>',

	"region": '<?php echo $billing_state; ?>',

	"postal_code": '<?php echo str_replace("-","",$billing_postcode); ?>' ,

	"country": 'PT'

}

});


  gtag('event', 'conversion', {
      'send_to': 'AW-XXXXXXXXX/XXXXXXXX',
      'value': <?php echo $totalOrder;?>,
      'currency': 'EUR',
	'transaction_id': <?php echo $order_id; ?>,
      "items": [<?php 
	$itemsConversion = array();
		for ($x = 0; $x <= count($idsProducts) - 1; $x++) {
			echo '{';
			echo '"id": "' . $idsProducts[$x] .'",';
			echo '"name": "' . $namesProducts[$x].'",';
			echo '"brand": "' . $brands[$x].'",';	
			echo '"price": "' . $pricesProducts[$x].'",';
			echo '"quantity": ' . $quantities[$x].',';
			echo "'category': " . json_encode($categoriesArr[$x]);
			echo '}';
			if ($x < count($idsProducts) - 1) {
				echo ',';
		}
		}
	?>]	
  });
	
	gtag('event', 'purchase', {
      'value': <?php echo $totalOrder;?>,
      'currency': 'EUR',
	'transaction_id': <?php echo $order_id; ?>,
      "items": [<?php 
	$itemsConversion = array();
		for ($x = 0; $x <= count($idsProducts) - 1; $x++) {
			echo '{';
			echo '"id": "' . $idsProducts[$x] .'",';
			echo '"name": "' . $namesProducts[$x].'",';
			echo '"brand": "' . $brands[$x].'",';	
			echo '"price": "' . $pricesProducts[$x].'",';
			echo '"quantity": ' . $quantities[$x].',';
			echo "'category': " . json_encode($categoriesArr[$x]);
			echo '}';
			if ($x < count($idsProducts) - 1) {
				echo ',';
		}
		}
	?>]	
  });


window.dataLayer = window.dataLayer || [];
window.dataLayer.push({
 'event': 'Compra',
 'ids': <?php echo json_encode($idsProducts);?>,
'names': <?php echo json_encode($namesProducts);?>,
    'prices': <?php echo $totalOrder?>,
	'brand': <?php echo json_encode($brands)?>,
 });


</script> <?php
}
}
