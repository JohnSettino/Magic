<?php

/********************
 * String functions
 ********************/

/**
 * Removes "bad" characters from strings. 
 * 
 * @param string $string
 * @return string 
 */
function safeguard_string($string) {

  $bad_array = array('!','@','#','$','%','^','&','*','(',')','=','+','<','>');
  $string = strip_tags($string);
  $string = stripslashes($string);
  $string = str_replace($bad_array,'',$string);

  return $string;
}

/**
 * Checks for non-alphanumeric chars in a string. I don't know why this exists,
 * there are a ton of built-in PHP functions to do this.
 *
 * @param string $string
 * @return int
 */
function check_string($string) {
  $bad_array = array('!','@','#','$','%','^','&','*','(',')','=','+','<','>','"');
  if(in_array($string,$bad_array)) {
    return 0;
  }else{
    return 1;
  }
}

/**
 * Misc. cleaning function to help avoid XSS attacks.
 *
 * @param string $clean
 * @return string
 */
function clean($clean) {
  $clean = stripslashes($clean);
  $clean = str_replace("&", "&amp;", $clean);
  $clean = str_replace("<", "&#x3C;", $clean);
  $clean = str_replace(">", "&#x3E;", $clean);
  $clean = str_replace("'", "&#x27;", $clean);
  $clean = str_replace('"', "&#x22;", $clean);
  $clean = str_replace('$', "&#x53;", $clean);
  $clean = str_replace("script", "&#x73;&#x63;&#x72;&#x69;&#x70;&#x74;", $clean);
  $clean = str_replace("xmp", "&#x78;&#x6D;&#x70;", $clean);
  $clean = str_replace("pre", "&#x70;&#x72;&#x65;", $clean);
  $clean = str_replace("java", "&#x6A;&#x61;&#x76;&#x61;", $clean);
  $clean = str_replace("data", "&#x64;&#x61;&#x74;&#x61;", $clean);
  $clean = str_replace("=", "&#61;", $clean);
  $clean = str_replace("\n", "<br />", $clean);
  $clean = str_replace("document", "&#x64;&#x6F;&#x63;&#x75;&#x6D;&#x65;&#x6E;&#x74;", $clean);
  $clean = str_replace("(", "&#x28;", $clean);
  $clean = str_replace(")", "&#x29;", $clean);
  $clean = str_replace("script", "", $clean);
  $clean = str_replace("java", "", $clean);
  $clean = str_replace("data:", "", $clean);
  $clean = str_replace("object", "", $clean);
  $clean = str_replace("embed", "", $clean);
  $clean = str_replace("&#x70;&#x61;&#x73;&#x73;", "", $clean);
  $clean = str_replace("thru", "", $clean);
  $clean = str_replace("cmd", "", $clean);
  $clean = str_replace(":6666", ":80", $clean);

  return $clean;
}

/**
 * Generates a random filename with optional provided extension
 *
 * @param string $extension
 * @return string
 */
function generate_hash($extension=NULL) {
	if($extension == ".jpeg") { $extension = ".jpg"; }
  $hash = md5(uniqid("lmfao")).$extension;
  return $hash;
}

/**
 * Simple e-mail verification via regex
 *
 * @param string $email
 * @return boolean
 */
