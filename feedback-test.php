<!DOCTYPE html>
<html>
<head>
    <title>Feedback Test</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background: #08122a; color: white; padding: 20px; font-family: Poppins, sans-serif;">
    <h1>🧪 Feedback System Test</h1>
    
    <div style="background: rgba(0,200,255,0.1); padding: 20px; border-radius: 10px; margin: 20px 0;">
        <h2>Test Results:</h2>
        <div id="results"></div>
    </div>

    <div style="background: rgba(0,200,255,0.1); padding: 20px; border-radius: 10px; margin: 20px 0;">
        <h2>Console Output:</h2>
        <div id="console" style="background: #000; padding: 10px; border-radius: 5px; max-height: 400px; overflow-y: auto; font-family: monospace; font-size: 12px;"></div>
    </div>

    <a href="feedback.php" style="display: inline-block; background: linear-gradient(135deg, #00e0ff, #0077ff); padding: 10px 20px; border-radius: 8px; color: white; text-decoration: none; margin-top: 20px;">Go to Feedback.php →</a>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/12.5.0/firebase-app.js";
        import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/12.5.0/firebase-database.js";

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

        const logs = [];
        const originalLog = console.log;
        const originalError = console.error;

        console.log = function(...args) {
            logs.push("LOG: " + args.join(" "));
            originalLog.apply(console, args);
            updateConsole();
        };

        console.error = function(...args) {
            logs.push("ERROR: " + args.join(" "));
            originalError.apply(console, args);
            updateConsole();
        };

        function updateConsole() {
            document.getElementById("console").innerHTML = logs.map(log => 
                `<div style="color: ${log.includes("ERROR") ? "#e74c3c" : "#00e0ff"}">${log}</div>`
            ).join("");
            document.getElementById("console").scrollTop = document.getElementById("console").scrollHeight;
        }

        try {
            const app = initializeApp(firebaseConfig);
            const db = getDatabase(app);
            console.log("✅ Firebase initialized!");

            const feedbackRef = ref(db, "feedback");
            onValue(feedbackRef, (snapshot) => {
                const data = snapshot.val();
                const count = data ? Object.keys(data).length : 0;
                
                console.log(`✅ Feedback count: ${count}`);
                
                document.getElementById("results").innerHTML = `
                    <p><strong>Firebase Status:</strong> ✅ Connected</p>
                    <p><strong>Total Feedback:</strong> ${count}</p>
                    <p><strong>Data Sample:</strong> ${JSON.stringify(data ? Object.values(data)[0] : {}).substring(0, 100)}...</p>
                `;
            }, (error) => {
                console.error(`❌ Firebase error: ${error.message}`);
                document.getElementById("results").innerHTML = `<p style="color: #e74c3c;">❌ Error: ${error.message}</p>`;
            });

        } catch (error) {
            console.error(`❌ Initialization error: ${error.message}`);
            document.getElementById("results").innerHTML = `<p style="color: #e74c3c;">❌ Error: ${error.message}</p>`;
        }
    </script>
</body>
</html>