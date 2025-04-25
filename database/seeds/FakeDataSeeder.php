<?php

use Illuminate\Database\Seeder;
use App\BlogCategory;
use App\Blog;
use App\Tag;

class FakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories_data = ['การรับรองระบบงาน', 'การขออนุญาต', 'การมาตรฐานระหว่างประเทศ', 'ระบบ NSW', 'การฝึกอบรม'];
        foreach ($categories_data as $category_name){
            $category = BlogCategory::firstOrCreate(['title' => $category_name]);
            $category->slug = str_slug($category_name,'-');
            $category->slug = ($category->slug=='')?str_slug(md5($category_name), '-'):$category->slug;
            $category->save();
        }


        $blog = new Blog();
        $blog->title = 'การขอรับใบอนุญาต มอก.';
        $blog->slug = str_slug(md5($blog->title),'-');

        $message= '<h4><span style="font-family: times new roman,times; font-size: large;">การขอใบอนุญาตต่าง ๆ</span></h4>
                   <table class="table">	<tbody><!--<tr> <td>&#160;</td> <td><i class="fa fa-angle-double-right text-warning"></i></td> <td><a href="https://www.tisi.go.th/contents/details/1931"><span style="color: #000000;">&#3586;&#3633;&#3657;&#3609;&#3605;&#3629;&#3609;&#3649;&#3621;&#3632;&#3619;&#3632;&#3618;&#3632;&#3648;&#3623;&#3621;&#3634;&#3651;&#3609;&#3585;&#3634;&#3619;&#3586;&#3629;&#3629;&#3609;&#3640;&#3597;&#3634;&#3605;</span></a> </td> </tr> --> <tr> <td>&nbsp;</td> <td>&nbsp;</td> <td><a href="https://www.tisi.go.th/contents/details/2109" target="_blank"><span style="color: #000000;">หลักเกณฑ์ต่างๆ</span></a></td> </tr> <tr> <td>&nbsp;</td> <td>&nbsp;</td> <td><a href="https://www.tisi.go.th/website/standardlist/request_form" target="_blank"><span style="color: #000000;">แบบคำขอตามพระราชบัญญัติมาตรฐานผลิตภัณฑ์อุตสาหกรรม พ.ศ. ๒๕๑๑</span></a></td> </tr> <tr> <td>&nbsp;</td> <td>&nbsp;</td> <td><a href="https://www.tisi.go.th/data/pdf/Checklist.pdf" target="_blank"><span style="color: #000000;">รายชื่อมาตรฐานที่ สมอ. ถ่ายโอนให้หน่วยตรวจดำเนินการแทน</span></a></td> </tr> <tr> <td>&nbsp;</td> <td>&nbsp;</td> <td><a href="https://www.tisi.go.th/data/law/pdf_files/law1/tip211148.pdf" target="_blank"><span style="color: #000000;">ค่าธรรมเนียมในการขอใบอนุญาต</span></a></td> </tr> <tr> <td>&nbsp;</td> <td>&nbsp;</td> <td> <p><span style="color: #993300;">การนำเข้าผลิตภัณฑ์อุตสาหกรรมที่มีพระราชกฤษฎีกา กำหนดให้ต้องเป็นไปตามมาตรฐาน (26 ม.ค. 2558)</span></p> </td> </tr> <tr> <td>&nbsp;</td> <td>&nbsp;</td> <td> <p><a href="https://www.tisi.go.th/data/pdf/form_the_grid.xlsx" target="_blank"><span style="color:#0000ff;"><span style="font-size:16px;">Download แบบฟอร์ม</span> : แบบแจ้งปริมาณการ ทำ/นำเข้า ผลิตภัณฑ์ที่ต้องมีพระราชกฤษฎีกา&nbsp;กำหนดให้ต้องเป็นไปตามมาตรฐาน&nbsp;ตาม อก 0706/21891 (เหล็กทรงแบน)</span></a></p> </td> </tr> <tr> <td>&nbsp;</td> <td>&nbsp;</td> <td> <p><a href="https://www.tisi.go.th/data/pdf/formsv1g6.xls" target="_blank"><span style="color:#0000ff;"><span style="font-size:16px;">Download แบบฟอร์ม</span> : แบบแจ้งปริมาณการ ทำ/นำเข้า ผลิตภัณฑ์ที่ต้องมีพระราชกฤษฎีกากำหนดให้ต้องเป็นไปตามมาตรฐาน (เหล็กทรงยาว)</span></a></p> </td> </tr> <!--<td><a href="https://www.tisi.go.th/data/stories/license/pdf/2554/a101154_1d.pdf" target="_blank"><span style="color: #993300;">&#3649;&#3609;&#3623;&#3607;&#3634;&#3591;&#3585;&#3634;&#3619;&#3629;&#3629;&#3585;&#3651;&#3610;&#3629;&#3609;&#3640;&#3597;&#3634;&#3605;&#3651;&#3627;&#3657;&#3609;&#3635;&#3648;&#3586;&#3657;&#3634;&#3648;&#3593;&#3614;&#3634;&#3632;&#3588;&#3619;&#3633;&#3657;&#3591;&#3626;&#3635;&#3627;&#3619;&#3633;&#3610;&#3612;&#3641;&#3657;&#3652;&#3604;&#3657;&#3619;&#3633;&#3610;&#3612;&#3621;&#3585;&#3619;&#3632;&#3607;&#3610;&#3592;&#3634;&#3585;&#3629;&#3640;&#3607;&#3585;&#3616;&#3633;&#3618;</span></a> </td>-->	</tbody></table>
        ';
        $dom = new \DOMDocument();
        $dom->loadHtml('<?xml encoding="utf-8" ?>'.$message);
        $blog->content = $dom->saveHTML();
        $blog->blog_category_id = 1;
        $blog->user_id = 1;
        $blog->save();

        //Adding tags
        $tags_data = "การขอใบอนุอนุญาต";
            $tag_ids = [];
            $tags = explode(',',$tags_data);
            foreach ($tags as $item){
                $tag =  Tag::where('slug','=', str_slug(md5($item), '-'))->first();
                if($tag == null){
                    $tag = new Tag();
                    $tag->name = $item;
                    $tag->slug = str_slug(md5($item),'-');
                    $tag->save();
                }
                $tag_ids[]= $tag->id;
            }
        if($tag_ids != null){
            $blog->tags()->attach($tag_ids);
        }


        $blog = new Blog();
        $blog->title = 'ฝึกอบรม Overview of product safety activity  in Thailand and Japan ครั้งที่ 1';
        $blog->slug = str_slug($blog->title,'-');
        $message= '<p><img data-filename="2019082109312819539.jpg" style="width: 100%;" src="https://192.168.0.244:888/itisi-center/public/storage/uploads/blog/5d60e8afa5812.jpeg"></p><p>วันนี้(20 สิงหาคม 2562) นายวีระกิตติ์ รันทกิจธนวัชร์ รองเลขาธิการ สมอ.
