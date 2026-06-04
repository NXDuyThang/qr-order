<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Category;
use App\Models\Food;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Phở & Bún', 'slug' => 'pho-bun', 'image' => '/images/pho-bo.png'],
            ['name' => 'Bánh Mì', 'slug' => 'banh-mi', 'image' => '/images/banh-mi.png'],
            ['name' => 'Món Đặc Sản', 'slug' => 'dac-san', 'image' => '/images/bun-dau-mam-tom.jpg'],
            ['name' => 'Đồ Uống', 'slug' => 'do-uong', 'image' => '/images/tra-tac.jpg'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        $foods = [
            ['category_id' => 1, 'name' => 'Phở Bò Đặc Biệt', 'slug' => 'pho-bo-dac-biet', 'price' => 65, 'description' => 'Nước dùng thanh ngọt, thịt bò tươi, quẩy giòn', 'image' => '/images/pho-bo.png'],
            ['category_id' => 1, 'name' => 'Bún Riêu Cua', 'slug' => 'bun-rieu-cua', 'price' => 55, 'description' => 'Bún riêu cua đồng, chả lụa, đậu hũ chiên, huyết, ốc', 'image' => '/images/bun-rieu.jpg'],
            ['category_id' => 2, 'name' => 'Bánh Mì Thịt Nướng', 'slug' => 'banh-mi-thit-nuong', 'price' => 25, 'description' => 'Bánh mì giòn, thịt nướng, patê, chả lụa, rau thơm', 'image' => '/images/banh-mi.png'],
            ['category_id' => 3, 'name' => 'Bún Đậu Mắm Tôm', 'slug' => 'bun-dau-mam-tom', 'price' => 60, 'description' => 'Bún lá, đậu hũ chiên, thịt luộc, chả cốm, mắm tôm', 'image' => '/images/bun-dau-mam-tom.jpg'],
            ['category_id' => 3, 'name' => 'Cơm Tấm Sườn Bì', 'slug' => 'com-tam-suon-bi', 'price' => 70, 'description' => 'Sườn nướng mật ong, bì lợn dai, chả trứng, nước mắm', 'image' => 'https://images.unsplash.com/photo-1627914225211-140b0f719dd6?w=800&q=80'],
            ['category_id' => 3, 'name' => 'Gỏi Cuốn Tôm Thịt', 'slug' => 'goi-cuon-tom-thit', 'price' => 20, 'description' => 'Tôm tươi, thịt luộc, bún, rau thơm, tương đen', 'image' => 'https://images.unsplash.com/photo-1555196301-8acc011dfb97?w=800&q=80'],
            ['category_id' => 4, 'name' => 'Trà Tắc', 'slug' => 'tra-tac', 'price' => 15, 'description' => 'Trà tắc chua ngọt mát lạnh, giải nhiệt ngày hè', 'image' => '/images/tra-tac.jpg'],
            ['category_id' => 4, 'name' => 'Trà Bí Đao Hạt Chia', 'slug' => 'tra-bi-dao', 'price' => 20, 'description' => 'Trà bí đao thơm mát kết hợp hạt chia bổ dưỡng', 'image' => '/images/tra-bi-dao.jpg'],
        ];

        foreach ($foods as $food) {
            Food::create($food);
        }
    }
}
