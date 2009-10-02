<?php

include_once(dirname(__FILE__) . "/../config.php");

/**
 * Pulls the requested LDAP entry
 *
 * @global array $cfg['ldap']
 * @param string $UID
 * @return mixed
 */
function get_ldap_entry($UID) {
  global $cfg;
  $ds = ldap_connect($cfg['ldap']['readserver']);
  if ($ds) {
    $r = ldap_bind($ds, $cfg['ldap']['mandn'], $cfg['ldap']['manpw']);

    $search  = "(&";
	  $search .= "(uid=$UID)";
	  $search .= ")";

	  $basedn = $cfg['ldap']['basedn'];
	  $sr = ldap_search($ds, $basedn, $search);
	  $info = ldap_get_entries($ds, $sr);
	  ldap_close($ds);

    return $info;
  }else{
    return false;
  }

}



/** 
 * Pull a single attribute from an LDAP entry
 * 
 * @global array $cfg['ldap']
 * @param string $UID
 * @param string $attr
 * @return mixed 
 */
function get_attr_from_ldap($UID,$attr) {
  $info = get_ldap_entry($UID);
  if(!$info) return false;
  $retval = isset($info[0][$attr]) ? $info[0][$attr][0]: false;
  if($retval===false) $retval = isset($info[0][strtolower($attr)]) ? $info[0][strtolower($attr)][0]: false;
  return $retval;
}

/**
 * Pull the unit (LDAP "departmentnumber" attribute) for an LDAP entry
 *
 * @param string $UID
 * @return mixed
 */
function get_unit_from_ldap($UID) {
  return get_attr_from_ldap($UID,'departmentnumber');
}

/**
 * Pull a person or unit's display name (LDAP "cn" attribute) for an LDAP entry
 * @param string $UID
 * @return mixed
 */
function get_display_from_ldap($UID) { 
  return get_attr_from_ldap($UID,'cn'); 
}

/**
 * Pull the combined first and last name atrributes for an LDAP entry
 * @param string $UID
 * @return mixed
 */
function get_name_from_ldap($UID) {
  $info = get_ldap_entry($UID);
  if(!$info) return false;
  $ldap_first = isset($info[0]["givenname"]) ? $info[0]["givenname"][0]: "";
  $ldap_last  = isset($info[0]["sn"])        ? $info[0]["sn"][0]       : "";
  return "$ldap_first $ldap_last";
}

/**
 * Get a list of all of all SAGE units from LDAP
 *
 * @global array $cfg['ldap']
 * @return array
 */
function get_units_from_ldap() {
  $return = array();
  global $cfg;
  $ds = ldap_connect($cfg['ldap']['readserver']);
  if($ds) {
    $r = ldap_bind($ds, $cfg['ldap']['mandn'], $cfg['ldap']['manpw']);
  
    $search  = "(&";
    $search .= "(uid=s0*)";
    $search .= ")";
    
    $basedn = $cfg['ldap']['basedn'];
    $sr = ldap_search($ds, $basedn, $search);
    ldap_sort($ds,$sr,"uid");
    $info = ldap_get_entries($ds, $sr);
    ldap_close($ds);
        
    for ($i=0; $i<$info["count"]; $i++) {
      $id = strtoupper($info[$i]["uid"][0]);
      $name = $info[$i]["cn"][0];
      $return[$id] = $name;
    }
  }
  return $return;
}

/**
 * Creates an HTML select element of SAGE units
 * @param null $perms
 * @param string $selected
 * @return null
 */
function unit_dropdown($perms=NULL,$selected) {

  $units = get_units_from_ldap();

  echo '<select id="unit_dropdown" name="unit_dropdown" size="1" style="width:213px">'."\n";
  echo '<option value="">Select a unit</option>'."\n";

  if($units) {
    foreach($units as $unit_num => $unit_name ) {
      $id = strtoupper($unit_num);
      $name = htmlentities($unit_name);
      $sel = ($id == $selected) ? ' selected="selected"':"";
      echo "<option value=\"$id\"$sel>Unit $id ($name)</option>\n";
      unset($sel);
    }
  } 
  echo '</select>';

  return null;
}

/**
 * New function to pull all SAGE employees from a certain unit in LDAP,
 * or all employees of a certain type, e.g. DMs, VPs, etc.
 *
 * @global array $cfg['ldap']
 * @param string $department
 * @return array
 */