เป็นประธานร่วมกับ Ms.Naomi KIJIMOTO ผู้แทน Product Safety Division แห่ง
Ministry of Economy, Trade and Industry(METI),Japan เปิดการฝึกอบรม
Overview of product safety activity &nbsp;in Thailand and Japan ครั้งที่ 1
ระหว่างวันที่ 20-21 สิงหาคม 2562 ณ โรงแรม เดอะ เบอร์เคลีย์ กรุงเทพฯ
&nbsp;โดยการฝึกอบรมครั้งนี้เป็นความร่วมมือระหว่าง METI &nbsp;สมอ. National
Institute of Technology and Evaluation (NITE) และ The Association for
Overseas Technical Scholarship&nbsp;(AOTS)
ซึ่งการฝึกอบรมดังกล่าวได้จัดต่อเนื่องเป็นปีที่ 3 แล้ว
โดยมีวัตถุประสงค์เพื่อศึกษาวิธีการเก็บข้อมูลอุบัติเหตุที่เกี่ยวข้องกับผลิตภัณฑ์
 และการวิเคราะห์สาเหตุของอุบัติเหตุที่เกิดขึ้นกับผลิตภัณฑ์
มีผู้เข้าร่วมฝึกอบรม จาก สำนักงานตำรวจแห่งชาติ<br>สำนักงานคณะกรรมการคุ้มครองผู้บริโภค และ สมอ. รวมทั้งห้องปฏิบัติการต่างๆ<br></p>
';
        $dom = new \DOMDocument();
        $dom->loadHtml('<?xml encoding="utf-8" ?>'.$message);
        $blog->content = $dom->saveHTML();
        $blog->blog_category_id = 5;
        $blog->user_id = 1;
        $blog->save();

        //Adding tags
        $tags_data = "ฝึกอบรม";
        $tag_ids = [];
        $tags = explode(',',$tags_data);
        foreach ($tags as $item){
            $tag =  Tag::where('slug','=',str_slug(md5($item), '-'))->first();
            if($tag == null){
                $tag = new Tag();
                $tag->name = $item;
                $tag->slug = str_slug(md5($item),'-');
                $tag->save();
            }
            $tag_ids[]= $tag->id;
        }
        if($tag_ids != null){
            $blog->tags()->attach($tag_ids);
        }

    }
}
