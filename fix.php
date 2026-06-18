<?php
$c = curl_init('https://online.nks.vn/api/nks/provinces');
curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($c, CURLOPT_POST, true);
curl_setopt($c, CURLOPT_POSTFIELDS, ['slcBox'=>1]);
$r = json_decode(curl_exec($c), true);

$titles = array_column($r['data'] ?? [], 'title');
foreach($titles as $i => $t) {
    echo "$i: $t\n";
}
