<?php

$XML = '<?xml version="1.0" encoding="utf-8"?>';
$XML .= get_ALC_API_Version();

header("Content-Type:text/xml");
echo $XML;
?>
