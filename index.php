<?php
$files = scandir('./');
sort($files); // this does the sorting
foreach($files as $file){
   echo'<hr><a href="./'.$file.'">'.$file.'</a>';
}
/*
$arrFiles = array();
$handle = opendir('/path/to/directory');
 
if ($handle) {
    while (($entry = readdir($handle)) !== FALSE) {
        $arrFiles[] = $entry;
    }
}
 
closedir($handle);
*/
?>