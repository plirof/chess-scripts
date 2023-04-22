<?php //print_r($_REQUEST);
/*
Chess-results -filter categories & get PGNs

Changes
-220820 - Find Weekend - Hide old tournaments
-220819 - Initial version


*/
error_reporting( error_reporting() & ~E_NOTICE ); // evil
 ?>
 <html><head>
<!--
  <script src="include_cr_get_location_date51.js"></script>
-->
  <script type="text/javascript" >
  const do_get_age=false;
  age_table= new Array();
  name_table= new Array();
  pending=false;
  //age_table[0]="0";
</script>
 </head>
 <body>
<form action="" method="POST" > 
<input type="text" value="BUL" name=country>   
<!--

<input type="submit" value="SelectCheckedNames">   
<input type="submit" value="SelectCheckedNames">

<HR><input type="button" class="createlink" value="Get Ages" onclick="grabAges(age_table);"   >
<HR><input type="button" class="createlink" value="Update Ages" onclick="updateAges(age_table);"   >
<HR><input type="button" class="createlink" value="Count Age Categories" onclick="countCategories(age_table);"   >
-->
<?php
/*



//print_r($_REQUEST);



<form action="return_request.php" >
*/

$array_wanted_cities=["Sofia","СОФИЯ","Добринище","Dobrinishte","ЯКОРУДА","Yakoruda"];
$array_wanted_cities=[ 303,       303,        232,          232,      250,      250];



//error_reporting(E_ERROR | E_PARSE);
// Filter line containing text :
$keywords_pattern='#\b(|u12|u99|etc)\b#i';
$do_get_age=false; //set it to false

$temp_keywords_pattern='';
$debug=true;
$production_show=true;
include 'Html2Text.php';
//echo "<h5>Execution time ".microtime(true)." </h5>";


if (@$_REQUEST["country"]!="") {

    //$url="https://chess-results.com/tnr609201.aspx?lan=1&art=1&rd=7";
    $country=$_REQUEST["country"];
  }
   else {
    
    $country = "BUL";
 }

