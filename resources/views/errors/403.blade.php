<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>403 - Forbidden</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
      body{
        margin:0;
        font-family:Inter, sans-serif;
        background:#0e172a;
        color:#e2e8f0;
        display:flex;
        align-items:center;
        justify-content:center;
        height:100vh;
        padding:20px;
        text-align:center;
      }
      .box{
        background:rgba(255,255,255,0.05);
        padding:40px 32px;
        border-radius:18px;
        backdrop-filter:blur(6px);
        box-shadow:0 8px 30px rgba(0,0,0,0.45);
        max-width:420px;
        width:100%;
        animation: fadeIn .6s ease;
      }
      h1{font-size:48px; margin:0 0 8px; font-weight:700;}
      p{margin:0 0 20px; font-size:15px; color:#94a3b8;}
      a{
        display:inline-block;
        padding:12px 20px;
        border-radius:10px;
        background:#6366f1;
        color:white;
        text-decoration:none;
        font-weight:600;
        transition:.25s;
      }
      a:hover{
        background:#4f46e5;
      }
      @keyframes fadeIn{
        from{opacity:0; transform:translateY(18px);}
        to{opacity:1; transform:translateY(0);}
      }
  </style>
</head>
<body>
  <div class="box">
      <h1>403</h1>
      <p>Akses ditolak. Anda tidak memiliki izin.</p>
      <a href="{{ url('/') }}">Kembali ke Beranda</a>
  </div>
</body>
</html>
