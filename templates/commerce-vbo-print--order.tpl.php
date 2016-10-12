<?php
/**
 * @file
 * Template for invoiced orders.
 */
//drupal_set_message('<pre>' . print_r($content, true) . '</pre>');
$order = $content['commerce_line_items']['#object'];
$order_wrapper = entity_metadata_wrapper('commerce_order', $order);
$current_order_id = $order->order_id;

$nr_luni_sezon = 5;
?>

<div class="order-printed">
  <div class="customer">
  	<strong>Cumpărător</strong>
    <br/><br/>
    <div class="commerce-customer-billing">
		<h3>Adresă de facturare</h3>
		<?php 
    
        print render($content['commerce_customer_billing']);
        
        // afiseaza informatiile societatii, daca este cazul
        if(isset($order->commerce_customer_billing)) {
            $commerce_customer_billing = commerce_customer_profile_load($order->commerce_customer_billing['und'][0]['profile_id']);
            $commerce_customer_profile = entity_metadata_wrapper('commerce_customer_profile', $commerce_customer_billing);
            $field_societate = $commerce_customer_profile->field_societate->value();
            $field_cui = $commerce_customer_profile->field_cui->value();
            $field_j = $commerce_customer_profile->field_j->value();
            $field_banca = $commerce_customer_profile->field_banca->value();
            $field_telefon = $commerce_customer_profile->field_telefon->value();
            if($field_societate != '' && $field_cui != '') {
                print '<div class="customer-company">';
                    print '<strong>Societate</strong><br/>';
                    print $field_societate . '<br/>';
                    print 'R.C.: ' . $field_j . '<br/>';
                    print 'C.U.I.: ' . $field_cui . '<br/>';
                    print 'Banca: ' . $field_banca . '<br/>';
                    print 'Telefon: ' . $field_telefon . '<br/>';	
                print '</div>';	
            }
        }
        ?>
    </div>
    <div class="commerce-customer-shipping">
    	<h3>Adresă de livrare</h3>
		<?php
        print render($content['commerce_customer_shipping']);
        ?>
    </div>
    <div class="alte-comenzi">
    	<?php 
		$all_orders = commerce_order_load_multiple(array(), array('uid' => $order->uid));
		//print '<pre>' . print_r($all_orders, true) . '</pre>';
		unset($all_orders[$current_order_id]); // remove current order from array
		$has_completed_orders = FALSE;
		$has_cumulated_orders = FALSE;
		$cumulated_orders_text = '';
		
		foreach($all_orders as $o) {
			if($o->status == 'completed') { 
				$has_completed_orders = TRUE;
				$comenzi_finalizate_created_date[] = format_date($o->created, 'custom', 'M Y');
			}
			elseif($o->status == 'cumulata' && $o->created >= strtotime('- ' . $nr_luni_sezon . 'month') && $o->created >= $order->created) { // comanda cumulata este din sezonul curent -> este o comanda care a fost cumulata la comanda curenta -> putem extrage mesajul
				$has_cumulated_orders = TRUE;
				if(!empty($o->field_mesaj_order)) {
					//print '<pre>' . print_r($all_orders, true) . '</pre>';
					$cumulated_orders_text .= 'Comanda cumulata <strong>' . $o->order_number . '</strong> contine mesajul: <em>"' . $o->field_mesaj_order['und'][0]['safe_value'] . '"</em><br>';
				}
			}
		}
		if($has_completed_orders) print '<h3>Comenzi finalizate:</h3>' . implode(', ', $comenzi_finalizate_created_date);
		else print '<h3>Nu mai are comenzi finalizate.</h3>';
		?>
    </div>
  </div>
  
  <div class="order-info">
  	  <div class="order-number"><?php print '<strong>' . t('Order no.') . '</strong>' . ': ' . $order->order_number; ?></div>
      <div class="order-date"><?php print '<strong>' . t('Order date') . '</strong>' . ': ' . format_date($order->created, 'custom', 'j F Y'); ?></div>
  </div>
  
  <div class="line-items">
    <div class="line-items-view">
    <?php
	
	$line_items_ids = views_get_view_result('commerce_line_item_table', 'block_line_item_custom_sort', $current_order_id);
	
	$nr_pomi_fructiferi = $nr_trandafiri = $nr_altele = 0;
	foreach ($line_items_ids as $line_items_id) {
		
		$line_item = commerce_line_item_load($line_items_id->line_item_id);
     	$line_item_wrapper = entity_metadata_wrapper('commerce_line_item', $line_item);
		
		// If the current line item actually no longer exists...
		if (!$line_item_wrapper->value()) {
		  continue;
		}
		print views_embed_view('commerce_line_item_table', 'block_1', $line_item_wrapper->line_item_id->value());
		if ($line_item_wrapper->type->value() == 'product') {
			//print '<pre>' . print_r($line_item_wrapper->commerce_product, true) . '</pre>';
			$product_type = $line_item_wrapper->commerce_product->type->value();
			$quantity = (int)$line_item_wrapper->quantity->value();
			if ($product_type == 'pomi_fructiferi') {
				$nr_pomi_fructiferi += $quantity;
			}
			elseif($product_type == 'trandafiri') {
				$nr_trandafiri += $quantity;
			}
			else {
				$nr_altele += $quantity;
			}
		}
	}

	?>
    </div>
    <div class="order-total"><?php print render($content['commerce_order_total']); ?></div>
  </div>
  
  <div class="order-total-products">
      <strong>Total produse</strong><br/>
      <?php print '<strong>' . $nr_pomi_fructiferi . '</strong>' . ' x <strong>Pomi</strong>';?><br/>
      <?php print '<strong>' . $nr_trandafiri . '</strong>' . ' x <strong>Trandafiri</strong>';?><br/>
      <?php print '<strong>' . $nr_altele . '</strong>' . ' x <strong>Altele</strong>';?>
  </div>
  
  <div class="order-messages">
  <?php
  	if ($order_wrapper->field_mesaj_order->value() != '') {
		print '<strong>Comentariu client</strong>: <br/>';
		print $order_wrapper->field_mesaj_order->value();
	}
	if ($has_cumulated_orders && !empty($cumulated_orders_text)) {
		print '<strong>Comentarii de pe comenzile cumulate (la aceasta comanda):</strong> <br/>';
		print $cumulated_orders_text;
	}
  ?>
  </div>
  
  
</div>