function verify_email($email) {
  if(!$email) { return false; }
  if( !preg_match( "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)) { return false; }else{ return true; }
}

/**
 * Calculates age based on date
 *
 * @link http://snippets.dzone.com/posts/show/1310
 * @param string $p_strDate
 * @return int
 */
function getAge( $p_strDate ) {
  list($Y,$m,$d)    = explode("-",$p_strDate);
  return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
}


/**
 * Assign alphanumeric value to number between 1-36
 *
 * @link http://www.i-fubar.com/random-string-generator.php
 * @param int $num
 * @return string
 */
function assign_rand_value($num) {
  // accepts 1 - 36
  switch($num)
  {
    case "1":
     $rand_value = "a";
    break;
    case "2":
     $rand_value = "b";
    break;
    case "3":
     $rand_value = "c";
    break;
    case "4":
     $rand_value = "d";
    break;
    case "5":
     $rand_value = "e";
    break;
    case "6":
     $rand_value = "f";
    break;
    case "7":
     $rand_value = "g";
    break;
    case "8":
     $rand_value = "h";
    break;
    case "9":
     $rand_value = "i";
    break;
    case "10":
     $rand_value = "j";
    break;
    case "11":
     $rand_value = "k";
    break;
    case "12":
     $rand_value = "l";
    break;
    case "13":
     $rand_value = "m";
    break;
    case "14":
     $rand_value = "n";
    break;
    case "15":
     $rand_value = "o";
    break;
    case "16":
     $rand_value = "p";
    break;
    case "17":
     $rand_value = "q";
    break;
    case "18":
     $rand_value = "r";
    break;
    case "19":
     $rand_value = "s";
    break;
    case "20":
     $rand_value = "t";
    break;
    case "21":
     $rand_value = "u";
    break;
    case "22":
     $rand_value = "v";
    break;
    case "23":
     $rand_value = "w";
    break;
    case "24":
     $rand_value = "x";
    break;
    case "25":
     $rand_value = "y";
    break;
    case "26":
     $rand_value = "z";
    break;
    case "27":
     $rand_value = "0";
    break;
    case "28":
     $rand_value = "1";
    break;
    case "29":
     $rand_value = "2";
    break;
    case "30":
     $rand_value = "3";
    break;
    case "31":
     $rand_value = "4";
    break;
    case "32":
     $rand_value = "5";
    break;
    case "33":
     $rand_value = "6";
    break;
    case "34":
     $rand_value = "7";
    break;
    case "35":
     $rand_value = "8";
    break;
    case "36":
     $rand_value = "9";
    break;
  }
  return $rand_value;
}

/**
 * Generate random string of $length
 *
 * @link http://www.i-fubar.com/random-string-generator.php
 * @param int $length
 * @return string
 */
function get_rand_id($length)
{
  if($length>0) 
  { 
  $rand_id="";
   for($i=1; $i<=$length; $i++)
   {
   mt_srand((double)microtime() * 1000000);
   $num = mt_rand(1,36);
   $rand_id .= assign_rand_value($num);
   }
  }
return $rand_id;
} 

/**
 * Splits up LDAP departmentnumber field in neat ways.
 * Used at work (SAGE Dining)
 *
 * @param string $unit
 * @return array
 */

function get_perms($unit) { 
  if(strpos($unit,' ') !== false) { 
    $u = explode(' ',$unit);
    $level = strtoupper($u[0]);
    $perms = array();
    switch($level) {
      case "DM":
        foreach($u as $key=>$unit_num) {
          if($key != '0' && $unit_num != ' ' && $unit_num != '') {
            $perms[0] = '';
            $perms['master'] = 'DM';
            $perms['status'] = 'District Manager';
            $perms[] = $unit_num;
          }
        }
        break;
      case "VP":
        foreach($u as $key=>$unit_num) {
          if($key != '0' && $unit_num != ' ' && $unit_num != '') {
            $perms[0] = '';
            $perms['master'] = 'VP';
            $perms['status'] = 'Vice President';
            $perms[] = $unit_num;
          }
        }
        break;
      case "CST":
        foreach($u as $key=>$unit_num) { 
          if($key != '0' && $unit_num != ' ' && $unit_num != '') { 
            $perms[0] = '';
            $perms['master'] = 'CST';
            $perms['status'] = 'Culinary Support Team';
            $perms[] = $unit_num;
          }
        }
        break;
      case "SALES":
        foreach($u as $key=>$unit_num) { 
          if($key != '0' && $unit_num != ' ' && $unit_num != '') { 
            $perms[0] = '';
            $perms['master'] = 'Sales';
            $perms['status'] = 'Sales Team';
            $perms[] = $unit_num;
          }
        }
        break;
    }
  }elseif($unit == "HQ") {
    $perms['master'] = 'ALL';
    $perms['status'] = 'HQ Admin';
  }else{
    $perms['master'] = $unit;
    $perms['status'] = 'Unit';
    $perms[0] = '';
    $perms[] = $unit;
  }
  
  return $perms;
}	  


/**
 * Creates HTML dropdown based on given perms array from get_perms()
 *
 * @param array $perms
 * @param string $selected
 * @uses get_units_from_ldap()
 * @uses unit_dropdown()
 */
function unit_dropdown2($perms,$selected=NULL) {
  if($perms['master'] == "ALL") {
 	  unit_dropdown($perms,$selected);
  }else{
    $unitlist = get_units_from_ldap();
	  //print_r($perms);
	  //$a= array( 'rack' => 'dlab', 'foo', 'bar', 'baz', 'quux' );
    /*foreach($perms as $k=>$v) {
     if(0 == $k)
       echo "RACKDLAB!";
       echo "$k:$v\n";
     }*/
     //$done = '0';
    echo "\n\n";
    echo '<select id="unit_dropdown" name="unit_dropdown" size="1" style="width:213px">
	<option value="">Select a unit</option>'."\n";
    while (list($k, $v) = each($perms)) {

	  if($k == 'master') { echo '';
	  }elseif($k == 'status') { echo '';
	  }else{
	    //if(0 == $k) { echo 'hey'; }
		//if($k == '1') { echo 'key is 1'; }
		if($v==$selected) { $y=' selected';}else{ $y=''; }
        echo '<option value="'.$v.'"'.$y.'>Unit '.$v.' ('.$unitlist[$v].')</option>'."\n";
	  }
	  $i++;
	}
    echo '</select>';	
  }
}

/**
 * Back link.
 *
 * @param string $string
 */
function backmsg($string) {
  echo '<span class="error">'.$string. ' <a href="javascript:back();" style="color:#FFFFFF">Go back</a>.</span>';
}

/**
 * Simple error message
 * @param string $string
 */
function msg($string) { 
  echo '<span class="error">'.$string.'</span><br />';
}

/**
 * Creates an HTML <select> element, with $name as the form field name
 * @deprecated Use make_dropdown() instead 
 * @param int $type
 * @param string $name
 * @return none
 */

function make_dropdown_old($type,$name) { 
  /*  
    Will return false for a blank type or name.
    Today's date is automagically selected by default
    Type key:
      1 - month
      2 - day
      3 - year
  */
  $k = "1990"; // Set the start year here 
  
  if(!$type || !$name) { return false; }
  echo '<select name="'.$name.'">'."\n";
 
  switch($type) { 
    case 1:
	  $m = date('m');
	  echo $m;
	  $i=1;
      while($i <= 12) {
        if($i < 10) { $i = '0'.$i; }
		if($i == $m) { $a = ' selected'; }else{ $a = ''; }
		echo '  <option value="'.$i.'"'.$a.'>'.$i.'</option>'."\n";
		$i++;
	  }
	  break;
    case 2:
      $d = date('d');
	  echo $d;
	  $j=1;
      while($j <= 31) {
        if($j < 10) { $j = '0'.$j; }
		if($j == $d) { $a = ' selected'; }else{ $a = ''; }
		echo '  <option value="'.$j.'"'.$a.'>'.$j.'</option>'."\n";
		$j++;
	  }
	  break;
    case 3:
	  echo $y;
      $y = date('Y');
      while($k <= ($y + 2)) {
		if($k == $y) { $a = ' selected'; }else{ $a = ''; }
		echo '  <option value="'.$k.'"'.$a.'>'.$k.'</option>'."\n";
		$k++;
	  }
	  break;
  }
  echo '</select>'."\n";
}

/**
 * Creates an HTML <select> element, with $name as the form field name
 * 
 * @param int $type
 * @param string $name
 * @param string $selected
 * @param int $tabindex
 * @param string $htmlclass
 * @param int $is_disabled
 * @return none 
 */
function make_dropdown($type,$name,$selected=NULL,$tabindex=NULL,$htmlclass=NULL,$is_disabled=false) {
  /*
    Will return false for a blank type or name.
    Today's date is automagically selected by default if it is a date field
    Type key:
      1 - Month
      2 - Day
      3 - Year
      4 - Hour
      5 - Minutes
      6 - Minutes, increments of 5
      7 - U.S. States
  */
  $k = "1990"; // Set the start year here

  if(!$type || !$name) { return false; }
  //if(!is_null($selected)) { echo 'attempting to select something'; }
  $selecttag = '<select id="'.$name.'" ';
  if($htmlclass != NULL) { $selecttag .= 'class="'.$htmlclass.'" '; }
  if($is_disabled === TRUE) { $selecttag .='disabled="true" '; }
  if($tabindex != NULL) { $selecttag .= 'tabindex="'.$tabindex.'" '; }
  $selecttag .= ' name="'.$name.'">'."\n";
  echo $selecttag;
  switch($type) {
    case 1:
	    $m = date('m');
	    //echo $m;
	    $i=1;
      while($i <= 12) {
        if($i < 10) { $i = '0'.$i; }
		    if(!is_null($selected) && $i == $selected) { $a = ' selected'; $passed=1; }
		    elseif(!isset($passed) && $i == $m) { $a = ' selected'; }else{ $a = ''; }
		    echo '  <option value="'.$i.'"'.$a.'>'.$i.'</option>'."\n";
		    $i++;
	    }
	    break;
    case 2:
      $d = date('d');
	    //echo $d;
	    $j=1;
      while($j <= 31) {
        if($j < 10) { $j = '0'.$j; }
        if(!is_null($selected) && $j == $selected) { $a = ' selected'; $passed=1; }
		    elseif(!isset($passed) && $j == $d) { $a = ' selected'; }else{ $a = ''; }
        echo '  <option value="'.$j.'"'.$a.'>'.$j.'</option>'."\n";
        $j++;
      }
      break;
    case 3:
      //echo $y;
      $y = date('Y');
      $lim = (int)$y + 5;
      while($k <= $lim) {
      	if(!is_null($selected) && $k == $selected) { $a = ' selected'; $passed=1; }
        elseif(!isset($passed) && $k == $y) { $a = ' selected'; }else{ $a = ''; }
        echo '  <option value="'.$k.'"'.$a.'>'.$k.'</option>'."\n";
        $k++;
      }
      break;
    case 4:
      $h = 0;
      while($h <= 24) { // 24 hours in a day
      	if(!is_null($selected) && $h == $selected) { $a = ' selected'; $passed=1; }
		    elseif(!isset($passed) && $h == $y) { $a = ' selected'; }else{ $a = ''; } // not needed but, might break if I take it out
		    echo '  <option value="'.$h.'"'.$a.'>'.$h.'</option>'."\n";
		    $h++;
	    }
      break;
    case 5:
      $M = 0;
      while($M <= 59) { // up to 59 minutes in an hour
      	$M = str_pad($M,2,'0',STR_PAD_LEFT);
      	if(!is_null($selected) && $M == $selected) { $a = ' selected'; $passed=1; }
		    elseif(!isset($passed) && $M == $y) { $a = ' selected'; }else{ $a = ''; } // not needed but, might break if I take it out
		    echo '  <option value="'.$M.'"'.$a.'>'.$M.'</option>'."\n";
		    $M++;
	    }
      break;
    case 6:
      $M = 0;
      while($M <= 59) { // up to 59 minutes in an hour
      	if(!is_null($selected) && $M == $selected) { $a = ' selected'; $passed=1; }
		    elseif(!isset($passed) && $M == $y) { $a = ' selected'; }else{ $a = ''; } // not needed but, might break if I take it out
		    if($M % 5 == 0) { echo '  <option value="'.str_pad($M,2,'0',STR_PAD_LEFT).'"'.$a.'>'.str_pad($M,2,'0',STR_PAD_LEFT).'</option>'."\n"; }
		    $M++;
	    }
      break;
    case 7:
      $state_list = array('AL'=>"Alabama",'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California",
'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida",
'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa",
'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts",
'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana",'NE'=>"Nebraska",
'NV'=>"Nevada",'NH'=>"New Hampshire",'NJ'=>"New Jersey",'NM'=>"New Mexico",'NY'=>"New York",'NC'=>"North Carolina",
'ND'=>"North Dakota",'OH'=>"Ohio", 'OK'=>"Oklahoma", 'OR'=>"Oregon", 'PA'=>"Pennsylvania", 'RI'=>"Rhode Island",
'SC'=>"South Carolina", 'SD'=>"South Dakota",'TN'=>"Tennessee", 'TX'=>"Texas", 'UT'=>"Utah", 'VT'=>"Vermont",
'VA'=>"Virginia", 'WA'=>"Washington", 'WV'=>"West Virginia", 'WI'=>"Wisconsin", 'WY'=>"Wyoming");
      foreach($state_list as $abbrev => $state) {
        if(!is_null($selected) && $abbrev == $selected) { $a = ' selected'; $passed=1; }else{ $a=null; }
        echo '  <option value="'.$abbrev.'"'.$a.'>'.$state.'</option>'."\n";
      }
  }
  echo '</select>'."\n";
}

/**
 * I am using this here to clear data in a CMS against SQL injections and other mayhem. The flow is:
 * 1. input into form
 * 2. get from $_GET/$_POST
 * 3. cleanup($data, true)
 * 4. save to SQL
 * 5. load from SQL
 * 6. cleanup($data, false)
 * 7. show in form for new edit or on the website
 * @link http://us2.php.net/manual/en/function.stripslashes.php#80349
 * @param mixed $data
 * @param boolean $write
 * @return mixed
 */
function cleanup($data, $write=false) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = cleanup_lvl2($value, $write);
        }
    } else {
        $data = cleanup_lvl2($data, $write);
    }
    return $data;
}

