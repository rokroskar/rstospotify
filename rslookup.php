<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="rslookup.css">
</head>
<body>
<div class='body'>

<?php
include('simple_html_dom.php');
$songnames = array();
$query = array();
$artists = array();
$list = array();

ob_implicit_flush(true);
ob_end_flush();


echo "<strong>Pulling tracks from <a href='" . $_GET["urlname"] . "'> the best radio in town</a></strong><br><br>";
flush();

$html = file_get_html($_GET["urlname"]);
 
foreach($html->find('div div div[class=field field-name-field-naslov-skladbe field-type-text field-label-hidden]') as $d)
  {
    array_push($songnames,$d->plaintext);
    $cmd = "http://ws.spotify.com/search/1/track.json?q=" . str_replace(" ", "+", $d->plaintext);
    array_push($query, $cmd);
  }
      
$i = 0;

foreach($html->find('div[class=field field-name-field-izvajalec-skladbe field-type-text field-label-hidden]') as $d)
  {
    array_push($artists,$d->plaintext);
    $query[$i] = $query[$i] . "+" . str_replace(" ", "+", $d->plaintext);
    $res = file_get_html($query[$i]);
    $j = json_decode($res,true);
    
    if(count($j["tracks"]) > 0)
      {
        array_push($list,$j["tracks"][0]["href"]);
#        echo $j["tracks"][0]["href"] . "<br>";
#        echo "<font id='found'>";
      }
    else
 #     echo "<font id='notfound'>";

    echo 
      "<font class='body' id='artist'>" . $artists[$i] . "</font>" .
      "<font class='body' id='song'>" . $songnames[$i] . "</font></font>";
    $i++;
  }

echo "number of total songs is " . count($songnames) . "<br>";
echo "number of found songs is " . count($list) . "<br>";

echo "<br><br><h2>Spotify list</h2><br>";

foreach($list as $d)
  echo $d . "<br>";


?>
</div>
</body>
</html>

