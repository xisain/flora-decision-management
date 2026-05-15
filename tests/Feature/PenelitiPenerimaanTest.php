<?php

namespace Tests\Feature;

use App\Models\CollectorInfo;
use App\Models\Penerimaan;
use App\Models\Role;
use App\Models\TimExplorasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PenelitiPenerimaanTest extends TestCase
{
    use RefreshDatabase;

    protected $peneliti;

    protected function setUp(): void
    {
        parent::setUp();

        $rolePeneliti = Role::factory()->create(['name' => 'peneliti']);
        $this->peneliti = User::factory()->create(['roles_id' => $rolePeneliti->id]);
    }

    public function test_peneliti_bisa_melihat_daftar_penerimaan()
    {
        Penerimaan::factory()->count(2)->create();

        $response = $this->actingAs($this->peneliti)->get(route('peneliti.penerimaan.index'));

        $response->assertStatus(200);
        $response->assertViewHas('penerimaan');
    }

    public function test_peneliti_bisa_membuat_penerimaan_tanaman()
    {
        $tim = TimExplorasi::factory()->create();
        $collector = CollectorInfo::factory()->create();

        $file = UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf');

        $data = [
            'dokumen' => [
                [
                    'namaSurat' => 'Surat Pengantar',
                    'nomorSurat' => '123/SP/2026',
                    'fileSurat' => $file
                ]
            ],
            'tanggal_penerimaan' => '2026-05-14',
            'tanggal_explorasi' => '2026-05-10',
            'jenis_form' => 'penerimaan_baru',
            'tempat_asal' => 'Hutan Lindung',
            'country' => 'Indonesia',
            'native' => 'Jawa',
            'source' => 'Alam Liar',
            'tim_id' => $tim->id,
            'tanaman' => [
                [
                    'scientific_name' => 'Shorea leprosula',
                    'author_name' => 'Author Test',
                    'tipe_tanaman' => 'tree',
                    'jumlah_material' => 10,
                    'collector_id' => $collector->id,
                    'locality' => 'Hutan A',
                    'vak_no' => 'VAK-1',
                ],
                [
                    'scientific_name' => 'Dipterocarpus',
                    'author_name' => 'Author Test',
                    'tipe_tanaman' => 'tree',
                    'jumlah_material' => 5,
                    'collector_id' => $collector->id,
                    'locality' => 'Hutan B',
                    'vak_no' => 'VAK-1',
                ],
                [
                    'scientific_name' => 'Agathis dammara',
                    'author_name' => 'Author Test',
                    'tipe_tanaman' => 'tree',
                    'jumlah_material' => 8,
                    'collector_id' => $collector->id,
                    'locality' => 'Hutan C',
                    'vak_no' => 'VAK-1',
                ]
            ]
        ];

        $response = $this->actingAs($this->peneliti)->post(route('peneliti.penerimaan.store'), $data);

        $response->assertRedirect(route('peneliti.penerimaan.index'));
        $this->assertDatabaseHas('penerimaans', [
            'tempat_asal' => 'Hutan Lindung'
        ]);
        $this->assertDatabaseHas('tanaman_infos', [
            'scientific_name' => 'Shorea leprosula'
        ]);
    }

    public function test_field_wajib_divalidasi_saat_penerimaan()
    {
        $data = [
            // missing required fields
            'tanggal_penerimaan' => '2026-05-14',
        ];

        $response = $this->actingAs($this->peneliti)->post(route('peneliti.penerimaan.store'), $data);

        $response->assertSessionHasErrors(['tanggal_explorasi', 'jenis_form', 'tempat_asal']);
    }
}
