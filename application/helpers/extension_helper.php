<?php
function createExtensionQuery($nickname, $ext_uuid, $this_ext, $vm, $extpassword, $did, $description)
{
    return <<<MYSQL
INSERT INTO fusionpbx.v_extensions
(`extension_uuid`, `domain_uuid`, `extension`, `number_alias`, `password`, `accountcode`,
 `effective_caller_id_name`, `effective_caller_id_number`, `outbound_caller_id_name`, `outbound_caller_id_number`, `emergency_caller_id_name`, `emergency_caller_id_number`,
 `directory_full_name`, `directory_visible`, `directory_exten_visible`, `limit_max`, `limit_destination`, `user_context`,
 `toll_allow`, `call_timeout`, `call_group`, `user_record`, `hold_music`, `auth_acl`,
 `cidr`, `sip_force_contact`, `nibble_account`, `sip_force_expires`, `mwi_account`, `sip_bypass_media`,
 `unique_id`, `dial_string`, `dial_user`, `dial_domain`, `do_not_disturb`, `forward_all_destination`,
 `forward_all_enabled`, `forward_busy_destination`, `forward_busy_enabled`, `follow_me_uuid`, `enabled`, `description`)
VALUES
  ("$ext_uuid", "dd9b5af7-2f29-42b1-a21c-8ca2339748dc", "$this_ext", "$nickname", "$extpassword",
   "$this_ext",
   "Cardiff", "$did", "Cardiff", "$did", "Cardiff", "$did",
   "$nickname", "true", "true", "1", "*99$vm", "wrtc.crdff.net",
   "", "30", "agent", "all", "/var/lib/freeswitch/recordings/wrtc.crdff.net/moh.mp3", "",
   "", "", null, null, "", "",
   null, "", null, null, null, null,
   null, null, null, null, "true", "$description");

MYSQL;
}

function createVoicemailQuery($nickname, $ext, $email, $vm_password, $vm_uuid)
{
    return <<<MYSQL
INSERT INTO `fusionpbx`.`v_voicemails`
(`domain_uuid`, `voicemail_uuid`, `voicemail_id`, `voicemail_password`, `greeting_id`, `voicemail_mail_to`,
 `voicemail_attach_file`, `voicemail_local_after_email`, `voicemail_enabled`, `voicemail_description`)
VALUES
  ("dd9b5af7-2f29-42b1-a21c-8ca2339748dc", "$vm_uuid", "$ext", "$vm_password", null, "$email",
   "true", "true", "true", "$nickname");

MYSQL;
}

function updateVoicemailPasswordQuery($ext, $newpassword){
    return <<<MYSQL
update `fusionpbx`.`v_voicemails` set voicemail_password = '$newpassword' where voicemail_id regexp '$ext$'
MYSQL;

}