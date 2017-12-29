<div class="h3">available
    <small>Checks if an extension number is available</small>
</div>
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>Field Name</th>
            <th>Type</th>
            <th>Description</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class=""><code>ext</code>
                <small><b>Required</b></small>
            </td>
            <td><i>Int</i></td>
            <td>Extension to check, e.g. "123"</td>
        </tr>
        </tbody>
    </table>
</div>


<div class="h3">Returns:
    <small>JSON with <code>return <i>Bool</i></code> and <code>message <i>String</i></code> fields, e.g.</small>
</div>
<code>{return: "false", message: "Extension is not available"}
</code>
