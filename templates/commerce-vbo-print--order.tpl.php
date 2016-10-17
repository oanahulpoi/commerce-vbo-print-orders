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
  	  <div class="order-number"><?php print render($content['order_number']);?></div>
      <div class="order-date"><?php print render($content['order_created_date']);?></div>
  </div>
  
  <div class="line-items">
    <div class="line-items-view">
      <?php print render($content['line_items']);?>
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
      <?php // print render($content['cumulated_orders_text']); ?>
      <?php print render($content['admin_messages']); ?>
  </div>
  
  
</div>
