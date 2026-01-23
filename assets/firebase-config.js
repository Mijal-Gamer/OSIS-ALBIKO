// /assets/js/firebase.js
import { initializeApp } from "https://www.gstatic.com/firebasejs/12.4.0/firebase-app.js";
import { getDatabase, ref as dbRef, set, onValue, push, remove } from "https://www.gstatic.com/firebasejs/12.4.0/firebase-database.js";
import { getStorage, ref as storageRef, uploadBytes, getDownloadURL, deleteObject } from "https://www.gstatic.com/firebasejs/12.4.0/firebase-storage.js";

const firebaseConfig = {
  apiKey: "AIzaSyDAvmSiSgfijLYb1_e8p1mf5rA8oaYpG1Y",
  authDomain: "osis-asstamayana.firebaseapp.com",
  databaseURL: "https://osis-asstamayana-default-rtdb.asia-southeast1.firebasedatabase.app",
  projectId: "osis-asstamayana",
  storageBucket: "osis-asstamayana.appspot.com",
  messagingSenderId: "487901502731",
  appId: "1:487901502731:web:e0ed0778bb4c796bd2960e",
  measurementId: "G-TJ8W5XV0GH"
};

const app = initializeApp(firebaseConfig);
const db = getDatabase(app);
const storage = getStorage(app);

export { db, dbRef, set, onValue, push, remove, storage, storageRef, uploadBytes, getDownloadURL, deleteObject };