if (@$_REQUEST["url"]!="") {

    //$url="https://chess-results.com/tnr609201.aspx?lan=1&art=1&rd=7";
    $url=$_REQUEST["url"];
  }
   else {
    //echo "url ok<HR size=10>";
    $url = "https://chess-results.com/fed.aspx?lan=1&fed=".$country;
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






//Check if filter is given
if (@$_REQUEST["filter"]!="") {
   // if(strlen($temp_keywords_pattern)>2){ echo'strlen($temp_keywords_pattern)>2'; $temp_keywords_pattern="|".$temp_keywords_pattern; }//add a | in case we have both filters
    $temp_keywords_pattern=''.$_REQUEST["filter"].''.$temp_keywords_pattern;
  }
   else {
    //$temp_keywords_pattern='(u12|u99|etc)';
 }


//Checks if date is a weekend (if >=5 checks for friday also)
function isWeekend($date) {
    return (date('N', strtotime($date)) >= 6);
}



function fproxy($tnr=615899,$snr=59){
//$url='http://chess-results.com/tnr615899.aspx?art=9&snr=59';
$url="http://chess-results.com/tnr$tnr.aspx?art=9&snr=$snr";
$output_birth = file_get_contents($url);
return $output_birth;


}

function extract_text($str,$start_text,$end_text){
  $regular='/'.$start_text.'(.*?)'.$end_text.'/';
  if (preg_match($regular, $str, $match) == 1) {
  //if (preg_match('/Arbiter(.*?)Pairing/', $str, $match) == 1) {

    //echo $match[1];
    return $match[1];
  }

}

function extract_next_text($data,$start_text,$length=10){
   $part = substr($data, strpos($data, $start_text)+strlen($start_text),$length);
   return $part;
 
}



function checkIfFutureDate($given_date){
//echo "dat(Y/mm/dd):".date("Y/m/d") ." given date=$given_date";
if( $given_date > date("Y/m/d") ) {
    // today's date is before 20140505 (May 5, 2014)
    return true;
}

$time1  = strtotime(date("Y/mm/dd", $given_date));
  if(time() > $time1)
   {
//     echo "too early!".$time1."  given_date=$given_date";
  //   return true;
   } 
return false;
}



function array_search_partial($keyword,$arr) {
    foreach($arr as $index => $string) {
        if (strpos($string, $keyword) !== FALSE)
            return $index;
    }
}

function fetch_tournament_data($tour_url="http://localhost"){
  $tourdata=["NOT FOUND","--","---"];
//if (preg_match('/before-(.*?)-after/', $str, $match) == 1) {
//    echo $match[1];
//}

//  /*  #####################################
$ch = curl_init($tour_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: graph.facebook.com'));
$output = curl_exec($ch);
curl_close($ch); 
$mytext=$output;
//@$mytext = \Soundasleep\Html2Text::convert($output);
if (preg_match('/Arbiter(.*?)Pairing/', $mytext, $match) == 1) {

    //echo $match[1];
    $tourdata=$match[1];
   // $date=extract_text($match[1],$start_text,$end_text);
    @$plaintext = \Soundasleep\Html2Text::convert($tourdata);
    //echo '<BR>ZZZZZZZZZZZZZZZZZZZZ'.$plaintext;

    $array=explode("\n", $plaintext);
    //var_dump($array);

    $date=extract_next_text($plaintext,"Date",13);
    $location=extract_next_text($plaintext,"Location",13);
    $timecontrol=extract_next_text($plaintext,"Time",13);

    $location=$array[array_search_partial("Location", $array)]; $location=substr($location,9 );//$location=substr($location,9 ,40);
    $date=$array[array_search_partial('Date', $array)]; $date=substr($date,5 ,25);
    $timecontrol=$array[array_search_partial("Time", $array)]; $timecontrol=substr($timecontrol,12 ,20);

    //$tourdata=["<HR>aaaaaaaaaa DATE = ".$date." , |||==Location = ".$location." ,||||==Time Control ".$timecontrol."  aaaaaaaa ||||<br>","".$match[1]."","||||cccccccCCCc<HR>\n"];//TEST;

    $tourdata=[$date,$location,$timecontrol];
}

// */   ################################

return $tourdata;
}

if(strlen($temp_keywords_pattern)<3)  { echo'strlen($temp_keywords_pattern)<3 '."<h2>$temp_keywords_pattern</h2>"; $temp_keywords_pattern='(|u12|u99|etc)'; };

$keywords_pattern='#\b('.$temp_keywords_pattern.')\b#i';

@$player_url="".$fide_id;//NOT used yet - url with player games


echo "<h1>URL = ".$url." </h1>";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: graph.facebook.com'));
$output = curl_exec($ch);
curl_close($ch); 
$mytext=$output;

// $result = array_filter(explode('<div class="defaultDialog"', $mytext));//print_r($result);
$split_text='<td class="CRc">';
//$result = array_filter(explode('<div class="defaultDialog"', $mytext));//print_r($result);
$result = array_filter(explode($split_text, $mytext));//print_r($result);
//echo $result[2];
//GET St
//$mytext=$result[2];
//if($debug) echo "<hr>AAAAAAA  ".$result[0]."<hr>";
$result[0]="";
$mytext=$result;
echo "<hr size=5>";
#if ($debug)echo $mytext;
//include 'Html2Text.php';
//$mytext=$_REQUEST["text_entered"] ;// get TinyMCE html
$txt="";
//$txt = "<h2>$file_name</h2>\n";
//fwrite($myfile, $txt);

foreach ($mytext as $mytext1) $txt = $txt."$mytext1\n";


@$txt = \Soundasleep\Html2Text::convert($txt); // STrip HTML tags
#echo $txt;


//Split text per line
$txt2= explode("\n",$txt) ;

//print_r($txt2);
$end_result="<ol>";
$exectime=0;
echo "<table border=1>";
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
            //if($debug)echo "<hr>snr_array=".print_r($snr_array);
            $snr=str_replace("snr=","",$snr_array[0]);
            $tnr=str_replace("tnr","",$tnr_array[0]);
            //if($debug)echo "<hr>SNR===== $snr ,TNR= $tnr <hr>";
            $link = 'https://chess-results.com/tnr'.$tnr.'.aspx?lan=1&turdet=YES';
            //if($debug) echo "<hr> <a href=".$link." target='_blank' > ".$link."</a>";

            $end_result.="<tr><td><li id='tnr$tnr-snr$snr'>";

           // ******  CALL fetch function for individual here
            $tour_url="https://chess-results.com/tnr663707.aspx?lan=1&turdet=YES";
            $tour_url=$link;
            $tourdata=fetch_tournament_data($tour_url);
            
            $date_only=substr($tourdata[0], 0,10); 
             
          //  if($debug) echo "<h5>$date_only isWeekend:".isWeekend($date_only)." checkIfFutureDate($date_only):".checkIfFutureDate($date_only)."</h5>";


            if (!checkIfFutureDate($date_only)) {$end_result.='<FONT COLOR=red>OLD - </FONT></TD></tr>';CONTINUE;}
            if (isWeekend($date_only)) $end_result.='<FONT COLOR=GREEN>WEEKEND- </FONT>';
            $end_result.=''.$tourdata[1].'</td><td>'.$tourdata[0].'</td><td>'.$tourdata[2].'</td>'; // 0:date, 1:location,2:time control


            //$name_comma_space = substr_replace($name, "%2C", strpos($name, ' '), strlen(' '));
            //$name_comma_space = substr_replace($name_comma_space, "%20", strrpos($name, ' '), strlen(' '));
            //$fide_id=$matches[0][1];
            //$end_result.=$string."<BR>\n";
            
            $end_result.="<td><input type='checkbox' name='selected_names[]' id='player' value='$name' />";
            //if($do_get_age) { $name=cr_get_age($string,true)."-".$name; //if($debug) echo "<hr>AGE+name=$name";  @@@@ PHP ok slow
            $name2=$name;

            $end_result.="<b><a target=_blank href='$link'>".$name2."|</a></b>--";
            //$newstring = substr_replace($haystack, $replace, strpos($haystack, $needle), strlen($needle));
            // $newstring = substr_replace($name, "%2C", strpos($name, ' '), strlen(' '));//https://stackoverflow.com/questions/1252693/using-str-replace-so-that-it-only-acts-on-the-first-match





            $end_result.="</td></tr>";


        } 
//}
}
if ($production_show) echo $end_result;


?><br>
<!--
<input type="submit" value="SelectCheckedNames"  >
</form>
<hr>
<form action="" method="post"> 
url: <input type="text" name="url"><br>
filter (values seperated by | ): <input type="text" name="filter" value="|u12|u99|etc" ><br>
Show/Get ages : <input type='checkbox' name='getage' value='YES-getage' /><BR>  
-->
<input type="submit">
<?php echo "Last URL= $url <BR> Last Filter =$keywords_pattern  "; ?>
</form>
<?php 
//selected_names%5B%5D=Mamedov+Edgar&selected_names%5B%5D=Korelskiy+Egor&url=http%3A%2F%2Fchess-results.com%2Ftnr615897.aspx%3Flan%3D1%26flag%3D30%26turdet%3DYES&filter=u12%7Cu99%7Cetc
//a$="selected_names[]=" ;
//echo "Last FILTER : <a href=? > </a>URL= $url <BR> Last Filter =$keywords_pattern  "; 

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
