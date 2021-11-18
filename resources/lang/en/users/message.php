<?php
/**
 * Language file for blog error/success messages
 *
 */

return [

    'user_exists'              => 'บัญชีผู้ใช้งานนี้มีผู้ใช้แล้ว กรุณากรอกชื่ออื่น',
    'user_not_found'           => 'ไม่พบบัญชีผู้ใช้งานนี้',
    'user_login_required'      => 'กรุณากรอกบัญชีผู้ใช้งาน',
    'user_password_required'   => 'กรุณากรอกรหัสผ่าน',
    'insufficient_permissions' => 'Insufficient Permissions.',
    'banned'              => 'banned',
    'suspended'             => 'suspended',

    'success' => [
        'create' => 'เพิ่มข้อมูลผู้ดูแลระบบสำเร็จ',
        'update' => 'แก้ไขข้อมูลผู้ดูแลระบบสำเร็จ',
        'delete' => 'ลบข้อมูลผู้ดูแลระบบสำเร็จ',
        'restore' => 'กู้คืนข้อมูลผู้ดูแลระบบสำเร็จ',
        'ban'       => 'User was successfully banned.',
        'unban'     => 'User was successfully unbanned.',
        'suspend'   => 'User was successfully suspended.',
        'unsuspend' => 'User was successfully unsuspended.',
        'restored'  => 'User was successfully restored.',
        'change_password_success' => 'แก้ไขรหัสผ่านสำเร็จ'
    ],

    'error' => [
        'create' => 'เพิ่มข้อมูลผู้ดูแลระบบไม่สำเร็จ \n กรุณาตรวจสอบข้อมูลอีกครั้ง',
        'update' => 'แก้ไขข้อมูลผู้ดูแลระบบไม่สำเร็จ \n กรุณาตรวจสอบข้อมูลอีกครั้ง',
        'delete' => 'ลบข้อมูลผู้ดูแลระบบไม่สำเร็จ \n กรุณาตรวจสอบข้อมูลอีกครั้ง',
        'restore' => 'กู้คืนข้อมูลผู้ดูแลระบบไม่สำเร็จ \n กรุณาตรวจสอบข้อมูลอีกครั้ง',
        'unsuspend' => 'There was an issue unsuspending the user. \n Please try again.',
        'file_type_error'   => 'Only jpg, jpeg, bmp, png extensions are allowed.',
        'password_incorrect' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง \n กรุณาตรวจสอบข้อมูลอีกครั้ง',
        'check_confirm_password' => 'รหัสผ่านใหม่ ไม่ตรงกับ ยืนยันรหัสผ่าน \n กรุณาตรวจสอบข้อมูลอีกครั้ง',
        'params_required' => 'ข้อมูลไม่ครบ \n กรุณาตรวจสอบข้อมูลอีกครั้ง',
    ],

];
