<?php
/**
 * @file
 * Template for invoiced orders.
 */
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
    <h3><?php print render($content['older_completed_orders_text']); ?></h3>
    </div>
  </div>
  
  <div class="order-info">
  	  <div class="order-number"><?php print '<strong>' . t('Order no.') . '</strong>' . ': ' . $content['order_number']['#value']; ?></div>
      <div class="order-date"><?php print '<strong>' . t('Order date') . '</strong>' . ': ' . render($content['order_created_date']); ?></div>
  </div>
  
  <div class="line-items">
    <div class="line-items-view">
    <?php
	
	$line_items_ids = views_get_view_result('commerce_line_item_table', 'block_line_item_custom_sort', $content['order_id']['#value']);
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
      <?php print render($content['customer_message_on_order']); ?>
      <?php print render($content['cumulated_orders_text']); ?>
  </div>
  
  
</div>
