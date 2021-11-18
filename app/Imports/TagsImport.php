<?php

namespace App\Imports;

use App\Tag;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; //import ไฟล์นี้กรณีไม่ต้องการ insert header

class TagsImport implements ToModel, WithHeadingRow //เรียกใช้ WithHeadingRow กรณีไม่ต้องการ insert header
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        return new Tag([
            //ชื่อต้องตรงกับชื่อ header column ในไฟล์ excel 
            //ค่าใน excel จะเป็นตัวเล็กหรือตัวใหญ่ก็ได้ แต่ค่าที่เป็น key ของ $row ต้องเป็นตัวเล็กเท่านั้น (Tag_Name)
            //set ค่า default ให้กับ field ที่ไม่ได้ระบุใน $row ด้วยเพื่อให้ข้อมูลสมบูรณ์ 

            'tag_name'     => $row['tag_name'], 
            'tag_status'    => $row['tag_status'],

            'date'    => $row['date'],
            'type'    => $row['type'],
            'brief_time'    => $row['brief_time'],
            'checkout_time'    => $row['checkout_time'],
        ]);
    }
    
    public function headingRow(): int
    {
        return 1; //set start row
    }

    public function collection()
    {   
        return Tag::all();
    }
    
}
