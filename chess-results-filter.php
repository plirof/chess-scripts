<?php
/*
Chess-results -filter categories & get PGNs
-220310 : v004 - added chessbase
-220308 : v003 - 

*/

//error_reporting(E_ERROR | E_PARSE);
// Filter line containing text :
$keywords_pattern='#\b(u12|u99|etc)\b#i';

//$url = "https://graph.facebook.com/19165649929?fields=name";
//example :http://localhost/img/cr05.php?url=https://chess-results.com/tnr609201.aspx?lan=1&art=1&rd=7
// URL of chess-results to fetch
if (@$_REQUEST["url"]!="") {

    //$url="https://chess-results.com/tnr609201.aspx?lan=1&art=1&rd=7";
    $url=$_REQUEST["url"];
  }
   else {
    $url = "https://chess-results.com/tnr615899.aspx?lan=1&flag=30&turdet=YES&zeilen=99999";
 }

//Check if filter is given
if (@$_REQUEST["filter"]!="") {

    $keywords_pattern='#\b('.$_REQUEST["filter"].')\b#i';
  }
   else {
    $keywords_pattern='#\b(u12|u99|etc)\b#i';
 }



@$player_url="".$fide_id;//NOT used yet - url with player games




$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: graph.facebook.com'));
$output = curl_exec($ch);
curl_close($ch); 
 $mytext=$output;

// $result = array_filter(explode('<div class="defaultDialog"', $mytext));//print_r($result);
 $result = array_filter(explode('<div class="defaultDialog"', $mytext));//print_r($result);
//echo $result[2];
//GET St
$mytext=$result[2];
echo "<hr size=5>";
//echo $mytext;
include 'Html2Text.php';
//$mytext=$_REQUEST["text_entered"] ;// get TinyMCE html
$txt="";
//$txt = "<h2>$file_name</h2>\n";
//fwrite($myfile, $txt);
$txt = $txt."$mytext\n";
@$txt = \Soundasleep\Html2Text::convert($txt); // STrip HTML tags
//echo $txt;


//Split text per line
$txt2= explode("\n",$txt) ;

//print_r($txt2);
$end_result="<ol>";
foreach ( $txt2 as $string ) {
  //echo "<BR> HELLO:".$string;
 //foreach ($keywords as $keyword) {

  if (preg_match_all($keywords_pattern, $string)) 
        //if (strstr($string,$keyword) !== false) 

        {
            //preg_match_all("/\[([^\]]*)\]/", $string, $matches); //ok works with braces
            //preg_match_all("/\[([^]]*)\]/is", $string, $matches); //only text
            preg_match_all("/\[(.*?)\]/", $string, $matches); //only text

            @$matches[0][0]=str_replace(array('[', ']'),"",$matches[0][0]);
            @$matches[0][1]=str_replace(array('[', ']'),"",$matches[0][1]);

            $name=$matches[0][0]; 
            //$name_comma_space = substr_replace($name, "%2C", strpos($name, ' '), strlen(' '));
            //$name_comma_space = substr_replace($name_comma_space, "%20", strrpos($name, ' '), strlen(' '));
            $fide_id=$matches[0][1];
            //$end_result.=$string."<BR>\n";
            
            $end_result.="<li><b>".$name."--<a target=_blank href='https://ratings.fide.com/profile/".$fide_id."'>".$fide_id."|</a></b>--"
            //$newstring = substr_replace($haystack, $replace, strpos($haystack, $needle), strlen($needle));
            // $newstring = substr_replace($name, "%2C", strpos($name, ' '), strlen(' '));//https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
            ."<a href=https://ratings.fide.com/view_games.phtml?id=$fide_id target=_blank >1.ratingsFideViewGames|</a> "
            ."<a href=https://www.chessbites.com/Games.aspx?player=".str_replace(array(' ', ']'),"%2C",$name)." target=_blank >2.chessbites|</a> "
//+++++++++++++++++++++++++++
			."<a href=chessbites.com-grab.php?url=https://www.chessbites.com/Games.aspx?player=".str_replace(array(' ', ']'),"%2C",$name)." target=_blank >2b.GRABchessbites|</a> "
//------------------            
			."<a href=https://players.chessbase.com/en/player/".str_replace(array(' ', ']'),"_",$name)." target=_blank >3.Chessbase|</a> "            

            //."<a href=https://www.chessbites.com/Games.aspx?player=".$name_comma_space." target=_blank >2.chessbites.com-Games.aspx</a> "

            ."--".$string."<BR>\n";
        } 
//}
}
echo $end_result;



?>

<hr>
<form action="" method="post">
url: <input type="text" name="url"><br>
filter (values seperated by | ): <input type="text" name="filter" value="u12|u99|etc" ><br>

<input type="submit">
<?php echo "Last URL= $url <BR> Last Filter =$keywords_pattern "; ?>
</form>
<hr>