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
    <div class="commerce-customer-shipping">
        <h3>Adresă de livrare</h3>
        <?php print render($content['commerce_customer_shipping']); ?>
    </div>
    <div class="commerce-customer-billing">
		<h3>Adresă de facturare</h3>
		<?php print render($content['commerce_customer_billing']);?>
        <?php if(!empty($content['commerce_customer_billing_company'])): ?>
            <div class="customer-company"><?php print render($content['commerce_customer_billing_company']); ?></div>
        <?php endif; ?>
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
	foreach ($line_items_ids as $line_items_id) {
		
		$line_item = commerce_line_item_load($line_items_id->line_item_id);
     	$line_item_wrapper = entity_metadata_wrapper('commerce_line_item', $line_item);
		
		// If the current line item actually no longer exists...
		if (!$line_item_wrapper->value()) {
		  continue;
		}
		print views_embed_view('commerce_line_item_table', 'block_1', $line_item_wrapper->line_item_id->value());
	}

	?>
    </div>
    <div class="order-total"><?php print render($content['commerce_order_total']); ?></div>
  </div>

  <div class="order-total-products">
    <strong>Total produse</strong><br/>
    <strong><?php print render($content['no_fruit_trees']); ?> x Pomi</strong> <br/>
    <strong><?php print render($content['no_roses']); ?> x Trandafiri</strong> <br/>
    <strong><?php print render($content['no_others']); ?> x Altele</strong>
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