/**
 * I am using this here to clear data in a CMS against SQL injections and other mayhem. The flow is:
 * 1. input into form
 * 2. get from $_GET/$_POST
 * 3. cleanup($data, true)
 * 4. save to SQL
 * 5. load from SQL
 * 6. cleanup($data, false)
 * 7. show in form for new edit or on the website
 * @link http://us2.php.net/manual/en/function.stripslashes.php#80349
 * @param mixed $data
 * @param boolean $write
 * @return mixed
 */
function cleanup_lvl2($data, $write=false) {
    if (isset($data)) { // preserve NULL
        if (get_magic_quotes_gpc()) {
            $data = stripslashes($data);
        }
        if ($write) {
            $data = mysql_real_escape_string($data);
        }
    }
    return $data;
}

/**
 * Inspired from:     PHP.net Contributions
 * @link http://us2.php.net/manual/en/function.var-dump.php#80288
 * @desc Helps with php debugging
 * @param mixed $var
 * @param mixed $info
 */
function dump(&$var, $info = FALSE)
{
    $scope = false;
    $prefix = 'unique';
    $suffix = 'value';
 
    if($scope) $vals = $scope;
    else $vals = $GLOBALS;

    $old = $var;
    $var = $new = $prefix.rand().$suffix; $vname = FALSE;
    foreach($vals as $key => $val) if($val === $new) $vname = $key;
    $var = $old;

    echo "<pre style='margin: 0px 0px 10px 0px; display: block; background: white; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 10px; line-height: 13px;'>";
    if($info != FALSE) echo "<b style='color: red;'>$info:</b><br>";
    do_dump($var, '$'.$vname);
    echo "</pre>";
}

