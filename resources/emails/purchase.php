<table>
<tr>
    <td>
    <img src='{%product_img%}' alt='{$product->title}' />
    </td>
    <td style='padding: 0 20px; font-size: 25px; text-align: center;'>
        <p>Hey! Thanks for purchasing:</p>
        <p><strong>{%product_title%}</strong></p>
        <p>
            Login to your <a target='_blank' href='{%login_url%}' style='color: #61a055;'>Dashboard</a> to start!
        </p>
    </td>
</tr>
</table>

<hr />

<table cellspacing='15'>
<tr>
    <td>Product:</td>
    <td>{%product_title%}</td>
</tr>
<tr>
    <td>Paid:</td>
    <td>${%product_price%}</td>
</tr>
<tr>
    <td>Gateway:</td>
    <td>{%gateway%}</td>
</tr>
<tr>
    <td>Transaction ID:</td>
    <td>{%transaction_id%}</td>
</tr>
</table>
<p>
    This is a confirmation email for your records.
</p>
<p>
    You may also receive a confirmation email from the Payment Gateway Provider.
</p>