function get_department_from_ldap($department) {
  $dep = strtolower($department);
  switch($dep) {
    case 'dm':                $searchterm = "DM";     break;
    case 'dms':               $searchterm = "DM";     break;
    case 'district managers': $searchterm = "DM";     break;
    case 'vp':                $searchterm = "VP";     break;
    case 'vps':               $searchterm = "VP";     break;
    case 'vice presidents':   $searchterm = "VP";     break;
    case 'cst':               $searchterm = "CST";    break;
    case 'csts':              $searchterm = "CST";    break;
    case 'sales':             $searchterm = "Sales";  break;
    case 'sales team':        $searchterm = "Sales";  break;
    case 'hq':                $searchterm = "HQ";     break;
    case 'home office':       $searchterm = "HQ";     break;
    default:
      $searchterm = $department;
      break;
  }

  global $cfg;
  $ds = ldap_connect($cfg['ldap']['readserver']);
  if ($ds) {
    $r = ldap_bind($ds, $cfg['ldap']['mandn'], $cfg['ldap']['manpw']);

    $search  = "(&";
	  $search .= "(departmentnumber=$searchterm*)";
	  $search .= ")";

	  $basedn = $cfg['ldap']['basedn'];
	  $sr = ldap_search($ds, $basedn, $search);
	  $info = ldap_get_entries($ds, $sr);
    ldap_close($ds);

    $return = array();

	  foreach($info as $i => $data) {
		  $unit = $info[$i]["departmentnumber"][0];
	    $ldap_first = $info[$i]["givenname"][0];
	    $ldap_last = $info[$i]["sn"][0];
	    $uid = $info[$i]["uid"][0];
	    $fax= (isset($info[$i]['facsimiletelephonenumber'][0]))?$info[$i]['facsimiletelephonenumber'][0]:'';
	    $temp = array(
	      'uid'  => $uid,
        'unit' => $unit,
        'name' => "$ldap_first $ldap_last",
        'home' => (isset($info[$i]["homephone"][0]))?$info[$i]["homephone"][0]:'',
        'cell' => (isset($info[$i]["mobile"][0]))?$info[$i]["mobile"][0]:'',
        'fax'  => $fax
      );
	    if(!is_null($uid)) {
        $return[] = $temp;
        unset($temp);
      }
	  }

    return $return;

  }else{
    return array('uid'=>0,'unit'=>0,'name'=>'Error');
  }
}

/**
 * Pull all DMs from LDAP. Function retained for backwards compatibility.
 *
 * @uses get_department_from_ldap()
 * @return array
 */
function get_dms_from_ldap() {
  return get_department_from_ldap('DM');
}

/**
 * Pull all VPs from LDAP. Function retained for backwards compatibility.
 *
 * @uses get_department_from_ldap()
 * @return array
 */
function get_vps_from_ldap() {
  return get_department_from_ldap('VP');
}

/**
 * Pull all CSTs from LDAP. Function retained for backwards compatibility.
 *
 * @uses get_department_from_ldap()
 * @return array
 */
function get_csts_from_ldap() {
  return get_department_from_ldap('CST');
}

/**
 * Pull Sales Team from LDAP. Function retained for backwards compatibility.
 *
 * @uses get_department_from_ldap()
 * @return array
 */
function get_sales_from_ldap() {
  return get_department_from_ldap('Sales');
}

/**
 * Pull Home Office from LDAP. Function retained for backwards compatibility.
 *
 * @uses get_department_from_ldap()
 * @return array
 */
function get_hq_from_ldap() {
  return get_department_from_ldap('HQ');
}

/**
 * Pulls all managers from LDAP that are assigned to a given SAGE unit
 *
 * @global array $cfg['ldap']
 * @param string $unit
 * @return array
 */
function getmanagers($unit) {
	if(!$unit) { return false; }
  global $cfg;
  $ds = ldap_connect($cfg['ldap']['readserver']);
  if ($ds) {
    $r = ldap_bind($ds, $cfg['ldap']['mandn'], $cfg['ldap']['manpw']);

    $search  = "(&(departmentnumber=$unit)(!(uid=s0*)))";

    $basedn = $cfg['ldap']['basedn'];
	  $sr = ldap_search($ds, $basedn, $search);
    ldap_sort($ds,$sr,'cn');
	  $info = ldap_get_entries($ds, $sr);
    ldap_close($ds);

    return $info;
  }else{
    return false;
  }
}

/**
 * Prints HTML list of managers using the get_managers() function
 *
 * @global array $cfg['ldap']
 * @param string $unit
 * @uses get_managers()
 * @return none
 */
function listmanagers($unit) {
  global $cfg;
	if(!$unit) { return false; }
  $managers = getmanagers($unit);
  $mancount = count($managers);
  $names = '';
  $numbers = '';
  if ($mancount > 0 ) {
    for ($m=0;$m < $mancount; $m++) {
      if(isset($managers[$m])) {
  	    $names .= $managers[$m]['cn'][0].'<br />';
  	    $numbers .= ( isset($managers[$m]['homephone']) ? $managers[$m]['homephone'][0] : "N/A" ) .'<br />';
      }
    }
  }
  echo '<td width="25%" valign="top">'.$names.'</td><td width="25%" valign="top">'.$numbers.'</td>';
}

/**
 * Gets list of LDAP group members. Returns an array of member UIDs or
 * false if unable to get members
 *
 * @param string $group
 * @return mixed
 */
function get_ldap_group_members($group) {
  global $cfg;
  $ds = ldap_connect($cfg['ldap']['readserver']);
  if ($ds) {
    $r = ldap_bind($ds, $cfg['ldap']['mandn'], $cfg['ldap']['manpw']);

    $search  = "(&";
    $search .= "(cn=$group)";
    $search .= ")";

    $basedn = $cfg['ldap']['groupdn'];
    $sr = ldap_search($ds, $basedn, $search);
    $info = ldap_get_entries($ds, $sr);
    ldap_close($ds);
    return ( isset($info[0]["memberuid"])) ? $info[0]["memberuid"] : false;
  }else{
    return false;
  }
}

/**
 * Checks a given UID's ldap group membership. Depends on function
 * get_ldap_group_members. Returns true(1) if in group, false(0) if not in
 * group or false if unable to get group members.
 *
 * @param string $UID
 * @param string $group
 * @return int
 */
function ldap_group_membership($UID,$group) {
  if(!$UID) return false;
  $members = get_ldap_group_members($group);
  if($members !== false) {
    return (in_array($UID,$members)) ? true:false;
  }else{
    return false;
  }
}