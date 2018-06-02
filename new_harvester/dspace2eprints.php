<?php
header('Content-Type: text/html; charset=utf-8'); 	
?>

<html>
<head>
  <title>Convert file format DSpace Simple Archive Format to XML Export Format</title>
</head>

This servise convert  <a href="https://wiki.duraspace.org/display/DSDOC18/Importing+and+Exporting+Items+via+Simple+Archive+Format">DSpace Simple Archive Format</a>   to <a href="http://wiki.eprints.org/w/XML_Export_Format">XML Export Format</a> 
<body>

      <h2>DSpace Simple Archive Format zip archive</h2>
      <h3><a href="/pack/example.zip">Example input file</a></h3>
      <form action="dspace2eprints.php" method="post" enctype="multipart/form-data">
      <input type="file" name="filename"><br> 
      <input type="submit" value="Загрузить"><br>
      </form>
</body>

Developing by  Institute of Software Systems NAS Ukraine <br>
Novytskyi Oleksandr  <br>
alex at zu.edu.ua  <br>
</html>

      

<?php

$random_name= md5(uniqid());
$base_dir='./dspace/';

/* set list languadge ex. array('uk', 'ru', 'en') */
$lang=array();
   
$default_lang='uk';
 
$map_subject=array('GR'=>'Студії', 'QR'=>'Студії');
  
$allow_file=array('pdf', 'doc', 'docx');



if($_FILES["filename"]["size"] > 1024*19*1024)
   {
     echo ("size file more 5 Mb");
     exit;
   }
  
  
   if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
   {   
   	   $ext= end(explode(".", $_FILES["filename"]["name"]));
   	   if ($ext=='zip'){
		 move_uploaded_file($_FILES["filename"]["tmp_name"], "./pack/".$random_name.'.'.$ext);
		 
		 $zip = new ZipArchive; 
		 $res = $zip->open("./pack/".$random_name.'.'.$ext);
		 if ($res === TRUE) {
    	 echo 'ok';
    	 $zip->extractTo('dspace');
    	 $zip->close();
		 } else {
    	 echo 'failed, code:' . $res;
		 }
		   
   	   }
        
     
    
     
     
   } else {
      echo("Error load file");
      exit;
   }









  

  

function mapSubjects ($subject){
	global $map_subject;
	$subject=(array) $subject;
	$rezult=array_keys(array_intersect($map_subject, $subject)); 
	if (sizeof($rezult)==0){
	 $rezult[]='A';	 
	 } 
    return  $rezult;
}
 
