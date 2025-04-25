<?php

use App\Models\WS\Client;

class HP_API
{

    //เช็คข้อมูล app_name, app_secret และสิทธิ์การใช้ API
    static function check_client($header, $API)
    {
        $status = true;
        $code   = '000';
        $msg    = '';

        $field_name   = 'app-name';
        $field_secret = 'app-secret';

        if(array_key_exists($field_name, $header) && count($header[$field_name]) > 0){
            $app_name = $header[$field_name][0];
        }else{
            $status = false;
            $code   = '101';
            $msg    = "$field_name is required.";
            goto end;
        }

        if(array_key_exists($field_secret, $header) && count($header[$field_secret]) > 0){
            $app_secret = $header[$field_secret][0];
        }else{
            $status = false;
            $code   = '102';
            $msg    = "$field_secret is required.";
            goto end;
        }

        $ws = Client::where('app_name', $app_name)->first();

        if(is_null($ws)){
            $status = false;
            $code   = '103';
            $msg    = "$field_name not found in the system.";
            goto end;
        }

        if($ws->app_secret!=$app_secret){
            $status = false;
            $code   = '104';
            $msg    = "$field_secret invalid.";
            goto end;
        }

        if($ws->state!=1){
            $status = false;
            $code   = '105';
            $msg    = "$field_name suspended.";
            goto end;
        }

        $api_list = json_decode($ws->ListAPI, true);
        if(!is_array($api_list) || !in_array(strtolower($API), $api_list)){
            $status = false;
            $code   = '106';
            $msg    = "$field_name is not licensed to use this service.";
            goto end;
        }

        end:
        return compact('status', 'code', 'msg');

    }

    /* Web Service */
    static function APILists()
    {
        return array(
                        'auth' => ['name' => 'auth',
                                   'detail' => '001 ดึงข้อมูลผู้ใช้จาก session id',
                                   'url' => 'api/v1/auth',
                                   'domain' => 'url_sso',
                                   'manual' => 'tisi_api_manual_auth_v1.0.5.pdf'
                                  ],
                        'login' => ['name' => 'login',
                                    'detail' => '002 ลงชื่อเข้าใช้งานระบบ',
                                    'url' => 'api/v1/login',
                                    'domain' => 'url_sso',
                                    'manual' => 'tisi_api_manual_login_v1.0.2.pdf'
                                   ],
                        'officer_auth' => [
                                            'name' => 'officer_auth',
                                            'detail' => '051 ดึงข้อมูลเจ้าหน้าที่ผู้งานใช้จาก session id',
                                            'url' => 'api/v1/officer_auth',
                                            'domain' => 'url_sso',
                                            'manual' => 'tisi_api_manual_officer_auth_v1.0.1.pdf'
                                        ],
                        'officer_login' => [
                                            'name' => 'officer_login',
                                            'detail' => '052 ลงชื่อเข้าใช้งานระบบเจ้าหน้าที่',
                                            'url' => 'api/v1/officer_login',
                                            'domain' => 'url_sso',
                                            'manual' => 'tisi_api_manual_officer_login_v1.0.2.pdf'
                                        ],
                        'officer_role' => [
                                            'name' => 'officer_role',
                                            'detail' => '053 ดึงข้อมูลกลุ่มผู้ใช้งานของเจ้าหน้าที่',
                                            'url' => 'api/v1/officer_role',
                                            'domain' => 'url_sso',
                                            'manual' => 'tisi_api_manual_officer_role_v1.0.0.pdf'
                                        ],
                        'elicense_no' => [
                                            'name' => 'elicense_no',
                                            'detail' => '201 API เชื่อมโยงข้อมูลใบอนุญาตด้วยเลขที่ใบอนุญาต',
                                            'url' => 'api/v1/elicense_no',
                                            'domain' => null,
                                            'manual' => 'tisi_api_manual_license_no_201_v1.0.1.pdf'
                                        ],
                        'tis_number' => [
                                            'name' => 'tis_number',
                                            'detail' => '202 API เชื่อมโยงข้อมูลใบอนุญาตด้วยเลขที่ มอก.',
                                            'url' => 'api/v1/tis_number',
                                            'domain' => null,
                                            'manual' => 'tisi_api_manual_tis_number_202_v1.1.0.pdf'
                                        ],
                        'tax_number' => [
                                            'name' => 'tax_number',
                                            'detail' => '203 API เชื่อมโยงข้อมูลใบอนุญาตด้วยเลขนิติบุคคล',
                                            'url' => 'api/v1/tax_number',
                                            'domain' => null,
                                            'manual' => 'tisi_api_manual_tax_number_203_v1.1.0.pdf'
                                        ],
                        'license_type' => [
                                            'name' => 'license_type',
                                            'detail' => '204 API เชื่อมโยงข้อมูลใบอนุญาตด้วยประเภทใบอนุญาต',
                                            'url' => 'api/v1/license_type',
                                            'domain' => null,
                                            'manual' => 'tisi_api_manual_license_type_204_v1.1.0.pdf'
                                        ],
                        'tis_standards' => [
                                            'name' => 'tis_standards',
                                            'detail' => '205 API เชื่อมโยงข้อมูล มอก.',
                                            'url' => 'api/v1/tis_standards',
                                            'domain' => null,
                                            'manual' => 'tisi_api_manual_tis_standards_v1.0.0.pdf'
                                        ],
                        'manufacturer_foreigns' => [
                                            'name' => 'manufacturer_foreigns',
                                            'detail' => '206 API เชื่อมโยงข้อมูลโรงงานที่ให้บริการ',
                                            'url' => 'api/v1/manufacturer_foreigns',
                                            'domain' => null,
                                            'manual' => 'tisi_api_manual_manufacturer_foreigns_v1.1.0.pdf'
                                        ],
                        'estandard' => [
                                            'name' => 'estandard',
                                            'detail' => '301 API เชื่อมโยงข้อมูลมาตรฐานการตรวจสอบและรับรอง',
                                            'url' => 'api/v1/estandard',
                                            'domain' => null,
                                            'manual' => 'tisi_api_manual_estandard_v1.0.0.pdf'
                                        ]
                    );
    }

