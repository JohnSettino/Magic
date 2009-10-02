<?php
/*******************
 * MySQL functions
 *******************/

include_once(dirname(__FILE__) . "/../config.php");

/**
 * Simple function to send an email on failure of MySQL commands
 * @param string $subject
 * @param string $body
 * @param array $trace
 * @return boolean
 */

function sql_mailman($subject,$body,$trace) {
  ob_start();
  dump($trace);
  $trace = ob_get_contents();
  ob_end_clean();
  global $MYSQL_OPTS;
  $to         = DEBUG_EMAIL;
  $from       = "noreply@{$MYSQL_OPTS['domain_name']}";
	$params     = "-f" . $from;
  $headers    = "From: MySQL Debug <" . $from . ">\r\n";
  $headers   .= "MIME-Version: 1.0\r\n";
  $headers   .= "Content-Type: multipart/mixed; boundary=\"MIME-BOUNDARY\"\r\n";
  $message    = "\r\n";
  $message   .= "--MIME-BOUNDARY\r\n";
  $message   .= "Content-type: text/html; charset=US-ASCII\r\n";
  $message   .= "\r\n";
  $message   .= "<HTML>\r\n"
              . "<HEAD>\r\n"
              . "<STYLE TYPE='text/css'>\r\n"
              ."#SQL, #SQL TD, #SQL TH { font-family: Tahoma, Helvetica, sans-serif; font-size: 10pt; color: #000000; }\r\n"
              ."#SQL { border: 1px solid #000000 }\r\n"
              ."#SQL TD { border: 1px solid #000000 }\r\n"
              ."#SQL TH { background: #000000; color: #FFFFFF }\r\n"
              ."</STYLE>\r\n"
              . "</HEAD>\r\n"
              . "<BODY BGCOLOR=WHITE>\r\n"
              . "$body\r\n"
              . "</BODY>\r\n"
              . "</HTML>\r\n";
    $message  .= "\r\n";
	  $message  .= "--MIME-BOUNDARY\r\n";
    $message  .= "Content-Type: text/html;\n"
              . " name=\"backtrace.html\"\r\n"
              . "Content-Transfer-Encoding: base64\r\n\r\n"
              . chunk_split(base64_encode($trace))
              . "\r\n\r\n"
              . "--MIME-BOUNDARY--";
  ob_start();
  $ok = mail($to, $subject, $message, $headers, $params);
  $buffer = ob_get_contents();
  ob_clean();
  return ($ok) ? true:false;
}

/**
 * Connects to MySQL server and selects DB.
 * Will send an email to DEBUG_EMAIL with details if 
 * unable to do either of these things. 
 * 
 * @param string $database optional database name
 *
 * @return mixed MySQL Connection Resource on success or false on failure
 */ 
