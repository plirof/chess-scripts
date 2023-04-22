<?php
/*
Knockdown tournament simple manager PHP
Random pairing

Changes
-v230407d - Minor fix
-v230407c - First working version
-

https://stackoverflow.com/questions/21232825/randomly-generate-pair-team-not-pairing-with-itself
Note: Online random item list generator : http://www.mynikko.com/tools/tool_incrementstr.html

ToDO:
-implement swiss_pairing
- Show game history in the end - maybe with tabs
ok -Front end text area to enter list of teams
ok -List of teams is entered in the terams[] array and then they are paired and returned to frontend
ok -Each paired team has a checkbox for the winner
ok -Submit form get only the winners and puts them again in the teams[] array to do a next random pairing


*/
$debug=false;
if ($debug) {echo "<h3>";print_r($_REQUEST) ;echo " </h3>"; }
$random_pair_for_all_rounds=false; //false for only use random pair in the first round
$swiss_pairing=true; //NOTE swiss_pairing ignores random

$round=0;
if(@$_REQUEST['round']==1 ) $round=1;
if(@$_REQUEST['round']>1 ) { 
    //if($debug)echo "round".$_REQUEST['round']; 
    $round=$_REQUEST['round'];
    //if($debug)echo "round got from form:$round"; 
}

/*
//Filter request : https://stackoverflow.com/questions/69416655/filtering-and-sanitizing-checkboxes-input-with-php
$args=array(
        'onename'   =>  array(
            'filter'    =>  FILTER_SANITIZE_NUMBER_INT,
            'flags'     =>  FILTER_REQUIRE_ARRAY
        )
    );
$_REQUEST=filter_input_array( INPUT_POST, $args );
*/
echo "<h3> Round : $round </h3>";


function name_checkbox_create($teams,$index){

    return "<b>".$teams[$index ]."</b> [Won? : <input type='checkbox' name=winner[] value='".$teams[$index ]."''  >]";

}

function swiss_pair($teams){
    echo "<h3>Using Swiss Pairing! (note: ignores points)</h3>";
    $number_of_teams = count($teams);
    $teams_swiss=array();
    $counter=0;
    for($i=0;$i<($number_of_teams/2);$i++) {

        $teams_swiss[$counter]=$teams[$i];
        $counter++;
        $teams_swiss[$counter]=$teams[$i+($number_of_teams/2)];
        $counter++;

    }
    return $teams_swiss;




}


function  generate_pairing($teams) {
    global $round,$random_pair_for_all_rounds,$swiss_pairing;
    $number_of_teams = count($teams);
    
    // Shuffle the teams
    if(($round==1 || $random_pair_for_all_rounds ) && !$swiss_pairing ) shuffle($teams);// You get a shuffled array

    $nextround=$round+1;
    if($number_of_teams%2==1) {$teams[] = "Bye"; }// Bye is always the last entry echo
    if ($swiss_pairing) $teams=swiss_pair($teams);

    //echo '<form action="tournament.php" method=POST id="usrform">
 	echo '<form action="" method=POST id="usrform">

  <input type="hidden" name=round value='.$nextround.'>';
    // Pair the adjacent teams
    $pairing_table="";
    for ( $index = 0; $index < $number_of_teams; $index +=2) {
        // Pair $teams[$index ] with $teams[$index +1]
        //For RoboJSbattle it could generate a match link for each pair eg ?index.html?bot1=$teams[$index ]&bot2=$teams[$index+1]
        $pairing_table .= name_checkbox_create($teams,$index)  . "-> " . name_checkbox_create($teams,$index+1) ." <br>"; //Print with checkboxes
    } //end of for
    echo $pairing_table;
    echo '<input type="submit">';

}



//First page 
if($round==0 ){
    echo 'Enter list of names (one name per line). Pairing is random.<br>
    <!-- <form action="tournament.php" method=POST id="usrform"> -->
     <form action="" method=POST id="usrform">
     
      <input type="hidden" name=round value=1>
      <textarea rows=10 name="initialteamlist" form="usrform">
name1
name2
name3
name4
name5
name6
name7
name8
      </textarea>
      <input type="submit">
    </form>


    ';
}



//$ids = explode(PHP_EOL, $input);
if($round==1 ){
    echo "<h2>First Round</h2>";
    $teamtext_filtered = preg_replace('/\n+/', "\n", trim($_REQUEST['initialteamlist']));
    //$teamtext_filtered = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", trim($_REQUEST['initialteamlist']));
    //$teams = explode(PHP_EOL,$teamtext_filtered );
    $teams = explode("\n",$teamtext_filtered );
    generate_pairing($teams);
}


if($round>1 ){
    echo "<h2>Next Round</h2>";
    $teams=$_REQUEST['winner'];
    if ($debug) {echo "<h3>ROUND>1 : ";print_r($teams) ;echo " </h3>"; }
    if(count($teams)>1 )generate_pairing($teams); //here we need checkboxes
}
/*
$teams[] = "Austria";
$teams[] = "Hungary";
$teams[] = "Czech Republic";
$teams[] = "New Zealang";
$teams[] = "France";
$teams[] = "Belgium";
$teams[] = "Estonia";
$teams[] = "Iceland";
*/










?>