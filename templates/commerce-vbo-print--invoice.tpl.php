<?php

/**
 * @file
 * Template for invoiced orders.
 */
//drupal_set_message('<pre>' . print_r($content, true) . '</pre>');
$order = $content['commerce_line_items']['#object'];
$order_wrapper = entity_metadata_wrapper('commerce_order', $order);
?>

<div class="invoice-invoiced">
  <div class="header">
    <img src="/<?php print $content['invoice_logo']['#value']; ?>"/>
    <div class="invoice-header">
        <p><?php print render($content['invoice_header']); ?></p>
    </div>
  </div>

  <h1 class="invoice-title">Factură fiscală</h1>
  
  <div class="customer">
  	<strong>Cumpărător</strong>
    <br/>
    <?php 
	$commerce_customer_billing = commerce_customer_profile_load($order->commerce_customer_billing['und'][0]['profile_id']);
	$commerce_customer_profile = entity_metadata_wrapper('commerce_customer_profile', $commerce_customer_billing);
	$field_societate = $commerce_customer_profile->field_societate->value();
	$field_cui = $commerce_customer_profile->field_cui->value();
	$field_j = $commerce_customer_profile->field_j->value();
	$field_banca = $commerce_customer_profile->field_banca->value();
	$field_telefon = $commerce_customer_profile->field_telefon->value();
	if($field_societate != '' && $field_cui != '') {
		print $field_societate . '<br/>';
		print 'R.C.: ' . $field_j . '<br/>';
		print 'C.U.I.: ' . $field_cui . '<br/>';
		print 'Banca: ' . $field_banca . '<br/>';
		print 'Telefon: ' . $field_telefon . '<br/>';		
	}
	else {
		print render($content['commerce_customer_billing']);
	}
	?>
  </div>
  
  <div class="invoice-info">
  	  <div class="invoice-number"><?php print t('Invoice') . ': ' . $order->order_number; ?></div>
      <div class="invoice-date">Data: <?php print render($content['invoice_header_date']); ?></div>
  </div>
  
  <div class="line-items">
    <div class="line-items-view"><?php print render($content['commerce_line_items']); ?></div>
    <div class="order-total"><?php print render($content['commerce_order_total']); ?></div>
  </div>
  
	 <?php
    // Calculeaza cate produse de un anumit tip s-au vandut in acesta comanda: x pomi fructiferi, x trandafiri, x altele    
    $nr_pomi_fructiferi = $nr_trandafiri = $nr_altele = 0;
    foreach ($order_wrapper->commerce_line_items as $delta => $line_item_wrapper) {
        // If the current line item actually no longer exists...
        if (!$line_item_wrapper->value()) {
          continue;
        }
        if ($line_item_wrapper->type->value() == 'product') {
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
  <div class="order-total-products">
      <strong>Total produse</strong><br/>
      <?php print '<strong>' . $nr_pomi_fructiferi . '</strong>' . ' x <strong>Pomi</strong>';?><br/>
      <?php print '<strong>' . $nr_trandafiri . '</strong>' . ' x <strong>Trandafiri</strong>';?><br/>
      <?php print '<strong>' . $nr_altele . '</strong>' . ' x <strong>Altele</strong>';?>
  </div>
  
  <div class="invoice-text">
  	<?php print render($content['invoice_text']); ?>
    <br/>
    <p>
        Facturat în baza comenzii numărul <?php print $order->order_id;?> din <?php print format_date($order->created, 'custom', 'j F Y, H:i'); ?> plasată pe zdravan.ro.
        <br/>
        Factura circulă fără semnătură și ștampilă în original, conform Legii 571/2003, privind Codul Fiscal, art. 155 (6).
	</p>
    <p class="va-multumim">
        Vă mulţumim!
        <br/>
        Vă dorim să vă bucuraţi de plantele cumpărate de le noi!
    </p>
  </div>

  <div class="invoice-footer"><?php print render($content['invoice_footer']); ?></div>
</div>
