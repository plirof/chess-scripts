/*
Changes
-220402- grab age JS+php proxy


https://chess-results.com/tnr613936.aspx?art=9&snr=3


            if($do_get_age) { $name=cr_get_age($string,true)."-".$name; //if($debug) console.log ( "<hr>AGE+name=$name");  
            }; //^^^ Might need to put Ucategory to end


TESTS /INFO
CORS proxy apps : https://gist.github.com/jimmywarting/ac1be6ea0297c16c477e17f8fbe51347
https://stackoverflow.com/questions/46881154/get-html-file-from-href-and-load-it-into-the-page-using-vanilla-javascript

*/

var elem;
var html;
var pending=false;
do_get_age=true;// it should be activated from PHP code
corsproxyurl="proxy_jon.php";
//corsproxyurl="proxy.php?url=";
// 11 players : https://chess-results.com/tnr622495.aspx?lan=1

//grab web page and display age +agegroup
function cr_get_age($cr_url,$return_u_category=false){
//

//console.log ("======================INSIDE JS cr_get_age");
// const do_get_age=true
if (!do_get_age)console.log ("NOT do_get_age");

//return "9999";

$birth_result=$cr_url;
//console.log ( "<hr>AAAAAAAAA9 $cr_url zzzzzzzzzzzzzzzzzzz<BR>");

//preg_match_all('/\s*(?i)href\s*=\s*(\"(tnr)([^"]*\")|\'[^\']*\'|([^\'">\s]+))/', $cr_url, $bd_output);
//preg_match('/(\]\()(.*)(\) \[)/s', $cr_url, $bd_output);
$bd_output="";
preg_match('/(tnr)(.*)(snr\=)/', $cr_url, $bd_output);
//preg_match('/(tnr)(.*)(\) \[)/',$cr_url, $bd_output);
//var_dump( $bd_output);
//print_r($bd_output);

preg_match('/snr=([^]]*)\)/s', $cr_url, $snr_output_array);
$birth_result=$snr_output_array[1];
//@$cr_query="https://chess-results.com/tnr613936.aspx?art=9&snr=".$snr_output_array[1];
$cr_query="https://chess-results.com/".$bd_output[0].$snr_output_array[1];
$cr_query=corsproxyurl+$cr_query;
//$output_birth='class="CR">0</td></tr><tr><td class="CR">Fide-ID</td><td class="CR">4201990</td></tr><tr><td class="CR">Year of birth </td><td class="CR">1979</td></tr></table><p Class="CRlz">&nbsp;</p>';

//console.log ( "<hr>AAAAAAAAA20 $cr_query");
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
	if( ($cur_year-$birth_result)==16 || ($cur_year-$birth_result)==15)  return "U16-".$birth_result;
	if( ($cur_year-$birth_result)==13 || ($cur_year-$birth_result)==14)  return "U14-".$birth_result;
	if( ($cur_year-$birth_result)==11 || ($cur_year-$birth_result)==12)  return "U12-".$birth_result;
	if( ($cur_year-$birth_result)==10 || ($cur_year-$birth_result)==9)  return "U10-".$birth_result;
console.log ("<BR>UUUUUUUUUUUU $cur_year , $birth_result");

}

return $birth_result;
}




//grab web page and display age +agegroup
function cr_get_one_age3(tnr,snr,div_name,return_u_category=false){

//console.log("======================cr_get_one_age2 ,div_name="+div_name+"  cr_url="+cr_url+ "  ,  \n");
 if (!do_get_age)console.log ("NOT do_get_age");
//div_name="age"+snr;
//snr=59;
//tnr=615899;
//url='http://chess-results.com/tnr'+tnr+'.aspx?art=9&snr='+snr;

//url="https://chess-results.com/"+cr_url;
url=corsproxyurl+"?tnr="+tnr+"&snr="+snr;
//console.log("ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZz cr_url ="+url);


    Promise.all([
     // fetch(url, { mode: 'no-cors'}).then(x => x.text()) //fetches EMPTY response when remote
      fetch(url, {
        headers: {
            //'content-type': 'multipart/byteranges',
           // 'range': 'bytes=2-5,500-10000',
             //'method': 'post',
             //"cors": "https://chess-results.com/"+cr_url,
        },
      }

        ).then(x => x.text())
    ]).then(([sampleResp]) => {
      //console.log("\n\n\n\n\n\nEEEEEEEEEEEEEEEEEEEEEEEEE \nsampleResp =\n"+sampleResp+"\nSSSSSSSSSSSSSSSSSSSSSS");
      //var username1=SECTIONS.username.input.getText();
      //var username1ucfirst=username1.charAt(0).toUpperCase() + username1.slice(1);
      //console.log (username1 + " ===============," +username1ucfirst);
      //sampleResp=sampleResp.toUpperCase();
      //sampleResp=sampleResp.replace(username1, username1ucfirst);
      //preg_match_all('/(?s)(?<=\<td class=\"CR\"\>)[1-2]\d{3}(?=\<\/td\><\/tr\><\/table\>)/s', $output_birth, $age_array);
      //age= sampleResp.search(/(?s)(?<=\<td class=\"CR\"\>)[1-2]\d{3}(?=\<\/td\><\/tr\><\/table\>)/s);
      //age= sampleResp.search(/(?<=\<td class=\"CR\"\>)[1-2]\d{3}(?=\<\/td\><\/tr\><\/table\>)/s);

      //\"CR\"\>[1-2]\d{3}\<\/td>\<\/tr\>\<\/table>
      age_part= sampleResp.match(/\"CR\"\>[1-2]\d{3}\<\/td>\<\/tr\>\<\/table>/s);
      age=age_part[0].match(/[1-2]\d{3}/s);
      //age=age_part;
      //age= sampleResp.search(/[1-2]\d{3}/s);
      ////age= sampleResp.search(/[1-2]\d{3}/s);
      

      //sampleResp=replaceAll(sampleResp,username1, username1ucfirst);  
      //console.log("__________________________sampleResp"+sampleResp);
      //download(username1ucfirst+".pgn", sampleResp);
      pending = false;
  age_text=age;
  if(return_u_category){
    var currentTime = new Date();
    var cur_year = currentTime.getFullYear();
 
  if( (cur_year-age)==16 || (cur_year-age)==15)  age_text="U16-"+age;
  if( (cur_year-age)==13 || (cur_year-age)==14)  age_text="U14-"+age;
  if( (cur_year-age)==11 || (cur_year-age)==12)  age_text="U12-"+age;
  if( (cur_year-age)==10 || (cur_year-age)==9)  age_text="U10-"+age;
  if( (cur_year-age)==8 || (cur_year-age)==7)  age_text="U08-"+age;
console.log ("<BR>UUUUUUUUUUUU year="+cur_year+" , birth_result="+age_text);

}


    var output = document.getElementById(div_name);
    output.innerHTML = age_text;//.value;
    console.log("tnr="+tnr+" , snr= "+snr+"div_name= "+div_name+"age ="+age_text );
    //div_name.textContent = "New text";


    });

    return "5000";

}