    /* Web Service Status */
    static function APIStatus()
    {
        return array(
                        '000' => [
                                    'code' => '000',
                                    'name' => 'Success.',
                                    'css'  => 'success'
                                 ],
                        '101' => [
                                    'code' => '101',
                                    'name' => 'app-name is required.',
                                    'css'  => 'danger'
                                 ],
                        '102' => [
                                    'code' => '102',
                                    'name' => 'app-secret is required.',
                                    'css'  => 'danger'
                                 ],
                        '103' => [
                                    'code' => '103',
                                    'name' => 'app-name not found in the system.',
                                    'css'  => 'danger'
                                 ],
                        '104' => [
                                    'code' => '104',
                                    'name' => 'app-secret invalid.',
                                    'css'  => 'danger'
                                 ],
                        '105' => [
                                    'code' => '105',
                                    'name' => 'app-name suspended.',
                                    'css'  => 'danger'
                                 ],
                        '106' => [
                                    'code' => '106',
                                    'name' => 'app-name is not licensed to use this service.',
                                    'css'  => 'danger'
                                 ],
                        '200' => [
                                    'code' => '200',
                                    'name' => 'Invalid information.',
                                    'css'  => 'warning'
                                 ],
                        '500' => [
                                    'code' => '500',
                                    'name' => 'Session Id or User Agent incorrect.',
                                    'css'  => 'warning'
                                 ],
                        '501' => [
                                    'code' => '501',
                                    'name' => 'User login not found.',
                                    'css'  => 'warning'
                                 ],
                        '502' => [
                                     'code' => '502',
                                     'name' => 'Session Officer or User Agent incorrect.',
                                     'css'  => 'warning'
                                 ],
                        '503' => [
                                     'code' => '503',
                                     'name' => 'Username not found.',
                                     'css'  => 'warning'
                                 ],
                        '504' => [
                                     'code' => '504',
                                     'name' => 'Password incorrect.',
                                     'css'  => 'warning'
                                 ],
                        '505' => [
                                     'code' => '505',
                                     'name' => 'Username has not been verified in email.',
                                     'css'  => 'warning'
                                 ],
                        '506' => [
                                     'code' => '506',
                                     'name' => 'Username suspended.',
                                     'css'  => 'warning'
                                 ],
                    );
    }

}
