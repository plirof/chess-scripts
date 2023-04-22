<?php
//https://gist.github.com/dropmeaword/a050231a5767adc52b986faf587f64c9
//error_reporting( error_reporting() & ~E_NOTICE ); // evil

$tnr=$_GET['tnr'];
$snr=$_GET['snr'];

//$url='http://chess-results.com/tnr615899.aspx?art=9&snr=59';
$url="http://chess-results.com/tnr$tnr.aspx?art=9&snr=$snr";
$output_birth = file_get_contents($url);
echo $output_birth;
return $output_birth;

exit(0);
//echo "AAAAAAAAAAAAAAA";

$url = $_REQUEST['url'];
echo $url;
//$url='http://chess-results.com/tnr615899.aspx?art=9&snr=59';
$output_birth = file_get_contents($url);
echo $output_birth;
/*
  $ch = curl_init( $url );
 curl_exec( $ch );
  // @lf get domain from url and keep it around
  //$parts = parse_url( $url );
  //$domain = $parts['scheme']."://".$parts['host'];
  //list( $header, $contents ) = preg_split( '/([\r\n][\r\n])\\1/', curl_exec( $ch ), 2 );
  //curl_close( $ch );




*/

?>