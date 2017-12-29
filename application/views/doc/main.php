<div class="container">
    <legend>Freeswitch API Docs for BOC CRM</legend>
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li class="active"><a href="#Basic" data-toggle="tab">Main</a></li>
        <li><a class="Create" href="#Create" data-toggle="tab">create</a></li>
        <li><a class="Change" href="#Change" data-toggle="tab">change</a></li>
        <li><a class="Delete" href="#Delete" data-toggle="tab">delete</a></li>
        <li><a class="Available" href="#Available" data-toggle="tab">available</a></li>
        <li><a class="Next" href="#Next" data-toggle="tab">next</a></li>
        <li><a class="did_available" href="#did" data-toggle="tab">did_available</a></li>
        <li><a class="Test" href="#Test" data-toggle="tab">test</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade in active" id="Basic">
            <?php require_once 'basic.php'; ?>
        </div>
        <div class="tab-pane fade" id="Create">
            <?php require_once 'create.php'; ?>
        </div>
        <div class="tab-pane fade" id="Change">
            <?php require_once 'change.php'; ?>
        </div>
        <div class="tab-pane fade" id="Delete">
            <?php require_once 'delete.php'; ?>
        </div>
        <div class="tab-pane fade" id="Available">
            <?php require_once 'available.php'; ?>
        </div>
        <div class="tab-pane fade" id="Next">
            <?php require_once 'next.php'; ?>
        </div>
        <div class="tab-pane fade" id="Test">
            <?php require_once 'test.php'; ?>
        </div>
         <div class="tab-pane fade" id="did">
             <?php require_once 'did_available.php'; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('td i a').on('click', function(e){
        e.preventDefault();
        var tgt = $(this).attr('href').replace('#', '')
        $('.' + tgt).click();

    })
</script>
