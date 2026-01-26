<?php
include 'connect.php';

$result = mysqli_query($conn, "SELECT * FROM halaman WHERE id = 1");
$data = mysqli_fetch_assoc($result);

$judul_tentang = $data ? htmlspecialchars($data['judul_tentang'] ?? '') : '';
$isi_tentang = $data['isi_tentang'] ?? '';
$isi_kegiatan = $data['isi_kegiatan'] ?? '';
$judul_kegiatan = $data ? htmlspecialchars($data['judul_kegiatan'] ?? '') : '';
$instagram = $data ? htmlspecialchars($data['instagram'] ?? '') : '';
$tiktok = $data ? htmlspecialchars($data['tiktok'] ?? '') : '';

if (!$judul_tentang) $judul_tentang = 'Tentang OSIS Astamayana';
if (!$isi_tentang) $isi_tentang = 'OSIS Astamayana adalah organisasi siswa intra sekolah yang berfokus pada pengembangan kreativitas, solidaritas, dan kepemimpinan.';
if (!$judul_kegiatan) $judul_kegiatan = 'Kegiatan OSIS';
if (!$isi_kegiatan) $isi_kegiatan = 'Berbagai kegiatan menarik menunggu untuk Anda ikuti.';
if (!$instagram) $instagram = 'https://www.instagram.com/osisalbidskh';
if (!$tiktok) $tiktok = 'https://www.tiktok.com/@osis.albiko';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSIS Astamayana - SMP AL ABIDIN SKH</title>
    <link rel="icon" type="image/png" href="https://raw.githubusercontent.com/Mijal-Gamer/OSIS-ALBIKO/refs/heads/main/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        body {
            background: linear-gradient(135deg, #08122a, #020409, #0d1b2a);
            color: white;
            position: relative;
            overflow-x: hidden;
            min-height: 100vh;
        }

        .light {
            position: fixed;
            width: 600px;
            height: 600px;
            pointer-events: none;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 180, 255, 0.5), transparent 70%);
            filter: blur(100px);
            z-index: 0;
            animation: drift 18s cubic-bezier(0.42, 0, 0.58, 1) infinite;
            box-shadow: 0 0 120px rgba(0, 180, 255, 0.3);
        }
        
        @keyframes drift {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(100px, -150px) scale(1.1); }
            50% { transform: translate(200px, 100px) scale(0.9); }
            75% { transform: translate(-100px, 80px) scale(1.05); }
        }

        header {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(0, 15, 30, 0.96);
            backdrop-filter: blur(12px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 30px;
            z-index: 6;
            border-bottom: 2px solid rgba(0, 200, 255, 0.15);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4), 0 0 30px rgba(0, 200, 255, 0.1);
            animation: slideDown 1s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        @keyframes slideDown {
            from { transform: translateY(-100%) scale(0.95); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            animation: fadeInLeft 1s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s backwards;
        }

        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .logo-container img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(0, 255, 255, 0.5);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 0 10px rgba(0, 200, 255, 0.3);
        }

        .logo-container img:hover {
            transform: scale(1.2) rotate(5deg);
            box-shadow: 0 0 30px rgba(0, 255, 255, 0.8), inset 0 0 15px rgba(0, 255, 255, 0.3);
            border-color: rgba(0, 255, 255, 1);
        }

        header h2 {
            color: #00e6ff;
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 1px;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-shadow: 0 2px 8px rgba(0, 255, 255, 0.3);
            animation: fadeInRight 1s cubic-bezier(0.34, 1.56, 0.64, 1) 0.3s backwards;
        }

        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .logo-container:hover h2 {
            color: #00ffff;
            text-shadow: 0 2px 20px rgba(0, 255, 255, 0.8);
            transform: translateY(-3px) scale(1.05);
        }

        .burger {
            position: relative;
            width: 30px;
            height: 25px;
            cursor: pointer;
            z-index: 100;
        }

        .burger span {
            display: block;
            position: absolute;
            height: 3px;
            width: 100%;
            background: #00ffff;
            border-radius: 3px;
            left: 0;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 0 8px rgba(0, 255, 255, 0.6);
        }

        .burger span:nth-of-type(1) { top: 0; }
        .burger span:nth-of-type(2) { top: 11px; opacity: 1; }
        .burger span:nth-of-type(3) { bottom: 0; }

        .burger input:checked ~ span:nth-of-type(1) {
            transform: rotate(45deg) translate(8px, 8px);
        }

        .burger input:checked ~ span:nth-of-type(2) {
            opacity: 0;
            transform: translateX(-10px);
        }

        .burger input:checked ~ span:nth-of-type(3) {
            transform: rotate(-45deg) translate(7px, -7px);
        }

        .burger input {
            display: none;
        }

        .sidebar {
            position: fixed;
            left: -280px;
            top: 0;
            width: 280px;
            height: 100vh;
            background: rgba(0, 20, 40, 0.98);
            backdrop-filter: blur(15px);
            border-right: 2px solid rgba(0, 200, 255, 0.3);
            display: flex;
            flex-direction: column;
            padding-top: 80px;
            padding-bottom: 20px;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            z-index: 5;
            overflow-y: auto;
            box-shadow: 8px 0 40px rgba(0, 0, 0, 0.5), inset -2px 0 30px rgba(0, 200, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(0, 200, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(0, 200, 255, 0.4);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 200, 255, 0.6);
        }

        .sidebar.active {
            left: 0;
            box-shadow: 8px 0 50px rgba(0, 200, 255, 0.3), inset -2px 0 40px rgba(0, 200, 255, 0.15);
        }

        .sidebar a {
            color: #cceeff;
            text-decoration: none;
            padding: 14px 25px;
            font-weight: 600;
            border-left: 4px solid transparent;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
            overflow: hidden;
        }

        .sidebar a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(180deg, #00e0ff, #00ffff);
            transform: translateY(-100%);
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .sidebar a i {
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            z-index: 1;
        }

        .sidebar a:hover {
            background: rgba(0, 255, 255, 0.18);
            color: #00ffff;
            border-left-color: transparent;
            padding-left: 35px;
            box-shadow: inset 6px 0 20px rgba(0, 200, 255, 0.2);
        }

        .sidebar a:hover::before {
            transform: translateY(0);
        }

        .sidebar a:hover i {
            transform: rotate(15deg) scale(1.25);
        }

        .sidebar a.admin-btn {
            margin-top: auto;
            background: linear-gradient(135deg, #00e0ff, #0077ff);
            color: white;
            border-radius: 8px;
            margin: 20px 12px 0 12px;
            padding: 12px 20px;
            text-align: center;
            border-left: none;
        }

        .sidebar a.admin-btn:hover {
            background: linear-gradient(135deg, #00ffff, #00e0ff);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 255, 255, 0.4);
        }

        section {
            padding: 120px 50px;
            text-align: center;
            max-width: 1200px;
            margin: 0 auto;
            animation: fadeInUp 1.2s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            opacity: 0;
            transform: translateY(60px);
            position: relative;
        }

        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        section h1, section h2 {
            font-size: 2.5em;
            color: #00e0ff;
            margin-bottom: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-shadow: 0 0 20px rgba(0, 200, 255, 0.6);
            animation: titlePulse 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes titlePulse {
            0% { opacity: 0; transform: scale(0.9) translateY(20px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }

        section h1:hover, section h2:hover {
            transform: scale(1.08) translateY(-3px);
            color: #00ffff;
            text-shadow: 0 0 35px rgba(0, 200, 255, 1), 0 0 60px rgba(0, 150, 255, 0.5);
        }

        section p {
            font-size: 1.1em;
            color: #e0f7ff;
            line-height: 1.8;
            margin-bottom: 15px;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        section p:hover {
            transform: translateX(12px);
            color: #00ffff;
        }

        .section-logo {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 30px;
            cursor: pointer;
            border: 3px solid #00e0ff;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 0 30px rgba(0, 180, 255, 0.4), inset 0 0 20px rgba(0, 200, 255, 0.2);
            animation: logoFloat 3s ease-in-out infinite;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .section-logo:hover {
            transform: scale(1.15) rotateZ(8deg);
            box-shadow: 0 0 50px rgba(0, 255, 255, 0.9), 0 15px 50px rgba(0, 200, 255, 0.5), inset 0 0 30px rgba(0, 255, 255, 0.3);
            border-color: #00ffff;
        }

        .card-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 40px;
        }

        .card {
            background: rgba(255, 255, 255, 0.06);
            border: 2px solid rgba(0, 255, 255, 0.25);
            border-radius: 15px;
            width: 280px;
            overflow: hidden;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2), 0 0 20px rgba(0, 200, 255, 0.1);
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            cursor: pointer;
            position: relative;
        }

        .card:nth-child(1) { animation: slideIn 1s 0.2s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
        .card:nth-child(2) { animation: slideIn 1s 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
        .card:nth-child(3) { animation: slideIn 1s 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(50px) rotateX(10deg); }
            to { opacity: 1; transform: translateY(0) rotateX(0); }
        }

        .card:hover {
            transform: translateY(-18px) scale(1.06) rotateY(-2deg);
            box-shadow: 0 20px 60px rgba(0, 225, 255, 0.4), 0 0 40px rgba(0, 150, 255, 0.3), inset 0 0 20px rgba(0, 200, 255, 0.1);
            border-color: rgba(0, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.1);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            opacity: 0;
            transition: all 0.6s ease;
            display: block;
        }

        .card img.loaded {
            opacity: 1;
        }

        .card:hover img.loaded {
            transform: scale(1.1);
        }

        .card-content {
            padding: 20px;
            position: relative;
            z-index: 2;
        }

        .card-content h3 {
            color: #00e0ff;
            font-size: 1.3em;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .card:hover .card-content h3 {
            color: #00ffff;
            transform: translateY(-5px);
        }

        .card-content p {
            font-size: 0.95em;
            color: #b3eaff;
            line-height: 1.5;
            transition: color 0.3s ease;
        }

        .card:hover .card-content p {
            color: #e0f7ff;
        }

        footer {
            text-align: center;
            padding: 50px 30px;
            color: #9be8ff;
            font-size: 0.95em;
            border-top: 1px solid rgba(0, 200, 255, 0.1);
            background: rgba(0, 15, 30, 0.3);
            transition: all 0.3s ease;
        }

        footer p {
            margin: 8px 0;
        }

        footer p:first-child {
            color: #00e0ff;
            font-weight: 600;
            font-size: 1.05em;
        }

        footer:hover {
            background: rgba(0, 15, 30, 0.5);
        }

        .particle {
            position: fixed;
            width: 3px;
            height: 3px;
            background: rgba(0, 255, 255, 0.6);
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
            animation: float 8s linear infinite;
            box-shadow: 0 0 8px rgba(0, 255, 255, 0.6);
        }

        @keyframes float {
            0% { transform: translateY(100vh) translateX(0) scale(1); opacity: 1; }
            100% { transform: translateY(-100vh) translateX(100px) scale(0); opacity: 0; }
        }

        .wrapper {
            display: inline-flex;
            list-style: none;
            padding: 40px 20px;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .wrapper .icon {
            position: relative;
            background: linear-gradient(135deg, #00e0ff, #0077ff);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 12px 30px rgba(0, 200, 255, 0.3), inset 0 0 15px rgba(255, 255, 255, 0.1);
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 2px solid rgba(0, 200, 255, 0.4);
            animation: iconPop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes iconPop {
            0% { opacity: 0; transform: scale(0) rotate(-180deg); }
            100% { opacity: 1; transform: scale(1) rotate(0); }
        }

        .wrapper .icon a {
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            text-decoration: none;
            position: relative;
            z-index: 1;
        }

        .wrapper .icon:hover {
            transform: translateY(-15px) scale(1.25) rotate(5deg);
            box-shadow: 0 20px 50px rgba(0, 200, 255, 0.5), 0 0 30px rgba(0, 150, 255, 0.4), inset 0 0 25px rgba(255, 255, 255, 0.2);
            border-color: rgba(0, 200, 255, 0.9);
        }

        .wrapper .tooltip {
            position: absolute;
            top: -60px;
            font-size: 13px;
            background: rgba(0, 200, 255, 0.95);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            white-space: nowrap;
            z-index: 10;
        }

        .wrapper .tooltip::before {
            position: absolute;
            content: "";
            height: 7px;
            width: 7px;
            background: rgba(0, 200, 255, 0.95);
            bottom: -3px;
            left: 50%;
            transform: translate(-50%) rotate(45deg);
        }

        .wrapper .icon:hover .tooltip {
            top: -60px;
            opacity: 1;
            visibility: visible;
        }

        .wrapper .instagram:hover {
            background: linear-gradient(135deg, #e4405f, #c21e56);
            border-color: #e4405f;
        }

        .wrapper .instagram:hover .tooltip,
        .wrapper .instagram:hover .tooltip::before {
            background: #e4405f;
        }

        .wrapper .tiktok:hover {
            background: linear-gradient(135deg, #25f4ee, #000000);
            border-color: #25f4ee;
        }

        .wrapper .tiktok:hover .tooltip,
        .wrapper .tiktok:hover .tooltip::before {
            background: #000000;
        }

        .wrapper .email:hover {
            background: linear-gradient(135deg, #1da1f2, #1a91da);
            border-color: #1da1f2;
        }

        .wrapper .email:hover .tooltip,
        .wrapper .email:hover .tooltip::before {
            background: #1da1f2;
        }

        .wrapper .location:hover {
            background: linear-gradient(135deg, #17bf63, #0c995f);
            border-color: #17bf63;
        }

        .wrapper .location:hover .tooltip,
        .wrapper .location:hover .tooltip::before {
            background: #17bf63;
        }

        .float-btn {
            position: fixed;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 24px;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            z-index: 9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4), inset 0 0 15px rgba(255, 255, 255, 0.1);
        }

        #feedbackBtn {
            bottom: 25px;
            right: 25px;
            background: linear-gradient(135deg, #00e0ff, #0077ff);
            border: 2px solid rgba(0, 200, 255, 0.5);
            animation: bounce 3s cubic-bezier(0.45, 0, 0.55, 1) infinite;
            box-shadow: 0 12px 35px rgba(0, 200, 255, 0.3), 0 0 30px rgba(0, 150, 255, 0.2);
        }

        #scrollToTopBtn {
            bottom: 100px;
            right: -70px;
            background: linear-gradient(135deg, #0077ff, #005f99);
            border: 2px solid rgba(0, 200, 255, 0.5);
            transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 12px 35px rgba(0, 150, 255, 0.3), 0 0 20px rgba(0, 120, 200, 0.2);
        }

        #scrollToTopBtn.show {
            right: 25px;
        }

        .float-btn:hover {
            transform: scale(1.2) rotate(10deg);
            box-shadow: 0 15px 45px rgba(0, 200, 255, 0.5), 0 0 40px rgba(0, 150, 255, 0.3), inset 0 0 25px rgba(255, 255, 255, 0.15);
        }

        .float-btn:active {
            transform: scale(0.95);
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-12px) scale(1.05); }
        }

        .feedback-popup {
            position: fixed;
            bottom: 95px;
            right: 25px;
            background: rgba(0, 15, 30, 0.98);
            border: 2px solid rgba(0, 200, 255, 0.4);
            border-radius: 12px;
            padding: 25px;
            width: 320px;
            box-shadow: 0 18px 60px rgba(0, 0, 0, 0.4), 0 0 40px rgba(0, 200, 255, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            z-index: 9;
            backdrop-filter: blur(15px);
            opacity: 0;
            transform: translateY(30px) scale(0.9) rotateY(20deg);
            pointer-events: none;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .feedback-popup.show {
            opacity: 1;
            transform: translateY(0) scale(1) rotateY(0);
            pointer-events: all;
        }

        .feedback-popup h3 {
            color: #00e0ff;
            margin-bottom: 15px;
            text-align: center;
            font-size: 1.1em;
            animation: slideDown 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-shadow: 0 0 10px rgba(0, 200, 255, 0.3);
        }

        .feedback-popup textarea {
            width: 100%;
            height: 100px;
            border-radius: 8px;
            border: 2px solid rgba(0, 200, 255, 0.25);
            background: rgba(255, 255, 255, 0.05);
            color: #e0f7ff;
            padding: 12px;
            resize: none;
            outline: none;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95em;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.1s backwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .feedback-popup textarea::placeholder {
            color: rgba(224, 247, 255, 0.5);
        }

        .feedback-popup textarea:focus {
            border-color: rgba(0, 200, 255, 0.8);
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 30px rgba(0, 200, 255, 0.3), inset 0 0 15px rgba(0, 200, 255, 0.1);
            transform: scale(1.02);
        }

        .feedback-popup button {
            margin-top: 12px;
            width: 100%;
            background: linear-gradient(135deg, #00e0ff 0%, #00b4ff 50%, #0077ff 100%);
            border: 2px solid rgba(0, 200, 255, 0.4);
            padding: 12px;
            border-radius: 10px;
            cursor: pointer;
            color: white;
            font-weight: 700;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            font-family: 'Poppins', sans-serif;
            animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s backwards;
            box-shadow: 0 8px 25px rgba(0, 200, 255, 0.25);
            position: relative;
            overflow: hidden;
        }

        .feedback-popup button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .feedback-popup button:hover {
            background: linear-gradient(135deg, #00ffff 0%, #00d0ff 50%, #0088ff 100%);
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 15px 45px rgba(0, 200, 255, 0.4), 0 0 30px rgba(0, 150, 255, 0.3), inset 0 0 20px rgba(255, 255, 255, 0.1);
            border-color: rgba(0, 200, 255, 0.8);
        }

        .feedback-popup button:hover::before {
            width: 300px;
            height: 300px;
        }

        .feedback-popup button:active {
            transform: translateY(-1px) scale(0.98);
        }

        .status-message {
            text-align: center;
            font-weight: 600;
            margin-top: 12px;
            display: none;
            font-size: 0.9em;
            animation: slideIn 0.4s ease;
        }

        .status-success {
            color: #2ecc71;
        }

        .status-error {
            color: #e74c3c;
        }

        #scrollProgress {
            position: fixed;
            top: 0;
            left: 0;
            height: 4px;
            background: linear-gradient(90deg, #00e0ff, #00b4ff, #0077ff, #00b4ff, #00e0ff);
            z-index: 11;
            transition: width 0.1s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            box-shadow: 0 0 20px rgba(0, 200, 255, 0.7), 0 0 40px rgba(0, 150, 255, 0.3);
        }

        .particle {
            animation-duration: 12s !important;
            animation-timing-function: cubic-bezier(0.25, 0.46, 0.45, 0.94) !important;
        }

        @media (max-width: 768px) {
            header {
                padding: 10px 20px;
            }

            header h2 {
                font-size: 1.05rem;
            }

            .logo-container img {
                width: 35px;
                height: 35px;
            }

            section {
                padding: 100px 25px;
            }

            section h1, section h2 {
                font-size: 2em;
            }

            .card-container {
                gap: 20px;
            }

            .card {
                width: 100%;
                max-width: 300px;
            }

            .feedback-popup {
                width: 90%;
                right: 5%;
                bottom: auto;
                top: 50%;
                transform: translateY(-50%) scale(0.95);
            }

            .feedback-popup.show {
                transform: translateY(-50%) scale(1);
            }

            #scrollToTopBtn.show {
                bottom: 90px;
            }
        }

        @media (max-width: 480px) {
            section {
                padding: 80px 15px;
            }

            section h1, section h2 {
                font-size: 1.5em;
            }

            section p {
                font-size: 0.95em;
            }

            header h2 {
                display: none;
            }

            .float-btn {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
        }

        /* ===== PREMIUM BUTTON EFFECTS ===== */
        
        /* 1. GOOEY EFFECT */
        .btn-gooey {
            padding: 14px 35px;
            background: white;
            border: none;
            border-radius: 50px;
            color: #1a1a1a;
            font-weight: 700;
            font-size: 1em;
            cursor: pointer;
            position: relative;
            overflow: visible;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            font-family: 'Poppins', sans-serif;
        }

        .btn-gooey::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(0, 224, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-gooey:hover {
            transform: scale(1.08);
            box-shadow: 0 10px 30px rgba(0, 224, 255, 0.4);
            background: #f0f0f0;
        }

        .btn-gooey:hover::before {
            width: 300px;
            height: 300px;
        }

        /* 2. AURORA EFFECT */
        .btn-aurora {
            padding: 14px 35px;
            background: linear-gradient(45deg, #0a1428, #1a1a2e);
            border: 2px solid rgba(0, 224, 255, 0.3);
            border-radius: 50px;
            color: #00e0ff;
            font-weight: 700;
            font-size: 1em;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            font-family: 'Poppins', sans-serif;
        }

        .btn-aurora::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: -100%;
            background: linear-gradient(90deg, transparent, #e74c3c, transparent);
            animation: aurora 3s infinite;
        }

        @keyframes aurora {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .btn-aurora:hover {
            box-shadow: 0 0 30px rgba(231, 76, 60, 0.5), 0 0 60px rgba(231, 76, 60, 0.3);
            border-color: #e74c3c;
            transform: translateY(-3px);
        }

        .btn-aurora:hover::before {
            animation: aurora 1s infinite;
        }

        /* 3. WAVE EFFECT */
        .btn-wave {
            padding: 14px 35px;
            background: linear-gradient(135deg, #00e0ff, #0077ff);
            border: none;
            border-radius: 50px;
            color: white;
            font-weight: 700;
            font-size: 1em;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            font-family: 'Poppins', sans-serif;
        }

        .btn-wave::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.3), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .btn-wave:hover::after {
            transform: translateX(100%);
        }

        .btn-wave:hover {
            transform: scale(1.08);
            box-shadow: 0 10px 40px rgba(0, 200, 255, 0.5);
        }

        /* 4. SPLASH EFFECT */
        .btn-splash {
            padding: 14px 35px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 50px;
            color: white;
            font-weight: 700;
            font-size: 1em;
            cursor: pointer;
            position: relative;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            font-family: 'Poppins', sans-serif;
        }

        .btn-splash:hover {
            transform: translateY(-5px) scale(1.08);
            box-shadow: 0 20px 50px rgba(102, 126, 234, 0.6),
                        0 0 30px rgba(118, 75, 162, 0.4),
                        inset 0 0 30px rgba(255, 255, 255, 0.1);
        }

        .btn-splash::before {
            content: '';
            position: absolute;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-splash:hover::before {
            width: 400px;
            height: 400px;
        }

        /* 5. ZAP ME EFFECT */
        .btn-zap {
            padding: 14px 35px;
            background: white;
            border: 2px solid #667eea;
            border-radius: 50px;
            color: #667eea;
            font-weight: 700;
            font-size: 1em;
            cursor: pointer;
            position: relative;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            font-family: 'Poppins', sans-serif;
        }

        .btn-zap:hover {
            animation: btnZap 0.6s;
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.5),
                        inset 0 0 20px rgba(102, 126, 234, 0.1);
            transform: scale(1.05);
        }

        @keyframes btnZap {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-3px); }
            20%, 40%, 60%, 80% { transform: translateX(3px); }
        }

        /* 6. SQUISHY EFFECT */
        .btn-squishy {
            padding: 14px 35px;
            background: linear-gradient(135deg, #1abc9c, #16a085);
            border: none;
            border-radius: 50px;
            color: white;
            font-weight: 700;
            font-size: 1em;
            cursor: pointer;
            position: relative;
            box-shadow: 0 8px 15px rgba(26, 188, 156, 0.3);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-family: 'Poppins', sans-serif;
        }

        .btn-squishy:hover {
            box-shadow: 0 15px 35px rgba(26, 188, 156, 0.5),
                        inset 0 0 20px rgba(255, 255, 255, 0.15);
            transform: translateY(-5px) scaleY(1.15) scaleX(0.95);
        }

        .btn-squishy:active {
            transform: translateY(-2px) scaleY(0.85) scaleX(1.15);
        }

        /* 7. SURGE EFFECT */
        .btn-surge {
            padding: 14px 35px;
            background: transparent;
            border: 2px solid #667eea;
            border-radius: 50px;
            color: #667eea;
            font-weight: 700;
            font-size: 1em;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            font-family: 'Poppins', sans-serif;
        }

        .btn-surge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, #667eea, transparent);
            animation: surge 3s infinite;
            opacity: 0.5;
        }

        @keyframes surge {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .btn-surge:hover {
            box-shadow: 0 0 30px rgba(102, 126, 234, 0.6),
                        inset 0 0 30px rgba(102, 126, 234, 0.1);
            border-color: #764ba2;
            transform: scale(1.08);
        }

        .btn-surge:hover::before {
            animation: surge 0.8s infinite;
        }

        /* Responsive button effects */
        @media (max-width: 768px) {
            .btn-gooey, .btn-aurora, .btn-wave, .btn-splash, .btn-zap, .btn-squishy, .btn-surge {
                padding: 12px 28px;
                font-size: 0.95em;
            }
        }
    </style>
</head>
<body>
    <div id="scrollProgress"></div>
    <div class="light"></div>
    <div class="light"></div>

    <header>
        <div class="logo-container">
            <img src="OSIS.ico" alt="Logo OSIS">
            <h2>OSIS Astamayana</h2>
        </div>
        <label class="burger">
            <input type="checkbox" id="menu-toggle" />
            <span></span>
            <span></span>
            <span></span>
        </label>
    </header>

    <div class="sidebar" id="sidebar">
        <a href="#tentang"><i class="ri-information-line"></i> Tentang</a>
        <a href="#struktur"><i class="ri-organization-chart"></i> Struktur</a>
        <a href="#kegiatan"><i class="ri-calendar-event-line"></i> Kegiatan</a>
        <a href="#galeri"><i class="ri-image-gallery-line"></i> Galeri</a>
        <a href="#kontak"><i class="ri-mail-send-line"></i> Kontak</a>
        <a href="login.php" class="admin-btn"><i class="ri-lock-line"></i> Login</a>
    </div>

    <section id="tentang">
        <img src="OSIS.ico" class="section-logo" alt="Logo OSIS" loading="lazy">
        <h1><?php echo $judul_tentang; ?></h1>
        <p><?php echo $isi_tentang; ?></p>
    </section>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .struktur-card {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        .struktur-card:nth-child(1) { animation-delay: 0.1s; }
        .struktur-card:nth-child(2) { animation-delay: 0.2s; }

        .divisi-card {
            animation: slideInLeft 0.5s ease-out forwards;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .divisi-card:hover {
            transform: translateX(10px) translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,200,255,0.3) !important;
        }

        .divisi-card:nth-child(1) { animation-delay: 0s; }
        .divisi-card:nth-child(2) { animation-delay: 0.1s; }
        .divisi-card:nth-child(3) { animation-delay: 0.2s; }
        .divisi-card:nth-child(4) { animation-delay: 0.3s; }
        .divisi-card:nth-child(5) { animation-delay: 0.4s; }
        .divisi-card:nth-child(6) { animation-delay: 0.5s; }
    </style>

    <section id="struktur">
        <h2>Struktur Organisasi OSIS Astamayana</h2>
        <div style="background:rgba(0,20,40,0.7); padding:30px; border-radius:15px; box-shadow:0 0 20px rgba(0,200,255,0.4); max-width:900px; margin:auto;">
            
            <?php
            require 'connect-auth.php';
            
            // Get struktur data grouped by kategori
            $query = "SELECT * FROM struktur_organisasi WHERE tipe = 'pengurus' ORDER BY kategori, urutan";
            $result = mysqli_query($conn_auth, $query);
            
            $struktur_data = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $kategori = $row['kategori'];
                    if (!isset($struktur_data[$kategori])) {
                        $struktur_data[$kategori] = [];
                    }
                    $struktur_data[$kategori][] = $row;
                }
            }
            
            // Display struktur
            $icons = ['Pembina' => '👔', 'Pengurus Inti' => '⭐'];
            foreach ($struktur_data as $kategori => $members) {
                $icon = $icons[$kategori] ?? '👥';
                echo '<div class="struktur-card" style="margin-bottom:30px;">
                <h3 style="color:#00e0ff; font-size:1.3em; margin-bottom:15px; border-bottom:2px solid rgba(0,200,255,0.3); padding-bottom:10px;">' . $icon . ' ' . htmlspecialchars($kategori) . '</h3>
                <div style="background:rgba(0,50,100,0.4); padding:20px; border-radius:10px; transition:all 0.3s ease;" onmouseover="this.style.boxShadow=\'0 8px 20px rgba(0,200,255,0.3)\'; this.style.background=\'rgba(0,80,150,0.5)\';" onmouseout="this.style.boxShadow=\'none\'; this.style.background=\'rgba(0,50,100,0.4)\';">';
                
                foreach ($members as $member) {
                    echo '<p style="margin:8px 0;"><strong style="color:#00ffff;">' . htmlspecialchars($member['posisi']) . ':</strong> ' . htmlspecialchars($member['nama']) . '</p>';
                }
                
                echo '</div></div>';
            }
            
            mysqli_close($conn_auth);
            ?>

            <button id="toggleMore" style="margin:20px auto; padding:16px 40px; background:linear-gradient(135deg, #00e0ff 0%, #00b4ff 50%, #0077ff 100%); border:2px solid rgba(0,200,255,0.4); border-radius:12px; color:white; cursor:pointer; font-weight:700; font-size:1.05em; transition:all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); display:block; font-family:'Poppins', sans-serif; animation:scaleIn 0.6s ease-out 0.4s backwards; box-shadow:0 12px 35px rgba(0,200,255,0.3), 0 0 30px rgba(0,150,255,0.2); position:relative; overflow:hidden; letter-spacing:0.5px;" onmouseover="this.style.boxShadow='0 18px 50px rgba(0,224,255,0.5), 0 0 50px rgba(0,150,255,0.3)'; this.style.transform='translateY(-5px) scale(1.02)'; this.style.background='linear-gradient(135deg, #00ffff 0%, #00d0ff 50%, #0088ff 100%)';" onmouseout="this.style.boxShadow='0 12px 35px rgba(0,200,255,0.3), 0 0 30px rgba(0,150,255,0.2)'; this.style.transform='translateY(0) scale(1)'; this.style.background='linear-gradient(135deg, #00e0ff 0%, #00b4ff 50%, #0077ff 100%)';">📋 Lihat Semua Divisi</button>

            <div id="moreContent" style="margin-top:20px; text-align:left; display:none; opacity:0; transform:translateY(20px); transition:all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);">
                <?php
                require 'connect-auth.php';
                
                $query = "SELECT DISTINCT kategori FROM struktur_organisasi WHERE tipe = 'divisi' ORDER BY kategori ASC";
                $result = mysqli_query($conn_auth, $query);
                
                $divisi_colors = [
                    'KPA' => ['color' => '#2ecc71', 'bg' => 'rgba(46,204,113,0.1)', 'icon' => '🏆', 'border' => '#2ecc71'],
                    'Korseni' => ['color' => '#e74c3c', 'bg' => 'rgba(231,76,60,0.1)', 'icon' => '🎭', 'border' => '#e74c3c'],
                    'Komdis' => ['color' => '#f39c12', 'bg' => 'rgba(241,196,15,0.1)', 'icon' => '⚖️', 'border' => '#f39c12'],
                    'Rohis' => ['color' => '#9b59b6', 'bg' => 'rgba(155,89,182,0.1)', 'icon' => '☪️', 'border' => '#9b59b6'],
                    'APK' => ['color' => '#3498db', 'bg' => 'rgba(52,152,219,0.1)', 'icon' => '🎨', 'border' => '#3498db'],
                    'Humas' => ['color' => '#1abc9c', 'bg' => 'rgba(26,188,156,0.1)', 'icon' => '📢', 'border' => '#1abc9c']
                ];
                
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $kategori = $row['kategori'];
                        $color_config = $divisi_colors[$kategori] ?? ['color' => '#00e0ff', 'bg' => 'rgba(0,200,255,0.1)', 'icon' => '👥', 'border' => '#00e0ff'];
                        
                        // Get members
                        $member_query = "SELECT nama FROM struktur_organisasi WHERE tipe = 'divisi' AND kategori = ? ORDER BY urutan ASC";
                        $member_stmt = mysqli_prepare($conn_auth, $member_query);
                        mysqli_stmt_bind_param($member_stmt, "s", $kategori);
                        mysqli_stmt_execute($member_stmt);
                        $member_result = mysqli_stmt_get_result($member_stmt);
                        
                        echo '<div class="divisi-card" style="margin-bottom:25px; padding:20px; background:' . $color_config['bg'] . '; border-left:4px solid ' . $color_config['border'] . '; border-radius:8px;">
                        <h3 style="color:' . $color_config['color'] . '; margin-bottom:10px;">' . $color_config['icon'] . ' ' . htmlspecialchars($kategori) . '</h3>';
                        
                        if (mysqli_num_rows($member_result) > 0) {
                            while ($member = mysqli_fetch_assoc($member_result)) {
                                echo '<p style="color:#b3eaff; margin:5px 0;">' . htmlspecialchars($member['nama']) . '</p>';
                            }
                        }
                        
                        echo '</div>';
                        mysqli_stmt_close($member_stmt);
                    }
                }
                
                mysqli_close($conn_auth);
                ?>
            </div>
        </div>
    </section>

    <script>
        document.getElementById('toggleMore').addEventListener('click', function() {
            const moreContent = document.getElementById('moreContent');
            const button = this;
            const isOpen = moreContent.style.display !== 'none';
            
            if (!isOpen) {
                // ===== BUKA DIVISI =====
                moreContent.style.display = 'block';
                void moreContent.offsetWidth;
                
                moreContent.style.opacity = '1';
                moreContent.style.transform = 'translateY(0)';
                
                // Button animasi
                button.style.transition = 'all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1)';
                button.style.background = 'linear-gradient(135deg, #e74c3c 0%, #c0392b 50%, #a93226 100%)';
                button.style.boxShadow = '0 18px 50px rgba(231,76,60,0.4), 0 0 50px rgba(192,57,43,0.3), inset 0 0 20px rgba(255,255,255,0.05)';
                button.style.transform = 'rotate(180deg) scale(1.05)';
                button.style.textShadow = '0 2px 8px rgba(0,0,0,0.3)';
                
                // Text fade transition
                button.style.opacity = '0.7';
                setTimeout(() => {
                    button.textContent = '📋 Tutup Divisi';
                    button.style.opacity = '1';
                }, 150);
                
                // Animate divisi cards
                const cards = moreContent.querySelectorAll('.divisi-card');
                cards.forEach((card, index) => {
                    card.style.animation = `none`;
                    void card.offsetWidth;
                    card.style.opacity = '0';
                    card.style.animation = `slideInLeft 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) ${index * 0.1}s forwards`;
                });
                
            } else {
                // ===== TUTUP DIVISI =====
                moreContent.style.opacity = '0';
                moreContent.style.transform = 'translateY(-30px) scale(0.95)';
                
                // Animate cards out
                const cards = moreContent.querySelectorAll('.divisi-card');
                cards.forEach((card) => {
                    card.style.animation = `slideOutRight 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards`;
                });
                
                setTimeout(() => {
                    moreContent.style.display = 'none';
                }, 300);
                
                // Button animasi close dengan pulse effect
                button.style.transition = 'all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1)';
                button.style.background = 'linear-gradient(135deg, #00e0ff 0%, #00b4ff 50%, #0077ff 100%)';
                button.style.boxShadow = '0 18px 50px rgba(0,200,255,0.4), 0 0 50px rgba(0,150,255,0.3), inset 0 0 20px rgba(255,255,255,0.08)';
                button.style.transform = 'rotate(0deg) scale(0.98)';
                button.style.textShadow = '0 2px 8px rgba(0,0,0,0.2)';
                
                // Text fade transition
                button.style.opacity = '0.7';
                setTimeout(() => {
                    button.textContent = '📋 Lihat Semua Divisi';
                    button.style.opacity = '1';
                }, 150);
                
                // Pulse effect saat close
                setTimeout(() => {
                    button.style.animation = 'buttonPulse 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)';
                }, 100);
            }
        });

        // Add CSS animations dynamically
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInLeft {
                from {
                    opacity: 0;
                    transform: translateX(-50px) translateY(15px) rotateY(20deg);
                }
                to {
                    opacity: 1;
                    transform: translateX(0) translateY(0) rotateY(0);
                }
            }
            
            @keyframes slideOutRight {
                from {
                    opacity: 1;
                    transform: translateX(0) translateY(0) rotateY(0);
                }
                to {
                    opacity: 0;
                    transform: translateX(50px) translateY(-15px) rotateY(-20deg);
                }
            }
            
            @keyframes buttonPulse {
                0% {
                    transform: scale(0.98);
                    box-shadow: 0 18px 50px rgba(0,200,255,0.4), 0 0 50px rgba(0,150,255,0.3);
                }
                50% {
                    transform: scale(1.03);
                    box-shadow: 0 25px 70px rgba(0,200,255,0.5), 0 0 70px rgba(0,150,255,0.4);
                }
                100% {
                    transform: scale(1);
                    box-shadow: 0 18px 50px rgba(0,200,255,0.4), 0 0 50px rgba(0,150,255,0.3);
                }
            }
        `;
        document.head.appendChild(style);
    </script>

    <section id="kegiatan">
        <h2><?php echo $judul_kegiatan; ?></h2>
        <p style="font-size: 1.05em; color: #9be8ff; margin: 30px 0;"><?php echo $isi_kegiatan; ?></p>
    </section>

    <section id="galeri">
        <h2>🖼️ Galeri Kegiatan</h2>
        <div class="card-container" id="gallery-container">
            <?php
            // Koneksi ke database auth untuk mengambil galeri
            require 'connect-auth.php';
            
            // Query galeri dari database osis_auth
            $query = "SELECT id, judul, deskripsi, foto, tipe_file FROM galeri ORDER BY dibuat_at DESC";
            $result = mysqli_query($conn_auth, $query);
            
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $foto_src = 'data:' . $row['tipe_file'] . ';base64,' . $row['foto'];
                    echo '
                    <div class="card" data-galeri-id="' . $row['id'] . '">
                        <img src="' . $foto_src . '" loading="lazy" alt="' . htmlspecialchars($row['judul']) . '">
                        <div class="card-content">
                            <h3>' . htmlspecialchars($row['judul']) . '</h3>
                            <p>' . htmlspecialchars($row['deskripsi']) . '</p>
                        </div>
                    </div>
                    ';
                }
            } else {
                // Default gallery jika belum ada foto
                echo '
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=400&h=200&fit=crop" loading="lazy" alt="Galeri">
                    <div class="card-content">
                        <h3>Galeri Kosong</h3>
                        <p>Foto-foto kegiatan OSIS akan ditampilkan di sini. Login untuk menambahkan foto!</p>
                    </div>
                </div>
                ';
            }
            
            mysqli_close($conn_auth);
            ?>
        </div>
    </section>

    <section id="kontak">
        <h2>Hubungi Kami</h2>
        <p>Ikuti media sosial kami untuk update terbaru dan informasi kegiatan</p>

        <!-- Premium Hover Button Effects -->
        <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 20px; margin: 50px 0; max-width: 900px; margin-left: auto; margin-right: auto;">
            <button class="btn-gooey">
                <span>Gooey</span>
            </button>
            <button class="btn-aurora">
                <span>Aurora</span>
            </button>
            <button class="btn-wave">
                <span>Wave</span>
            </button>
            <button class="btn-splash">
                <span>Splash</span>
            </button>
            <button class="btn-zap">
                <span>ZAP ME</span>
            </button>
            <button class="btn-squishy">
                <span>Squishy</span>
            </button>
            <button class="btn-surge">
                <span>Surge</span>
            </button>
        </div>

        <ul class="wrapper">
            <li class="icon instagram">
                <span class="tooltip">Instagram</span>
                <a href="<?php echo $instagram; ?>" target="_blank" title="Ikuti Instagram kami"><i class="ri-instagram-line"></i></a>
            </li>
            <li class="icon tiktok">
                <span class="tooltip">TikTok</span>
                <a href="<?php echo $tiktok; ?>" target="_blank" title="Ikuti TikTok kami"><i class="ri-tiktok-line"></i></a>
            </li>
            <li class="icon email">
                <span class="tooltip">Email</span>
                <a href="mailto:osisalbisuk1@gmail.com" title="Kirim email"><i class="ri-mail-line"></i></a>
            </li>
            <li class="icon location">
                <span class="tooltip">Lokasi</span>
                <a href="https://maps.google.com/?q=SMPII+Al+Abidin+Sukoharjo" target="_blank" title="Buka lokasi"><i class="ri-map-pin-line"></i></a>
            </li>
        </ul>
    </section>

    <footer>
        <p>© 2025 OSIS Astamayana - SMP AL ABIDIN Sukoharjo</p>
        <p>Bersama Membangun, Bersama Berinovasi, Bersama Maju</p>
        <p style="margin-top: 15px; color: #7f8c8d; font-size: 0.85em;">Dibuat dengan ❤️ oleh Tim HUMAS</p>
    </footer>

    <button id="feedbackBtn" class="float-btn" title="Berikan Feedback"><i class="ri-chat-3-line"></i></button>
    <button id="scrollToTopBtn" class="float-btn" title="Kembali ke Atas"><i class="ri-arrow-up-line"></i></button>

    <div class="feedback-popup" id="feedbackPopup">
        <h3>💬 Kirim Feedback</h3>
        <textarea id="feedbackText" placeholder="Sampaikan saran dan masukan Anda di sini..."></textarea>
        <button id="sendFeedback">Kirim Feedback</button>
        <div id="statusMessage" class="status-message"></div>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/12.5.0/firebase-app.js";
        import { getDatabase, ref, push, set } from "https://www.gstatic.com/firebasejs/12.5.0/firebase-database.js";

        const firebaseConfig = {
            apiKey: "AIzaSyDAvmSiSgfijLYb1_e8p1mf5rA8oaYpG1Y",
            authDomain: "osis-asstamayana.firebaseapp.com",
            databaseURL: "https://osis-asstamayana-default-rtdb.asia-southeast1.firebasedatabase.app",
            projectId: "osis-asstamayana",
            storageBucket: "osis-asstamayana.firebasestorage.app",
            messagingSenderId: "487901502731",
            appId: "1:487901502731:web:e0ed0778bb4c796bd2960e",
            measurementId: "G-TJ8W5XV0GH"
        };

        const app = initializeApp(firebaseConfig);
        const db = getDatabase(app);
        
        console.log("✅ Firebase initialized in index.php");

        const feedbackBtn = document.getElementById("feedbackBtn");
        const feedbackPopup = document.getElementById("feedbackPopup");
        const feedbackText = document.getElementById("feedbackText");
        const sendFeedback = document.getElementById("sendFeedback");
        const statusMessage = document.getElementById("statusMessage");
        const scrollToTopBtn = document.getElementById("scrollToTopBtn");
        const scrollProgress = document.getElementById("scrollProgress");
        const menuToggle = document.getElementById("menu-toggle");
        const sidebar = document.getElementById("sidebar");

        window.addEventListener("scroll", () => {
            const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrolled = window.scrollY / scrollHeight;
            scrollProgress.style.width = scrolled * 100 + "%";

            if (window.scrollY > 300) {
                scrollToTopBtn.classList.add("show");
            } else {
                scrollToTopBtn.classList.remove("show");
            }
        });

        scrollToTopBtn.addEventListener("click", () => {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });

        feedbackBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            feedbackPopup.classList.toggle("show");
            if (feedbackPopup.classList.contains("show")) {
                feedbackText.focus();
            }
        });

        document.addEventListener("click", (e) => {
            if (!feedbackBtn.contains(e.target) && !feedbackPopup.contains(e.target)) {
                feedbackPopup.classList.remove("show");
            }
        });

        sendFeedback.addEventListener("click", async () => {
            const text = feedbackText.value.trim();
            
            if (!text) {
                showStatus("Harap isi feedback terlebih dahulu!", "error");
                return;
            }

            if (text.length < 5) {
                showStatus("Feedback minimal 5 karakter", "error");
                return;
            }

            sendFeedback.disabled = true;
            sendFeedback.textContent = "Mengirim...";

            try {
                const feedbackRef = ref(db, "feedback");
                const newFeedbackRef = push(feedbackRef);
                
                console.log("📤 Sending feedback to Firebase...");
                await set(newFeedbackRef, {
                    text: text,
                    timestamp: new Date().toLocaleString("id-ID"),
                    userAgent: navigator.userAgent
                });
                
                console.log("✅ Feedback sent successfully!");
                showStatus("✅ Terima kasih! Feedback Anda telah diterima.", "success");
                feedbackText.value = "";
                
                setTimeout(() => {
                    feedbackPopup.classList.remove("show");
                    statusMessage.style.display = "none";
                }, 2000);
            } catch (error) {
                console.error("❌ Error sending feedback:", error);
                showStatus("❌ Gagal mengirim feedback. Coba lagi nanti.", "error");
            }

            sendFeedback.disabled = false;
            sendFeedback.textContent = "Kirim Feedback";
        });

        function showStatus(message, type) {
            statusMessage.textContent = message;
            statusMessage.className = "status-message status-" + type;
            statusMessage.style.display = "block";
        }

        feedbackText.addEventListener("keypress", (e) => {
            if (e.key === "Enter" && e.ctrlKey) {
                sendFeedback.click();
            }
        });

        const particleCount = 20;
        for (let i = 0; i < particleCount; i++) {
            const p = document.createElement("div");
            p.classList.add("particle");
            p.style.left = Math.random() * 100 + "vw";
            p.style.top = Math.random() * 100 + "vh";
            p.style.animationDuration = (5 + Math.random() * 5) + "s";
            p.style.animationDelay = Math.random() * 2 + "s";
            document.body.appendChild(p);
        }

        menuToggle.addEventListener("change", () => {
            sidebar.classList.toggle("active");
        });

        sidebar.querySelectorAll("a").forEach(link => {
            link.addEventListener("click", () => {
                menuToggle.checked = false;
                sidebar.classList.remove("active");
            });
        });

        const lazyImages = document.querySelectorAll("img[loading='lazy']");
        lazyImages.forEach(img => {
            img.addEventListener("load", () => {
                img.classList.add("loaded");
            });
            
            if (img.complete) {
                img.classList.add("loaded");
            }
        });

        console.log("✨ OSIS Astamayana - Semua fitur berjalan dengan sempurna!");
    </script>
</body>
</html>