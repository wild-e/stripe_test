<?php
require_once 'shared.php';

// This is the root of the URL and includes the scheme. It usually looks like
// `http://localhost:4242`. This is used when constructing the fully qualified
// URL where the user will be redirected to after going through the payment
// flow.
$domain_url = $_ENV['DOMAIN'];

if (isset($_POST['price']) && !empty($_POST['price'])) {
  $price = (float) $_POST['price']*100;
} else {
  $price = 1000 ;
}

// Create new Checkout Session for the order
// ?session_id={CHECKOUT_SESSION_ID} means the redirect will have the session ID set as a query param
// try {
  $checkout_session = $stripe->checkout->sessions->create([
    'success_url' => $domain_url . '/success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => $domain_url . '/canceled.html',
    'payment_method_types' => [
      'card',
    ],
    'customer' => 'cus_JsgLlyYyWoYt6p', // if already existing customer
    'mode' => 'payment',
    "billing_address_collection"=> "auto", // will ask for address only if not one provided
    'line_items' => [[
      'price_data' => [
        'currency' => 'eur',
        'unit_amount' => $price,
        'product_data' => [
          'name' => 'Achetez des points !',
          'images' => ["https://tualu.fr/images/bookprofile.png"],
        ],
      ],
      'quantity' => 1,
      'adjustable_quantity' => [
        'enabled' => true,
        'minimum' => 1,
        'maximum' => 10,
      ],
      'tax_rates' => [['txr_1JDqbVHDVy7hsJPSOwL3Jj7f']] // send a created tax id
    ]],
    "locale" => 'fr',
  ]);
// } catch (\Stripe\Exception\ApiErrorException $e) {
//   error_log($e);
// }
error_log($checkout_session);

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);
