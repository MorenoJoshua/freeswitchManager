<div class="container">
    <div class="table-responsive">
        <table class="table table-hover table-condensed" style="table-layout: auto">
            <thead>
            <tr>
                <th>User</th>
                <th>Extension</th>
                <th><a href="#create_extension" data-toggle="modal" class="pull-right" id="open_createmodal">Create
                        Extension</a></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($res as $row) {
                echo <<<HTML
    			<tr id="$row->extension" class="rows">
    				<td>$row->number_alias</td>
    				<td>$row->extension</td>
    				<td>
                        <div class="pull-right">
                            <a href="extensions/edit/{$row->extension_uuid}_{$row->domain_uuid}" class="text-primary">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </a>
                            <a class="text-danger" data-toggle="modal" href="delete_extension" onclick="delete_extension_confirm('{$row->extension}_{$row->domain_uuid}')">
                                <span class="glyphicon glyphicon-trash"></span>
                            </a>
                        </div>
                    </td>
    			</tr>
HTML;
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<style>
    .inbound {
        background: pink;
    }

    .outbound {
        background: blue;
    }
</style>
<script>
    function delete_extension_confirm(extvar) {
        $('#delete_extention_confirmation_link').attr('href', 'delete/' + extvar);
        $('#delete_extension').modal('show');
    }
    $('#open_createmodal').on('click', function (e) {
        var topost = 'key=5fd432b8-b410-4790-b1fc-d001e66a642e&secret=13df4c5a8bb1438088ce93068568286c9385bab5ce7d4ace8594886afdfa45f9&function=next';
        $.post('https://wrtc.crdff.net/extension_manager/extensions/crm', topost, function (data) {
            $('#ext').val(data);
        })
    });
</script>
<script>
    function updateCalls() {
        $.post('http://wrtc.crdff.net/extension_manager/extensions/calls', function (data) {
            activecalls = data;
            $('.rows').attr('class', 'rows');
            data.forEach(function (v, i) {
                var ext = v.presence_id.substr(0, v.presence_id.indexOf('@'));
                $('#' + ext).addClass(v.direction);
            })
        })
    }


    setInterval(updateCalls, 1000);

</script>