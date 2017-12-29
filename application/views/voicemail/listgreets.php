<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Page Description">
    <meta name="author" content="josh">
    <title>Page Title</title>
</head>
<body>

<table>
    <tr>
        <td>Available Greets</td>
        <td>Listen</td>
        <td>Choose</td>
        <td>Remove</td>
    </tr>
    <?php
    foreach ($greets as $k => $v) {
        $greetnum = preg_replace('/\D/', '', $v);
        echo <<<HTML
<tr>
    <td>$greetnum</td>
    <td><a href="http://wrtc.crdff.net/extension_manager/voicemails/listen/$ext/$greetnum" target="_listengreet">http://wrtc.crdff.net/extension_manager/voicemails/listen/$ext/$greetnum</a></td>
    <td><a href="http://wrtc.crdff.net/extension_manager/voicemails/usegreet/$ext/$greetnum">Use this</a></td>
    <td>xxx</td>
</tr>
HTML;
    }


    ?>
</table>

</body>
</html>