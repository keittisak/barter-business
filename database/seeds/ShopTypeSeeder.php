<?php

use Illuminate\Database\Seeder;
use App\ShopType;

class ShopTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            'เฟอร์นิเจอร์ อุปกรณ์ ภายในบ้าน',
            'ร้านอาหารต่างๆ ภัตตาคาร',
            'โรงแรม รีสอร์ท ที่พัก',
            'คลินิก ยา อาหารเสริม',
            'เครื่องใช้ไฟฟ้า อุปกรณ์ในบ้าน',
            'คอมพิวเตอร์ อุปกรณ์คอมๆ',
            'เครื่องประดับ จิวเวอร์รี่ นาฬิกา',
            'เสื้อผ้า เครื่องแต่งกาย สูท',
            'เครื่องหนัง รองเท้า เข็มขัด อุปกรณ์',
            'ธุรกิจบริการ รถเช่า อุปกรณ์เช่าต่างๆ',
            'ชา กาแฟ ไอศกรีม เบเกอรี่ เครื่องดื่ม',
            'รถยนต์ มอเตอร์ไซต์ จักรยาน',
            'คาร์แคร์ อุปกรณ์ นำ้ยาทำความสะอาด',
            'เครื่องสำอางค์ เสริมสวย สปา น้ำหอม',
            'อสังหาๆ บ้าน ที่ดิน คอนโด',
            'อุปกรณ์สำนักงาน เครื่องใช้สำนักงาน',
            'เครื่องนอน ผ้าห่ม หมอน',
            'โฆษณา สิ่งพิมพ์ อินเตอร์เน็ต',
            'สบู่ แชมพู ครีม',
            'สัตว์เลี้ยง อาหารสัตว์ อุปกรณ์',
            'ทนายความ นักบัญชี สัมมนา',
            'เครื่องมือช่าง',
            'อุปกรณ์การเกษตร ปุ๋ย ยา',
            'เครื่องออกกำลังกาย ชุดและอุปกรณ์กีฬา',
            'ตกแต่งบ้าน สวน อาคาร รับเหมา',
        ];

        foreach($items as $item)
        {
        //     ShopType::updateOrCreate([
        //         'name' => $item['title'],
        //         'image' => $item['icon'] 
        //     ]);
        // }
            ShopType::updateOrCreate([
                'name' => $item
            ]);
        }

    }
}