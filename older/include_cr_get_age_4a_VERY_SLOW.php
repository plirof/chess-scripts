<?php
/*
https://chess-results.com/tnr613936.aspx?art=9&snr=3


*/
function cr_get_age($cr_url,$return_u_category=false){
$birth_result=$cr_url;
//echo "<hr>AAAAAAAAA9 $cr_url zzzzzzzzzzzzzzzzzzz<BR>";

//preg_match_all('/\s*(?i)href\s*=\s*(\"(tnr)([^"]*\")|\'[^\']*\'|([^\'">\s]+))/', $cr_url, $bd_output);
//preg_match('/(\]\()(.*)(\) \[)/s', $cr_url, $bd_output);
$bd_output="";
preg_match('/(tnr)(.*)(snr\=)/', $cr_url, $bd_output);
//preg_match('/(tnr)(.*)(\) \[)/',$cr_url, $bd_output);
//var_dump( $bd_output);
//print_r($bd_output);

preg_match('/snr=([^]]*)\)/s', $cr_url, $snr_output_array);
@$birth_result=$snr_output_array[1];
//@$cr_query="https://chess-results.com/tnr613936.aspx?art=9&snr=".$snr_output_array[1];
@$cr_query="https://chess-results.com/".$bd_output[0].$snr_output_array[1];

//$output_birth='class="CR">0</td></tr><tr><td class="CR">Fide-ID</td><td class="CR">4201990</td></tr><tr><td class="CR">Year of birth </td><td class="CR">1979</td></tr></table><p Class="CRlz">&nbsp;</p>';

//echo "<hr>AAAAAAAAA20 $cr_query";
/*
$ch_cr_birth = curl_init($cr_query);
curl_setopt($ch_cr_birth, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch_cr_birth, CURLOPT_BINARYTRANSFER, true);
curl_setopt($ch_cr_birth, CURLOPT_SSL_VERIFYPEER, FALSE);
//curl_setopt($ch_cr_birth, CURLOPT_HTTPHEADER, array('Host: graph.facebook.com'));
$output_birth = curl_exec($ch_cr_birth);
curl_close($ch_cr_birth); 

*/
//sleep(1);
$output_birth = file_get_contents($cr_query);
//print_r($output_birth);

//$birth_result=$output_birth;
preg_match_all('/(?s)(?<=\<td class=\"CR\"\>)[1-2]\d{3}(?=\<\/td\><\/tr\><\/table\>)/s', $output_birth, $age_array);
//print_r($age_array[0]);
$birth_result=$age_array[0][0];


if($return_u_category){
	$cur_year=date("Y");
	if( ($cur_year-$birth_result)==16 || ($cur_year-$birth_result)==15)  $birth_result="U16-".$birth_result;
	if( ($cur_year-$birth_result)==13 || ($cur_year-$birth_result)==14)  $birth_result="U14-".$birth_result;
	if( ($cur_year-$birth_result)==11 || ($cur_year-$birth_result)==12)  $birth_result="U12-".$birth_result;
	if( ($cur_year-$birth_result)==10 || ($cur_year-$birth_result)==9)  $birth_result="U10-".$birth_result;
echo "<BR>UUUUUUUUUUUU $cur_year , $birth_result";

}

return $birth_result;
}

?>