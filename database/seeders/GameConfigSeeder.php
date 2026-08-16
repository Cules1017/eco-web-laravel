<?php

namespace Database\Seeders;

use App\Models\GameConfig;
use Illuminate\Database\Seeder;

class GameConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            [
                'key' => 'game_enabled',
                'value' => '1',
                'description' => 'Bật/tắt tính năng game tặng voucher (1=bật, 0=tắt)'
            ],
            [
                'key' => 'daily_questions',
                'value' => '5',
                'description' => 'Số câu hỏi miễn phí mỗi ngày'
            ],
            [
                'key' => 'streak_required',
                'value' => '3',
                'description' => 'Số câu trả lời đúng liên tiếp để được lắc hũ'
            ],
            [
                'key' => 'win_probability',
                'value' => '30',
                'description' => 'Xác suất trúng voucher khi lắc hũ (%)'
            ],
            [
                'key' => 'game_voucher_id',
                'value' => '',
                'description' => 'ID của voucher làm phần thưởng'
            ]
        ];

        foreach ($configs as $config) {
            GameConfig::updateOrCreate(
                ['key' => $config['key']],
                ['value' => $config['value'], 'description' => $config['description']]
            );
        }
    }
}
