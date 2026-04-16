<footer class="footer">
    <div class="footer-content">
        <div class="footer-brand">
            <div class="brand-icon">
                <img src="/storage/images/logo.png" alt="Tanaman Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div class="brand-text">
                <span class="brand-name">Tanaman</span>
                <span class="brand-sub">Koleksi & Berita</span>
            </div>
        </div>
        <div class="footer-links">
            <div class="footer-section">
                <h4>Navigasi</h4>
                <ul>
                    <li><a @click="navTo('tanaman')">Tanaman</a></li>
                    <li><a @click="navTo('koleksi')">Koleksi</a></li>
                    <li><a @click="navTo('berita')">Berita</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Bantuan</h4>
                <ul>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Kontak</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Ikuti Kami</h4>
                <div class="social-links">
                    <a href="#" class="social-icon">📘</a>
                    <a href="#" class="social-icon">🐦</a>
                    <a href="#" class="social-icon">📷</a>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 Tanaman. All rights reserved.</p>
    </div>
</footer>
