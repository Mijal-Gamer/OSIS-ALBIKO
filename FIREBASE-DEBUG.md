# Firebase Debugging Guide

## Untuk Check Feedback tidak muncul di feedback.php

### 1. **Buka Browser Developer Console**
- Tekan `F12` atau `Ctrl+Shift+I`
- Pilih tab "Console"

### 2. **Lihat Log Messages**
Anda akan melihat pesan seperti:
```
✅ Firebase initialized successfully!
📊 Feedback snapshot received!
✅ Added feedback: <id>
📈 Total feedback: 5
```

### 3. **Jika Ada Error**
- **Error: "Permission denied"** → Check Firebase Rules di Realtime Database
- **Error: Network"** → Check internet connection
- **No feedback data** → Check apakah ada data di Firebase Console

### 4. **Check Firebase Console**
1. Buka https://console.firebase.google.com/
2. Pilih project "osis-asstamayana"
3. Pilih "Realtime Database"
4. Lihat apakah ada folder "feedback" dengan data di dalamnya

### 5. **Kirim Feedback Test**
1. Buka index.php
2. Klik tombol Chat bubble di kanan bawah
3. Ketik feedback test
4. Klik "Kirim Feedback"
5. Check console apakah ada log "✅ Feedback sent successfully!"

### 6. **Troubleshooting**

#### Problem: Loading spinner tidak bergerak
**Solution:** Reload halaman (Ctrl+F5)

#### Problem: Firebase Error - PERMISSION_DENIED
**Solution:** Update Firebase Realtime Database Rules ke:
```json
{
  "rules": {
    "feedback": {
      ".read": true,
      ".write": true,
      ".indexOn": ["timestamp"]
    }
  }
}
```

#### Problem: Feedback sent tapi tidak muncul di feedback.php
**Solution:**
1. Check console untuk error message
2. Verify Firebase config sama di semua file:
   - index.php
   - feedback.php
   - assets/firebase-config.js

#### Problem: "Cannot read properties of undefined"
**Solution:** Cek apakah `timestamp` format valid. Gunakan format ISO atau format lokal dengan konsisten.

### 7. **File yang Berhubungan Feedback**
- `index.php` - Form feedback dengan Firebase sender
- `feedback.php` - Admin panel untuk view/delete feedback
- `assets/firebase-config.js` - Config file (reference)
- Firebase Realtime Database - Data storage

### 8. **Struktur Data Firebase**
```
feedback/
  ├── -NyZ1A2B3C4D5E6F7G8H9I0J (document ID, auto-generated)
  │   ├── text: "Ini adalah feedback ..."
  │   ├── timestamp: "23/1/2026, 15:30:45"
  │   └── userAgent: "Mozilla/5.0..."
  │
  └── -OyA1B2C3D4E5F6G7H8I9J0K
      ├── text: "Feedback lainnya ..."
      ├── timestamp: "23/1/2026, 14:20:15"
      └── userAgent: "..."
```

### 9. **Console Commands untuk Debug**
Ketik di Developer Console:

```javascript
// Check Firebase app
console.log(app);

// Check database
console.log(db);

// Check allFeedback array
console.log(allFeedback);

// Manual read dari Firebase
import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/12.5.0/firebase-database.js";
const testRef = ref(db, "feedback");
onValue(testRef, (snapshot) => {
  console.log("TEST READ:", snapshot.val());
});
```

### 10. **Quick Checklist**
- [ ] Firebase project aktif
- [ ] Realtime Database sudah created
- [ ] Security Rules memperbolehkan read/write
- [ ] firebaseConfig sama di semua file
- [ ] Internet connection stabil
- [ ] Browser console tidak ada error merah
- [ ] Clear browser cache (Ctrl+Shift+Del)
