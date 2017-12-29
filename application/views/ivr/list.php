<div class="container">
    <?php

    foreach ($res as $ivr) {
        echo <<<HTML
<div class="col-xs-12">
<a href="ivr/edit/{$ivr['ivr_menu_uuid']}">IVR uuid: {$ivr['ivr_menu_uuid']}</a>
</div>
HTML;

    }

    ?>
</div>