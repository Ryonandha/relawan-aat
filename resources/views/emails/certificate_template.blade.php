<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', sans-serif; text-align: center; border: 10px solid #003366; padding: 50px; }
        .title { font-size: 50px; font-weight: bold; color: #003366; }
        .subtitle { font-size: 20px; margin-top: 10px; }
        .name { font-size: 35px; font-weight: bold; border-bottom: 2px solid #333; display: inline-block; margin: 30px 0; }
        .content { font-size: 18px; line-height: 1.6; }
        .footer { margin-top: 50px; font-size: 14px; color: #555; }
        .signature { margin-top: 40px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="title">SERTIFIKAT</div>
    <div class="subtitle">Diberikan kepada:</div>
    
    <div class="name">{{ $nama }}</div>
    
    <div class="content">
        Atas partisipasinya sebagai <strong>Relawan</strong> dalam kegiatan:<br>
        <span style="color: #003366; font-size: 22px;">"{{ $kegiatan }}"</span><br>
        Yang diselenggarakan pada tanggal {{ $tanggal }}<br>
        oleh <strong>Yayasan Anak-Anak Terang Indonesia - {{ $sekre }}</strong>
    </div>

    <div class="footer">
        Nomor: {{ $nomor_sertifikat }}
    </div>

    <div class="signature">
        Pengurus Yayasan AAT Indonesia
    </div>
</body>
</html>