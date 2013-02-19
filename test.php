<html>
<body>
<?php

include('simple_html_dom.php');

$html = file_get_html('http://www.radiostudent.si/ostalo/glasbene-opreme');

$res = $html->find('a');

foreach($res as $a)
  {
    if (strpos($a->plaintext,'skladb') !== false)
      echo str_replace(array('č','Č','š','Š','ž','Ž'),array('&#269;','&#268;','&#353;','&#352;','&#382;','&#381;'),$a) . "<br>";
  }
?>
</body>
</html>


