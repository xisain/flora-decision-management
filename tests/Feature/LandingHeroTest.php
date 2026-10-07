<?php

namespace Tests\Feature;

use Tests\TestCase;

class LandingHeroTest extends TestCase
{
    public function test_original_hero_renders_with_background_photo_and_accessible_video_control(): void
    {
        $this->withoutVite();

        $view = $this->view('home.landing', ['berita' => collect()]);

        $view->assertSee('src="'.asset('storage/images/banner.jpg').'"', false)
            ->assertSee('fetchpriority="high"', false)
            ->assertSee('Kebun Raya Kalimantan Utara')
            ->assertSee('Kebun Raya Bundayati')
            ->assertSee('href="#tentang"', false)
            ->assertSee('Jelajahi Kebun Raya')
            ->assertSee('aria-label="Putar atau hentikan video latar"', false)
            ->assertSee('Putar video latar')
            ->assertSee('Foto kawasan belum tersedia.');
    }

    public function test_restoring_hero_preserves_the_other_sections_and_empty_news_state(): void
    {
        $this->withoutVite();

        $view = $this->view('home.landing', ['berita' => collect()]);

        $view->assertSee('class="landing-scroll"', false)
            ->assertSeeInOrder([
                'Jelajahi Kebun Raya',
                'Tentang kebun raya',
                'Pelestarian flora',
                'Tempat menarik',
                'Cerita &amp; kegiatan terbaru',
                'Belum ada kabar terbaru.',
            ], false)
            ->assertSee('Tempat menarik sedang disiapkan.');
    }
}
