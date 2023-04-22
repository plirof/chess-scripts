/*
Changes
-220404- extra button to rank U-categories
-220403- extra button in case we don't have full age update
-220402- grab age JS+php proxy


https://chess-results.com/tnr622149.aspx?lan=1&turdet=YES   :6 players (1 u14)
<HR><input type="button" class="createlink" value="Count Age Categories" onclick="countCategories(age_table);"   >

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

function wait(){
   sec=Math.floor(Math.random() * 5); 
   var start = new Date().getTime();
   var end = start;
   while(end < start + sec*1000) {
     end = new Date().getTime();
  }
}



//grab web page and display age +agegroup
function cr_get_one_age3(tnr,snr,div_name,return_u_category=false){
wait();
//console.log("======================cr_get_one_age2 ,div_name="+div_name+"  cr_url="+cr_url+ "  ,  \n");
 if (!do_get_age)console.log ("NOT do_get_age");
//div_name="age"+snr;
//snr=59;
//tnr=615899;
//url='http://chess-results.com/tnr'+tnr+'.aspx?art=9&snr='+snr;
if(!(snr>0)) return;
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
      //preg_match_all('/(?s)(?<=\<td class=\"CR\"\>)[1-2]\d{3}(?=\<\/td\><\/tr\><\/table\>)/s', $output_birth, $age_array);
      //age= sampleResp.search(/(?s)(?<=\<td class=\"CR\"\>)[1-2]\d{3}(?=\<\/td\><\/tr\><\/table\>)/s);
      //age= sampleResp.search(/(?<=\<td class=\"CR\"\>)[1-2]\d{3}(?=\<\/td\><\/tr\><\/table\>)/s);

      //\"CR\"\>[1-2]\d{3}\<\/td>\<\/tr\>\<\/table>
      age_part= sampleResp.match(/\"CR\"\>[1-2]\d{3}\<\/td\>\<\/tr\>\<\/table\>/s);
      //if(typeof age_part[0] === 'undefined') age_part[0]="9999";
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
  if( (cur_year-age)==10 || (cur_year-age)==9)   age_text="U10-"+age;
  if( (cur_year-age)==8  || (cur_year-age)==7)   age_text="U08-"+age;
  //console.log ("<BR>UUUUUUUUUUUU year="+cur_year+" , birth_result="+age_text);

}
    age_table[snr]=age_text;

    var output = document.getElementById(div_name);
    output.innerHTML = age_text;//.value;
    //console.log("tnr="+tnr+" , snr= "+snr+"div_name= "+div_name+"age ="+age_text );
    //div_name.textContent = "New text";


    });

    return "5000";

}



// Display
function countCategories(age_table){
// https://bobbyhadz.com/blog/javascript-get-all-elements-by-id-starting-with
const elList = document.querySelectorAll(`[id^="age"]`);
//console.log(elements1);

var count_u08=0;
var count_u10=0;
var count_u12=0;
var count_u14=0;
var count_u16=0;
var age_text;

var data = {};
elList.forEach(function(el){
	//console.log(el.value);
	age_text=el.innerHTML;  
	console.log("el.innerHTML -age_text "+age_text);
	if ((age_text.indexOf("U08") !== -1)) {count_u08++;el.innerHTML =count_u08+")"+age_text ;}  
	if ((age_text.indexOf("U10") !== -1)) {count_u10++;el.innerHTML =count_u10+")"+age_text ;}  
	if ((age_text.indexOf("U12") !== -1)) {count_u12++;el.innerHTML =count_u12+")"+age_text ;}  
	if ((age_text.indexOf("U14") !== -1)) {count_u14++;el.innerHTML =count_u14+")"+age_text ;console.log("el.innerHTML age-text contains  : U14");}  
	if ((age_text.indexOf("U16") !== -1)) {count_u16++;el.innerHTML =count_u16+")"+age_text ;}  
	//console.log(el);  
  //data[el.id] = el.value;
});
} // END of function countCategories(age_table){





// Display
function updateAges(age_table){

//var arrayLength = age_table.length;
var age_text;
//console.log(age_table[0]+" ___ ");


for (var key in age_table) {
  var snr=key;
  var age_text = age_table[snr];
  div_name="age"+snr;
  console.log("snr ,age_text : "+snr, age_text);
  var output = document.getElementById(div_name);
  output.innerHTML = age_text;//.value;

  console.log(div_name+" = "+age_text);

}




    return "5000";

}

