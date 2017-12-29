<div class="h3">All requests are made thru POST to the following URL, and must include the following fields:</div>
<div class="h4">URL: <code>https://wrtc.crdff.net/extension_manager/extensions/crm</code></div>
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
            <td class=""><code>key</code>
                <small><b>Required</b></small>
            </td>
            <td><i>String</i></td>
            <td>Unique access key e.g. "7a9d126a-b12a-462a-b2ee-x1a6d45f1a3b"</td>
        </tr>
        <tr>
            <td class=""><code>secret</code>
                <small><b>Required</b></small>
            </td>
            <td><i>String</i></td>
            <td>Secret or password that matches your unique key, e.g.
                "13df4cfdfa45f4ace859495ab1438088ce93068568b8286c9385bab5ce7d886a"
            </td>
        </tr>
        <tr>
            <td class=""><code>function</code>
                <small><b>Required</b></small>
            </td>
            <td><i>String</i></td>
            <td>Function to execute. Must match one of the following: <i><a href="#Create" data-toggle="tab">"create"</a>,
                    <a href="#Change" data-toggle="tab">"change"</a>, <a href="#Delete" data-toggle="tab">"delete"</a>,
                    <a href="#Available" data-toggle="tab">"available",</a>
                    <a href="#Next" data-toggle="tab">"next"</a>,
                    <a href="#did_available" data-toggle="tab">"did_available"</a></i>
                    <a href="#Test" data-toggle="tab">"test"</a></i>
            </td>
        </tr>
        </tbody>
    </table>
</div>
