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


echo "<strong>Pulling tracks from <font style='color:grey;'><a href='" . $_GET["urlname"] . "'> the best radio in town - R&#352 89.3 MHz</a></font></strong><br><br>";
echo "<font id='found'>found</font>/<font id='notfound'>notfound</font>  (<font id='artist'>artist </font><font id='song'>song</font>)<br><br>";

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
        $found = true;
        $track = $j["tracks"][0]["href"];
        array_push($list,$track);

        echo "<font id='found'>";
        echo "<a href='http://open.spotify.com/track/".
          substr($track,-22)."'>";
      }
    else
      {
        $found = false;
        echo "<font id='notfound'>";
      }
    echo 
      "(<font id='artist'>" . $artists[$i] . "  </font>" . "<font id='song'>" . $songnames[$i] . ")  </font></font></a>";
    $i++;
  }

echo "<br><br><a href='rslookup.html'>&lt;--- back to search</a><br><br>";
$title = substr($_GET["urlname"], 67);
echo "(if you don't have Spotify installed, click on the <font id='found'>found</font> tracks above, otherwise use the player below)<br><br>";

echo "<iframe src='https://embed.spotify.com/?uri=spotify:trackset:". $title .":";

foreach($list as $d)
  echo ",".substr($d,-22);

echo "' frameborder='0' allowtransparency='true' width='300' height='380'></iframe>";


?>
</div>
</body>
</html>

