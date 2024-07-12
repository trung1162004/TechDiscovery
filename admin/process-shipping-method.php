<?php
$data = array(
    "pick_province" => "Hồ Chí Minh",
    "pick_district" => "Quận 3",
    "pick_ward" => "Phường Võ Thị Sáu",
    "pick_street" => "Nam Kỳ Khởi Nghĩa",
    "province" => $_POST['province'],
    "district" => $_POST['district'],
    "ward" => $_POST['ward'],
    "weight" => 500,
    "value"=> 3000000,
    "transport" => "road",
    "deliver_option" => "none",
    "tags"  => [1]
);
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://services.giaohangtietkiem.vn/services/shipment/fee?" . http_build_query($data),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_HTTPHEADER => array(
        "Token: e1de32e29e0a4c4a372abdf8f7a7a0ca7d22a281",
    ),
));

$response = curl_exec($curl);
curl_close($curl);

echo $response;
?>