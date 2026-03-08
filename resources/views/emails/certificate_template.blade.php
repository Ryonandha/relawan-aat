<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Penghargaan</title>
    <style>
        @page {
            margin: 0; /* Hilangkan margin bawaan PDF agar background penuh */
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            /* GANTI NAMA FILE BACKGROUND SESUAI MILIKMU (JPG ATAU PNG) */
            background-image: url("{{ public_path('storage/logo/bg-sertifikat.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-referrer;
            color: #333;
        }

        .container {
            width: 100%;
            height: 100%;
            position: relative;
            text-align: center;
            /* Jarak konten dari atas, sesuaikan jika tertutup corak background */
            padding-top: 150px; 
        }

        .logo {
            width: 100px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 45px;
            font-weight: bold;
            color: #1a4f8b; /* Warna Biru AAT */
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .subtitle {
            font-size: 20px;
            color: #555;
            margin-bottom: 40px;
        }

        .presented-to {
            font-size: 18px;
            margin-bottom: 10px;
            color: #666;
        }

        .name {
            font-size: 40px;
            font-weight: bold;
            color: #d4af37; /* Warna Emas/Kuning AAT */
            margin-bottom: 30px;
            text-decoration: underline;
        }

        .description {
            font-size: 16px;
            line-height: 1.6;
            margin: 0 auto 50px auto;
            width: 70%;
            color: #444;
        }

        .event-name {
            font-weight: bold;
            color: #1a4f8b;
        }

        .footer {
            width: 80%;
            margin: 0 auto;
            position: absolute;
            bottom: 100px;
            left: 10%;
        }

        .signature-box {
            width: 40%;
            display: inline-block;
            text-align: center;
        }

        .signature-line {
            width: 80%;
            border-bottom: 1px solid #333;
            margin: 0 auto 5px auto;
        }

        .signature-name {
            font-weight: bold;
            font-size: 14px;
        }

        .signature-title {
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <div class="title">Sertifikat Penghargaan</div>
        <div class="subtitle">Diberikan Sebagai Bentuk Apresiasi Kepada:</div>
        
        <div class="name">{{ $registration->user->name }}</div>

        <div class="description">
            Atas dedikasi, partisipasi aktif, dan kontribusinya sebagai relawan dalam menyukseskan kegiatan:<br>
            <span class="event-name">"{{ $registration->event->title }}"</span><br>
            yang diselenggarakan pada tanggal {{ \Carbon\Carbon::parse($registration->event->event_date)->translatedFormat('d F Y') }}
            di {{ $registration->event->location }}.
        </div>

        <div class="footer">
            <div class="signature-box" style="float: left;">
                <div style="height: 60px;"></div> <div class="signature-line"></div>
                <div class="signature-name">Ketua Panitia Regional</div>
                <div class="signature-title">Yayasan Anak-Anak Terang</div>
            </div>

            <div class="signature-box" style="float: right;">
                <div style="height: 60px;"></div> <div class="signature-line"></div>
                <div class="signature-name">Ketua Umum / Pembina</div>
                <div class="signature-title">Yayasan Anak-Anak Terang</div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</body>
</html>