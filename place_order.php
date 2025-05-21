<?php
$conn = new mysqli("localhost", "root", "", "cracker_shop");

$name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$items = implode(", ", $_POST['items']);

$stmt = $conn->prepare("INSERT INTO orders (customer_name, phone, address, items) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $phone, $address, $items);
$stmt->execute();

$token = "YOUR_ACCESS_TOKEN";
$phone_number_id = "YOUR_PHONE_NUMBER_ID";
$owner_number = "OWNER_PHONE_NUMBER";

$msg = "New Order:\nName: $name\nPhone: $phone\nAddress: $address\nItems: $items";

$data = [
    'messaging_product' => 'whatsapp',
    'to' => $owner_number,
    'type' => 'text',
    'text' => ['body' => $msg]
];

$ch = curl_init("https://graph.facebook.com/v17.0/$phone_number_id/messages");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo "Order placed successfully!";
?>
