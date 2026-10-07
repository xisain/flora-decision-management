<?php

namespace Tests\Feature;

use App\Models\Berita;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class BeritaDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_public_article_displays_model_data_and_formatted_content(): void
    {
        $berita = Berita::factory()->create([
            'judul' => 'Pelestarian tumbuhan Kalimantan',
            'content' => '<div>Catatan <strong>konservasi</strong>.</div><ul><li>Pembibitan</li></ul>',
            'visitor' => 1234,
        ]);

        $response = $this->get(route('berita.detail', $berita->slugs));

        $response->assertOk()
            ->assertViewIs('home.berita.detail')
            ->assertSee($berita->judul)
            ->assertSee($berita->kategoriBerita->nama_kategori)
            ->assertSee($berita->user->name)
            ->assertSee($berita->created_at->translatedFormat('d F Y'))
            ->assertSee('1.234')
            ->assertSee('<strong>konservasi</strong>', false)
            ->assertSee('<li>Pembibitan</li>', false)
            ->assertSee('href="'.route('berita.index').'"', false)
            ->assertDontSee('class="landing-scroll"', false);

        $this->assertSame(1234, $berita->fresh()->visitor);
    }

    #[DataProvider('unpublishedStatuses')]
    public function test_unpublished_articles_are_not_accessible(string $status): void
    {
        $berita = Berita::factory()->create(['status' => $status]);

        $this->get(route('berita.detail', $berita->slugs))->assertNotFound();
    }

    public static function unpublishedStatuses(): array
    {
        return [['draft'], ['private']];
    }

    public function test_unknown_slug_returns_not_found(): void
    {
        $this->get(route('berita.detail', 'tidak-ada'))->assertNotFound();
    }

    public function test_empty_image_and_content_show_readable_fallbacks(): void
    {
        $berita = Berita::factory()->create(['image_url' => '', 'content' => '<div><br></div>']);

        $this->get(route('berita.detail', $berita->slugs))
            ->assertOk()
            ->assertSee('Berita ini belum memiliki foto.')
            ->assertSee('Isi berita belum tersedia.')
            ->assertDontSee('src=""', false);
    }

    public function test_local_image_uses_configured_storage_url_and_includes_error_state(): void
    {
        $berita = Berita::factory()->create(['image_url' => 'berita/foto.jpg']);

        $this->get(route('berita.detail', $berita->slugs))
            ->assertOk()
            ->assertSee('src="'.Storage::url('berita/foto.jpg').'"', false)
            ->assertSee('Memuat foto berita…')
            ->assertSee('Foto berita tidak dapat dimuat.')
            ->assertSee('x-on:error="imageFailed = true"', false);
    }

    public function test_external_image_url_is_preserved(): void
    {
        $berita = Berita::factory()->create(['image_url' => 'https://example.com/foto.jpg']);

        $this->get(route('berita.detail', $berita->slugs))
            ->assertOk()
            ->assertSee('src="https://example.com/foto.jpg"', false);
    }

    public function test_s3_image_uses_the_configured_disk_url(): void
    {
        $berita = Berita::factory()->create(['image_url' => 'bulungan/berita/foto.jpg']);
        $disk = Mockery::mock(FilesystemAdapter::class);
        Storage::shouldReceive('disk')->once()->with('s3')->andReturn($disk);
        $disk->shouldReceive('url')->once()
            ->with('bulungan/berita/foto.jpg')
            ->andReturn('https://example.com/signed-foto.jpg');

        $this->get(route('berita.detail', $berita->slugs))
            ->assertOk()
            ->assertSee('src="https://example.com/signed-foto.jpg"', false);
    }

    public function test_unsafe_html_is_removed_while_safe_formatting_and_links_are_retained(): void
    {
        $berita = Berita::factory()->create([
            'judul' => '<script>judulTidakAman()</script>',
            'content' => '<h1>Isi berita</h1><p onclick="dangerousClick()">Teks <em>penting</em>.</p>'
                .'<script>dangerousScript()</script><img src="x" onerror="dangerousImage()">'
                .'<a href="jav&#x61;script:dangerousLink()">tautan buruk</a>'
                .'<svg><script>dangerousSvg()</script></svg>'
                .'<a href="https://example.com/konservasi" style="color:red">Sumber</a>',
        ]);

        $this->get(route('berita.detail', $berita->slugs))
            ->assertOk()
            ->assertSee($berita->judul)
            ->assertDontSee('<script>judulTidakAman()</script>', false)
            ->assertSee('<h2>Isi berita</h2>', false)
            ->assertSee('<em>penting</em>', false)
            ->assertSee('<a href="https://example.com/konservasi">Sumber</a>', false)
            ->assertSee('tautan buruk')
            ->assertDontSee('dangerousClick()')
            ->assertDontSee('dangerousScript()')
            ->assertDontSee('dangerousImage()')
            ->assertDontSee('dangerousLink()')
            ->assertDontSee('dangerousSvg()');
    }

    public function test_plain_text_keeps_line_breaks_and_escapes_characters(): void
    {
        $berita = Berita::factory()->create(['content' => "Baris pertama & flora.\nBaris kedua."]);

        $this->get(route('berita.detail', $berita->slugs))
            ->assertOk()
            ->assertSee('Baris pertama &amp; flora.<br />', false)
            ->assertSee('Baris kedua.');
    }
}
