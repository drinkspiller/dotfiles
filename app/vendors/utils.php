<?php

// Hugh Bothwell  hugh_bothwell@hotmail.com
// August 31 2001
// Number-to-word converter



 // recursive fn, converts three digits per pass
 function convertTri($num, $tri) {
	$ones = array(
	"",
	" one",
	" two",
	" three",
	" four",
	" five",
	" six",
	" seven",
	" eight",
	" nine",
	" ten",
	" eleven",
	" twelve",
	" thirteen",
	" fourteen",
	" fifteen",
	" sixteen",
	" seventeen",
	" eighteen",
	" nineteen"
	);
	
	$tens = array(
	"",
	"",
	" twenty",
	" thirty",
	" forty",
	" fifty",
	" sixty",
	" seventy",
	" eighty",
	" ninety"
	);
	
	$triplets = array(
	"",
	" thousand",
	" million",
	" billion",
	" trillion",
	" quadrillion",
	" quintillion",
	" sextillion",
	" septillion",
	" octillion",
	" nonillion"
	);

  // chunk the number, ...rxyy
  $r = (int) ($num / 1000);
  $x = ($num / 100) % 10;
  $y = $num % 100;

  // init the output string
  $str = "";

  // do hundreds
  if ($x > 0)
   $str = $ones[$x] . " hundred";

  // do ones and tens
  if ($y < 20)
   $str .= $ones[$y];
  else
   $str .= $tens[(int) ($y / 10)] . $ones[$y % 10];

  // add triplet modifier only if there
  // is some output to be modified...
  if ($str != "")
   $str .= $triplets[$tri];

  // continue recursing?
  if ($r > 0)
   return convertTri($r, $tri+1).$str;
  else
   return $str;
 }

// returns the number as an anglicized string
function convertNum($num) {
 $num = (int) $num;    // make sure it's an integer

 if ($num < 0)
  return "negative".convertTri(-$num, 0);

 if ($num == 0)
  return "zero";

 return convertTri($num, 0);
}

function titleCase($string) {
	$len=strlen($string);
	$i=0;
	$last= "";
	$new= "";
	$string=strtoupper($string);
	
	while ($i<$len):
		$char=substr($string,$i,1);
		if (ereg( "[A-Z]",$last)):
			$new.=strtolower($char);
		else:
			$new.=strtoupper($char);
		endif;
		$last=$char;
		$i++;
	endwhile;
	
	return($new);
}; 

function rmdir_recurse($sDir) {
    if (is_dir($sDir)) {
	$sDir = rtrim($sDir, '/');
	$oDir = dir($sDir);
	while (($sFile = $oDir->read()) !== false) {
	    if ($sFile != '.' && $sFile != '..') {
		(!is_link("$sDir/$sFile") && is_dir("$sDir/$sFile")) ? rmdir_recurse("$sDir/$sFile") : unlink("$sDir/$sFile");
	    }
	}
	$oDir->close();
	rmdir($sDir);
	return true;
    }
    return false;
}

function in_arrayr($needle, $haystack, &$found = false) {
    foreach ($haystack as $v) {
	if ($needle == $v) {
	  $found = true;
	  return true;
	} elseif (is_array($v)) {
	  $this->in_arrayr($needle, $v, $found);
	}
    }
   
    return $found;
}


function byteConvert(&$bytes){
    $s = array('B', 'Kb', 'MB', 'GB', 'TB', 'PB');
    $e = floor(log($bytes)/log(1024));
   
    return sprintf('%.2f '.$s[$e], ($bytes/pow(1024, floor($e))));
}

function getFileExtension($filepath){
    $pathInf = pathinfo($filepath);
    return low($pathInf['extension']);
}

/* creates a compressed zip file */
function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
        
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		$zip->open($destination, ZIPARCHIVE::OVERWRITE); 
		//if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
		//	return false;
		//}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file, 'assets/' . pathinfo($file, PATHINFO_BASENAME));
		}
		//debug
		# echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
	        # echo "<br /> dest: " . $destination;	
		//close the zip -- done!
		$zip->close();
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}


?>
