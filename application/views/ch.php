<div class="container">
    <form id="form" action="" method="post" role="form">
        <input type="hidden" name="function" id="function" class="form-control" value="change" required="required" >

    	<div class="form-group">
    		<label for="key" class="col-sm-2 control-label">Key:</label>
    		<div class="col-sm-10">
    			<input type="text" name="key" id="key" class="form-control" required="required" >
    		</div>
    	</div>
        <div class="form-group">
        	<label for="secret" class="col-sm-2 control-label">Secret:</label>
        	<div class="col-sm-10">
        		<input type="text" name="secret" id="secret" class="form-control" required="required" >
        	</div>
        </div>
        <div class="form-group">
        	<label for="from" class="col-sm-2 control-label">From</label>
        	<div class="col-sm-10">
        		<input type="text" name="from" id="from" class="form-control" required="required" >
        	</div>
        </div>
        <div class="form-group">
        	<label for="to" class="col-sm-2 control-label">To:</label>
        	<div class="col-sm-10">
        		<input type="text" name="to" id="to" class="form-control" required="required" >
        	</div>
        </div>
    
    	<button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<script>
    $('#form').submit(function(e){
        e.preventDefault();
        var topost = $('#form').serialize()
        $.post('https://wrtc.crdff.net/extension_manager/extensions/crm', topost, function(data){
            alert(data);
        })
    })
</script>