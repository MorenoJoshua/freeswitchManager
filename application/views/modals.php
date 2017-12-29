<div class="modal fade" id="create_extension" xmlns="http://www.w3.org/1999/html">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Create a New Extension</h4>
            </div>
            <form action="https://wrtc.crdff.net/extension_manager/extensions/crm" method="post" role="form" id="create_extension_form">
                <div class="modal-body">
                    <div style="height: 150px;">
                        <div class="form-group">
                            <label for="nickname" class="col-sm-2 control-label">Nickname:</label>

                            <div class="col-sm-10">
                                <input type="text" name="nickname" id="nickname" class="form-control"
                                       required="required" placeholder="e.g. Name Lastname" autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">Email:</label>

                            <div class="col-sm-10">
                                <input type="text" name="email" id="email" class="form-control" required="required"
                                       placeholder="e.g. name@caridffbank.com">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ext" class="col-sm-2 control-label" id="checkextension">Extension</label>

                            <div class="col-sm-4">
                                <input type="number" min="100" max="999" name="ext" id="ext" class="form-control" required="required"
                                       placeholder="e.g. NNN">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="extpw" class="col-sm-2 control-label">Ext PW</label>

                            <div class="col-sm-4">
                                <input type="text" name="extpw" id="extpw" class="form-control"
                                       value="855pass" required="required" placeholder="e.g. 855pass">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="did" class="col-sm-2 control-label">DID:</label>

                            <div class="col-sm-4">
                                <input type="number" min="10000000000" max="19999999999" name="did" id="did" class="form-control" required="required"
                                       placeholder="e.g. 1858NNNNNNN">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="vmpw" class="col-sm-2 control-label">VM PW</label>

                            <div class="col-sm-4">
                                <input type="text" name="vmpw" id="vmpw" class="form-control"
                                       value="<?= substr(microtime(), -4) ?>" required="required"
                                       placeholder="e.g. XXXX">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="polycomm" class="col-sm-2 control-label">Polycomm?</label>

                            <div class="col-sm-4">
                                <input type="checkbox" name="polycomm" id="polycomm" class="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="panasonic" class="col-sm-2 control-label">Panasonic?</label>

                            <div class="col-sm-4">
                                <input type="checkbox" name="panasonic" id="panasonic" class="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <input type="submit" value="Create" class="btn btn-success">
                </div>
                <input type="hidden" value="create" name="function">
                <input type="hidden" value="5fd432b8-b410-4790-b1fc-d001e66a642e" name="key">
                <input type="hidden" value="13df4c5a8bb1438088ce93068568286c9385bab5ce7d4ace8594886afdfa45f9" name="secret">
            </form>
            <script>
                $('#create_extension_form').submit(function(e){
                    e.preventDefault();
//                    alert();
                    var topost = $('#create_extension_form').serialize();
                    $.post('https://wrtc.crdff.net/extension_manager/extensions/crm', topost, function(data){
                        toastr[data.return](data.message);
                        data.return == 'success' ? setTimeout('$(\'#create_extension\').modal(\'hide\')', 2e3):false;
                        data.return == 'success' ? setTimeout('location.reload()', 3e3):false;
                    })
                })
            </script>
        </div>
    </div>
</div>

<div class="modal fade" id="delete_extension">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are You sure?</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this extension?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Don't delete</button>
                <a class="btn btn-danger" id="delete_extention_confirmation_link" href="">Delete</a>
            </div>
        </div>
    </div>
</div>