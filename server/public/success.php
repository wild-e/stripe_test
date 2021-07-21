<?php
require_once 'shared.php';
// Retrieve the Checkout Session for the successful payment flow that just
// completed. This will be displayed in a `pre` tag as json in this file.
$checkout_session = $stripe->checkout->sessions->retrieve(
  $_GET['session_id'],
    [
      'expand' => [ 'line_items' ],
    ]
);
// create invoiceItem
$stripe->invoiceItems->create(
  [
  'amount' => $checkout_session->amount_total,
  'currency' => 'eur',
  'customer' => $checkout_session->customer,
  'description' => "Bibliothèque Tualu"
]);

//create invoice
$invoice = $stripe->invoices->create(  [
  'customer' => $checkout_session->customer,
  'collection_method' => 'send_invoice',
  'due_date' => time() + (30), // dates are in timestamp // compelled to add it
  'default_tax_rates' => [['txr_1JFfDgHDVy7hsJPSoktManRu']],
]);

$invoice->sendInvoice(); // send it by email !

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Stripe Checkout Sample</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="css/global.css" />
  </head>

  <body>
    <div class="sr-root">
      <div class="sr-main">
        <div class="sr-payment-summary completed-view">
          <h1>Your payment succeeded</h1>
          <h4>
            View CheckoutSession response:</a>
          </h4>
        </div>
        <div class="">
          <div class="">
            <pre><?= json_encode($checkout_session, JSON_PRETTY_PRINT); ?></pre>
          </div>
          <div class="">
            <pre><?= json_encode($checkout_session->customer, JSON_PRETTY_PRINT); ?></pre>
          </div>
          <div class="">
            <pre><?= json_encode($checkout_session->line_items, JSON_PRETTY_PRINT); ?></pre>
          </div>
          <div class="">
            <a href="<?= $invoice->invoice_pdf; ?>"> Votre facture</a>
          </div>
          <button onclick="window.location.href = '/';">Restart demo</button>
        </div>
      </div>
    </div>
  </body>
</html>
