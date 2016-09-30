<?php
require("phpsqlajax_dbinfo.php");

if (PHP_VERSION>='5')
 require_once('domxml-php4-to-php7.php'); //Load the PHP5 converter
// Start XML file, create parent node
$doc = domxml_new_doc("1.0");

$node = $doc->create_element("markers");
$parnode = $doc->append_child($node);


$mysqli = new mysqli("localhost", $username, $password, $database);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

// Select all the rows in the markers table
$query = "SELECT * FROM markers WHERE 1";
$result = $mysqli->query($query);
if (!$result) {
  die('Invalid query: ' . mysqli_error());
}

function parseToXML($htmlStr)
{
  $xmlStr=str_replace('<','&lt;',$htmlStr);
  $xmlStr=str_replace('>','&gt;',$xmlStr);
  $xmlStr=str_replace('"','&quot;',$xmlStr);
  $xmlStr=str_replace("'",'&#39;',$xmlStr);
  $xmlStr=str_replace("&",'&amp;',$xmlStr);
  return $xmlStr;
}

header("Content-type: text/xml");

// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
while ($row = @mysqli_fetch_assoc($result)){
  // ADD TO XML DOCUMENT NODE
  echo '<marker ';
  echo 'name="' . parseToXML($row['name']) . '" ';
  echo 'address="' . parseToXML($row['address']) . '" ';
  echo 'lat="' . $row['lat'] . '" ';
  echo 'lng="' . $row['lng'] . '" ';
  echo 'type="' . $row['type'] . '" ';
  echo '/>';
}

// End XML file
echo '</markers>';

?>
