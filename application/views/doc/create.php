<div class="h3">create
    <small>Creates a new extension group for the webphone and optional Panasonic/Polycomm phones</small>
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
            <td class=""><code>nickname</code>
                <small><b>Required</b></small>
            </td>
            <td><i>String</i></td>
            <td>Name to identify extension group, e.g. "John Doe"</td>
        </tr>
        <tr>
            <td class=""><code>email</code>
                <small><b>Required</b></small>
            </td>
            <td><i>String</i></td>
            <td>Email to send voicemail recordings to, e.g. "johndoe@cardiffbank.com"</td>
        </tr>
        <tr>
            <td class=""><code>did</code>
                <small><b>Required</b></small>
            </td>
            <td><i>Int</i></td>
            <td>External number that will be assigned to the extension, e.g. "18589098800"</td>
        </tr>
        <tr>
            <td class=""><code>ext</code>
                <small><b>Required</b></small>
            </td>
            <td><i>Int</i></td>
            <td>3 digit extension number for extension group, will create "[ext]", "1[ext]", "999[ext]", "888[ext]".
                Default should be result of "next" API call, e.g. "123"
            </td>
        </tr>
        <tr>
            <td class=""><code>extpw</code>
                <small><b>Required</b></small>
            </td>
            <td><i>String</i></td>
            <td>Password used for signing extensions in, default is "855pass"</td>
        </tr>
        <tr>
            <td class=""><code>vmpw</code>
                <small><b>Required</b></small>
            </td>
            <td><i>Int</i></td>
            <td>Password used for checking voice messages, between 4 and 7 digits, e.g. "7878"</td>
        </tr>
        <tr>
            <td class=""><code>pan</code>
                <small>optional</small>
            </td>
            <td><i>Bool</i></td>
            <td>If sent, will create extra extension for panasonic physical phone ("2[ext]"), e.g. "1"</td>
        </tr>
        <tr>
            <td class=""><code>poly</code>
                <small>optional</small>
            </td>
            <td><i>Bool</i></td>
            <td>If sent, will create extra extension for polycomm physical phone ("3[ext]"), e.g. "1"</td>
        </tr>
        </tbody>
    </table>
</div>

<div class="h3">Returns:
    <small>JSON with <code>return <i>String</i></code> and <code>message <i>String</i></code> fields, e.g.</small>
</div>
<code>{return: "success", message: "Extensions created!"}
</code>