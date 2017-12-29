<div class="h3">change
    <small>Migrates from one extension group number to another, keeping everything else intact</small>
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
            <td class=""><code>from</code>
                <small><b>Required</b></small>
            </td>
            <td><i>Int</i></td>
            <td>Extension to change, e.g. "123"</td>
        </tr>
        <tr>
            <td class=""><code>to</code>
                <small><b>Required</b></small>
            </td>
            <td><i>Int</i></td>
            <td>Extension number to change into, e.g. "199"</td>
        </tr>
        </tbody>
    </table>
</div>


<div class="h3">Returns:
    <small>JSON with <code>return <i>String</i></code> and <code>message <i>String</i></code> fields, e.g.</small>
</div>
<code>{return: "success", message: "Extensions modified"}
</code>