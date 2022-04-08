<?php //print_r($_REQUEST);
/*
Chess-results -filter categories & get PGNs

Changes
-220405 v50b -220406- grabage moved to JS part (still used php proxy)
-220405 v49b -JS swiss initial pairing, Removed PHP swiss dependencies
-220403- extra button in case we don't have full age update
-220402- grab age JS+php proxy
-220322-TEST age phillipeion
-220314- TEST edit phillipeion
-220322 : v006 - added initial pairing predict (checkbox)
-220320 : v005 - added filter by name
-220310 : v004 - added chessbase
-220308 : v003 - 


*/
error_reporting( error_reporting() & ~E_NOTICE ); // evil
 ?>
 <html><head><script src="include_cr_get_age50.js"></script>
  <script type="text/javascript" >
  const do_get_age=true;
  age_table= new Array();
  name_table= new Array();
  pending=false;
  //age_table[0]="0";
</script>
 </head>
 <body>
<form action="" method="POST" >    
<input type="submit" value="SelectCheckedNames">
<HR><input type="button" class="createlink" value="Get Ages" onclick="grabAges(age_table);"   >
<HR><input type="button" class="createlink" value="Update Ages" onclick="updateAges(age_table);"   >
<HR><input type="button" class="createlink" value="Count Age Categories" onclick="countCategories(age_table);"   >
<HR><input type="button" class="createlink" value="InitialPairing" onclick="initialPairing(name_table);"   >
<?php
/*


//print_r($_REQUEST);

//+++++++++++++Extract LINKS to detail from Whole Page++++++++++
\s*(?i)href\s*=\s*(\"([^"]*\")|'[^']*'|([^'">\s]+))
\s*(?i)href\s*=\s*(\"(tnr)([^"]*\")|'[^']*'|([^'">\s]+))

https://github.com/xRuiAlves/fide-ratings-scraper/blob/master/src/parser.js
https://code.tutsplus.com/tutorials/search-and-replace-with-regular-expressions-in-php--cms-36690


//+++++++++++++EXTRACT AGE from detail page++++++++++++++
agelink-- <a Class="CRdb" href="  AND ?lan=

https://regex101.com/r/p1Ubqv/1/

/(?s)(?<=\<td class=\"CR\"\>)\d{4}(?=\<\/td\><\/tr\><\/table\>)/g
/(?s)(?<=\<td class=\"CR\"\>)[1-2]\d{3}(?=\<\/td\><\/tr\><\/table\>)/g

PageAge :  <td class="CR">1977</td></tr></table>
<td class="CR">####</td></tr></table>




<form action="return_request.php" >
*/


//error_reporting(E_ERROR | E_PARSE);
// Filter line containing text :
$keywords_pattern='#\b(u12|u99|etc)\b#i';
$do_get_age=true; //set it to false

$temp_keywords_pattern='';
$debug=true;
$production_show=true;

//echo "<h5>Execution time ".microtime(true)." </h5>";

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
if (@$_REQUEST["selected_names"]!="") {
    //echo "<h1>AAAAAAAAAAAAAAAAAAA</h1>";
    //print_r($_REQUEST); //$_REQUEST["selected_names"]
    $temp_keywords_pattern=''.implode("|", $_REQUEST["selected_names"]).''.$temp_keywords_pattern;
  }
   else {
    //$temp_keywords_pattern='|u12|u99|etc';
 }



if (@$_REQUEST["getage"]!="") {

    //$url="https://chess-results.com/tnr609201.aspx?lan=1&art=1&rd=7";
    $do_get_age=true;
  }
   else {
   	$do_get_age=false;
    
 }


//Check if filter is given
if (@$_REQUEST["filter"]!="") {
    if(strlen($temp_keywords_pattern)>2){ echo'strlen($temp_keywords_pattern)>2'; $temp_keywords_pattern="|".$temp_keywords_pattern; }//add a | in case we have both filters
    $temp_keywords_pattern=''.$_REQUEST["filter"].''.$temp_keywords_pattern;
  }
   else {
    //$temp_keywords_pattern='(u12|u99|etc)';
 }



function fproxy($tnr=615899,$snr=59){
//$url='http://chess-results.com/tnr615899.aspx?art=9&snr=59';
$url="http://chess-results.com/tnr$tnr.aspx?art=9&snr=$snr";
$output_birth = file_get_contents($url);
return $output_birth;


}


if(strlen($temp_keywords_pattern)<3)  { echo'strlen($temp_keywords_pattern)<3 '."<h2>$temp_keywords_pattern</h2>"; $temp_keywords_pattern='(u12|u99|etc)'; };

$keywords_pattern='#\b('.$temp_keywords_pattern.')\b#i';

@$player_url="".$fide_id;//NOT used yet - url with player games