/**
 * Inspired from:     PHP.net Contributions
 * @link http://us2.php.net/manual/en/function.var-dump.php#80288
 * @desc Better GI than print_r or var_dump
 * @param mixed $var
 * @param string $var_name
 * @param string $indent
 * @param string $reference
 */
function do_dump(&$var, $var_name = NULL, $indent = NULL, $reference = NULL)
{
    $do_dump_indent = "<span style='color:#eeeeee;'>|</span> &nbsp;&nbsp; ";
    $reference = $reference.$var_name;
    $keyvar = 'the_do_dump_recursion_protection_scheme'; $keyname = 'referenced_object_name';

    if (is_array($var) && isset($var[$keyvar]))
    {
        $real_var = &$var[$keyvar];
        $real_name = &$var[$keyname];
        $type = ucfirst(gettype($real_var));
        echo "$indent$var_name <span style='color:#a2a2a2'>$type</span> = <span style='color:#e87800;'>&amp;$real_name</span><br>";
    }
    else
    {
        $var = array($keyvar => $var, $keyname => $reference);
        $avar = &$var[$keyvar];
   
        $type = ucfirst(gettype($avar));
        if($type == "String") $type_color = "<span style='color:green'>";
        elseif($type == "Integer") $type_color = "<span style='color:red'>";
        elseif($type == "Double"){ $type_color = "<span style='color:#0099c5'>"; $type = "Float"; }
        elseif($type == "Boolean") $type_color = "<span style='color:#92008d'>";
        elseif($type == "NULL") $type_color = "<span style='color:black'>";
   
        if(is_array($avar))
        {
            $count = count($avar);
            echo "$indent" . ($var_name ? "$var_name => ":"") . "<span style='color:#a2a2a2'>$type ($count)</span><br>$indent(<br>";
            $keys = array_keys($avar);
            foreach($keys as $name)
            {
                $value = &$avar[$name];
                do_dump($value, "['$name']", $indent.$do_dump_indent, $reference);
            }
            echo "$indent)<br>";
        }
        elseif(is_object($avar))
        {
            echo "$indent$var_name <span style='color:#a2a2a2'>$type</span><br>$indent(<br>";
            foreach($avar as $name=>$value) do_dump($value, "$name", $indent.$do_dump_indent, $reference);
            echo "$indent)<br>";
        }
        elseif(is_int($avar)) echo "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $type_color$avar</span><br>";
        elseif(is_string($avar)) echo "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $type_color\"$avar\"</span><br>";
        elseif(is_float($avar)) echo "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $type_color$avar</span><br>";
        elseif(is_bool($avar)) echo "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $type_color".($avar == 1 ? "TRUE":"FALSE")."</span><br>";
        elseif(is_null($avar)) echo "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> {$type_color}NULL</span><br>";
        else echo "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $avar<br>";

        $var = $var[$keyvar];
    }
}

