<div class="container">
        <table class="table table-condensed table-hover">
            <tr>
                <!--<td>{$row['ivr_menu_option_uuid']}</td>-->
                <!--<td>{$row['ivr_menu_uuid']}</td>-->
                <!--<td>{$row['domain_uuid']}</td>-->
                <td>Digit</td>
                <td>Action</td>
                <td>Param</td>
                <td>Priority</td>
                <td>Desc</td>
            </tr>
            <?php
            foreach ($res as $row) {
                echo <<<HTML
<tr>
<!--<td>{$row['ivr_menu_option_uuid']}</td>-->
<!--<td>{$row['ivr_menu_uuid']}</td>-->
<!--<td>{$row['domain_uuid']}</td>-->
<td>{$row['ivr_menu_option_digits']}</td>
<td>{$row['ivr_menu_option_action']}</td>
<td>{$row['ivr_menu_option_param']}</td>
<td>{$row['ivr_menu_option_order']}</td>
<td>{$row['ivr_menu_option_description']}</td>
</tr>
HTML;

            }
            ?>
        </table>
</div>