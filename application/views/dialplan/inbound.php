<div class="container">
    <div class="col-xs-4">
        <span class="highlightn">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        Pink background = Shared DID
    </div>
    <div class="col-xs-4">
        <span class="highlighte">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        Red border = Shared Extension
    </div>
    <div class="col-xs-4 highlightnm">
        Blue text = Shared Name
    </div>
    <legend>Specified Inbound Routes on Freeswitch</legend>
    <?php

    $colclass = 'col-xs-12 col-sm-6 col-md-4';


    function phoneformat($in)
    {
//        return $in[0] . '(' . substr($in, 1, 3) . ')' . substr($in, 4, 3) . '-' . substr($in, 7, 4);
        return $in;
    }


    foreach ($fsroutes as $row) {

        $number = isset($row['number']) ? phoneformat($row['number']) : 'None specified';
        $to = substr(strtok($row['to'], ' '), 1);

        $to = $to[0] == '0' ? '1' . substr($to, 1) : $to;


        echo <<<HTML
    <div class="$colclass check" n="$number" e="$to" nm="{$row['name']}">
        {$row['name']}
        <code class="pull-right">$number => $to</code>
    </div>
HTML;

    }


    echo '<legend>Specified in DID file</legend>';
    foreach ($did as $number => $to) {
        $number = phoneformat($number);
        echo <<<HTML
    <div class="$colclass check" n="$number" e="$to">
        <code class="pull-right">$number => $to</code>
    </div>
HTML;

    }


    echo '<legend>Specified in CRM.users table</legend>';
    foreach ($crm as $crmrow) {
        $number = phoneformat($crmrow['did']);
        $to = $crmrow['ext'];
        echo <<<HTML
    <div class="$colclass check" n="1$number" n="$to" nm="{$crmrow['nickname']}" e="$to">
        {$crmrow['nickname']}<code class="pull-right">1$number => $to</code>
    </div>
HTML;

    }

    ?>
</div>
<style type="text/css">
    .check {
        border: 1px solid white;
    }

    .highlightn {
        background: pink;
    }

    .highlighte {
        border: 1px solid rgba(200, 100, 100, 0.5);
    }

    .highlightnm {
        color: blue;
    }
</style>
<script>

    $('.check').click(function () {
        var num = $(this).attr('n');
        var ext = $(this).attr('e');
        var nm = $(this).attr('nm');
        $('.check').removeClass('highlightn');
        $('.check').removeClass('highlighte');
        $('.check').removeClass('highlightnm');
        $('[n="' + num + '"]').addClass('highlightn');
        $('[e="' + ext + '"]').addClass('highlighte');
        $('[nm="' + nm + '"]').addClass('highlightnm');
    })
</script>
