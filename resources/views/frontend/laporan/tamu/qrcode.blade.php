@php
    if (!$tamu || !$tamu->uuid) {
        abort(404);
    }
    $uuid = $tamu->uuid;
@endphp
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>QR Code Tamu</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    body{
      margin:0;
      font-family:Inter, sans-serif;
      background:#0e172a;
      display:flex;
      align-items:center;
      justify-content:center;
      height:100vh;
      color:#e6eef8;
      text-align:center;
      padding:20px;
    }
    .box{
      background:rgba(255,255,255,0.05);
      padding:32px;
      border-radius:18px;
      backdrop-filter:blur(6px);
      box-shadow:0 8px 30px rgba(0,0,0,0.45);
      max-width:360px;
      width:100%;
    }
    h1{
      font-size:22px;
      margin:0 0 6px;
      font-weight:700;
    }
    p{
      margin:0 0 18px;
      color:#94a3b8;
      font-size:14px;
      font-weight:500;
    }
    #qrcode{
      padding:14px;
      background:white;
      border-radius:12px;
      display:inline-block;
    }
  </style>
</head>
<body>
  <div class="box">
      <h1>{{ $tamu->company->company_name ?? 'Nama Perusahaan' }}</h1>
      <p>Silahkan tunjukkan Qrcode ini ke security yang bertugas</p>
      <div id="qrcode"></div>
      
      <p style="margin-top:20px;">powered by <a href="https://qbsc.cloud">QBSC</a></p>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script>
      new QRCode(document.getElementById("qrcode"), {
        text: @json($uuid),
        width: 240,
        height: 240,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
      });
  </script>
</body>
</html>
