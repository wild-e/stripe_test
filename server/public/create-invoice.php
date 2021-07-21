<?php
require_once 'shared.php';

// Create a customer
$customer = $stripe->customers->create([
    'name' => 'Maurice JOHNSSON',
    'email' => 'maurice@john.com'
  ]);

// create invoiceItem
$stripe->invoiceItems->create(
    [
    'price' => $_ENV['PRICE'],
    'customer' => $customer['id'],
    'quantity' => 1,
  ]);
  
  //create invoice
  $invoice = $stripe->invoices->create(  [
    'customer' => $customer['id'],
    'collection_method' => 'send_invoice',
    'due_date' => time() + (30), // dates are in timestamp // compelled to add it
    'default_tax_rates' => [['txr_1JFfDgHDVy7hsJPSoktManRu']], // apply tax rate to invoice rather than on invoiceItem if selling one type of product
    'payment_settings' => [
        'payment_method_types' => [
            'card',
        ],
    ]
  ]);

  $invoice->sendInvoice(); // send it by email !


header("HTTP/1.1 303 See Other");
header("Location: " . $invoice->hosted_invoice_url);
// ?>

// <pre><?= json_encode($invoice->hosted_invoice_url, JSON_PRETTY_PRINT); ?></pre>