/**
 * Inspired from:     PHP.net Contributions
 * @desc Same as dump() but returns the value as a string instead of outputting
 * it to the browser.
 * @param mixed $var
 * @param string $var_name
 * @param string $indent
 * @param string $reference
 * @return string
 */
function return_dump(&$var, $var_name = NULL, $indent = NULL, $reference = NULL)
{
    $do_dump_indent = "";
    $reference = $reference.$var_name;
    $keyvar = 'the_do_dump_recursion_protection_scheme'; $keyname = 'referenced_object_name';
    $buffer = (isset($buffer))? $buffer:'';

    if (is_array($var) && isset($var[$keyvar]))
    {
        $real_var = &$var[$keyvar];
        $real_name = &$var[$keyname];
        $type = ucfirst(gettype($real_var));
        $buffer .= "$indent$var_name <span style='color:#a2a2a2'>$type</span> = <span style='color:#e87800;'>&amp;$real_name</span>";
    }
    else
    {
        $var = array($keyvar => $var, $keyname => $reference);
        $avar = &$var[$keyvar];
   
        $type = ucfirst(gettype($avar));
        if($type == "String") $type_color = "<span style='color:green'>";
        elseif($type == "Integer") $type_color = "<span style='color:red'>";
        elseif($type == "Double"){ $type_color = "<span style='color:#0099c5'>"; $type = "Float"; }
        elseif($type == "Boolean") $type_color = "<span style='color:#92008d'>";
        elseif($type == "NULL") $type_color = "<span style='color:black'>";
   
        if(is_array($avar))
        {
            $count = count($avar);
            $buffer .= "$indent" . ($var_name ? "$var_name => ":"") . "<span style='color:#a2a2a2'>$type ($count)</span><br>$indent";
            $keys = array_keys($avar);
            foreach($keys as $name)
            {
                $value = &$avar[$name];
                $buffer .= return_dump($value, "['$name']", $indent.$do_dump_indent, $reference);
            }
        }
        elseif(is_object($avar))
        {
            $buffer .= "$indent$var_name <span style='color:#a2a2a2'>$type</span><br>$indent";
            foreach($avar as $name=>$value) { $buffer .= return_dump($value, "$name", $indent.$do_dump_indent, $reference); }
        }
        elseif(is_int($avar)) $buffer .= "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $type_color$avar</span><br>";
        elseif(is_string($avar)) $buffer .= "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $type_color\"$avar\"</span><br>";
        elseif(is_float($avar)) $buffer .= "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $type_color$avar</span><br>";
        elseif(is_bool($avar)) $buffer .= "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $type_color".($avar == 1 ? "TRUE":"FALSE")."</span><br>";
        elseif(is_null($avar)) $buffer .= "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> {$type_color}NULL</span><br>";
        else $buffer .= "$indent$var_name = <span style='color:#a2a2a2'>$type(".strlen($avar).")</span> $avar<br>";

        $var = $var[$keyvar];
    }
    return $buffer;
}

