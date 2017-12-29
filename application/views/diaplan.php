<div class="container">
    <legend>Dialplan routes:</legend>
    <div class="row bg-primary">
        <div class="col-xs-4">Name</div>
        <div class="col-xs-4">Context</div>
        <div class="col-xs-4">Enabled?</div>
        <div class="clearfix"></div>
    </div>
    <?php

    $toecho = [];

    $act = ' active';
    foreach ($routes as $row) {
        $toecho[$row['dialplan_context']] = '';
    }
    echo '<ul class="nav nav-tabs" role="tablist">';
    foreach ($toecho as $key => $val) {
        $key = str_ireplace('.', '_', $key);
        echo <<<HTML
<li role="presentation" class="$act"><a href="#$key" aria-controls="home" role="tab" data-toggle="tab">$key</a></li>
HTML;
        $act = '';

    }
    echo '</ul>';

    foreach ($routes as $row) {
        $glyph = $row['dialplan_enabled'] == 'true' ? 'ok' : 'remove';
        $text = $row['dialplan_enabled'] == 'true' ? 'success' : 'danger';
        $toecho[$row['dialplan_context']] .= <<<HTML
<tr>
<td class="text-center"><span onclick="toggle_active('{$row['dialplan_uuid']}')" id="{$row['dialplan_uuid']}" class="glyphicon glyphicon-$glyph btn btn-$text btn-xs"></span></td>
<td class="">{$row['dialplan_name']}</td>
<td class="">{$row['dialplan_order']}</td>
</tr>
HTML;
    }

    echo '<div class="tab-content">';


    $act = ' active';
    foreach ($toecho as $key => $val) {
        $key = str_ireplace('.', '_', $key);
        echo <<<HTML
<div role="tabpanel" class="tab-pane $act" id="$key">
    <table class="table table-condensed">
        <tbody class="row">
        <tr>
            <th class="text-center">Active</th>
            <th>Name</th>
            <th>Priority</th>
        </tr>
        $val
        </tbody>
    </table>
</div>

HTML;
        $act = '';
    }
    echo '</div>';

    ?>
</div>
<script>

    var _true = 'glyphicon glyphicon-ok btn btn-success btn-xs';
    var _false = 'glyphicon glyphicon-remove btn btn-danger btn-xs';

    function toggle_active(uuid) {
        $.post('http://wrtc.crdff.net/extension_manager/dialplan/toggle_active', 'uuid=' + uuid, function (data) {
            var tgt = '#' + uuid;
            data === 'true' ? $(tgt).attr('class', _true) : $(tgt).attr('class', _false);
        })
    }
</script>