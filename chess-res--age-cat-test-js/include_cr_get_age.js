/*
https://chess-results.com/tnr613936.aspx?art=9&snr=3


            if($do_get_age) { $name=cr_get_age($string,true)."-".$name; //if($debug) console.log ( "<hr>AGE+name=$name");  
            }; //^^^ Might need to put Ucategory to end


TESTS /INFO
https://stackoverflow.com/questions/46881154/get-html-file-from-href-and-load-it-into-the-page-using-vanilla-javascript

*/

var elem;
var html;

//grab web page and display age +agegroup
function cr_get_age($cr_url,$return_u_category=false){
console.log ("INSIDE JS cr_get_age");
// const do_get_age=true
if (!do_get_age)console.log ("NOT do_get_age");

return "9999";

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

function make_table(myarray) {
	//https://towardsdatascience.com/quickly-extract-all-links-from-a-web-page-using-javascript-and-the-browser-console-49bb6f48127b
    var table = '<table><thead><th>Name</th><th>Links</th></thead><tbody>';
   for (var i=0; i<myarray.length; i++) {
            table += '<tr><td>'+ myarray[i][0] + '</td><td>'+myarray[i][1]+'</td></tr>';
    };
 	console.log("==================inside make_table  myarray.length="+myarray.length);
 	console.log(table);




    //var w = window.open("");
//w.document.write(table); 
}




//grab web page and display age +agegroup
function cr_get_one_age(cr_url,thissnr,return_u_category=false){
console.log("======================cr_get_one_age  cr_url="+cr_url+ "  ,  \nthissnr="+thissnr);
 if (!do_get_age)console.log ("NOT do_get_age");

cr_query="https://chess-results.com/"+cr_url;
console.log("cr_query ="+cr_query);


}



function process_link_array(link_array) {
	//https://towardsdatascience.com/quickly-extract-all-links-from-a-web-page-using-javascript-and-the-browser-console-49bb6f48127b
   
   var table = '';
   for (var i=0; i<link_array.length; i++) {
   			line=link_array[i];
   			if (line.includes('tnr'))     {
          line_splitted=line.split(')'); 
          //console.log("process_link_array -- line_splitted = \n"+line_splitted[0]+" \n"+line_splitted[1]+" \n"+line_splitted[2]);
   				//if (line.match(/snr=([^]]*)\)/s)) {table += "snr_match! "; //grabs only 1 digit snr}
   				if (line.match(/snr=([^]*)\)/s)) { 
   				mysnr=line.split('snr=').pop().split(')')[0];
   				
          //table += '\n'+ link_array[i];//ok
          table += '\n'+ line_splitted[0];//ok
          table += "___snr_===! "+mysnr+"  ___ ";
          cr_get_one_age(line_splitted[0],mysnr);

   				}


   			        
   			    
   			 }       
    };
 	console.log("==================================================================================inside make_table link-array.length="+link_array.length);
 	//console.log(table);


//console.log(link_array);



    //var w = window.open("");
//w.document.write(table); 
}


function grabAges(){
//global elem,html;
//elem = document.querySelector('wholebody');
elem = document.body;
// Get HTML content
html = elem.textContent;

//preg_match('/snr=([^]]*)\)/s', $cr_url, $snr_output_array);




console.log("hello "+ html);

//link_array=html.split('](tnr'); 
link_array=html.split(']('); //ok works
//link_array=html.split('<li>'); 
process_link_array(link_array)

/*
///var x = document.querySelectorAll("a");
var x = document.querySelectorAll("ol");
var myarray = []
for (var i=0; i<x.length; i++){
var nametext = x[i].textContent;
var cleantext = nametext.replace(/\s+/g, ' ').trim();
var cleanlink = x[i].href;
myarray.push([cleantext,cleanlink]);
};

make_table(myarray);

*/

//if (do_get_age)alert ("do_get_age");
}