/**
 * Inspired from:     PHP.net Contributions
 * @desc Same as return_dump() but returns the value as a string instead of
 * outputting it to the browser, MINUS any HTML and prettiness.
 * @param mixed $var
 * @param string $var_name
 * @param string $indent
 * @param string $reference
 * @return string
 */
function return_clean_dump(&$var, $var_name = NULL, $indent = NULL, $reference = NULL)
{
    $do_dump_indent = "";
    $reference = $reference.$var_name;
    $keyvar = 'the_do_dump_recursion_protection_scheme'; $keyname = 'referenced_object_name';
    $buffer = (isset($buffer))?$buffer:'';

    if (is_array($var) && isset($var[$keyvar]))
    {
        $real_var = &$var[$keyvar];
        $real_name = &$var[$keyname];
        $type = ucfirst(gettype($real_var));
        $buffer .= "$indent$var_name $type = &amp;$real_name";
    }
    else
    {
        $var = array($keyvar => $var, $keyname => $reference);
        $avar = &$var[$keyvar];
   
        $type = ucfirst(gettype($avar));
        if($type == "String") $type_color = "";
        elseif($type == "Integer") $type_color = "";
        elseif($type == "Double"){ $type_color = ""; $type = "Float"; }
        elseif($type == "Boolean") $type_color = "";
        elseif($type == "NULL") $type_color = "";
   
        if(is_array($avar))
        {
            $count = count($avar);
            $buffer .= "$indent" . ($var_name ? "$var_name => ":"") . "$type ($count)\n$indent";
            $keys = array_keys($avar);
            foreach($keys as $name)
            {
                $value = &$avar[$name];
                $buffer .= return_clean_dump($value, "['$name']", $indent.$do_dump_indent, $reference);
            }
        }
        elseif(is_object($avar))
        {
            $buffer .= "$indent$var_name $type</span>\n";
            foreach($avar as $name=>$value) { $buffer .= return_clean_dump($value, "$name", $indent.$do_dump_indent, $reference); }
        }
        elseif(is_int($avar)) $buffer .= "$indent$var_name = $type(".strlen($avar).") $type_color$avar\n";
        elseif(is_string($avar)) $buffer .= "$indent$var_name = $type(".strlen($avar).") $type_color\"$avar\"\n";
        elseif(is_float($avar)) $buffer .= "$indent$var_name = $type(".strlen($avar).") $type_color$avar\n";
        elseif(is_bool($avar)) $buffer .= "$indent$var_name = $type(".strlen($avar).") $type_color".($avar == 1 ? "TRUE":"FALSE")."\n";
        elseif(is_null($avar)) $buffer .= "$indent$var_name = $type(".strlen($avar).") {$type_color}NULL\n";
        else $buffer .= "$indent$var_name = $type(".strlen($avar).") $avar\n";

        $var = $var[$keyvar];
    }
    return $buffer;
}
?>
