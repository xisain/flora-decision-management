<?php

namespace Tests\Feature;

use App\Models\Berita;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_shows_only_the_three_latest_public_articles_with_category_and_safe_excerpt(): void
    {
        $this->withoutVite();
        $articles = collect(range(1, 4))->map(fn (int $day) => Berita::factory()->create([
            'judul' => 'Kegiatan kebun '.$day,
            'created_at' => now()->subDays($day),
            'content' => '<p>Pengamatan koleksi tumbuhan.</p><script>teks berbahaya</script>',
        ]));
        $draft = Berita::factory()->create(['status' => 'draft']);
        $private = Berita::factory()->create(['status' => 'private']);

        $response = $this->get('/');

        $response->assertOk()->assertViewHas('berita', function ($items) use ($articles): bool {
            return $items->pluck('id')->all() === $articles->take(3)->pluck('id')->all()
                && $items->every(fn (Berita $item): bool => $item->relationLoaded('kategoriBerita'));
        });
        $response->assertSeeInOrder($articles->take(3)->pluck('judul')->all())
            ->assertSee($articles->first()->kategoriBerita->nama_kategori)
            ->assertSee('Pengamatan koleksi tumbuhan.')
            ->assertSee(route('berita.detail', $articles->first()->slugs), false)
            ->assertDontSee($articles->last()->judul)
            ->assertDontSee($draft->judul)
            ->assertDontSee($private->judul)
            ->assertDontSee('teks berbahaya');
    }

    #[DataProvider('imagePaths')]
    public function test_article_images_resolve_without_visible_storage_paths(string $path, ?string $expectedUrl): void
    {
        $this->withoutVite();
        if (str_starts_with($path, 'bulungan/berita/')) {
            $disk = \Mockery::mock();
            $disk->shouldReceive('url')->once()->with($path)->andReturn($expectedUrl);
            Storage::shouldReceive('disk')->with('s3')->once()->andReturn($disk);
        } elseif ($path !== '' && ! str_starts_with($path, 'https://')) {
            $expectedUrl = Storage::url($path);
        }
        $article = Berita::factory()->create(['image_url' => $path]);

        $response = $this->get('/');

        $response->assertOk()->assertViewHas('imageUrls', fn ($urls): bool => $urls[$article->id] === $expectedUrl);
        if ($expectedUrl === null) {
            $response->assertSee('Belum ada foto untuk berita ini.');
        } else {
            $response->assertSee('src="'.e($expectedUrl).'"', false);
            $this->assertStringNotContainsString($path, strip_tags($response->getContent()));
        }
    }

    public static function imagePaths(): array
    {
        return [
            'empty' => ['', null],
            'local' => ['berita/koleksi.jpg', null],
            'external' => ['https://example.com/koleksi.jpg', 'https://example.com/koleksi.jpg'],
            's3' => ['bulungan/berita/koleksi.jpg', 'https://example.com/signed-koleksi.jpg'],
        ];
    }
}