//echo "<h1>116 Execution time ".microtime(true)." </h1>";

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
$exectime=0;
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

            if (strlen($name)<4) continue;
            if (strpos($name,"Chess-Tournament-Results")!== false) continue;
            if (strpos($name,"Legal details")!== false) continue;
            //echo "<hr>".print_r($string);
            preg_match("/snr\=\d{1,3}/s",$string,$snr_array);
            preg_match("/tnr\d{1,7}/s",$string,$tnr_array);
            //echo "<hr>snr_array=".print_r($snr_array);
            $snr=str_replace("snr=","",$snr_array[0]);
            $tnr=str_replace("tnr","",$tnr_array[0]);
            //echo "<hr>SNR===== $snr ,TNR= $tnr <hr>";



            //$name_comma_space = substr_replace($name, "%2C", strpos($name, ' '), strlen(' '));
            //$name_comma_space = substr_replace($name_comma_space, "%20", strrpos($name, ' '), strlen(' '));
            $fide_id=$matches[0][1];
            //$end_result.=$string."<BR>\n";
            $end_result.="<li id='tnr$tnr-snr$snr'>";
            $end_result.="<input type='checkbox' name='selected_names[]' id='player' value='$name' />";
            //if($do_get_age) { $name=cr_get_age($string,true)."-".$name; //if($debug) echo "<hr>AGE+name=$name";  @@@@ PHP ok slow
            $name2=$name;
            //$name_no_spaces=str_replace(' ', '', $name);
            $div_name="age".$snr;
            if($do_get_age || $snr>0) { 
              //$name2="<div id='".$div_name."'  name='".$div_name."'></div> ".$name; //if($debug) echo "<hr>AGE+name=$name";
              $name2="<span id='".$div_name."'  name='".$div_name."'></span> ".$name; //if($debug) echo "<hr>AGE+name=$name";
            }; //^^^ Might need to put Ucategory to end
            $end_result.="<b>".$name2."--<a target=_blank href='https://ratings.fide.com/profile/".$fide_id."'>".$fide_id."|</a></b>--"
            //$newstring = substr_replace($haystack, $replace, strpos($haystack, $needle), strlen($needle));
            // $newstring = substr_replace($name, "%2C", strpos($name, ' '), strlen(' '));//https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match
            ." <a href=https://ratings.fide.com/view_games.phtml?id=$fide_id target=_blank >1.ratingsFideViewGames|</a> "
            ."<a href=https://www.chessbites.com/Games.aspx?player=".str_replace(array(' ', ']'),"%2C",$name)." target=_blank >2.chessbites|</a> "
//+++++++++++++++++++++++++++
			."<a href=chessbites.com-grab.php?url=https://www.chessbites.com/Games.aspx?player=".str_replace(array(' ', ']'),"%2C",$name)." target=_blank >2b.GRABchessbites|</a> "
//------------------            
			."<a href=https://players.chessbase.com/en/player/".str_replace(array(' ', ']'),"_",$name)." target=_blank >3.Chessbase|</a> "            

            //."<a href=https://www.chessbites.com/Games.aspx?player=".$name_comma_space." target=_blank >2.chessbites.com-Games.aspx</a> "

            ."--".$string."<BR>\n";
            if($do_get_age) { 

              //$test_user_url='http://chess-results.com/tnr615899.aspx?lan=1&art=9&fed=AUT&flag=30&snr=59';// Should return U12 - 2011
              $end_result=$end_result."\n <script type='text/javascript' > cr_get_one_age3('".$tnr."','".$snr."','age".$snr."',true); 
              </script>\n"; //if($debug) echo "<hr>AGE+name=$name";
            }; //^^^ Might need to put Ucategory to end

        } 
//}
}
if ($production_show) echo $end_result;


?>
<input type="submit" value="SelectCheckedNames"  >
<!--</form>
<hr>
<form action="" method="post"> -->
url: <input type="text" name="url"><br>
filter (values seperated by | ): <input type="text" name="filter" value="|u12|u99|etc" ><br>
Show/Get ages : <input type='checkbox' name='getage' value='YES-getage' /><BR>  

<input type="submit">
<?php echo "Last URL= $url <BR> Last Filter =$keywords_pattern  "; ?>
</form>
<?php 
//selected_names%5B%5D=Mamedov+Edgar&selected_names%5B%5D=Korelskiy+Egor&url=http%3A%2F%2Fchess-results.com%2Ftnr615897.aspx%3Flan%3D1%26flag%3D30%26turdet%3DYES&filter=u12%7Cu99%7Cetc
//a$="selected_names[]=" ;
echo "Last FILTER : <a href=? > </a>URL= $url <BR> Last Filter =$keywords_pattern  "; 

if ($debug) {echo "<hr size=10>";print_r($_REQUEST);};


    // Program to display current page URL.  (WORKS with Small GET requests only)
    $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] 
                === 'on' ? "https" : "http") . 
                "://" . $_SERVER['HTTP_HOST'] . 
                $_SERVER['REQUEST_URI'];
    echo "<BR> <A HREF=".$link." ?>CLICK for same URL </a> <BR>".$link;


?>

<hr>
<span id='swiss_name_table'></span>