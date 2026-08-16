<?php

namespace Database\Seeders;

use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class QuizQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'question' => 'Túi nilon cần khoảng 500 năm để phân hủy hoàn toàn trong môi trường tự nhiên?',
                'is_correct_true' => true,
                'explanation' => 'Túi nilon làm từ nhựa dầu mỏ rất khó phân hủy và có thể tồn tại từ 500 đến 1000 năm.',
                'is_active' => true,
            ],
            [
                'question' => 'Sử dụng ống hút tre thay ống hút nhựa không giúp ích gì cho môi trường?',
                'is_correct_true' => false,
                'explanation' => 'Ống hút tre có thể tái sử dụng và phân hủy sinh học, giảm lượng rác thải nhựa khổng lồ ra đại dương.',
                'is_active' => true,
            ],
            [
                'question' => 'Năng lượng mặt trời là một dạng năng lượng tái tạo?',
                'is_correct_true' => true,
                'explanation' => 'Năng lượng mặt trời, gió, thủy điện là các nguồn năng lượng tái tạo, không sinh ra khí thải nhà kính.',
                'is_active' => true,
            ],
            [
                'question' => 'Tái chế giấy tốn nhiều năng lượng hơn so với sản xuất giấy mới từ gỗ?',
                'is_correct_true' => false,
                'explanation' => 'Tái chế giấy tiết kiệm đến 60% năng lượng và giảm ô nhiễm nước đáng kể so với sản xuất giấy mới.',
                'is_active' => true,
            ],
            [
                'question' => 'Sản phẩm thân thiện môi trường (eco-friendly) không gây hại cho môi trường trong toàn bộ vòng đời của nó?',
                'is_correct_true' => true,
                'explanation' => 'Đúng vậy, từ khâu sản xuất, sử dụng đến khi tiêu hủy, sản phẩm eco đều giảm thiểu tác động xấu.',
                'is_active' => true,
            ]
        ];

        foreach ($questions as $q) {
            QuizQuestion::updateOrCreate(
                ['question' => $q['question']],
                $q
            );
        }
    }
}