if (is_dir($base_dir)){
  	  
try
{
    /*** a new dom object ***/
    $dom = new domDocument('1.0', 'UTF-8');
    $dom->formatOutput = true;
    $root = $dom->appendChild($dom->createElement( "eprints" ));
    $sxe = simplexml_import_dom( $dom );

  	  
  	  $list=scandir($base_dir);
  	  
  	  foreach ($list as $export){
  	  	  if (is_file($base_dir.$export.'/dublin_core.xml'))
  	  	  {
  	  	  	  
  	  	  	    $file_load= 'http://'.$_SERVER['SERVER_NAME'].'/dspace/'.$export.'/dublin_core.xml';
			  $xml=simplexml_load_file($file_load);	  
			  $errors = libxml_get_errors();
 
 				if (!$xml) 
 				{
    			echo "Ошибка загрузки XML\n";
    				foreach(libxml_get_errors() as $error) 
    				{
        				echo "\t", $error->message;
    						}
 				}
  	  	  	  
  	  	  	  $eprints = $sxe->addchild("eprint");
    $eprints->addAttribute("xmlns", "http://eprints.org/ep2/data/2.0");
    $eprints->addChild("eprint_status", "archive");
    $eprints->addChild("language", $default_lang);
  	$creators=$eprints->addChild("creators");
  	$subjects=$eprints->addChild("subjects");
  	$documents=$eprints->addChild("documents");
  	
  	if (sizeof($lang)){
	$abstract=$eprints->addChild("abstract");
	$publisher=$eprints->addChild("publisher");
	$title=$eprints->addChild("title");	
  	}
  	
  	$lang_ck=0;
  	$abstract_ck=0;
  	$publisher_ck=0;
  	$title_ck=0;
  	 	  	  
		foreach ($xml as $item) {
 			if ($item['element']=='contributor'){
						if ($item['qualifier']=='author'){
							$name_or=explode (',', (string)$item);
							$item=$creators->addChild("item");
							$name=$item->addChild("name");
							$name->addChild("family", $name_or[0]);
    						$name->addChild("given", $name_or[1]);
							}
 					}
 			if ($item['element']=='date'){
 				if ($item['qualifier']=='accessioned'){
 					$date_or=str_replace('T', ' ', (string)$item);
 					$date_or=str_replace('Z', '', $date_or);
 					$eprints->addChild("datestamp", $date_or);
 					$eprints->addChild("lastmod", $date_or);
 					$eprints->addChild("status_changed", $date_or);
 				}
			}
			
			if ($item['element']=='date'){
				if ($item['qualifier']=='issued'){
					$eprints->addChild("date", (string)$item);
				}
			}
			
			if ($item['element']=='identifier'){
				if ($item['qualifier']=='citation'){
					//need add citations fields to eprints
					
				}
			}
			
			if ($item['element']=='identifier'){
				if ($item['qualifier']=='issn'){
					$eprints->addChild("issn", (string)$item);
				}
			}
			
			if ($item['element']=='identifier'){
				if ($item['qualifier']=='uri'){
					$eprints->addChild("official_url", (string)$item);
				}
			}
			
			if ($item['element']=='description'){
				if ($item['element']=='abstract'){
					if (sizeof($lang)){
						$item_ab=$abstract->addChild("item");
						$item_ab->addChild("name", (string)$item);
						$item_ab->addChild("lang", $lang[$lang_ck++]);	
						}
					}
					else{
						if (!$abstract_ck){
						$eprints->addChild("abstract", (string)$item);	
						$abstract_ck=1;
						}
					}
			}
			
			if ($item['element']=='publisher'){
				if ($item['qualifier']=='none'){
					if (sizeof($lang)){
						$publisher_ab=$publisher->addChild("item");
						$publisher_ab->addChild("name", (string)$item);
						$publisher_ab->addChild("lang", $lang[$lang_ck++]);	
						}
					
					else{
						if($publisher_ck!==1){
						$eprints->addChild("publisher", (string)$item);	
						$publisher_ck=1;
						}
					}
				   }
				}
				
			
			if ($item['element']=='subject'){
				if ($item['qualifier']=='none'){
					 $sub_list=mapSubjects((string)$item);
					 foreach ($sub_list as $value){
					$subjects->addChild("item", $value);	 
					 }
				}
			}
			
			if ($item['element']=='title'){
				if ($item['qualifier']=='none'){
					if (sizeof($lang)){
						$title_ab=$title->addChild("item");
						$title_ab->addChild("name", (string)$item);
						$title_ab->addChild("lang", $lang[$lang_ck++]);	
						}
					
					else{
						if($title_ck!==1){
						$eprints->addChild("title", (string)$item);	
						$title_ck=1;
						}
					}
				}
			}
			
			
			
			if ($item['element']=='title'){
				if ($item['qualifier']=='alternative'){
					
					// need add alternative title
					
				}
			}
			
			if ($item['element']=='type'){
				if ($item['qualifier']=='none'){
					$eprints->addChild("type", strtolower((string)$item));
				}
			}
			
			if ($item['element']=='identifier'){
				if ($item['qualifier']=='udc'){
					//nedd add udc
					
					
				}
			}
			
		}
		
		 $item_file= './dspace/'.$export;
		 $list_doc=scandir($item_file);
		 
		 foreach ($list_doc as $file_doc){
		 $ext= end(explode(".", $file_doc));
		 	if (array_search($ext, $allow_file)===0){
		 	  $document=$documents->addChild("document");
		 	  $documents->addAttribute("xmlns", "http://eprints.org/ep2/data/2.0");
		 	  $document->addChild("docid", 1);
		 	  $document->addChild("rev_number", 1);
		 	  $document->addChild("pos", 1);
		 	  if ($ext=='pdf'){
				  $mime='application/pdf';
		 	  }
		 	  elseif($ext=='doc' || $ext=='docx'){
		 	  	  $mime='application/msword';
		 	  }
			  $document->addChild("format", $mime);
			  $document->addChild("language", $default_lang);
			  $document->addChild("security", 'public');
			  $document->addChild("main", $file_doc);
			  $files=$document->addChild("files");
			  $file=$files->addChild("file");
			  $file->addChild('filename', $file_doc);
			  $file->addChild('filesize', filesize($item_file.'/'.$file_doc));
			  $file->addChild('url', 'http://'.$_SERVER['SERVER_NAME'].'/dspace/'.$export.'/'.$file_doc);
			  
		 	 }
			 
		 }
	
  	}
  	  	  
		  
 }
  	  
 $random_name= md5(uniqid());
  	  
 $sxe->asXML('./eprints/'.$random_name.'.xml');
 
 echo "<a href='/eprints/$random_name.xml'>Eprints XML Export File</a>";
	  
	  }
catch( Exception $e )
{
    echo $e->getMessage();
} 
  	  
	  
  }
?>
