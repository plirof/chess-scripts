<?php
/*
chessbites.com-grab.php get PGNs
-220308 : v003 - 
https://www.phpliveregex.com/#tab-preg-match-all

add this to chess-script :

//+++++++++++++++++++++++++++
."<a href=chessbites.com-grab.php?url=https://www.chessbites.com/Games.aspx?player=".str_replace(array(' ', ']'),"%2C",$name)." target=_blank >2b.GRAB-FIRST games of chessbites.com</a> "
//------------------


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
    echo "<hr>URL=$url";
  }
   else {
    $url = "https://chess-results.com/tnr615899.aspx?lan=1&flag=30&prt=1&zeilen=99999";
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
/*
// $result = array_filter(explode('<div class="defaultDialog"', $mytext));//print_r($result);
 $result = array_filter(explode('GotoOverview(&#39;', $mytext));//print_r($result);
echo "<BR>".$result[1]."<BR>";
$arr = explode("');",$result[1]);
echo "<BR>".$arr[1];
//GET St
//var_dump($result);
exit(0);
//$mytext=$result[2];
echo "<hr size=5>";
echo $mytext;

//$mytext=$_REQUEST["text_entered"] ;// get TinyMCE html
$txt="";
//$txt = "<h2>$file_name</h2>\n";
//fwrite($myfile, $txt);

*/
$txt ="$mytext\n";
//include 'Html2Text.php';

//@$txt = \Soundasleep\Html2Text::convert($txt); // STrip HTML tags




//##########################################################################################################################################################
echo "<BR>";

//##########################################################################################################################################################
//echo $txt;
//$str = 'before-str-after';// [0] => GotoOverview('krxAxprLk');"> [1] => 'krxAxprLk');"
//if (preg_match('/before-(.*?)-after/', $str, $match) == 1) {
//if (preg_match('/GotoOverview\((.*?)\>/', $txt, $match) == 1) {  
if (preg_match('/\(\'(.*?)\'/', $txt, $match) == 1) {    
   // print_r ($match);
}
//$match=preg_match_all('/GotoOverview(.+)\)\;/', $txt, $match);// ok on online pregmatch
//$match=preg_match_all('/GotoOverview(.+)\)\;/', $mytext, $match);
//preg_match_all('/GotoOverview(.+)\)\;/', $input_lines, $output_array);
preg_match_all('/GotoOverview(.+)\)\;/', $mytext, $match);
//$matches=preg_match('/GotoOverview(.+)\)/s',$txt,$matches);
var_dump ($match[1]);
@$match[1][0]=str_replace(array('[', ']'),"",$matches[0][0]);

//exit(0);


//https://www.chessbites.com/ShowPGN.aspx?d=kQQkxpAZL_____________onclick="GotoOverview(&#39;kQQkxpAZL&#39;)
//exit(0);
//Split text per line
$txt2= explode("\n",$txt) ;

//print_r($txt2);
$end_result="<ol>";
foreach ( $match[1] as $string ) {
    @$string=str_replace(array("('", ''),"",$string);

    @$string=str_replace(array("(&#39;", ''),"",$string);
    @$string=str_replace(array("&#39;", ''),"",$string);
    $end_result.="<BR> <a href=https://www.chessbites.com/ShowPGN.aspx?d=$string target=download_pgn >chessbites.com Download $string </a>";
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