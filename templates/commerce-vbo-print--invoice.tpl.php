<?php
/**
 * @file
 * Template for invoiced orders.
 */
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
    <?php if(!empty($content['commerce_customer_billing_company'])): ?>
      <?php print render($content['commerce_customer_billing_company']); ?>
    <?php else: ?>
      <?php print render($content['commerce_customer_billing']); ?>
    <?php endif; ?>

  </div>
  
  <div class="invoice-info">
  	  <div class="invoice-number"><?php print t('Invoice') . ': ' . render($content['order_number']); ?></div>
      <div class="invoice-date">Data: <?php print render($content['invoice_header_date']); ?></div>
  </div>
  
  <div class="line-items">
    <div class="line-items-view"><?php print render($content['commerce_line_items']); ?></div>
    <div class="order-total"><?php print render($content['commerce_order_total']); ?></div>
  </div>

  <div class="order-total-products">
      <strong>Total produse</strong><br/>
      <strong><?php print render($content['no_fruit_trees']); ?> x Pomi</strong> <br/>
      <strong><?php print render($content['no_roses']); ?> x Trandafiri</strong> <br/>
      <strong><?php print render($content['no_others']); ?> x Altele</strong>
  </div>
  
  <div class="invoice-text">
  	<?php print render($content['invoice_text']); ?>
    <br/>
    <p>
        Facturat în baza comenzii numărul <?php print render($content['order_id']); ?> din <?php print render($content['order_created_date']); ?> plasată pe zdravan.ro.
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
