<?php
class EncryptDecrypt
{
function encrypt($str,$authIV,$authKey) {
$iv = $authIV;
$key=$authKey;
$s = $this->pkcs5_pad($str);
$td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);
mcrypt_generic_init($td, $key, $iv);
$encrypted = mcrypt_generic($td, $s);
$encrypted1=base64_encode($encrypted);
return (trim($encrypted1));
}
function decrypt($code,$authIV,$authKey) {
$iv = $authIV;
$key=$authKey;
$an=base64_decode($code);
//$iv = $this->iv;
$td = mcrypt_module_open('rijndael-128', '','cbc', $iv);
mcrypt_generic_init($td, $key, $iv);
$clear = mdecrypt_generic($td, $key, $iv);
$decrypted = mdecrypt_generic($td, $an);
return (trim($decrypted));
}
protected function pkcs5_pad ($text) {
$blocksize = 16;
$pad = $blocksize - (strlen($text) % $blocksize);
return $text . str_repeat(chr($pad), $pad);
}
}
?>
