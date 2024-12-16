<html>
<head>
<title>Mega.nz Downloader</title>
</head>
<body>
<h2>Enter mega.nz URL</h2>
<form action="<?php $_PHP_SELF ?>" method="POST">
<br>
URL: <input type="text" name="URL" style="width:90%;" placeholder="enter mega.nz link here">
<input type="submit" value="Submit">
</form>
<?php
if ($_POST) {

error_reporting(0);
$url = $_POST['URL'];
preg_match("/file\/(.+?)#/", $url, $output_array);
$fileID = $output_array[1];
$domain = "meganz";
$lang = "en";
$apiURL = "https://eu.api.mega.co.nz/cs?domain=$domain&lang=$lang";

$value = array(
array(
'a' => 'g',
'g' => 1,
'ssl' => 0, //0, 1, 2 (default is 2)
'p' => $fileID) // File id here
);

$rawPOST = json_encode($value);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,            $apiURL );
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_POST,           true );
curl_setopt($ch, CURLOPT_POSTFIELDS,     $rawPOST );
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.93 Safari/537.36');
curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain;charset=UTF-8'));

$result=curl_exec($ch);

$jsonResult = json_decode($result);
$directLink = $jsonResult[0]->g;
$fileSize = $jsonResult[0]->s;

if ($fileSize > 1023){
$new_fileSize = $fileSize / 1024;
$new_fileSize = round($new_fileSize);
$fsuffix="KB";
} 
if ($fileSize > 1048575){
$new_fileSize = $fileSize / 1048576;
$new_fileSize = round($new_fileSize);
$fsuffix="MB";
}
if ($fileSize > 1073741823){
$new_fileSize = $fileSize / 1073741824;
$new_fileSize = round($new_fileSize);
$fsuffix="GB";
}
if ($fileSize < 1024){
$new_fileSize = $fileSize;
$fsuffix="B";
}
echo "<a href='$directLink'>Download</a>";
echo '<br><br>';
echo "<b>File Size:</b> $new_fileSize $fsuffix";
echo '<br><br>';
}
?>
</body>
</html>
