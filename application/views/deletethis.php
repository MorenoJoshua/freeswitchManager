<pre><?php

$wrtc = mysqli_connect('localhost', 'root', '');
$crm = mysqli_connect('crm.crdff.net', 'josh', 'espada98');
$test = mysqli_connect('test.crdff.net', 'josh', 'espada98');

$userQuery = <<<MYSQL
SELECT
  id,
  ext,
  did,
  nickname
FROM crm.users
WHERE ext < '1000'
MYSQL;
$userRes = mysqli_query($crm, $userQuery) or die(mysqli_error($crm));

$userArray = array();
while ($userRow = mysqli_fetch_assoc($userRes)) {
    $userArray[$userRow['ext']]['nickname'] = $userRow['nickname'];
    $userArray[$userRow['ext']]['did'] = $userRow['did'];
    $userArray[$userRow['ext']]['id'] = $userRow['id'];
    $userArray[$userRow['ext']]['c'] = 0;
}





$wrtc_getQuery = <<<MYSQL
SELECT

#    IF(c.`direction` = 'outbound', c.`accountcode`, SUBSTRING(c.`destination_number`, 4, 4))
    (IF(c.`direction` = 'outbound', c.`accountcode`, REPLACE(c.`destination_number`, '999','')) * 1)
    AS ext,

#  IF(c.`direction` = 'outbound', c.`accountcode`, SUBSTRING(c.`destination_number`, 1, 4)) AS ext,
  REPLACE(IF(c.`direction` = 'inbound', c.`caller_id_number`, c.`destination_number`), '-','')	AS number,
  c.`direction`                                                                            	AS direction,
  c.`start_stamp`                                                                          	AS `start`,
  c.`answer_stamp`                                                                         	AS answer,
  c.`end_stamp`                                                                            	AS `end`,
  c.`duration`                                                                             	AS duration,
  c.`billsec`                                                                              	AS billsec,
  IF(c.direction = 'local', 'CHECKED_VM', c.`hangup_cause`)                                	AS `status`,
  c.`uuid`                                                                                 	AS UUID,
  c.uuid                                                                                   	AS recordingfile,
  'freeswitch'                                                                             	AS system,
  '0000-00-00 00:00:00'                                                                    	AS date_checked
FROM `fusionpbx`.`v_xml_cdr` c
WHERE
#c.checked IS NULL
#AND
IF(c.`direction` = 'outbound', c.`accountcode`, c.`destination_number`) != 'nope999'
ORDER BY start_stamp DESC
LIMIT 100;
MYSQL;

$i = 0;
$uuidArray = [];
$cdrRes = mysqli_query($wrtc, $wrtc_getQuery) or die(mysqli_error($wrtc));

while ($cdrRow = mysqli_fetch_assoc($cdrRes)) {
    $tdump[]=$cdrRow;

    $ext = $cdrRow['ext'];
    $i++;

    $cdrRow['user'] = $userArray[$ext]['id'];
    $cdrRow['did'] = $userArray[$ext]['did'];
    $userArray[$ext]['c'] = $userArray[$ext]['c'] + 1;
    if ($cdrRow['direction'] == 'outbound') {
        $cdrRow['direction'] = '0';
    } else {
        $cdrRow['direction'] = '1';
    }


    $toadd .= <<<MYSQL
('{$cdrRow['user']}','{$ext}','{$cdrRow['did']}','0','{$cdrRow['number']}','{$cdrRow['direction']}','{$cdrRow['start']}','{$cdrRow['answer']}','{$cdrRow['end']}','{$cdrRow['duration']}','{$cdrRow['billsec']}','{$cdrRow['status']}','{$cdrRow['UUID']}','{$cdrRow['recordingfile']}','{$cdrRow['system']}','{$cdrRow['date_checked']}', NULL),
MYSQL;
    $uuidArray[] = $cdrRow['UUID'];
}
print_r($userArray);

var_dump($tdump);

$uuids = join('","', $uuidArray);
$updateUUIDquery = <<<MYSQL
UPDATE fusionpbx.v_xml_cdr set checked = 1 where checked is null and uuid in ("$uuids")

MYSQL;

$toadd = trim($toadd, ', ');
$insertQuery = 'INSERT ignore INTO crm.cdr_2 VALUES ' . $toadd;

if ($i > '0') {
    $insertRes = mysqli_query($crm, $insertQuery);
    $updateRes = mysqli_query($wrtc, $updateUUIDquery);

    $cmd = 'echo "' . gmdate('D, d M Y H:i:s T', time()) . ' - Updated ' . mysqli_affected_rows($wrtc) . " records on the cdr\n";
    $cmd = 'echo "' . gmdate('D, d M Y H:i:s T', time()) . ' - Affected ' . mysqli_affected_rows($crm) . ' rows">> /var/www/html/josh/log.txt';
}
