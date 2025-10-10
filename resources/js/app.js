import './bootstrap';
import '../css/app.css';

// === DARK MODE TOGGLE ===
document.addEventListener("DOMContentLoaded", () => {
    const html = document.documentElement;
    const toggleBtn = document.querySelector("#theme-toggle");

    // Saat pertama kali load, ambil preferensi dari localStorage
    if (localStorage.theme === "dark") {
        html.classList.add("dark");
    } else {
        html.classList.remove("dark");
    }

    // Saat tombol diklik, toggle mode dan simpan preferensi
    toggleBtn?.addEventListener("click", () => {
        if (html.classList.contains("dark")) {
            html.classList.remove("dark");
            localStorage.theme = "light";
        } else {
            html.classList.add("dark");
            localStorage.theme = "dark";
        }
    });
});
