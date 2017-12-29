<?php
$users = json_decode(file_get_contents('http://crm.crdff.net/crm/crm_web_services/active_users.php'), true);

usort($users, "alph_nickname");
function alph_nickname($a, $b)
{
    return $a['nickname'] > $b['nickname'];
}

$levels = [];
foreach ($users as $urow) {
    $where = 'l' . $urow['level'];
    if (!isset($$where)) {
        $$where = array();
    }
    $levels[$where] = null;
    array_push($$where, $urow);
}
$howmany = count($levels);
$i = 1;
foreach (@$levels as $levelkey => $nada) {
    foreach ($$levelkey as $user) {
        @$options .= <<<HTML
<option value="{$user['ext']}">{$user['nickname']} - {$user['ext']}</option>
HTML;

    }
    $options .= $i == $howmany ?: '<option></option>';
}
?>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>Upload Form</title>
</head>
<body>
<?php echo form_open_multipart('voicemails/do_upload'); ?>
<label for="ext">Change greet for:</label>
<select name="ext" id="ext">
    <?= $options ?>
</select>

<label for="select">Overwrite slot no.</label>
<select name="no" id="select">
    <?php
    $i = 1;
    while ($i < 9) {
        echo "<option value='$i'>$i</option>";
        $i++;
    }
    ?></select>


<input type="file" name="userfile" size="20"/>
<br/><br/>

<input type="submit" value="upload"/>

<a href="http://wrtc.crdff.net/extension_manager/voicemails/choosegreet/107">Choose</a>

</form>

</body>
</html>