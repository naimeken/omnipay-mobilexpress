<?php
$threeDRedirectURL = 'https://test.mobilexpress.com.tr/VPayment/VPay3DGateway.aspx?t=1&Token=cf341d77-e43d-4b3d-a6c5-d7442a611360&Secret=lYBZYZb1BzPGLmyyRDvFZmXtLX4%3d';
?>

<form method="POST" action=<?php echo $threeDRedirectURL; ?>>
    <input type="text" id="CardNumber" name="CardNumber" value="4022774022774026"><br><br>
    <input type="text" id="CardLastYear" name="CardLastYear" value="2022"><br><br>
    <input type="text" id="CardLastMonth" name="CardLastMonth" value="12"><br><br>
    <input type="text" id="CardCVV" name="CardCVV" value="000"><br><br>
    <input type="submit" value="Submit">
</form>

