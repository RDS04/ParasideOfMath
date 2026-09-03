<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Verifikasi - Paradise of Math</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f2f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #2e1065;
        }
        .container {
            max-width: 560px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(76, 29, 149, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 50%, #4c1d95 100%);
            padding: 32px 24px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .header h1 span {
            color: #fbbf24;
        }
        .header p {
            margin: 6px 0 0 0;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .content {
            padding: 36px 32px;
            text-align: center;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #241b3d;
            margin-bottom: 12px;
        }
        .message {
            font-size: 14px;
            color: #584f73;
            line-height: 1.6;
            margin-bottom: 28px;
        }
        .otp-box {
            background: #faf8ff;
            border: 2px dashed #7c3aed;
            border-radius: 12px;
            padding: 20px;
            display: inline-block;
            margin-bottom: 28px;
        }
        .otp-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 8px;
            color: #6d28d9;
            margin: 0;
        }
        .expiry-note {
            font-size: 13px;
            color: #e11d48;
            background: #fff1f2;
            padding: 10px 16px;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 24px;
            font-weight: 500;
        }
        .warning-text {
            font-size: 12px;
            color: #948ea5;
            line-height: 1.5;
        }
        .footer {
            background-color: #faf9fd;
            padding: 20px 32px;
            text-align: center;
            border-top: 1px solid #f0ecf9;
            font-size: 12px;
            color: #8c85a2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Paradise <span>of Math</span></h1>
            <p>Verifikasi Akun {{ ucfirst($role) }}</p>
        </div>
        <div class="content">
            <div class="greeting">Halo, {{ $name }}! 👋</div>
            <p class="message">
                Terima kasih telah melakukan pendaftaran di <strong>Paradise of Math</strong> sebagai <strong>{{ ucfirst($role) }}</strong>.<br>
                Gunakan kode OTP di bawah ini untuk menyelesaikan proses verifikasi registrasi Anda:
            </p>
            <div class="otp-box">
                <p class="otp-code">{{ $otp }}</p>
            </div>
            <br>
            <div class="expiry-note">
                ⏱️ Kode ini hanya berlaku selama <strong>10 menit</strong>.
            </div>
            <p class="warning-text">
                Jika Anda tidak melakukan pendaftaran di platform kami, silakan abaikan email ini.<br>
                Jangan berikan kode ini kepada siapapun demi keamanan akun Anda.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Paradise of Math. Hak Cipta Dilindungi Undang-Undang.
        </div>
    </div>
</body>
</html>
