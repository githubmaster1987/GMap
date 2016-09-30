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

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
while ($row = @mysqli_fetch_assoc($result)){
  // ADD TO XML DOCUMENT NODE
  $node = $doc->create_element("marker");
  $newnode = $parnode->append_child($node);

  $newnode->set_attribute("name", $row['name']);
  $newnode->set_attribute("address", $row['address']);
  $newnode->set_attribute("lat", $row['lat']);
  $newnode->set_attribute("lng", $row['lng']);
  $newnode->set_attribute("type", $row['type']);
}

$xmlfile = $doc->dump_mem();
echo $xmlfile;
?>
