<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QRCODE</title>
</head>

<body>
    <div class="text-center">
        <h3>สแกนเพื่อชำระเงิน</h3>
        {!! QrCode::size(250)->generate($payload) !!}
        <p>จำนวนเงิน: {{ number_format(150.75, 2) }} บาท</p>
    </div>
</body>

</html>