function connectdb($database=NULL) {
  global $MYSQL_OPTS;
  if(!$MYSQL_OPTS) return false;

  $connection = @mysql_connect($MYSQL_OPTS['host'],$MYSQL_OPTS['user'],$MYSQL_OPTS['password']);
  if($connection) { 
    $result = $connection;
  }else{
    $trace = debug_backtrace();
    $subject = "MySQL Connect Failure";
    $body = "<TABLE WIDTH='700' BORDER='1' ID='SQL' CELLSPACING='0' CELLPADDING='2'>\r\n"
    ."<TR><TH COLSPAN='2' ALIGN='CENTER'>$subject</TH></TR>\r\n"
    ."<TR><TD ALIGN='RIGHT'>Date:</TD><TD ALIGN='CENTER'>".date('M/d/Y @ h:m:s a')."</TD></TR>\r\n"
    ."<TR><TD ALIGN='RIGHT'>File:</TD><TD ALIGN='CENTER'>{$_SERVER['SCRIPT_FILENAME']}</TD></TR>\r\n"
    ."<TR><TD ALIGN='RIGHT'>Query String:</TD><TD ALIGN='CENTER'>{$_SERVER['QUERY_STRING']}</TD></TR>\r\n"
    ."<TR><TD ALIGN='RIGHT'>IP @ Host:</TD><TD ALIGN='CENTER'>{$_SERVER['REMOTE_ADDR']} @ ".gethostbyaddr($_SERVER['REMOTE_ADDR'])."</TD></TR>\r\n"
    ."<TR><TD ALIGN='RIGHT'>Logged-In User:</TD><TD ALIGN='CENTER'>".(isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER']:"N/A")."</TD></TR>\r\n"
    ."<TR><TD ALIGN='RIGHT'>MySQL Said:</TD><TD ALIGN='CENTER'>".mysql_error()."</TD></TR>\r\n"
    ."</TABLE>";
    sql_mailman($subject,$body,$trace);
    $result = false;
    return $result;
  }
  if(is_null($database)) {
    return $result;
  }else{
    $db = @mysql_select_db($database);
    if($db) {
      $result = $connection;
    }else{
      $trace = debug_backtrace();
      $subject = "MySQL Select DB Failure";
      $body = "<TABLE WIDTH='700' BORDER='1' ID='SQL' CELLSPACING='0' CELLPADDING='2'>\r\n"
      ."<TR><TH COLSPAN='2' ALIGN='CENTER'>$subject</TH></TR>\r\n"
      ."<TR><TD ALIGN='RIGHT'>Date:</TD><TD ALIGN='CENTER'>".date('M/d/Y @ h:m:s a')."</TD></TR>\r\n"
      ."<TR><TD ALIGN='RIGHT'>Requested File:</TD><TD ALIGN='CENTER'>{$_SERVER['SCRIPT_FILENAME']}</TD></TR>\r\n"
      ."<TR><TD ALIGN='RIGHT'>Query String:</TD><TD ALIGN='CENTER'>". (!empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : "None") . "</TD></TR>\r\n"
      ."<TR><TD ALIGN='RIGHT'>IP @ Host:</TD><TD ALIGN='CENTER'>{$_SERVER['REMOTE_ADDR']} @ ".gethostbyaddr($_SERVER['REMOTE_ADDR'])."</TD></TR>\r\n"
      ."<TR><TD ALIGN='RIGHT'>Logged-In User:</TD><TD ALIGN='CENTER'>".(isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER']:"N/A")."</TD></TR>\r\n"
      ."<TR><TD ALIGN='RIGHT'>MySQL Said:</TD><TD ALIGN='CENTER'>".mysql_error()."</TD></TR>\r\n"
      ."</TABLE>";
      sql_mailman($subject,$body,$trace);
      $result = false;
      return $result;
    }
  }
  return $result;
}

/**
 * Performs a MySQL query and sends an error email on failure
 * Note that this is a dropin "replacement" for mysql_query 
 * and as such does NOT do any cleaning or escaping of your query,
 * so make sure you do that like a good webdev does :)
 *
 * @param string $query the MySQL query to run
 *
 * @return mixed results of mysql_query or false on failure
 */ 
function query($query,$link=NULL) {
  $result = ($link != NULL) ? @mysql_query($query,$link) : @mysql_query($query);

  if (!$result) {
    if($link) {
      ob_start();
      var_dump($link);
      $linkdump = ob_get_contents();
      ob_end_clean();
    }
    $trace = debug_backtrace();
    $subject = "MySQL Query Failure";
    $body = "<TABLE WIDTH='700' BORDER='1' ID='SQL' CELLSPACING='0' CELLPADDING='2'>\r\n"
    ."<TR><TH COLSPAN='2' ALIGN='CENTER'>$subject</TH></TR>\r\n"
    ."<TR><TD ALIGN='RIGHT'>Date:</TD><TD ALIGN='CENTER'>".date('M/d/Y @ h:m:s a')."</TD></TR>\r\n"
    ."<TR><TD ALIGN='RIGHT'>Requested File:</TD><TD ALIGN='CENTER'>{$_SERVER['SCRIPT_FILENAME']}</TD></TR>\r\n"
    ."<TR><TD ALIGN='RIGHT'>Query String:</TD><TD ALIGN='CENTER'>". (!empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : "None") . "</TD></TR>\r\n"
    ."<TR><TD ALIGN='RIGHT'>IP @ Host:</TD><TD ALIGN='CENTER'>{$_SERVER['REMOTE_ADDR']} @ ".gethostbyaddr($_SERVER['REMOTE_ADDR'])."</TD></TR>\r\n"
    
    ."<TR><TD ALIGN='RIGHT'>Logged-In User:</TD><TD ALIGN='CENTER'>".(isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER']:"N/A")."</TD></TR>\r\n"
    ."<TR><TD ALIGN='RIGHT'>MySQL Query:</TD><TD ALIGN='CENTER'><pre>$query</pre></TD></TR>\r\n"
    ."<TR><TD ALIGN='RIGHT'>MySQL Link:</TD><TD ALIGN='CENTER'>".( ($link != NULL) ? $linkdump : "None")."</TD></TR>\r\n"
    ."<TR><TD ALIGN='RIGHT'>MySQL Said:</TD><TD ALIGN='CENTER'>".mysql_error()."</TD></TR>\r\n"
    ."</TABLE>";
    sql_mailman($subject,$body,$trace);
    $result = false;
    return $result;
  }else{
    return $result;
  }
}

function list_all_menus() { 
  $query = "SELECT * FROM `menus` ORDER BY `schoolid` ASC";
  $result = mysql_query($query);
  while($r = mysql_fetch_array($result)) { 
    extract($r,EXTR_OVERWRITE);
	if($active == '1') {
	  $active = 'Yes (<a href="?x=deactivate&id='.$id.'">Deactivate</a>)';
	}else{
	  $active = 'No (<a href="?x=activate&id='.$id.'">Activate</a>)';
	}
	echo '
	<tr>
	<td align="left">'.$schoolid.'</td>
	<td align="left">'.$description.'</td>
	<td align="left">'.$daterange.'</td>
	<td align="left">'.$active.'</td>
	<td align="left"><a href="http://www.sagedining.com/content/'.$schoolid.'/'.$filename.$extension.'">View</a> | <a href="javascript:delete_menu('."'$id'".');">Delete</a></td>
	</tr>';
  }
}


function list_menus($unit,$type=NULL) { 
  $unit = strtoupper($unit);
  $type = clean(strtolower($type));
  $query = "SELECT * FROM `menus` WHERE `schoolid` = '$unit' AND `type` = '$type'";
  $result = mysql_query($query);
  if(mysql_num_rows($result) == 0) {
    echo '<tr><td colspan="5">No menus found.</td></tr>';
  }else{ 
    while($r = mysql_fetch_array($result)) { 
      extract($r,EXTR_OVERWRITE);
  	  if($active == '1') {
	    $active = 'Yes (<a href="?x=deactivate&id='.$id.'">Deactivate</a>)';
	  }else{
	    $active = 'No (<a href="?x=activate&id='.$id.'">Activate</a>)';
  	  }
	  echo '
	<tr>
	<td align="left">'.$schoolid.'</td>
	<td align="left">'.$description.'</td>
	<td align="left">'.$daterange.'</td>
	<td align="left">'.$active.'</td>
	<td align="left"><a href="http://www.sagedining.com/content/'.$schoolid.'/'.$filename.$extension.'">View</a> | <a href="javascript:delete_menu('."'$id'".');">Delete</a></td>
	</tr>';
    }
  }
}

function activate_menu($id) { 
  $query = "UPDATE `menus` SET `active` = '1' WHERE `id` = '$id' LIMIT 1";
  $result = mysql_query($query) or die(mysql_error());
  if($result) { return true; }else{ return false; }
}

function deactivate_menu($id) { 
  $query = "UPDATE `menus` SET `active` = '0' WHERE `id` = '$id' LIMIT 1";
  $result = mysql_query($query) or die(mysql_error());
  if($result) { return true; }else{ return false; }
}

function get_menu_types($unit) {
  if(!$unit) { 
    return false; 
  }else{
    $query = "SELECT * FROM `menutypes` WHERE `unit` = '$unit'";
//	echo $query;
	$result = mysql_query($query);
	if($result) { 
 	  if(mysql_num_rows($result) != 0) {
echo '
<form action="?x=menuedit" name="menu_type" id="menu_type" method="post" onsubmit="return validatetype(this)">
<input type="hidden" id="unit" name="unit" value="'.$unit.'" />
<select name="type" id="type" size="1" style="width:213px">
<option value="">Select a menu type</option>
<option value="add_new">&nbsp;&raquo; Add New Type</option>';
  	    while($r = mysql_fetch_array($result)) { 
		  if($id == $_POST[type]) { $selected = 'selected '; }else{ $selected=''; }
  	      extract($r,EXTR_OVERWRITE);
		  echo '<option value="'.$id.'" '.$selected.'>&nbsp;&raquo; '.$display.'</option>';
        }
		echo '</select> ';
		echo '<input type="submit" name="choice" value="Submit" class="transbutton2" />';
	  }else{
	    echo '
<form action="?x=menuedit" name="menu_type" id="menu_type" method="post" onsubmit="return validatetype(this)">
<input type="hidden" id="unit" name="unit" value="'.$unit.'" />
<select name="type" id="type" size="1" style="width:213px">
<option value="">Select a menu type</option>
<option value="add_new">&nbsp;&raquo; Add New Type</option>
</select>
<input type="submit" name="choice" value="Submit" class="transbutton2" />';
	  }
	}
  }
}

function get_menu_alias($id) { 
  if(!$id) { return false; }
  $query = "SELECT `alias` FROM `menutypes` WHERE `id`='$id' LIMIT 1";
  $result = @mysql_fetch_array(mysql_query($query));
  return $result['alias'];
}
 
function get_menu_name($id) { 
  if(!$id) { return false; }
  $query = "SELECT `display` FROM `menutypes` WHERE `id`='$id' LIMIT 1";
  $result = @mysql_fetch_array(mysql_query($query));
  return $result['display'];
} 
  

function pull_text($desc) { 
	if(!$desc) { return false; }
	$query = "SELECT * FROM `text` WHERE `desc` = '$desc'";
	$result = query($query);
	$rows = mysql_num_rows($result);
	if($result && $rows != 0) {
		$r = mysql_fetch_array($result);
		return $r['text'];
	}else{
		return false;
	}
}

  
?>