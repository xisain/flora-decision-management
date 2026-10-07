<?php

namespace Tests\Feature;

use App\Models\TempatMenarik;
use App\Models\User;
use App\Services\TempatMenarikPhotoStorage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Tests\TestCase;

class TempatMenarikTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Storage::fake('s3')->buildTemporaryUrlsUsing(fn (string $path): string => 'https://photos.example.test/'.$path.'?signed=1');
    }

    private function payload(): array
    {
        return ['nama' => 'Tempat pengujian', 'deskripsi' => 'Deskripsi untuk pengujian.', 'kategori' => 'taman', 'urutan' => 1, 'is_active' => 1, 'is_featured' => 1];
    }

    private function existingPlace(array $attributes = []): TempatMenarik
    {
        $place = TempatMenarik::factory()->create($attributes);
        Storage::disk('s3')->put($place->foto, 'existing photo');

        return $place;
    }

    public function test_admin_can_create_a_place_and_upload_its_photo_to_s3(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $this->get(route('admin.tempat-menarik.create'))->assertOk()->assertSee('Tambah tempat menarik');
        $this->post(route('admin.tempat-menarik.store'), $this->payload() + ['foto' => UploadedFile::fake()->image('foto.jpg')])
            ->assertRedirect(route('admin.tempat-menarik.index'))->assertSessionHas('success');

        $place = TempatMenarik::firstOrFail();
        $this->assertSame('Tempat pengujian', $place->nama);
        $this->assertTrue($place->is_active);
        $this->assertTrue($place->is_featured);
        $this->assertStringStartsWith('bulungan/tempat-menarik/', $place->foto);
        Storage::disk('s3')->assertExists($place->foto);
        $this->get(route('admin.tempat-menarik.index'))->assertOk()->assertSee($place->nama)->assertSee('Ditampilkan');
    }

    public function test_admin_can_edit_without_replacing_the_photo(): void
    {
        $place = $this->existingPlace();
        $oldPhoto = $place->foto;
        $this->actingAs(User::factory()->admin()->create());
        $this->get(route('admin.tempat-menarik.edit', $place))->assertOk()->assertSee('signed=1');
        $this->put(route('admin.tempat-menarik.update', $place), $this->payload())
            ->assertRedirect(route('admin.tempat-menarik.index'));
        $this->assertSame($oldPhoto, $place->fresh()->foto);
        Storage::disk('s3')->assertExists($oldPhoto);
    }

    public function test_replacing_a_photo_deletes_the_previous_s3_object(): void
    {
        $place = $this->existingPlace();
        $oldPhoto = $place->foto;
        $this->actingAs(User::factory()->admin()->create());
        $this->put(route('admin.tempat-menarik.update', $place), $this->payload() + ['foto' => UploadedFile::fake()->image('baru.webp')])->assertSessionHasNoErrors();

        $newPhoto = $place->fresh()->foto;
        $this->assertNotSame($oldPhoto, $newPhoto);
        Storage::disk('s3')->assertExists($newPhoto);
        Storage::disk('s3')->assertMissing($oldPhoto);
    }

    public function test_deleting_a_place_removes_the_record_and_photo(): void
    {
        $place = $this->existingPlace();
        $this->actingAs(User::factory()->admin()->create());
        $this->delete(route('admin.tempat-menarik.destroy', $place))->assertRedirect(route('admin.tempat-menarik.index'))->assertSessionHas('success');
        $this->assertModelMissing($place);
        Storage::disk('s3')->assertMissing($place->foto);
    }

    #[DataProvider('invalidFields')]
    public function test_create_validates_fields_and_photo(string $field, mixed $value): void
    {
        $payload = $this->payload() + ['foto' => UploadedFile::fake()->image('foto.jpg')];
        $payload[$field] = match ($value) {
            'oversized-photo' => UploadedFile::fake()->image('besar.jpg')->size(2049),
            'non-image' => UploadedFile::fake()->create('data.pdf', 10, 'application/pdf'),
            'svg-photo' => UploadedFile::fake()->create('foto.svg', 1, 'image/svg+xml'),
            default => $value,
        };
        $this->actingAs(User::factory()->admin()->create())->post(route('admin.tempat-menarik.store'), $payload)->assertSessionHasErrors($field);
        $this->assertDatabaseCount('tempat_menarik', 0);
        $this->assertSame([], Storage::disk('s3')->allFiles());
    }

    public static function invalidFields(): array
    {
        return [
            'name required' => ['nama', ''], 'description required' => ['deskripsi', ''],
            'photo required' => ['foto', null], 'photo size' => ['foto', 'oversized-photo'],
            'photo mime' => ['foto', 'non-image'], 'svg rejected' => ['foto', 'svg-photo'],
            'category' => ['kategori', 'invalid'], 'order' => ['urutan', -1],
            'featured boolean' => ['is_featured', 'yes'], 'active boolean' => ['is_active', 'yes'],
        ];
    }

    public function test_failed_upload_does_not_create_a_record(): void
    {
        $disk = \Mockery::mock();
        $disk->shouldReceive('putFileAs')->once()->andReturn(false);
        Storage::shouldReceive('disk')->with('s3')->andReturn($disk);
        $this->actingAs(User::factory()->admin()->create())->post(route('admin.tempat-menarik.store'), $this->payload() + ['foto' => UploadedFile::fake()->image('foto.jpg')])->assertSessionHasErrors('foto');
        $this->assertDatabaseCount('tempat_menarik', 0);
    }

    public function test_failed_delete_keeps_the_database_record(): void
    {
        $place = $this->existingPlace();
        $disk = \Mockery::mock();
        $disk->shouldReceive('delete')->with($place->foto)->once()->andReturn(false);
        Storage::shouldReceive('disk')->with('s3')->andReturn($disk);
        $this->actingAs(User::factory()->admin()->create())->delete(route('admin.tempat-menarik.destroy', $place))->assertSessionHasErrors('foto');
        $this->assertModelExists($place);
    }

    public function test_failed_replacement_upload_preserves_the_old_photo_and_information(): void
    {
        $place = $this->existingPlace();
        $original = $place->fresh()->getAttributes();
        $disk = \Mockery::mock();
        $disk->shouldReceive('putFileAs')->once()->andThrow(new RuntimeException('Upload unavailable'));
        Storage::shouldReceive('disk')->with('s3')->andReturn($disk);

        $this->actingAs(User::factory()->admin()->create())
            ->put(route('admin.tempat-menarik.update', $place), $this->payload() + ['foto' => UploadedFile::fake()->image('foto.jpg')])
            ->assertSessionHasErrors('foto');

        $this->assertSame($original, $place->fresh()->getAttributes());
    }

    public function test_replacement_cleanup_failure_reports_a_warning_after_saving_the_new_photo(): void
    {
        $place = $this->existingPlace();
        $oldPath = $place->foto;
        $disk = \Mockery::mock();
        $newPath = 'bulungan/tempat-menarik/new-photo.jpg';
        $disk->shouldReceive('putFileAs')->once()->andReturn($newPath);
        $disk->shouldReceive('delete')->once()->with($oldPath)->andReturn(false);
        Storage::shouldReceive('disk')->with('s3')->andReturn($disk);

        $this->actingAs(User::factory()->admin()->create())
            ->put(route('admin.tempat-menarik.update', $place), $this->payload() + ['foto' => UploadedFile::fake()->image('foto.jpg')])
            ->assertRedirect(route('admin.tempat-menarik.index'))
            ->assertSessionHas('success')->assertSessionHas('warning');

        $this->assertSame($newPath, $place->fresh()->foto);
    }

    public function test_database_failure_cleans_up_the_new_upload(): void
    {
        $place = \Mockery::mock(TempatMenarik::class)->makePartial();
        $place->mergeFillable(array_keys($this->payload()));
        $place->shouldReceive('save')->once()->andThrow(new RuntimeException('Database failure'));
        try {
            app(TempatMenarikPhotoStorage::class)->save($place, $this->payload(), UploadedFile::fake()->image('foto.jpg'));
            $this->fail('Expected database failure.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Database failure', $exception->getMessage());
        }
        $this->assertSame([], Storage::disk('s3')->allFiles());
    }

    #[DataProvider('adminActions')]
    public function test_admin_routes_reject_guests_and_nonadmins(string $method, string $route): void
    {
        $place = $this->existingPlace();
        $url = route('admin.tempat-menarik.'.$route, $place);
        $this->{$method}($url, $this->payload())->assertRedirect(route('login'));
        $this->actingAs(User::factory()->teknisiRegistrasi()->create());
        $this->{$method}($url, $this->payload())->assertForbidden();
    }

    public static function adminActions(): array
    {
        return [['get', 'index'], ['get', 'create'], ['post', 'store'], ['get', 'edit'], ['put', 'update'], ['delete', 'destroy']];
    }

    public function test_inactive_admin_and_missing_role_cannot_manage_places(): void
    {
        $this->actingAs(User::factory()->admin()->inactive()->create())->get(route('admin.tempat-menarik.index'))->assertRedirect(route('login'));
        $user = User::factory()->create();
        $user->setRelation('roles', null);
        $this->actingAs($user)->get(route('admin.tempat-menarik.index'))->assertForbidden();
    }

    public function test_public_index_filters_categories_and_hides_inactive_places(): void
    {
        $taman = TempatMenarik::factory()->create(['kategori' => 'taman']);
        $danau = TempatMenarik::factory()->create(['kategori' => 'danau']);
        $hidden = TempatMenarik::factory()->create(['kategori' => 'taman', 'is_active' => false]);
        $this->get(route('public.tempat-menarik.index', ['kategori' => 'taman']))->assertOk()
            ->assertSee($taman->nama)->assertDontSee($danau->nama)->assertDontSee($hidden->nama)
            ->assertSee('https://photos.example.test/'.$taman->foto.'?signed=1', false)
            ->assertSee('aria-current="page"', false);
        $this->get(route('public.tempat-menarik.index'))->assertOk()->assertSee($taman->nama)->assertSee($danau->nama)->assertDontSee($hidden->nama);
        $this->get(route('public.tempat-menarik.index', ['kategori' => 'invalid']))->assertSessionHasErrors('kategori');
    }

    public function test_public_index_paginates_and_preserves_category(): void
    {
        TempatMenarik::factory()->count(13)->create(['kategori' => 'taman']);
        $this->get(route('public.tempat-menarik.index', ['kategori' => 'taman']))->assertOk()
            ->assertViewHas('places', fn ($places): bool => $places->count() === 12 && $places->total() === 13)
            ->assertSee('kategori=taman&amp;page=2', false);
    }

    public function test_public_detail_escapes_description_and_rejects_hidden_or_missing_places(): void
    {
        $place = TempatMenarik::factory()->create(['deskripsi' => '<script>alert("unsafe")</script>']);
        $this->get(route('public.tempat-menarik.show', $place))->assertOk()->assertSee($place->nama)
            ->assertSee(e($place->deskripsi), false)->assertDontSee($place->deskripsi, false)
            ->assertDontSee('landing-scroll');
        $place->update(['is_active' => false]);
        $this->get(route('public.tempat-menarik.show', $place))->assertNotFound();
        $this->get(route('public.tempat-menarik.show', 99999))->assertNotFound();
    }

    public function test_landing_selects_three_active_featured_places_in_admin_order(): void
    {
        $places = collect(range(0, 3))->map(fn (int $order) => TempatMenarik::factory()->create(['is_featured' => true, 'urutan' => $order]));
        $inactive = TempatMenarik::factory()->create(['is_featured' => true, 'is_active' => false]);
        $ordinary = TempatMenarik::factory()->create();
        $response = $this->get(route('landing'))->assertOk()->assertViewHas('places', fn ($items): bool => $items->pluck('id')->all() === $places->take(3)->pluck('id')->all());
        $response->assertSeeInOrder($places->take(3)->pluck('nama')->all())
            ->assertDontSee($places->last()->nama)->assertDontSee($inactive->nama)->assertDontSee($ordinary->nama)
            ->assertSee(route('public.tempat-menarik.index'), false);
    }

    public function test_empty_and_photo_error_states_are_visible(): void
    {
        $this->get(route('public.tempat-menarik.index'))->assertOk()->assertSee('Tempat menarik sedang disiapkan.');
        $place = TempatMenarik::factory()->create();
        $emptyCategory = collect(array_keys(TempatMenarik::CATEGORIES))->first(fn (string $category): bool => $category !== $place->kategori);
        $this->get(route('public.tempat-menarik.index', ['kategori' => $emptyCategory]))->assertOk()->assertSee('Belum ada tempat dalam kategori ini.');
        $disk = \Mockery::mock();
        $disk->shouldReceive('temporaryUrl')->once()->andThrow(new RuntimeException('Unavailable photo URL'));
        Storage::shouldReceive('disk')->with('s3')->andReturn($disk);
        $this->get(route('public.tempat-menarik.show', $place))->assertOk()->assertSee('Foto tempat belum tersedia.');
    }

    public function test_admin_search_and_pagination_work(): void
    {
        TempatMenarik::factory()->count(11)->create();
        $place = TempatMenarik::factory()->create(['nama' => 'Tempat pencarian khusus']);
        $this->actingAs(User::factory()->admin()->create())->get(route('admin.tempat-menarik.index', ['search' => 'pencarian khusus']))->assertOk()->assertSee($place->nama)->assertViewHas('places', fn ($items): bool => $items->total() === 1);
        $this->get(route('admin.tempat-menarik.index'))->assertOk()->assertViewHas('places', fn ($items): bool => $items->count() === 10 && $items->total() === 12);
    }
}
