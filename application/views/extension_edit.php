<div class="container">
    <form method="post" action="../update">
        <?php
        foreach ($res as $fieldname => $value) {

            if ($fieldname == 'number_alias'
                OR $fieldname == 'voicemail_mail_to'
                OR $fieldname == 'extension'
                OR $fieldname == 'password'
                OR $fieldname == 'effective_caller_id_number'
                OR $fieldname == 'voicemail_password'
            ) {
                $class = '';
            } else {
                $class = ' hidden';
            }
            echo <<<HTML
<div class="form-group $class">
	<label for="$fieldname" class="col-sm-2 control-label">$fieldname</label>
	<div class="col-sm-4">
		<input type="text" name="$fieldname" id="$fieldname" class="form-control" value="$value">
	</div>
</div>
HTML;

        }
        ?>
        <div class="clearfix"></div>
        <div class="form-group">
            <button class="btn btn-danger pull-right">Cancel</button>
            <input class="btn btn-success pull-right" type="submit" value="Submit">
        </div>
    </form>
</div>