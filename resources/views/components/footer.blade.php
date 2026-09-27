<footer class="px-6 md:px-16 lg:px-24 xl:px-32 font-[Geist,sans-serif]">
    <div class="flex flex-col md:flex-row items-start justify-between gap-10 py-10 border-b border-zinc-200 text-zinc-500">

        <!-- Brand -->
        <div>
            <a class="flex items-center gap-3 cursor-pointer" @click="navTo('dashboard')">
                <div class="w-9 h-9 shrink-0">
                    <img src="/storage/images/logo.png" alt="Flora Logo" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col leading-tight">
                    <span class="text-base font-semibold text-zinc-900 tracking-tight">Flora</span>
                    <span class="text-[11px] text-zinc-500">Plant Decision Manager</span>
                </div>
            </a>
            <p class="max-w-xs mt-6 text-sm leading-relaxed">
                Platform pengelolaan keputusan tanaman untuk Kebun Raya — eksplorasi koleksi, ikuti berita terbaru, dan dukung pelestarian flora nusantara.
            </p>
        </div>

        <!-- Links -->
        <div class="flex flex-wrap justify-between w-full md:w-[50%] gap-8">

            <!-- Navigasi -->
            <div>
                <h3 class="font-semibold text-sm text-zinc-900 mb-4">Navigasi</h3>
                <ul class="text-sm space-y-2">
                    <li><a class="hover:text-zinc-800 hover:underline transition cursor-pointer" @click="navTo('tanaman')">Tanaman</a></li>
                    <li><a class="hover:text-zinc-800 hover:underline transition cursor-pointer" @click="navTo('koleksi')">Koleksi</a></li>
                    <li><a class="hover:text-zinc-800 hover:underline transition cursor-pointer" @click="navTo('berita')">Berita</a></li>
                </ul>
            </div>

            <!-- Bantuan -->
            <div>
                <h3 class="font-semibold text-sm text-zinc-900 mb-4">Bantuan</h3>
                <ul class="text-sm space-y-2">
                    <li><a href="#" class="hover:text-zinc-800 hover:underline transition">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-zinc-800 hover:underline transition">Kontak</a></li>
                    <li><a href="#" class="hover:text-zinc-800 hover:underline transition">FAQ</a></li>
                </ul>
            </div>

            <!-- Ikuti Kami -->
            <div>
                <h3 class="font-semibold text-sm text-zinc-900 mb-4">Ikuti Kami</h3>
                <ul class="text-sm space-y-2">
                    <li><a href="#" class="hover:text-zinc-800 hover:underline transition">Instagram</a></li>
                    <li><a href="#" class="hover:text-zinc-800 hover:underline transition">Twitter</a></li>
                    <li><a href="#" class="hover:text-zinc-800 hover:underline transition">Facebook</a></li>
                    <li><a href="#" class="hover:text-zinc-800 hover:underline transition">YouTube</a></li>
                </ul>
            </div>

        </div>
    </div>

    <p class="py-4 text-center text-sm text-zinc-400">
        &copy; {{ date('Y') }} Flora &mdash; Kebun Raya Bundayati. All rights reserved.
    </p>
</footer>
