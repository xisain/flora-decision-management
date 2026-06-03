<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use SplFileObject;

class TanamanInfoSainSeeder extends Seeder
{
    public function run(): void
    {
        $csv = new SplFileObject(
            base_path('dev/data/20260513_Info_tanaman_Husain_tanaman_infos.csv')
        );
        $csv->setFlags(
            SplFileObject::READ_CSV |
            SplFileObject::SKIP_EMPTY |
            SplFileObject::DROP_NEW_LINE
        );

        $header = null;
        $chunk = [];

        foreach ($csv as $row) {
            if (! is_array($row) || $row === [null]) {
                continue;
            }

            if ($header === null) {
                $header = $row;

                continue;
            }

            $record = array_combine($header, $row);

            if ($record === false || empty($record['scientific_name'])) {
                continue;
            }

            $chunk[] = [
                'scientific_name' => $record['scientific_name'],
                'nama_lokal' => $this->nullIfEmpty($record['nama_lokal'] ?? null),
                'marga' => $this->nullIfEmpty($record['marga'] ?? null),
                'marga_jenis' => $this->nullIfEmpty($record['marga_jenis'] ?? null),
                'suku' => $this->nullIfEmpty($record['suku'] ?? null),
                'spesies' => $this->nullIfEmpty($record['spesies'] ?? null),
                'author_name' => $this->nullIfEmpty($record['author_name'] ?? null),
                'redlist_category' => $this->nullIfEmpty($record['redlist_category'] ?? null),
                'endemisitas' => $this->nullIfEmpty($record['endemisitas'] ?? null),
            ];

            if (count($chunk) === 500) {
                DB::table('tanaman_infos')->insert($chunk);
                $chunk = [];
            }
        }

        if ($chunk !== []) {
            DB::table('tanaman_infos')->insert($chunk);
        }
    }

    private function nullIfEmpty(?string $value): ?string
    {
        $trimmed = $value !== null ? trim($value) : null;

        return $trimmed === '' ? null : $trimmed;
    }
}
