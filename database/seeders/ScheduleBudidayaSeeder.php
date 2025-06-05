<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchedulBudidaya;
use App\Models\KolamBudidaya;
use Illuminate\Support\Str;

class ScheduleBudidayaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada kolam_budidaya dulu
        $kolams = KolamBudidaya::pluck('id');

        if ($kolams->isEmpty()) {
            $this->command->warn('Tidak ada data kolam_budidaya, seeder tidak dijalankan.');
            return;
        }

        foreach (range(1, 20) as $i) {
            SchedulBudidaya::create([
                'kolam_budidaya_id' => $kolams->random(),
                'activity_type' => collect(['seed', 'feed', 'treatment', 'harvest'])->random(),
                'title' => fake()->sentence(3),
                'schedule_at' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
                'reminder_at' => fake()->time('H:i'),
                'is_done' => fake()->boolean(40),
                'note' => fake()->optional()->sentence(10),
            ]);
        }
    }
}
