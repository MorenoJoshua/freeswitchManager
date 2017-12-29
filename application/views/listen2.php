<style type="text/css">
    input[type=radio] {
        position: absolute;
        top: -9999px;
        left: -9999px;
    }

    label {
        text-align: center;
    }

    /* Toggled State */
    input[type=radio][name=spy]:checked ~ .spylabel {
        background: green;
        color: white;
    }

    input[type=radio][name=spied]:checked ~ .spiedlabel {
        background: blue;
        color: white;
    }

    .ACTIVE, .UNHELD {
        background: rgba(100, 255, 100, 0.2);
    }

    .EARLY {
        background: rgba(0, 101, 255, 0.2);
    }

    .HANGUP {
        background: rgba(255, 136, 0, 0.2);
    }

    .RINGING {
        background: rgba(0, 101, 255, 0.59);
    }

    .HELD {
        /*on hold*/
        color: rgba(255, 236, 0, 0.26);
    }
</style>
<div class="container">
    <div class="col-xs-6">
        <select name="location" id="location" class="form-control">
            <option value=""> -- Select One --</option>
            <option value="Us_Office"> US Office</option>
            <option value="Tijuana_Office"> Tijuana Office</option>
        </select>
    </div>
    <div class="col-xs-6"></div>
    <form id="listen">
        <div class="row">
            <button id="dolisten" class="btn-success hidden">
                <span id="spy">-</span> will listen to <span id="spied">-</span>
            </button>
        </div>
        <?php
        foreach ($users as $row) {
            $rowlocation = ' ' . str_replace(' ', '_', $row['location']);
            echo <<<HTML
<div class="row phonerow$rowlocation">
<div class="col-xs-6 status" id="user{$row['ext']}">
    <div class="col-xs-8 nickname">{$row['nickname']}</div>
<span>
<input type="radio" name="spied" id="spied{$row['ext']}" value="{$row['ext']}">
        <label for="spied{$row['ext']}" class="btn spiedlabel pull-right glyphicon glyphicon-volume-up"></label>
        <input type="radio" name="spy" id="spy{$row['ext']}" value="{$row['ext']}">
        <label for="spy{$row['ext']}" class="btn spylabel pull-right glyphicon glyphicon-headphones"></label>
</span></div>
</div>
HTML;
        }
        ?>
    </form>
</div>
<script src="https://wrtc.crdff.net:3000/socket.io/socket.io.js"></script>
<script>
    var socket = io('https://wrtc.crdff.net:3000', {port: 3000, secure: true});
    socket.on('connect', function (msg) {
        socket.emit('join', 'callflow')
    });
    var spy;
    var spied;
    var spynick;
    var spiednick;
    $('input').on('change', function () {
        spy = $('input[type=radio][name=spy]:checked').val();
        spynick = $('input[type=radio][name=spy]:checked').parent().parent().find('.nickname').text();
        $('#spy').text(spynick);
        spied = $('input[type=radio][name=spied]:checked').val();
        spiednick = $('input[type=radio][name=spied]:checked').parent().parent().find('.nickname').text();
        $('#spied').text(spiednick);
        $('#dolisten').removeClass('hidden');
    });

    $('#listen').submit(function (e) {
        e.preventDefault();
        if (spy != null && spied != null && spy != spied) {
            socket.emit('join', spynick);
            socket.emit('command', {'function': 'dial', 'command': '*88' + spied});
            socket.emit('command', {'function': 'dial', 'command': '-'});
            socket.emit('join', 'callflow');
        }
    })
</script>
<script>
    var statuses = {};
    function checkactive() {
        $.post('https://wrtc.crdff.net/extension_manager/extensions/active', function (data) {
            statuses = {};
            $('.status').attr('class', 'col-xs-12 status');
            if (data != null) {
                $.each(data, function (k, v) {
                    statuses[v.user] = v.status;
                });
            }
            $.each(statuses, function (k, v) {
                $('#user' + k).addClass(v);
            })
        })
    }
    checkactive();
    setInterval(checkactive, 5e3);
</script>

<script>
    $('#location').on('change', function () {
        var toshow = $(this).val();
        $('.phonerow').hide();
        $('.' + toshow).show();
    })
</script>