<?php
require 'auth-check.php';
include "connect.php";

$result = mysqli_query($conn, "SELECT * FROM halaman WHERE id = 1");
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Konten | OSIS Astamayana</title>
    <link rel="icon" type="image/png" href="https://raw.githubusercontent.com/Mijal-Gamer/OSIS-ALBIKO/refs/heads/main/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

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
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        .light {
            position: fixed;
            width: 600px;
            height: 600px;
            pointer-events: none;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 180, 255, 0.4), transparent 70%);
            filter: blur(80px);
            z-index: 0;
            animation: drift 15s ease-in-out infinite;
        }

        .light:nth-child(1) {
            top: -200px;
            left: -200px;
            animation-delay: 0s;
        }

        .light:nth-child(2) {
            bottom: -300px;
            right: -300px;
            animation-delay: 5s;
        }

        @keyframes drift {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(150px, -100px); }
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
            border-bottom: 1px solid rgba(0, 200, 255, 0.1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.8s ease-out;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        @keyframes slideDown {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logo-container img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(0, 255, 255, 0.5);
            transition: all 0.3s ease;
        }

        .logo-container img:hover {
            transform: scale(1.15);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.7);
            border-color: rgba(0, 255, 255, 0.9);
        }

        header h2 {
            color: #00e6ff;
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            text-shadow: 0 2px 8px rgba(0, 255, 255, 0.3);
            margin: 0;
        }

        .logo-container:hover h2 {
            color: #00ffff;
            text-shadow: 0 2px 15px rgba(0, 255, 255, 0.6);
            transform: translateY(-2px);
        }

        .nav-links {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .nav-links a {
            color: #cceeff;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9em;
        }

        .nav-links a:hover {
            color: #00ffff;
            background: rgba(0, 200, 255, 0.1);
        }

        .logout-btn {
            background: linear-gradient(135deg, #e74c3c, #c0392b) !important;
            color: white !important;
            padding: 8px 16px !important;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #e85e50, #d43f2f) !important;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
        }

        h1 {
            color: #00e0ff;
            font-size: 2.2em;
            margin-bottom: 30px;
            text-align: center;
            text-shadow: 0 0 15px rgba(0, 200, 255, 0.3);
            animation: slideDown 0.8s ease-out;
            font-weight: 700;
            letter-spacing: 1px;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .main {
            position: relative;
            z-index: 2;
            max-width: 1000px;
            margin: 100px auto 50px;
            padding: 40px 30px;
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-section {
            background: rgba(0, 30, 60, 0.5);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(0, 200, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            animation: slideInLeft 0.8s ease-out forwards;
            opacity: 0;
            transform: translateX(-30px);
            position: relative;
            overflow: hidden;
        }

        .form-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(0, 200, 255, 0.1), transparent);
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .form-section:hover::before {
            opacity: 1;
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

        .form-section:nth-child(1) { animation-delay: 0.1s; }
        .form-section:nth-child(2) { animation-delay: 0.2s; }
        .form-section:nth-child(3) { animation-delay: 0.3s; }

        .form-section:hover {
            border-color: rgba(0, 200, 255, 0.5);
            background: rgba(0, 30, 60, 0.8);
            box-shadow: 0 15px 50px rgba(0, 200, 255, 0.2);
            transform: translateY(-5px);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.3em;
            font-weight: 700;
            color: #00e0ff;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid rgba(0, 200, 255, 0.3);
            transition: all 0.3s ease;
            text-shadow: 0 0 10px rgba(0, 200, 255, 0.4);
            position: relative;
            z-index: 2;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, #00e0ff, #0077ff);
            transition: width 0.3s ease;
        }

        .section-title i {
            font-size: 28px;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: inline-block;
        }

        .form-section:hover .section-title {
            color: #00ffff;
            text-shadow: 0 0 15px rgba(0, 200, 255, 0.6);
        }

        .form-section:hover .section-title::after {
            width: 100%;
        }

        .form-section:hover .section-title i {
            transform: rotate(15deg) scale(1.15);
            filter: drop-shadow(0 0 10px rgba(0, 200, 255, 0.4));
        }

        .form-group {
            margin-bottom: 22px;
            animation: slideInForm 0.6s ease-out;
        }

        @keyframes slideInForm {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #00e0ff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .form-group:focus-within label {
            color: #00ffff;
            text-shadow: 0 0 8px rgba(0, 200, 255, 0.5);
            transform: translateY(-2px);
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 14px 18px;
            background: rgba(0, 0, 0, 0.3);
            border: 2px solid rgba(0, 200, 255, 0.3);
            border-radius: 12px;
            color: white;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            z-index: 2;
            backdrop-filter: blur(5px);
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: rgba(0, 200, 255, 0.4);
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: rgba(0, 200, 255, 0.8);
            background: rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 20px rgba(0, 200, 255, 0.25), inset 0 0 15px rgba(0, 200, 255, 0.05);
            transform: translateY(-2px);
        }

        .form-group input::selection,
        .form-group textarea::selection {
            background: rgba(0, 200, 255, 0.3);
            color: #00ffff;
        }
            border-color: rgba(0, 200, 255, 0.8);
            background: rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 20px rgba(0, 200, 255, 0.3), inset 0 0 15px rgba(0, 200, 255, 0.05);
            transform: translateY(-2px);
        }

        .quill-editor-container {
            margin: 15px 0;
        }

        .ql-toolbar.ql-snow {
            background: rgba(0, 20, 40, 0.6) !important;
            border: 1.5px solid rgba(0, 200, 255, 0.3) !important;
            border-radius: 12px 12px 0 0 !important;
            border-bottom: none !important;
            padding: 8px !important;
        }

        .ql-container.ql-snow {
            background: rgba(0, 0, 0, 0.3) !important;
            border: 1.5px solid rgba(0, 200, 255, 0.3) !important;
            border-top: none !important;
            border-radius: 0 0 12px 12px !important;
        }

        .ql-editor {
            background: rgba(0, 0, 0, 0.2) !important;
            color: white !important;
            min-height: 250px !important;
            font-family: 'Poppins', sans-serif !important;
            font-size: 14px !important;
            line-height: 1.8 !important;
        }

        .ql-editor.ql-blank::before {
            color: rgba(0, 200, 255, 0.4) !important;
            font-style: italic !important;
        }

        .ql-toolbar.ql-snow .ql-stroke {
            stroke: rgba(0, 200, 255, 0.7) !important;
        }

        .ql-toolbar.ql-snow .ql-fill,
        .ql-toolbar.ql-snow .ql-stroke.ql-fill {
            fill: rgba(0, 200, 255, 0.7) !important;
        }

        .ql-toolbar.ql-snow button:hover,
        .ql-toolbar.ql-snow button:focus,
        .ql-toolbar.ql-snow button.ql-active,
        .ql-toolbar.ql-snow .ql-picker-label:hover,
        .ql-toolbar.ql-snow .ql-picker-item:hover,
        .ql-toolbar.ql-snow .ql-picker-item.ql-selected {
            color: #00ffff !important;
        }

        .ql-toolbar.ql-snow button:hover .ql-stroke,
        .ql-toolbar.ql-snow button:focus .ql-stroke,
        .ql-toolbar.ql-snow button.ql-active .ql-stroke,
        .ql-toolbar.ql-snow .ql-picker-label:hover .ql-stroke,
        .ql-toolbar.ql-snow .ql-picker-item:hover .ql-stroke,
        .ql-toolbar.ql-snow .ql-picker-item.ql-selected .ql-stroke {
            stroke: #00ffff !important;
        }

        .ql-toolbar.ql-snow button:hover .ql-fill,
        .ql-toolbar.ql-snow button:focus .ql-fill,
        .ql-toolbar.ql-snow button.ql-active .ql-fill,
        .ql-toolbar.ql-snow .ql-picker-label:hover .ql-fill,
        .ql-toolbar.ql-snow .ql-picker-item:hover .ql-fill,
        .ql-toolbar.ql-snow .ql-picker-item.ql-selected .ql-fill {
            fill: #00ffff !important;
        }

        .ql-picker-label {
            color: rgba(0, 200, 255, 0.7) !important;
        }

        .ql-toolbar.ql-snow .ql-picker-options {
            background: rgba(0, 20, 40, 0.95) !important;
            border: 1px solid rgba(0, 200, 255, 0.3) !important;
        }

        .ql-toolbar.ql-snow .ql-picker-item {
            color: rgba(0, 200, 255, 0.7) !important;
        }

        .ql-editor a {
            color: #00e0ff !important;
        }

        .help-text {
            font-size: 12px;
            color: rgba(0, 200, 255, 0.6);
            margin-top: 8px;
            transition: all 0.3s ease;
        }

        /* Gallery Management Styles */
        .gallery-section {
            background: rgba(0, 30, 60, 0.5);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(0, 200, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            animation: slideInLeft 0.8s ease-out forwards;
            opacity: 0;
            transform: translateX(-30px);
            position: relative;
            overflow: hidden;
        }

        .gallery-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(0, 200, 255, 0.1), transparent);
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gallery-section:hover::before {
            opacity: 1;
        }

        .gallery-section:nth-of-type(4) {
            animation-delay: 0.4s;
        }

        .gallery-section:hover {
            border-color: rgba(0, 200, 255, 0.5);
            background: rgba(0, 30, 60, 0.8);
            box-shadow: 0 15px 50px rgba(0, 200, 255, 0.2);
            transform: translateY(-5px);
        }

        .gallery-upload {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 12px;
            position: relative;
            z-index: 2;
            flex-wrap: wrap;
        }

        .gallery-upload input[type="file"],
        .gallery-upload input[type="text"],
        .gallery-upload textarea {
            padding: 12px 16px;
            background: rgba(0, 0, 0, 0.3);
            border: 2px solid rgba(0, 200, 255, 0.3);
            border-radius: 8px;
            color: white;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .gallery-upload input[type="file"] {
            flex: 1;
            min-width: 200px;
        }

        .gallery-upload input[type="text"] {
            flex: 1;
            min-width: 200px;
        }

        .gallery-upload textarea {
            width: 100%;
            min-height: 80px;
            resize: vertical;
            flex: 1;
        }

        .gallery-upload input::placeholder,
        .gallery-upload textarea::placeholder {
            color: rgba(0, 200, 255, 0.4);
        }

        .gallery-upload input:focus,
        .gallery-upload textarea:focus {
            outline: none;
            border-color: rgba(0, 200, 255, 0.8);
            background: rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 20px rgba(0, 200, 255, 0.25), inset 0 0 15px rgba(0, 200, 255, 0.05);
            transform: translateY(-2px);
        }

        .btn-upload-galeri {
            padding: 12px 28px;
            background: linear-gradient(135deg, #00e0ff, #0077ff);
            color: white;
            border: 2px solid rgba(0, 200, 255, 0.4);
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            position: relative;
            overflow: hidden;
        }

        .btn-upload-galeri::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
            z-index: -1;
        }

        .btn-upload-galeri:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-upload-galeri:hover {
            background: linear-gradient(135deg, #00ffff, #00e0ff);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 200, 255, 0.4);
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
            margin-top: 30px;
            position: relative;
            z-index: 2;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            border: 2px solid rgba(0, 200, 255, 0.3);
            transition: all 0.3s ease;
            animation: slideIn 0.5s ease-out forwards;
            background: rgba(0, 0, 0, 0.3);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .gallery-item:hover {
            transform: translateY(-10px);
            border-color: rgba(0, 200, 255, 0.7);
            box-shadow: 0 15px 40px rgba(0, 200, 255, 0.25);
        }

        .gallery-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        .gallery-item-info {
            padding: 12px;
            background: rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 2;
        }

        .gallery-item-title {
            color: #00e0ff;
            font-weight: 600;
            font-size: 0.9em;
            margin-bottom: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: color 0.3s ease;
        }

        .gallery-item:hover .gallery-item-title {
            color: #00ffff;
        }

        .gallery-item-desc {
            color: #9be8ff;
            font-size: 0.8em;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .gallery-item-actions {
            display: flex;
            gap: 8px;
            margin-top: 8px;
            position: relative;
            z-index: 3;
        }

        .btn-delete-galeri {
            flex: 1;
            padding: 6px 10px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            border: 1px solid rgba(231, 76, 60, 0.3);
            border-radius: 6px;
            font-size: 0.8em;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .btn-delete-galeri:hover {
            background: linear-gradient(135deg, #e85e50, #d43f2f);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(231, 76, 60, 0.4);
        }

        .empty-gallery {
            text-align: center;
            padding: 40px;
            color: #9be8ff;
            position: relative;
            z-index: 2;
        }

        .empty-gallery i {
            font-size: 48px;
            color: #00e0ff;
            margin-bottom: 20px;
            display: block;
            opacity: 0.6;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 50px;
            padding-top: 40px;
            border-top: 2px solid rgba(0, 200, 255, 0.2);
            animation: fadeInUp 1s ease-out forwards;
            animation-delay: 0.4s;
            opacity: 0;
            transform: translateY(30px);
        }

        button {
            padding: 15px 35px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            flex: 1;
            letter-spacing: 0.5px;
        }

        .btn-simpan {
            background: linear-gradient(135deg, #00e0ff, #0077ff);
            color: white;
            box-shadow: 0 8px 20px rgba(0, 200, 255, 0.2);
            border: 1px solid rgba(0, 200, 255, 0.4);
        }

        .btn-simpan:hover {
            background: linear-gradient(135deg, #00ffff, #00d4ff);
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0, 200, 255, 0.4);
            border-color: rgba(0, 200, 255, 0.7);
        }

        .btn-simpan:active {
            transform: translateY(-1px) scale(0.98);
        }

        .btn-back {
            background: rgba(255, 100, 100, 0.15);
            color: #ff9999;
            border: 1.5px solid rgba(255, 100, 100, 0.4);
            box-shadow: 0 8px 20px rgba(255, 100, 100, 0.1);
        }

        .btn-back:hover {
            background: rgba(255, 100, 100, 0.25);
            border-color: rgba(255, 100, 100, 0.7);
            color: #ffbbbb;
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 15px 40px rgba(255, 100, 100, 0.3);
        }

        .btn-back:active {
            transform: translateY(-1px) scale(0.98);
        }

        @media (max-width: 1024px) {
            .main {
                margin: 80px 20px 30px;
                padding: 25px;
            }

            h1 {
                font-size: 2em;
            }

            .form-section {
                padding: 25px;
            }

            .section-title {
                font-size: 20px;
            }
        }

        @media (max-width: 768px) {
            .main {
                margin: 75px 15px 30px;
                padding: 20px;
            }

            header {
                flex-wrap: wrap;
                gap: 8px;
                padding: 10px 12px;
                justify-content: center;
                align-items: stretch;
            }

            .header-left {
                display: flex;
                align-items: center;
            }

            .logo-container {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .logo-container img {
                width: 32px;
                height: 32px;
            }

            .nav-links {
                display: flex;
                flex-direction: row;
                gap: 4px;
                align-items: center;
                flex-wrap: wrap;
                justify-content: center;
            }

            h1 {
                font-size: 1.5em;
                margin-bottom: 20px;
            }

            .form-section {
                padding: 18px;
                margin-bottom: 20px;
                border-radius: 12px;
            }

            .section-title {
                font-size: 18px;
                gap: 8px;
                margin-bottom: 16px;
                padding-bottom: 10px;
            }

            .section-title i {
                font-size: 20px;
            }

            .form-group {
                margin-bottom: 16px;
            }

            .form-group label {
                font-size: 13px;
                margin-bottom: 8px;
            }

            .button-group {
                flex-direction: column;
                gap: 12px;
            }

            button {
                padding: 12px 18px;
                font-size: 15px;
                width: 100%;
            }

            header h2 {
                font-size: 1.1rem;
            }

            .ql-editor {
                min-height: 180px !important;
            }

            input[type="text"],
            textarea,
            select {
                font-size: 16px;
                padding: 10px 12px;
            }
        }

        @media (max-width: 480px) {
            body {
                font-size: 14px;
            }

            .main {
                margin: 70px 10px 30px;
                padding: 12px;
            }

            header {
                padding: 8px 10px;
                min-height: auto;
                gap: 6px;
            }

            .header-left {
                display: flex;
                align-items: center;
            }

            .logo-container {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .logo-container img {
                width: 30px;
                height: 30px;
            }

            .nav-links {
                display: flex;
                flex-direction: row;
                gap: 4px;
                align-items: center;
                flex-wrap: wrap;
            }

            .nav-links a {
                padding: 6px 10px;
                font-size: 12px;
                gap: 3px;
                white-space: nowrap;
            }

            h1 {
                font-size: 1.2em;
                margin-bottom: 15px;
            }

            .form-section {
                padding: 12px 14px;
                margin-bottom: 15px;
                border-radius: 10px;
                border: 1px solid rgba(0, 200, 255, 0.15);
            }

            .section-title {
                font-size: 14px;
                gap: 8px;
                margin-bottom: 12px;
                padding-bottom: 8px;
                border-bottom: 2px solid rgba(0, 200, 255, 0.2);
            }

            .section-title i {
                font-size: 16px;
                min-width: 16px;
            }

            .form-group {
                margin-bottom: 12px;
            }

            .form-group label {
                font-size: 12px;
                margin-bottom: 6px;
            }

            .button-group {
                flex-direction: column;
                gap: 10px;
            }

            button {
                padding: 10px 12px;
                font-size: 13px;
                width: 100%;
                border-radius: 6px;
            }

            input[type="text"],
            textarea,
            select {
                font-size: 16px;
                padding: 8px 10px;
                border-radius: 6px;
            }

            .ql-editor {
                min-height: 150px !important;
                font-size: 14px;
            }

            header h2 {
                font-size: 0.9rem;
            }

            .nav-links a {
                padding: 6px 10px;
                font-size: 12px;
            }
        }

        /* Extra small screens */
        @media (max-width: 360px) {
            header {
                padding: 6px 8px;
                gap: 5px;
            }

            .logo-container {
                gap: 6px;
            }

            .logo-container img {
                width: 28px;
                height: 28px;
            }

            header h2 {
                font-size: 0.85rem;
            }

            .nav-links {
                gap: 3px;
            }

            .nav-links a {
                padding: 4px 8px;
                font-size: 11px;
                gap: 2px;
            }

            .form-section {
                padding: 10px 12px;
                margin-bottom: 12px;
            }

            .section-title {
                font-size: 13px;
                margin-bottom: 10px;
            }

            .section-title i {
                font-size: 14px;
            }

            h1 {
                font-size: 1em;
                margin-bottom: 12px;
            }

            .form-group label {
                font-size: 11px;
            }

            input[type="text"],
            textarea {
                padding: 6px 8px;
                font-size: 14px;
            }
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            transform: scale(0.9);
            pointer-events: none;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .modal-overlay.show {
            opacity: 1;
            transform: scale(1);
            pointer-events: all;
        }

        .modal-content {
            background: rgba(0, 20, 40, 0.95);
            border-radius: 20px;
            padding: 50px 40px;
            max-width: 450px;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(0, 200, 255, 0.3);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            animation: popIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: white;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        }

        .modal-close:hover {
            background: linear-gradient(135deg, #ec7063, #d63031);
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(231, 76, 60, 0.5);
        }

        .modal-close:active {
            transform: scale(0.95);
        }

        @keyframes popIn {
            0% {
                opacity: 0;
                transform: scale(0.7);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: bounce 0.6s ease-out;
        }

        @keyframes bounce {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }

        .modal-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
            color: white;
        }

        .modal-message {
            font-size: 16px;
            color: #b3eaff;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .modal-content.success {
            border-color: rgba(46, 204, 113, 0.5);
            box-shadow: 0 20px 60px rgba(46, 204, 113, 0.2);
        }

        .modal-content.success .modal-title {
            color: #2ecc71;
        }

        .modal-content.error {
            border-color: rgba(231, 76, 60, 0.5);
            box-shadow: 0 20px 60px rgba(231, 76, 60, 0.2);
        }

        .modal-content.error .modal-title {
            color: #e74c3c;
        }

        .modal-button {
            background: linear-gradient(135deg, #00e0ff, #0077ff);
            color: white;
            border: none;
            padding: 14px 40px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            flex: 1;
        }

        .modal-button-group {
            display: flex;
            gap: 12px;
            width: 100%;
        }

        .modal-button:hover {
            background: linear-gradient(135deg, #00ffff, #00d4ff);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 200, 255, 0.4);
        }

        .modal-content.success .modal-button {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
        }

        .modal-content.success .modal-button:hover {
            background: linear-gradient(135deg, #3de680, #2fbd6e);
            box-shadow: 0 10px 30px rgba(46, 204, 113, 0.4);
        }

        .modal-button:active {
            transform: translateY(0);
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 200, 255, 0.05);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(0, 200, 255, 0.4);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 200, 255, 0.6);
        }
    </style>
</head>
<body>
    <div class="light"></div>
    <div class="light"></div>

    <header>
        <div class="header-left">
            <div class="logo-container">
                <img src="OSIS.ico" alt="Logo OSIS">
                <h2><i class="ri-edit-line"></i> Edit Konten</h2>
            </div>
        </div>
        <div class="nav-links">
            <a href="dashboard.php"><i class="ri-dashboard-line"></i> Dashboard</a>
            <a href="feedback.php"><i class="ri-chat-3-line"></i> Feedback</a>
            <a href="logout.php" class="logout-btn"><i class="ri-logout-box-line"></i> Logout</a>
        </div>
    </header>

    <div class="main">
        <h1>📝 Edit Konten Website</h1>
        
        <form id="editForm" method="POST">
            <!-- Tentang OSIS -->
            <div class="form-section">
                <div class="section-title">
                    <i class="ri-information-line"></i>
                    Tentang OSIS Astamayana
                </div>

                <div class="form-group">
                    <label for="judul_tentang">Judul Tentang</label>
                    <input type="text" id="judul_tentang" name="judul_tentang" 
                           value="<?php echo htmlspecialchars($row['judul_tentang'] ?? 'Tentang OSIS Astamayana'); ?>" 
                           placeholder="Masukkan judul section Tentang">
                    <div class="help-text">💡 Judul yang akan ditampilkan di halaman utama</div>
                </div>

                <div class="form-group">
                    <label for="isi_tentang">Deskripsi Tentang</label>
                    <div class="quill-editor-container">
                        <div id="editor-tentang" style="height: 250px;"></div>
                    </div>
                    <textarea id="isi_tentang" name="isi_tentang" style="display: none;"><?php echo htmlspecialchars($row['isi_tentang'] ?? ''); ?></textarea>
                    <div class="help-text">💡 Jelaskan visi dan misi OSIS dengan detail</div>
                </div>
            </div>

            <!-- Kegiatan OSIS -->
            <div class="form-section">
                <div class="section-title">
                    <i class="ri-calendar-event-line"></i>
                    Kegiatan OSIS
                </div>

                <div class="form-group">
                    <label for="judul_kegiatan">Judul Kegiatan</label>
                    <input type="text" id="judul_kegiatan" name="judul_kegiatan" 
                           value="<?php echo htmlspecialchars($row['judul_kegiatan'] ?? 'Kegiatan OSIS'); ?>" 
                           placeholder="Masukkan judul section kegiatan">
                    <div class="help-text">💡 Judul untuk section kegiatan</div>
                </div>

                <div class="form-group">
                    <label for="isi_kegiatan">Deskripsi Kegiatan</label>
                    <div class="quill-editor-container">
                        <div id="editor-kegiatan" style="height: 250px;"></div>
                    </div>
                    <textarea id="isi_kegiatan" name="isi_kegiatan" style="display: none;"><?php echo htmlspecialchars($row['isi_kegiatan'] ?? ''); ?></textarea>
                    <div class="help-text">💡 Deskripsikan kegiatan-kegiatan yang akan dilaksanakan</div>
                </div>
            </div>

            <!-- Media Sosial -->
            <div class="form-section">
                <div class="section-title">
                    <i class="ri-share-forward-line"></i>
                    Media Sosial
                </div>

                <div class="form-group">
                    <label for="instagram">Link Instagram</label>
                    <input type="url" id="instagram" name="instagram" 
                           value="<?php echo htmlspecialchars($row['instagram'] ?? ''); ?>" 
                           placeholder="https://www.instagram.com/...">
                    <div class="help-text">💡 Paste link Instagram OSIS</div>
                </div>

                <div class="form-group">
                    <label for="tiktok">Link TikTok</label>
                    <input type="url" id="tiktok" name="tiktok" 
                           value="<?php echo htmlspecialchars($row['tiktok'] ?? ''); ?>" 
                           placeholder="https://www.tiktok.com/@...">
                    <div class="help-text">💡 Paste link TikTok OSIS</div>
                </div>
            </div>

            <!-- Galeri Management -->
            <div class="gallery-section">
                <div class="section-title">
                    <i class="ri-image-add-line"></i>
                    Kelola Galeri Kegiatan
                </div>

                <div class="gallery-upload">
                    <input type="file" id="fotoInput" accept="image/*" placeholder="Pilih foto">
                    <input type="text" id="judulGaleri" placeholder="Judul foto">
                    <textarea id="deskripsiGaleri" placeholder="Deskripsi foto (opsional)"></textarea>
                    <button type="button" class="btn-upload-galeri" onclick="uploadGaleri()">
                        <i class="ri-upload-cloud-2-line"></i> Upload Foto
                    </button>
                </div>

                <div id="galleryContainer" class="gallery-grid">
                    <!-- Galeri akan ditampilkan di sini via JavaScript -->
                </div>
            </div>

            <!-- Struktur Organisasi Management -->
            <div class="gallery-section">
                <div class="section-title">
                    <i class="ri-organization-chart-line"></i>
                    Kelola Struktur Organisasi
                </div>

                <div class="gallery-upload">
                    <h4 style="color:#00e0ff; margin-bottom:15px;">📋 Pilih Divisi/Kategori untuk Diedit</h4>
                    <select id="pilihDivisi" onchange="loadDivisiData()" style="padding:10px; border-radius:8px; border:none; margin-bottom:15px; width:100%; background:white; color:black; font-size:1em; font-weight:500;">
                        <option value="" style="color:black;">-- Pilih Divisi --</option>
                    </select>
                </div>

                <div id="strukturContainer" style="display:none;">
                    <div class="gallery-upload">
                        <h4 style="color:#00e0ff; margin-bottom:15px;">➕ Tambah Anggota Baru</h4>
                        <div style="display:flex; gap:10px; margin-bottom:10px;">
                            <input type="text" id="namaStrutur" placeholder="Nama lengkap" style="flex:1; padding:10px; border-radius:8px; border:none; background:rgba(255,255,255,0.1); color:white;">
                            <select id="posisiStrutur" style="flex:0.5; padding:10px; border-radius:8px; border:none; background:white; color:black; font-size:1em; font-weight:500;">
                                <option value="" style="color:black;">-- Pilih Posisi --</option>
                                <option value="Ketua" style="color:black;">Ketua</option>
                                <option value="Anggota" style="color:black;">Anggota</option>
                            </select>
                        </div>
                        <button type="button" class="btn-upload-galeri" onclick="addStrutur()">
                            <i class="ri-add-circle-line"></i> Tambah Anggota
                        </button>
                    </div>

                    <div style="margin-top:20px;">
                        <h4 style="color:#00e0ff; margin-bottom:15px;">👥 Daftar Anggota</h4>
                        <div id="anggotaList" style="background:rgba(0,50,100,0.4); padding:15px; border-radius:10px;">
                            <!-- Anggota akan ditampilkan di sini -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Button Actions -->
            <div class="button-group">
                <button type="submit" name="simpan" class="btn-simpan">
                    <i class="ri-save-3-line"></i>
                    Simpan Perubahan
                </button>
                <button type="button" class="btn-back" onclick="window.location.href='dashboard.php'">
                    <i class="ri-arrow-left-line"></i>
                    Kembali ke Dashboard
                </button>
            </div>
        </form>
    </div>

    <!-- Modal Popup -->
    <div id="modal" class="modal-overlay">
        <div id="modalContent" class="modal-content">
            <button class="modal-close" id="modalCloseBtn">
                <i class="ri-close-line"></i>
            </button>
            <div class="modal-icon" id="modalIcon"></div>
            <h2 class="modal-title" id="modalTitle"></h2>
            <p class="modal-message" id="modalMessage"></p>
            <div class="modal-button-group" id="modalButtonGroup">
                <button class="modal-button" id="modalButton">Tutup</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        const editorTentang = new Quill('#editor-tentang', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'header': [1, 2, 3, false] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['link', 'image'],
                    ['clean']
                ]
            },
            placeholder: 'Tulis deskripsi tentang OSIS di sini...'
        });

        const editorKegiatan = new Quill('#editor-kegiatan', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'header': [1, 2, 3, false] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['link', 'image'],
                    ['clean']
                ]
            },
            placeholder: 'Tulis deskripsi kegiatan OSIS di sini...'
        });

        window.addEventListener('load', function() {
            const isiTentang = document.getElementById('isi_tentang').value;
            const isiKegiatan = document.getElementById('isi_kegiatan').value;
            
            if (isiTentang && isiTentang !== '') {
                editorTentang.root.innerHTML = isiTentang;
            }
            if (isiKegiatan && isiKegiatan !== '') {
                editorKegiatan.root.innerHTML = isiKegiatan;
            }
        });

        document.getElementById('modalCloseBtn').addEventListener('click', function() {
            const modal = document.getElementById('modal');
            const btn = document.querySelector('.btn-simpan');
            
            modal.classList.remove('show');
            
            if (btn) {
                btn.innerHTML = '<i class="ri-save-3-line"></i> Simpan Perubahan';
                btn.disabled = false;
            }
        });

        function showModal(status, message, detail = null) {
            const modal = document.getElementById('modal');
            const modalContent = document.getElementById('modalContent');
            const modalIcon = document.getElementById('modalIcon');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const modalButtonGroup = document.getElementById('modalButtonGroup');

            if (status === 'success') {
                modalIcon.textContent = '✅';
                modalTitle.textContent = 'Berhasil!';
                modalMessage.innerHTML = '<strong>Konten berhasil diperbarui ke database!</strong><br><br>Data akan ditampilkan di halaman utama.';
                modalContent.classList.remove('error');
                modalContent.classList.add('success');
                
                modalButtonGroup.innerHTML = `
                    <button class="modal-button" onclick="window.location.href='index.php'">
                        <i class="ri-arrow-right-line"></i> Lanjutkan ke Halaman Utama
                    </button>
                `;
            } else {
                modalIcon.textContent = '❌';
                modalTitle.textContent = 'Gagal Menyimpan!';
                
                let errorHTML = '<strong style="color: #ff6b6b;">' + (message || 'Terjadi kesalahan!') + '</strong>';
                
                if (detail) {
                    errorHTML += '<br><br><div style="background: rgba(0,0,0,0.3); padding: 12px; border-radius: 8px; text-align: left; font-size: 13px; color: #ffcccc; border-left: 3px solid #e74c3c;">';
                    errorHTML += '<strong>Detail Error:</strong><br>';
                    errorHTML += detail;
                    errorHTML += '</div>';
                }
                
                modalMessage.innerHTML = errorHTML;
                modalContent.classList.remove('success');
                modalContent.classList.add('error');
                
                modalButtonGroup.innerHTML = `
                    <button class="modal-button" onclick="document.getElementById('modal').classList.remove('show'); const btn = document.querySelector('.btn-simpan'); btn.innerHTML = '<i class=\"ri-save-3-line\"></i> Simpan Perubahan'; btn.disabled = false;">
                        <i class="ri-refresh-line"></i> Coba Lagi
                    </button>
                `;
            }

            modal.classList.add('show');
        }

        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const isiTentangValue = editorTentang.root.innerHTML;
            const isiKegiatanValue = editorKegiatan.root.innerHTML;
            
            document.getElementById('isi_tentang').value = isiTentangValue;
            document.getElementById('isi_kegiatan').value = isiKegiatanValue;

            // Cek apakah ada field yang berisi data
            const judulTentang = document.getElementById('judul_tentang').value.trim();
            const judulKegiatan = document.getElementById('judul_kegiatan').value.trim();
            
            // Minimal ada satu field yang harus diisi
            if (!judulTentang && !isiTentangValue.trim() && !judulKegiatan && !isiKegiatanValue.trim()) {
                alert('❌ Minimal isi salah satu field sebelum menyimpan!');
                return;
            }

            const btn = document.querySelector('.btn-simpan');
            btn.innerHTML = '<i class="ri-loader-4-line" style="animation: spin 1s linear infinite;"></i> Menyimpan...';
            btn.disabled = true;

            const formData = new FormData(this);

            fetch("update-konten.php", {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showModal('success', data.message);
                } else {
                    let errorDetail = data.detail || '';
                    if (data.query_error) {
                        errorDetail += (errorDetail ? '<br>' : '') + '(Error Code: ' + data.query_error + ')';
                    }
                    showModal('error', data.message, errorDetail);
                    btn.innerHTML = '<i class="ri-save-3-line"></i> Simpan Perubahan';
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showModal('error', 'Gagal menghubungi server!', error.message);
                btn.innerHTML = '<i class="ri-save-3-line"></i> Simpan Perubahan';
                btn.disabled = false;
            });
        });

        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);

        // ===== GALLERY MANAGEMENT =====
        // Load gallery on page load
        loadGalleryItems();

        function loadGalleryItems() {
            fetch('get-galeri.php')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('galleryContainer');
                    container.innerHTML = '';

                    if (data.items && data.items.length > 0) {
                        data.items.forEach(item => {
                            const galleryItem = document.createElement('div');
                            galleryItem.className = 'gallery-item';
                            galleryItem.innerHTML = `
                                <img src="data:${item.tipe_file};base64,${item.foto}" alt="${item.judul}">
                                <div class="gallery-item-info">
                                    <div class="gallery-item-title">${item.judul}</div>
                                    <div class="gallery-item-desc">${item.deskripsi || 'Tidak ada deskripsi'}</div>
                                    <div class="gallery-item-actions">
                                        <button type="button" class="btn-delete-galeri" onclick="deleteGaleri(${item.id})">
                                            <i class="ri-delete-bin-line"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            `;
                            container.appendChild(galleryItem);
                        });
                    } else {
                        container.innerHTML = `
                            <div class="empty-gallery" style="grid-column: 1/-1;">
                                <i class="ri-gallery-line"></i>
                                <p>📁 Galeri kosong. Silakan upload foto pertama Anda!</p>
                            </div>
                        `;
                    }
                })
                .catch(error => console.error('Error loading gallery:', error));
        }

        function uploadGaleri() {
            const fileInput = document.getElementById('fotoInput');
            const judul = document.getElementById('judulGaleri').value.trim();
            const deskripsi = document.getElementById('deskripsiGaleri').value.trim();
            const file = fileInput.files[0];

            if (!file) {
                alert('❌ Pilih foto terlebih dahulu!');
                return;
            }

            if (!judul) {
                alert('❌ Masukkan judul foto!');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const fotoBase64 = e.target.result.split(',')[1];
                const tipeFile = file.type;
                const ukuranFile = file.size;

                const btn = document.querySelector('.btn-upload-galeri');
                btn.innerHTML = '<i class="ri-loader-4-line" style="animation: spin 1s linear infinite;"></i> Uploading...';
                btn.disabled = true;

                const formData = new FormData();
                formData.append('judul', judul);
                formData.append('deskripsi', deskripsi);
                formData.append('foto', fotoBase64);
                formData.append('tipe_file', tipeFile);
                formData.append('ukuran_file', ukuranFile);

                fetch('upload-galeri.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showModal('success', '✅ Foto berhasil diupload!');
                        fileInput.value = '';
                        document.getElementById('judulGaleri').value = '';
                        document.getElementById('deskripsiGaleri').value = '';
                        loadGalleryItems();
                    } else {
                        showModal('error', '❌ Gagal upload foto!', data.message);
                    }
                    btn.innerHTML = '<i class="ri-upload-cloud-2-line"></i> Upload Foto';
                    btn.disabled = false;
                })
                .catch(error => {
                    showModal('error', '❌ Error upload!', error.message);
                    btn.innerHTML = '<i class="ri-upload-cloud-2-line"></i> Upload Foto';
                    btn.disabled = false;
                });
            };
            reader.readAsDataURL(file);
        }

        function deleteGaleri(id) {
            if (!confirm('Yakin hapus foto ini?')) return;

            fetch('delete-galeri.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + id
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showModal('success', '✅ Foto berhasil dihapus!');
                    loadGalleryItems();
                } else {
                    showModal('error', '❌ Gagal hapus foto!', data.message);
                }
            })
            .catch(error => showModal('error', '❌ Error!', error.message));
        }

        // Struktur Organisasi Functions
        let currentDivisi = '';

        function loadDivisiOptions() {
            fetch('api-struktur.php?action=get&tipe=semua')
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('pilihDivisi');
                const grouped = {};
                
                if (data.items) {
                    data.items.forEach(item => {
                        const key = item.tipe + '|' + item.kategori;
                        if (!grouped[key]) {
                            grouped[key] = [];
                        }
                        grouped[key].push(item);
                    });
                }
                
                // Add optgroup for each tipe
                const types = ['pengurus', 'divisi'];
                types.forEach(tipe => {
                    const optgroup = document.createElement('optgroup');
                    optgroup.label = tipe.toUpperCase();
                    
                    Object.entries(grouped).forEach(([key, items]) => {
                        const [type, cat] = key.split('|');
                        if (type === tipe) {
                            const option = document.createElement('option');
                            option.value = key;
                            option.textContent = cat + ' (' + items.length + ' orang)';
                            option.style.color = 'black';
                            optgroup.appendChild(option);
                        }
                    });
                    select.appendChild(optgroup);
                });
            })
            .catch(error => console.error('Error:', error));
        }

        function loadDivisiData() {
            const key = document.getElementById('pilihDivisi').value;
            if (!key) {
                document.getElementById('strukturContainer').style.display = 'none';
                return;
            }
            
            const [tipe, kategori] = key.split('|');
            currentDivisi = kategori;
            
            fetch('api-struktur.php?action=get&tipe=' + tipe)
            .then(response => response.json())
            .then(data => {
                const anggotaList = document.getElementById('anggotaList');
                anggotaList.innerHTML = '';
                
                const filtered = data.items.filter(item => item.kategori === kategori);
                
                if (filtered.length > 0) {
                    filtered.forEach(item => {
                        const itemDiv = document.createElement('div');
                        itemDiv.style.cssText = 'background:rgba(0,100,200,0.3); padding:12px; margin-bottom:10px; border-radius:8px; display:grid; grid-template-columns:1fr auto auto; gap:10px; align-items:center; word-break:break-word;';
                        itemDiv.innerHTML = `
                            <div style="display:flex; flex-direction:column; gap:6px; min-width:0;">
                                <input type="text" class="nama-${item.id}" value="${item.nama}" placeholder="Nama" style="width:100%; padding:8px; background:rgba(255,255,255,0.1); border:1px solid rgba(0,200,255,0.3); border-radius:5px; color:white; margin:0; word-break:break-word;">
                                <input type="text" class="posisi-${item.id}" value="${item.posisi}" placeholder="Posisi" style="width:100%; padding:8px; background:rgba(255,255,255,0.1); border:1px solid rgba(0,200,255,0.3); border-radius:5px; color:white; margin:0; word-break:break-word;">
                            </div>
                            <button type="button" style="padding:8px 10px; background:#00e0ff; border:none; border-radius:5px; color:black; cursor:pointer; font-weight:600; white-space:nowrap; min-width:fit-content;" onclick="updateStrutur(${item.id})">Simpan</button>
                            <button type="button" style="padding:8px 10px; background:#ff6b6b; border:none; border-radius:5px; color:white; cursor:pointer; white-space:nowrap; min-width:fit-content;" onclick="deleteStrutur(${item.id})">Hapus</button>
                        `;
                        anggotaList.appendChild(itemDiv);
                    });
                } else {
                    anggotaList.innerHTML = '<p style="color:#888; text-align:center; padding:20px;">Tidak ada anggota di kategori ini</p>';
                }
                
                document.getElementById('strukturContainer').style.display = 'block';
            })
            .catch(error => console.error('Error:', error));
        }

        function addStrutur() {
            if (!currentDivisi) {
                showModal('error', '❌ Pilih divisi terlebih dahulu!');
                return;
            }
            
            const nama = document.getElementById('namaStrutur').value.trim();
            const posisi = document.getElementById('posisiStrutur').value.trim();

            if (!nama || !posisi) {
                showModal('error', '❌ Nama dan posisi harus diisi!');
                return;
            }

            const btn = event.target;
            btn.innerHTML = '<i class="ri-loader-4-line" style="animation: spin 1s linear infinite;"></i> Loading...';
            btn.disabled = true;

            const formData = new FormData();
            formData.append('action', 'add');
            formData.append('tipe', 'divisi');
            formData.append('kategori', currentDivisi);
            formData.append('nama', nama);
            formData.append('posisi', posisi);

            fetch('api-struktur.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showModal('success', '✅ Anggota berhasil ditambah!');
                    document.getElementById('namaStrutur').value = '';
                    document.getElementById('posisiStrutur').value = '';
                    loadDivisiData();
                } else {
                    showModal('error', '❌ Gagal tambah anggota!', data.message);
                }
                btn.innerHTML = '<i class="ri-add-circle-line"></i> Tambah Anggota';
                btn.disabled = false;
            })
            .catch(error => {
                showModal('error', '❌ Error!', error.message);
                btn.innerHTML = '<i class="ri-add-circle-line"></i> Tambah Anggota';
                btn.disabled = false;
            });
        }

        function updateStrutur(id) {
            const nama = document.querySelector('.nama-' + id).value.trim();
            const posisi = document.querySelector('.posisi-' + id).value.trim();

            if (!nama || !posisi) {
                showModal('error', '❌ Nama dan posisi harus diisi!');
                return;
            }

            const formData = new FormData();
            formData.append('action', 'update');
            formData.append('id', id);
            formData.append('nama', nama);
            formData.append('posisi', posisi);

            fetch('api-struktur.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showModal('success', '✅ Data berhasil diupdate!');
                } else {
                    showModal('error', '❌ Gagal update!', data.message);
                }
            })
            .catch(error => showModal('error', '❌ Error!', error.message));
        }

        function deleteStrutur(id) {
            if (!confirm('Yakin hapus anggota ini?')) return;

            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id);

            fetch('api-struktur.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showModal('success', '✅ Anggota berhasil dihapus!');
                    loadDivisiData();
                } else {
                    showModal('error', '❌ Gagal hapus!', data.message);
                }
            })
            .catch(error => showModal('error', '❌ Error!', error.message));
        }

        // Load struktur on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadGalleryItems();
            loadDivisiOptions();
        });
    </script>
</body>
</html>