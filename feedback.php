<?php
require 'auth-check.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - OSIS Astamayana</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
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
            min-height: 100vh;
            padding-bottom: 50px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 80px 30px 50px;
        }

        header {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(0, 15, 30, 0.96);
            backdrop-filter: blur(12px);
            padding: 12px 30px;
            z-index: 100;
            border-bottom: 1px solid rgba(0, 200, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        header h2 {
            color: #00e0ff;
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 0 2px 10px rgba(0, 200, 255, 0.3);
            transition: all 0.3s ease;
        }

        header h2:hover {
            color: #00ffff;
            text-shadow: 0 2px 15px rgba(0, 255, 255, 0.6);
            transform: translateY(-2px);
        }

        .nav-links {
            display: flex;
            gap: 20px;
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
            position: relative;
            overflow: hidden;
        }

        .nav-links a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(0, 200, 255, 0.1);
            transition: left 0.3s ease;
            z-index: -1;
        }

        .nav-links a:hover::before {
            left: 0;
        }

        .nav-links a:hover {
            color: #00ffff;
            transform: translateY(-2px);
        }

        .logout-btn {
            background: linear-gradient(135deg, #e74c3c, #c0392b) !important;
            color: white !important;
            padding: 8px 16px !important;
            position: relative;
            overflow: hidden;
        }

        .logout-btn::before {
            background: rgba(255, 255, 255, 0.1) !important;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #e85e50, #d43f2f) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
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
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 40px;
            justify-content: center;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .search-box input {
            padding: 12px 20px;
            border-radius: 8px;
            border: 2px solid rgba(0, 200, 255, 0.25);
            background: rgba(255, 255, 255, 0.05);
            color: white;
            width: 300px;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            backdrop-filter: blur(5px);
        }

        .search-box input::placeholder {
            color: rgba(224, 247, 255, 0.5);
        }

        .search-box input:focus {
            outline: none;
            border-color: rgba(0, 200, 255, 0.8);
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 20px rgba(0, 200, 255, 0.3), inset 0 0 15px rgba(0, 200, 255, 0.05);
            transform: translateY(-2px);
        }

        .search-box button {
            padding: 12px 30px;
            background: linear-gradient(135deg, #00e0ff, #0077ff);
            border: 2px solid rgba(0, 200, 255, 0.3);
            border-radius: 8px;
            color: white;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .search-box button::before {
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
            z-index: -1;
        }

        .search-box button:hover::before {
            width: 300px;
            height: 300px;
        }

        .search-box button:hover {
            background: linear-gradient(135deg, #00ffff, #00e0ff);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 200, 255, 0.4);
        }

        .feedback-grid {
            display: grid;
            gap: 20px;
            margin-bottom: 30px;
            animation: fadeInUp 0.8s ease-out 0.3s both;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .feedback-card {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(0, 200, 255, 0.25);
            border-radius: 12px;
            padding: 25px;
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            animation: slideIn 0.5s ease-out forwards;
            position: relative;
            overflow: hidden;
        }

        .feedback-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 200, 255, 0.1), transparent);
            transition: left 0.3s ease;
            z-index: 0;
        }

        .feedback-card:hover::before {
            left: 100%;
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

        .feedback-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 200, 255, 0.25);
            border-color: rgba(0, 200, 255, 0.6);
            background: rgba(255, 255, 255, 0.09);
        }

        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .feedback-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            color: #9be8ff;
            transition: all 0.3s ease;
        }

        .feedback-card:hover .feedback-meta {
            color: #00ffff;
        }

        .feedback-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00e0ff, #0077ff);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1em;
            box-shadow: 0 4px 15px rgba(0, 200, 255, 0.3);
            transition: all 0.3s ease;
        }

        .feedback-card:hover .feedback-avatar {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 6px 20px rgba(0, 200, 255, 0.5);
        }

        .feedback-info {
            display: flex;
            flex-direction: column;
        }

        .feedback-time {
            font-size: 0.85em;
            color: #7f8c8d;
            transition: color 0.3s ease;
        }

        .feedback-card:hover .feedback-time {
            color: #9be8ff;
        }

        .delete-btn {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            border: 2px solid rgba(231, 76, 60, 0.3);
            color: white;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85em;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .delete-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.5s, height 0.5s;
            z-index: -1;
        }

        .delete-btn:hover::before {
            width: 200px;
            height: 200px;
        }

        .delete-btn:hover {
            background: linear-gradient(135deg, #e85e50, #d43f2f);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(231, 76, 60, 0.4);
        }
            align-items: center;
            gap: 6px;
        }

        .delete-btn:hover {
            background: #c0392b;
            transform: scale(1.05);
        }

        .delete-btn:active {
            transform: scale(0.95);
        }

        .feedback-text {
            color: #e0f7ff;
            line-height: 1.6;
            font-size: 1em;
            word-wrap: break-word;
        }

        .no-feedback {
            text-align: center;
            color: #7f8c8d;
            padding: 60px 20px;
            font-size: 1.1em;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 40px;
        }

        .pagination button {
            padding: 10px 15px;
            background: rgba(0, 200, 255, 0.2);
            border: 2px solid rgba(0, 200, 255, 0.3);
            color: #00e0ff;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }

        .pagination button:hover:not(:disabled) {
            background: rgba(0, 200, 255, 0.4);
            border-color: rgba(0, 200, 255, 0.6);
            transform: translateY(-2px);
        }

        .pagination button:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .pagination button.active {
            background: linear-gradient(135deg, #00e0ff, #0077ff);
            border-color: rgba(0, 200, 255, 0.6);
        }

        .stats {
            display: flex;
            gap: 30px;
            margin-bottom: 40px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .stat-card {
            background: rgba(0, 200, 255, 0.1);
            border: 2px solid rgba(0, 200, 255, 0.25);
            border-radius: 10px;
            padding: 20px 30px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: rgba(0, 200, 255, 0.5);
            background: rgba(0, 200, 255, 0.15);
        }

        .stat-card h3 {
            color: #00e0ff;
            font-size: 1.5em;
            margin-bottom: 5px;
        }

        .stat-card p {
            color: #9be8ff;
            font-size: 0.9em;
        }

        .stat-card {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .stat-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stat-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .export-btn {
            background: linear-gradient(135deg, #27ae60, #229954);
            border: 2px solid rgba(39, 174, 96, 0.3);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: fadeInUp 0.6s ease-out 0.4s backwards;
        }

        .export-btn:hover {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(46, 204, 113, 0.3);
        }

        .loading-spinner {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            gap: 20px;
            animation: fadeIn 0.5s ease-out;
        }

        .spinner-icon {
            font-size: 3em;
            color: #00e0ff;
            animation: spin 2s linear infinite !important;
            text-shadow: 0 0 20px rgba(0, 224, 255, 0.6);
        }

        .loading-spinner p {
            color: #9be8ff;
            font-size: 1.05em;
            letter-spacing: 1px;
            animation: pulse 2s ease-in-out infinite;
        }

        @media (max-width: 1024px) {
            .container {
                padding: 80px 20px 50px;
            }

            h1 {
                font-size: 2em;
                margin-bottom: 30px;
            }

            .feedback-grid {
                gap: 20px;
            }

            .feedback-card {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 70px 15px 50px;
            }

            header {
                flex-wrap: wrap;
                gap: 10px;
                padding: 10px 15px;
            }

            .nav-links {
                flex-direction: column;
                gap: 5px;
                width: 100%;
            }

            h1 {
                font-size: 1.5em;
                margin-bottom: 20px;
            }

            .search-box {
                flex-direction: column;
                gap: 10px;
            }

            .search-box input {
                width: 100%;
                padding: 10px 12px;
                font-size: 14px;
            }

            .feedback-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .feedback-card {
                padding: 15px;
            }

            .stats {
                flex-direction: column;
                gap: 15px;
            }

            .stat-card {
                min-width: 100%;
                padding: 15px;
            }

            header h2 {
                font-size: 1.1rem;
            }

            .pagination-buttons button {
                padding: 10px 12px;
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            body {
                font-size: 13px;
            }

            .container {
                padding: 65px 10px 40px;
            }

            header {
                padding: 8px 10px;
            }

            h1 {
                font-size: 1.2em;
                margin-bottom: 15px;
            }

            .search-box {
                flex-direction: column;
                gap: 8px;
            }

            .search-box input {
                width: 100%;
                padding: 8px 10px;
                font-size: 13px;
                border-radius: 6px;
            }

            .feedback-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .feedback-card {
                padding: 12px;
                border-radius: 10px;
            }

            .feedback-card h3 {
                font-size: 14px;
            }

            .feedback-card p {
                font-size: 12px;
                line-height: 1.4;
            }

            .feedback-card .meta {
                font-size: 11px;
            }

            .stats {
                flex-direction: column;
                gap: 12px;
            }

            .stat-card {
                min-width: 100%;
                padding: 12px;
                border-radius: 10px;
            }

            .stat-card h3 {
                font-size: 13px;
            }

            .stat-card p {
                font-size: 11px;
            }

            .pagination-buttons {
                gap: 5px;
                margin-top: 15px;
            }

            .pagination-buttons button {
                padding: 8px 10px;
                font-size: 12px;
                border-radius: 6px;
            }

            header h2 {
                font-size: 0.9rem;
            }

            .nav-links a {
                padding: 6px 10px;
                font-size: 12px;
            }
        }
    </style>

    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

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

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.6;
            }
        }

        @keyframes glow {
            0%, 100% {
                box-shadow: 0 0 10px rgba(0, 224, 255, 0.5);
            }
            50% {
                box-shadow: 0 0 20px rgba(0, 224, 255, 0.8);
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

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        header {
            animation: slideInDown 0.6s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            animation: fadeInUp 0.8s ease-out;
        }

        .search-box {
            animation: fadeInUp 0.6s ease-out 0.3s backwards;
        }

        .feedback-card {
            animation: scaleIn 0.4s ease-out !important;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .feedback-card:hover {
            animation: glow 1.5s ease-in-out infinite;
        }

        .pagination button {
            animation: fadeIn 0.5s ease-out;
            transition: all 0.3s ease;
        }

        .pagination button:hover:not(:disabled) {
            animation: pulse 1s ease-in-out infinite;
        }

        .no-feedback {
            animation: fadeInUp 0.6s ease-out;
        }

        .feedback-avatar {
            animation: scaleIn 0.4s ease-out;
        }

        .delete-btn {
            transition: all 0.2s ease;
        }

        .delete-btn:hover {
            animation: pulse 0.6s ease-in-out;
        }

        .nav-links a {
            animation: slideInLeft 0.6s ease-out;
        }

        .nav-links a:nth-child(1) {
            animation-delay: 0.1s;
        }

        .nav-links a:nth-child(2) {
            animation-delay: 0.2s;
        }

        .nav-links a:nth-child(3) {
            animation-delay: 0.3s;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <h2><i class="ri-chat-3-line"></i> Feedback</h2>
        </div>
        <div class="nav-links">
            <a href="dashboard.php"><i class="ri-dashboard-line"></i> Dashboard</a>
            <a href="edit-konten.php"><i class="ri-edit-line"></i> Edit Konten</a>
            <a href="logout.php" class="logout-btn"><i class="ri-logout-box-line"></i> Logout</a>
        </div>
    </header>

    <div class="container">
        <h1>💬 Feedback dari Pengunjung</h1>

        <div class="stats">
            <div class="stat-card">
                <h3 id="totalFeedback">0</h3>
                <p>Total Feedback</p>
            </div>
            <div class="stat-card">
                <h3 id="todayFeedback">0</h3>
                <p>Feedback Hari Ini</p>
            </div>
            <div class="stat-card">
                <h3 id="avgLength">0</h3>
                <p>Rata-rata Panjang</p>
            </div>
        </div>

        <div style="display: flex; gap: 15px; justify-content: center; margin-bottom: 30px; flex-wrap: wrap;">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Cari feedback...">
                <button onclick="searchFeedback()">Cari</button>
            </div>
            <button class="export-btn" onclick="exportToCSV()"><i class="ri-download-2-line"></i> Export CSV</button>
        </div>

        <div class="feedback-grid" id="feedbackGrid">
            <div class="loading-spinner">
                <i class="ri-loader-4-line spinner-icon"></i>
                <p>Memuat feedback...</p>
            </div>
        </div>

        <div class="pagination" id="pagination"></div>
    </div>

    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
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
    </style>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/12.5.0/firebase-app.js";
        import { getDatabase, ref, onValue, remove } from "https://www.gstatic.com/firebasejs/12.5.0/firebase-database.js";

        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
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

        let allFeedback = [];
        let currentPage = 1;
        const itemsPerPage = 10;

        try {
            const app = initializeApp(firebaseConfig);
            const db = getDatabase(app);
            console.log("✅ Firebase initialized successfully!");
            console.log("🔗 Firebase DB:", db);
            console.log("🔗 Database URL:", firebaseConfig.databaseURL);

            const feedbackRef = ref(db, "feedback");
            console.log("📌 Listening to Firebase path: /feedback");
            
            onValue(feedbackRef, (snapshot) => {
                console.log("📊 ================================");
                console.log("📊 Feedback snapshot received!");
                console.log("📊 Snapshot exists:", snapshot.exists());
                console.log("📊 ================================");
                
                allFeedback = [];
                
                if (snapshot.exists()) {
                    const data = snapshot.val();
                    console.log("📊 Raw Firebase data:", data);
                    
                    snapshot.forEach((childSnapshot) => {
                        allFeedback.push({
                            id: childSnapshot.key,
                            ...childSnapshot.val()
                        });
                        console.log("✅ Added feedback:", childSnapshot.key, childSnapshot.val());
                    });

                    allFeedback.sort((a, b) => {
                        try {
                            const dateA = new Date(a.timestamp);
                            const dateB = new Date(b.timestamp);
                            return dateB - dateA;
                        } catch (e) {
                            console.log("⚠️ Date parse error:", e);
                            return 0;
                        }
                    });

                    console.log("📈 Total feedback loaded:", allFeedback.length);
                } else {
                    console.log("⚠️ No feedback data available in Firebase");
                }

                // IMPORTANT: Reset currentPage dan update UI immediately
                currentPage = 1;
                console.log("🔄 Calling updateStats...");
                updateStats();
                console.log("🔄 Calling displayFeedback...");
                displayFeedback(""); // Pass empty string for no filter
                console.log("✅ UI Updated successfully!");
                console.log("📌 Current allFeedback length:", allFeedback.length);
            }, (error) => {
                console.error("❌ Firebase error:", error);
                console.error("   Error code:", error.code);
                console.error("   Error message:", error.message);
                document.getElementById("feedbackGrid").innerHTML = `
                    <div class="no-feedback" style="padding: 40px;">
                        <i class="ri-error-warning-line" style="font-size: 3em; color: #e74c3c;"></i>
                        <p style="margin-top: 15px; color: #e74c3c;">Error: ${error.message}</p>
                        <p style="font-size: 0.85em; color: #9be8ff; margin-top: 10px;">Silakan check console untuk detail lebih lanjut</p>
                    </div>
                `;
            });

        } catch (error) {
            console.error("❌ Firebase initialization error:", error);
            console.error("   Error code:", error.code);
            console.error("   Error message:", error.message);
            document.getElementById("feedbackGrid").innerHTML = `
                <div class="no-feedback" style="padding: 40px;">
                    <i class="ri-error-warning-line" style="font-size: 3em; color: #e74c3c;"></i>
                    <p style="margin-top: 15px; color: #e74c3c;">Firebase Error!</p>
                    <p style="font-size: 0.85em; color: #9be8ff; margin-top: 10px;">${error.message}</p>
                </div>
            `;
        }

        function updateStats() {
            try {
                console.log("📊 updateStats called!");
                console.log("📊 allFeedback.length:", allFeedback.length);
                console.log("📊 allFeedback data:", allFeedback);
                
                const totalFeedback = allFeedback.length;
                const today = new Date().toLocaleDateString("id-ID");
                console.log("📅 Today's date (id-ID):", today);
                
                // Detailed logging untuk setiap feedback
                const todayFeedback = allFeedback.filter(f => {
                    const feedbackDate = f.timestamp ? f.timestamp.split(',')[0] : "NO_DATE";
                    const match = feedbackDate === today;
                    console.log(`  ➡️ Feedback: "${feedbackDate}" === "${today}" ? ${match}`);
                    return match;
                }).length;
                
                const totalLength = allFeedback.reduce((sum, f) => {
                    const len = f.text ? f.text.length : 0;
                    return sum + len;
                }, 0);
                
                const avgLength = totalFeedback > 0 
                    ? Math.round(totalLength / totalFeedback)
                    : 0;

                console.log("✅ STATS FINAL RESULT:");
                console.log("  📊 Total:", totalFeedback);
                console.log("  📅 Today:", todayFeedback);
                console.log("  📏 Avg Length:", avgLength);

                // UPDATE DOM
                const totalEl = document.getElementById("totalFeedback");
                const todayEl = document.getElementById("todayFeedback");
                const avgEl = document.getElementById("avgLength");

                console.log("🔗 DOM Elements found:", !!totalEl, !!todayEl, !!avgEl);

                if (totalEl) totalEl.textContent = totalFeedback;
                if (todayEl) todayEl.textContent = todayFeedback;
                if (avgEl) avgEl.textContent = avgLength + " kar";
                
                console.log("✅ DOM updated with stats!");
            } catch (error) {
                console.error("❌ Error in updateStats:", error);
                console.error("   Stack:", error.stack);
            }
        }

        function displayFeedback(filter = "") {
            try {
                console.log("🔄 displayFeedback called with filter:", filter || "(empty)");
                console.log("📊 allFeedback array:", allFeedback);
                console.log("📊 allFeedback length:", allFeedback.length);
                
                let filteredFeedback = allFeedback;

                if (filter && filter.trim() !== "") {
                    filteredFeedback = allFeedback.filter(f =>
                        f.text.toLowerCase().includes(filter.toLowerCase())
                    );
                    console.log("🔍 Filtered feedback count:", filteredFeedback.length);
                } else {
                    console.log("📊 Showing all feedback count:", filteredFeedback.length);
                }

                const totalPages = Math.ceil(filteredFeedback.length / itemsPerPage);
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                const pageFeedback = filteredFeedback.slice(startIndex, endIndex);

                const feedbackGrid = document.getElementById("feedbackGrid");
                console.log("✅ feedbackGrid element found:", !!feedbackGrid);

                if (allFeedback.length === 0) {
                    console.log("⚠️ No feedback available, showing empty state");
                    feedbackGrid.innerHTML = '<div class="no-feedback" style="padding: 60px 20px;"><i class="ri-inbox-line" style="font-size: 3em; color: #7f8c8d;"></i><p style="margin-top: 15px;">Belum ada feedback dari pengunjung</p></div>';
                    document.getElementById("pagination").innerHTML = "";
                    return;
                }

                // 🔥 Anti "harus klik Cari dulu" — jika page kosong, balik ke page 1
                if (pageFeedback.length === 0 && currentPage > 1) {
                    console.log("⚠️ Current page empty, resetting to page 1");
                    currentPage = 1;
                    displayFeedback(filter);
                    return;
                }

                if (pageFeedback.length === 0) {
                    console.log("⚠️ Filtered feedback is empty, showing not found");
                    feedbackGrid.innerHTML = '<div class="no-feedback" style="padding: 60px 20px;"><i class="ri-search-line" style="font-size: 3em; color: #7f8c8d;"></i><p style="margin-top: 15px;">📭 Tidak ada feedback yang ditemukan</p></div>';
                    document.getElementById("pagination").innerHTML = "";
                    return;
                }

                console.log("✅ Rendering", pageFeedback.length, "feedback items");
                feedbackGrid.innerHTML = pageFeedback.map(feedback => `
                    <div class="feedback-card">
                        <div class="feedback-header">
                            <div class="feedback-meta">
                                <div class="feedback-avatar">${feedback.text.charAt(0).toUpperCase()}</div>
                                <div class="feedback-info">
                                <div style="color: #00e0ff; font-weight: 600;">Feedback #${allFeedback.indexOf(feedback) + 1}</div>
                                <div class="feedback-time">${feedback.timestamp}</div>
                            </div>
                        </div>
                        <button class="delete-btn" onclick="deleteFeedback('${feedback.id}')">
                            <i class="ri-delete-bin-line"></i> Hapus
                        </button>
                    </div>
                    <div class="feedback-text">${feedback.text}</div>
                </div>
            `).join("");

                console.log("✅ Feedback HTML rendered successfully");
                renderPagination(totalPages);
            } catch (error) {
                console.error("❌ Error in displayFeedback:", error);
                document.getElementById("feedbackGrid").innerHTML = `
                    <div class="no-feedback" style="padding: 40px;">
                        <i class="ri-error-warning-line" style="font-size: 3em; color: #e74c3c;"></i>
                        <p style="margin-top: 15px; color: #e74c3c;">Error: ${error.message}</p>
                    </div>
                `;
            }
        }

        function renderPagination(totalPages) {
            const pagination = document.getElementById("pagination");

            if (totalPages <= 1) {
                pagination.innerHTML = "";
                return;
            }

            let buttons = [];

            if (currentPage > 1) {
                buttons.push(`<button onclick="changePage(1)"><i class="ri-arrow-left-double-line"></i> Pertama</button>`);
                buttons.push(`<button onclick="changePage(${currentPage - 1})"><i class="ri-arrow-left-line"></i> Sebelumnya</button>`);
            }

            for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
                buttons.push(`<button onclick="changePage(${i})" class="${i === currentPage ? 'active' : ''}">${i}</button>`);
            }

            if (currentPage < totalPages) {
                buttons.push(`<button onclick="changePage(${currentPage + 1})">Berikutnya <i class="ri-arrow-right-line"></i></button>`);
                buttons.push(`<button onclick="changePage(${totalPages})">Terakhir <i class="ri-arrow-right-double-line"></i></button>`);
            }

            pagination.innerHTML = buttons.join("");
        }

        window.changePage = function(page) {
            currentPage = page;
            displayFeedback(document.getElementById("searchInput").value);
            window.scrollTo({ top: 0, behavior: "smooth" });
        };

        window.searchFeedback = function() {
            const searchValue = document.getElementById("searchInput").value.trim();
            console.log("🔍 Search triggered with value:", searchValue);
            currentPage = 1;
            displayFeedback(searchValue);
        };

        window.deleteFeedback = function(id) {
            if (confirm("Apakah Anda yakin ingin menghapus feedback ini?")) {
                remove(ref(db, `feedback/${id}`)).then(() => {
                    alert("Feedback berhasil dihapus!");
                }).catch(err => {
                    alert("Gagal menghapus feedback: " + err.message);
                });
            }
        };

        window.exportToCSV = function() {
            if (allFeedback.length === 0) {
                alert("Tidak ada feedback untuk diekspor");

                return;
            }

            let csv = "No,Feedback,Tanggal\n";
            allFeedback.forEach((feedback, index) => {
                csv += `"${index + 1}","${feedback.text.replace(/"/g, '""')}","${feedback.timestamp}"\n`;
            });

            const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
            const link = document.createElement("a");
            const url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", `feedback-${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = "hidden";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };

        document.getElementById("searchInput").addEventListener("keypress", (e) => {
            if (e.key === "Enter") {
                searchFeedback();
            }
        });

        // Ensure display feedback is called on page load
        window.addEventListener("load", () => {
            console.log("📌 Page loaded, current feedback count:", allFeedback.length);
            if (allFeedback.length === 0) {
                displayFeedback("");
            }
        });

        console.log("✅ All event listeners registered successfully!");
    </script>
</body>
</html>