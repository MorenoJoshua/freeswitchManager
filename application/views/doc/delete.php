<div class="h3">delete
    <small class="text-danger">Deletes an extension group - Cannot be undone!!</small>
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
            <td>Extension to delete, e.g. "123"</td>
        </tr>
        </tbody>
    </table>
</div>


<div class="h3">Returns:
    <small>JSON with <code>return <i>String</i></code> and <code>message <i>String</i></code> fields, e.g.
    </small>
</div>
<code>{return: "success", message: "Extensions deleted"}
</code>
