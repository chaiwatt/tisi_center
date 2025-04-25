<?php

use Illuminate\Database\Seeder;
use App\Models\Basic\Config;

class ConfigsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $config = Config::where('variable', 'industry_auth_url')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'industry_auth_url',
                              'note' => 'URL สำหรับใช้ Auth เข้าใช้ระบบ i-industry',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'industry_client_id')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'industry_client_id',
                              'note' => 'ClientID สำหรับ Auth เข้าใช้ระบบ i-industry',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'industry_client_secret')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'industry_client_secret',
                              'note' => 'ClientSecret สำหรับ Auth เข้าใช้ระบบ i-industry',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'industry_juristic_url')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'industry_juristic_url',
                              'note' => 'URL สำหรับใช้ดึงข้อมูลนิติบุคคลผ่านระบบ i-industry',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'industry_personal_url')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'industry_personal_url',
                              'note' => 'URL สำหรับใช้ดึงข้อมูลบุคคลธรรมดาผ่านระบบ i-industry',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'active_check_iindustry')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'active_check_iindustry',
                              'note' => 'เปิดใช้งานการเช็คลงทะเบียนใน i-industry',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'industry_ijuristicid_url')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'industry_ijuristicid_url',
                              'note' => 'URL สำหรับใช้เช็คข้อมูลนิติบุคคลว่าลงทะเบียนในระบบ i-industry หรือยัง',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
       }

        $config = Config::where('variable', 'url_register')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'url_register',
                              'note' => 'URL สำหรับลงทะเบียนเข้าใช้งานส่วนของผู้ประกอบการ',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'url_elicense_trader')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'url_elicense_trader',
                              'note' => 'URL สำหรับเข้าใช้งานระบบ e-License (ผู้ประกอบการ)',
                              'data' => 'https://itisi.go.th/e-license/',
                              'created_at' => date('Y-m-d H:i:s')
                        ]);
        }

        $config = Config::where('variable', 'url_elicense_staff')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'url_elicense_staff',
                              'note' => 'URL สำหรับเข้าใช้งานระบบ e-License (เจ้าหน้าที่)',
                              'data' => 'https://itisi.go.th/e-license/administrator/index.php',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'refresh_dashboard_value')->first();
        if(is_null($config)){
            Config::insert([
                          'variable' => 'refresh_dashboard_value',
                          'note' => 'เวลาที่ต้องการให้รีเฟรชข้อมูลหน้า Dashboard',
                          'data' => '1',
                          'created_at' => date('Y-m-d H:i:s')
                    ]);
        }

        $config = Config::where('variable', 'refresh_dashboard_unit')->first();
        if(is_null($config)){
            Config::insert([
                          'variable' => 'refresh_dashboard_unit',
                          'note' => 'หน่วยเวลาที่ต้องการให้รีเฟรชข้อมูลหน้า Dashboard',
                          'data' => 'M',
                          'created_at' => date('Y-m-d H:i:s')
                    ]);
        }

        $config = Config::where('variable', 'recaptcha_site_key')->first();
        if(is_null($config)){
            Config::insert([
                          'variable' => 'recaptcha_site_key',
                          'note' => 'คีย์ของเว็บไซต์',
                          'data' => '',
                          'created_at' => date('Y-m-d H:i:s')
                    ]);
        }

        $config = Config::where('variable', 'recaptcha_secret_key')->first();
        if(is_null($config)){
            Config::insert([
                          'variable' => 'recaptcha_secret_key',
                          'note' => 'คีย์ลับ',
                          'data' => '',
                          'created_at' => date('Y-m-d H:i:s')
                    ]);
        }

        $config = Config::where('variable', 'url_sso')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'url_sso',
                              'note' => 'URL Single Sign On (ผปก.)',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        //sso_esurveillance_client_id
        $config = Config::where('variable', 'sso_esurveillance_app_name')->first();
        if(is_null($config)){

            $config_old = Config::where('variable', 'sso_esurveillance_client_id')->first();
            if(!is_null($config_old)){//มีชื่อตัวแปรเดิม
                $config_old->variable = 'sso_esurveillance_app_name';
                $config_old->note = 'app_name สำหรับไซต์ e-Surveillance เชื่อมไป SSO';
                $config_old->save();
            }else{
                Config::insert([
                                  'variable' => 'sso_esurveillance_app_name',
                                  'note' => 'app_name สำหรับไซต์ e-Surveillance เชื่อมไป SSO',
                                  'data' => '',
                                  'created_at' => date('Y-m-d H:i:s')
                              ]);
            }

        }

        $config = Config::where('variable', 'sso_esurveillance_app_secret')->first();
        if(is_null($config)){

            $config_old = Config::where('variable', 'sso_esurveillance_client_secret')->first();
            if(!is_null($config_old)){//มีชื่อตัวแปรเดิม
                $config_old->variable = 'sso_esurveillance_app_secret';
                $config_old->note = 'app_secret สำหรับไซต์ e-Surveillance เชื่อมไป SSO';
                $config_old->save();
            }else{
                Config::insert([
                                  'variable' => 'sso_esurveillance_app_secret',
                                  'note' => 'app_secret สำหรับไซต์ e-Surveillance เชื่อมไป SSO',
                                  'data' => '',
                                  'created_at' => date('Y-m-d H:i:s')
                              ]);
            }

        }

        $config = Config::where('variable', 'sso_eaccreditation_app_name')->first();
        if(is_null($config)){

            $config_old = Config::where('variable', 'sso_eaccreditation_client_id')->first();
            if(!is_null($config_old)){//มีชื่อตัวแปรเดิม
                $config_old->variable = 'sso_eaccreditation_app_name';
                $config_old->note = 'app_name สำหรับไซต์ e-Accreditation เชื่อมไป SSO';
                $config_old->save();
            }else{
                Config::insert([
                                  'variable' => 'sso_eaccreditation_app_name',
                                  'note' => 'app_name สำหรับไซต์ e-Accreditation เชื่อมไป SSO',
                                  'data' => '',
                                  'created_at' => date('Y-m-d H:i:s')
                              ]);
            }

        }

        $config = Config::where('variable', 'sso_eaccreditation_app_secret')->first();
        if(is_null($config)){

            $config_old = Config::where('variable', 'sso_eaccreditation_client_secret')->first();
            if(!is_null($config_old)){//มีชื่อตัวแปรเดิม
                $config_old->variable = 'sso_eaccreditation_app_secret';
                $config_old->note = 'app_secret สำหรับไซต์ e-Accreditation เชื่อมไป SSO';
                $config_old->save();
            }else{
                Config::insert([
                                  'variable' => 'sso_eaccreditation_app_secret',
                                  'note' => 'app_secret สำหรับไซต์ e-Accreditation เชื่อมไป SSO',
                                  'data' => '',
                                  'created_at' => date('Y-m-d H:i:s')
                              ]);
            }

        }

        $config = Config::where('variable', 'sso_google2fa_status')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'sso_google2fa_status',
                              'note' => 'การเปิดใช้งาน Google Authenticator ในไซต์ SSO 0=ไม่เปิดใช้, 1=เปิดใช้งาน (ไม่บังคับ), 2=เปิดใช้งาน (่บังคับ)',
                              'data' => '0',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'sso_name_cookie_login')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'sso_name_cookie_login',
                              'note' => 'ชื่อ Cookie ที่เก็บค่า Session การ Login',
                              'data' => 'session_id',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'sso_domain_cookie_login')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'sso_domain_cookie_login',
                              'note' => 'ชื่อ Domain ของ Cookie ที่เก็บค่า Session การ Login',
                              'data' => 'localhost',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'tisi_api_corporation_url')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'tisi_api_corporation_url',
                              'note' => 'URL ดึงข้อมูลนิติบุคคล',
                              'data' => 'https://www3.tisi.go.th/moiapi/srv.asp?pid=1',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'tisi_api_person_url')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'tisi_api_person_url',
                              'note' => 'URL ดึงข้อมูลบุคคล',
                              'data' => 'https://www3.tisi.go.th/moiapi/srv.asp?pid=2',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'tisi_api_house_url')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'tisi_api_house_url',
                              'note' => 'URL ดึงข้อมูลทะเบียนบ้าน',
                              'data' => 'https://www3.tisi.go.th/moiapi/srv.asp?pid=3',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'tisi_api_factory_url')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'tisi_api_factory_url',
                              'note' => 'URL ดึงข้อมูลทะเบียนโรงงาน',
                              'data' => 'https://www3.tisi.go.th/moiapi/srv.asp?pid=4&val=',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'tisi_api_factory_url2')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'tisi_api_factory_url2',
                              'note' => 'URL ดึงข้อมูลทะเบียนโรงงาน (ค้นจากเลขทะเบียนเดิมได้)',
                              'data' => 'https://www3.tisi.go.th/json/moi.asp?srv=diwfac&fid=',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'tisi_api_factory_url3')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'tisi_api_factory_url3',
                              'note' => 'URL ดึงข้อมูลทะเบียนโรงงาน (ค้นได้เฉพาะเลขทะเบียนใหม่)',
                              'data' => 'https://www3.tisi.go.th/moiapi/srv.asp?pid=9&val=',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'tisi_api_faculty_url')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'tisi_api_faculty_url',
                              'note' => 'URL ดึงข้อมูลคณะบุคคล',
                              'data' => 'https://www3.tisi.go.th/moiapi/srv.asp?pid=5',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'officer_name_cookie_login')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'officer_name_cookie_login',
                              'note' => 'ชื่อ Cookie ที่เก็บค่า Session Login (เจ้าหน้าที่)',
                              'data' => 'session_officer',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'officer_domain_cookie_login')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'officer_domain_cookie_login',
                              'note' => 'ชื่อ Domain ของ Cookie ที่เก็บค่า Session Login (เจ้าหน้าที่)',
                              'data' => 'localhost',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'sso_login_fail_amount')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'sso_login_fail_amount',
                              'note' => 'จำนวนครั้งที่กรอกผิดแล้วให้ระบบ Lock ไม่ให้ใช้งาน',
                              'data' => '5',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'sso_login_fail_lock_time')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'sso_login_fail_lock_time',
                              'note' => 'เวลาที่ให้ Lock ไว้(นาที)',
                              'data' => '15',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'digital_signing_api_token')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'digital_signing_api_token',
                              'note' => 'API ขอ Token',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }
        $config = Config::where('variable', 'digital_signing_api_document_id')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'digital_signing_api_document_id',
                              'note' => 'API ขอ DocumentID',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }
        $config = Config::where('variable', 'digital_signing_api_esgnatures')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'digital_signing_api_esgnatures',
                              'note' => 'API ลงลายมือชื่ออิเล็กทรอนิกส์',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }
        $config = Config::where('variable', 'digital_signing_api_downlaod_signed')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'digital_signing_api_downlaod_signed',
                              'note' => 'API Downlaod PDF Signed',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }
        $config = Config::where('variable', 'digital_signing_api_revoked')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'digital_signing_api_revoked',
                              'note' => 'API เพิกถอนการใช้งานเอกสาร',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }
        $config = Config::where('variable', 'digital_signing_consumer_secret')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'digital_signing_consumer_secret',
                              'note' => 'ConsumerSecret',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }
        $config = Config::where('variable', 'digital_signing_agent_id')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'digital_signing_agent_id',
                              'note' => 'AgentID',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }
        $config = Config::where('variable', 'digital_signing_consumer_key')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'digital_signing_consumer_key',
                              'note' => 'Consumer-Key',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }
        $config = Config::where('variable', 'digital_signing_cb')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'digital_signing_cb',
                              'note' => 'ห้องหน่วยรับรอง',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }
        $config = Config::where('variable', 'digital_signing_ib')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'digital_signing_ib',
                              'note' => 'หน่วยตรวจสอบ',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }
        $config = Config::where('variable', 'digital_signing_lab')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'digital_signing_lab',
                              'note' => 'ห้องปฏิบัติการ',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }
        $config = Config::where('variable', 'info_contact')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'info_contact',
                              'note' => 'ข้อมูลติดต่อสอบถาม',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }
        $config = Config::where('variable', 'faculty_title_allow')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'faculty_title_allow',
                              'note' => 'คำนำหน้าผู้ประกอบการ (vTitleName) ที่มาจาก API กรมสรรพากร ที่จัดเป็นคณะบุคคล',
                              'data' => 'คณะบุคคล,สหกรณ์',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'url_center')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'url_center',
                              'note' => 'URL  Sign On (จนท.)',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'mail_center')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'mail_center',
                              'note' => 'เมลแจ้งเตือน (จนท.)',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'url_acc')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'url_acc',
                               'note' => 'URL Sign On Acc (ผปก.)',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'reference_number_lab')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'reference_number_lab',
                               'note' => 'จำนวนวันที่แจ้งเตือนต่ออายุ (LAB)',
                              'data' => '120',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'reference_refno_lab')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'reference_refno_lab',
                               'note' => 'เลขอ้างอิง (LAB)',
                              'data' => 'Sur,#_-,BE2,#_-,NO3',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'reference_number_ib')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'reference_number_ib',
                               'note' => 'จำนวนวันที่แจ้งเตือนต่ออายุ (IB)',
                              'data' => '120',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'reference_refno_ib')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'reference_refno_ib',
                               'note' => 'เลขอ้างอิง (IB)',
                              'data' => 'Sur,#_-,BE2,#_-,NO3',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'reference_number_cb')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'reference_number_cb',
                               'note' => 'จำนวนวันที่แจ้งเตือนต่ออายุ (CB)',
                              'data' => '120',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'reference_refno_cb')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'reference_refno_cb',
                               'note' => 'เลขอ้างอิง (CB)',
                              'data' => 'Sur,#_-,BE2,#_-,NO3',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }


        $config = Config::where('variable', 'digital_signing_api_attachment')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'digital_signing_api_attachment',
                              'note' => 'API Upload เอกสารแนบ',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'refresh_notification')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'refresh_notification',
                              'note' => 'ระยะเวลาที่รีเฟรชข้อมูลแจ้งเตือน (วินาที)',
                              'data' => '60',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'check_api_asurv_accept_export')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'check_api_asurv_accept_export',
                              'note' => 'ระบบรับคำขอการทำผลิตภัณฑ์เพื่อส่งออก (20 ตรี)',
                              'data' => '1',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'check_api_asurv_accept_import')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'check_api_asurv_accept_import',
                              'note' => 'ระบบรับคำขอการทำผลิตภัณฑ์เพื่อใช้ในประเทศเป็นการเฉพาะคราว (20 ทวิ)',
                              'data' => '1',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'check_api_asurv_accept21_export')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'check_api_asurv_accept21_export',
                              'note' => 'ระบบรับคำขอการนำเข้าผลิตภัณฑ์เพื่อส่งออก (21 ตรี)',
                              'data' => '1',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'check_api_asurv_accept21_import')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'check_api_asurv_accept21_import',
                              'note' => 'ระบบรับคำขอการนำเข้าผลิตภัณฑ์เพื่อใช้ในประเทศเป็นการเฉพาะคราว (21 ทวิ)',
                              'data' => '1',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'check_api_asurv_accept21own_import')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'check_api_asurv_accept21own_import',
                              'note' => 'ระบบรับคำขอการนำเข้าผลิตภัณฑ์เพื่อนำเข้ามาใช้เอง (21)',
                              'data' => '1',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'check_api_certify_check_certificate')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'check_api_certify_check_certificate',
                              'note' => 'ระบบตรวจสอบคำขอรับใบรับรองห้องปฏิบัติการ (LAB)',
                              'data' => '1',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'check_api_certify_check_certificate_ib')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'check_api_certify_check_certificate_ib',
                              'note' => 'ระบบตรวจสอบคำขอรับใบรับรองหน่วยตรวจ (IB)',
                              'data' => '1',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'check_api_certify_check_certificate_cb')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'check_api_certify_check_certificate_cb',
                              'note' => 'ระบบตรวจสอบคำขอรับหน่วยรับรอง (CB)',
                              'data' => '1',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }
        
        $config = Config::where('variable', 'check_electronic_certificate')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'check_electronic_certificate',
                              'note' => 'เปิดใช้งานใบรับรองระบบงานแบบดิจิทัล',
                              'data' => '0',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }



        $config = Config::where('variable', 'check_contact_mail_footer')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'check_contact_mail_footer',
                              'note' => 'เปิดใช้งานข้อมูลติดต่อสอบถาม (ข้อมูลติดต่อในอีเมล)',
                              'data' => '1',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'contact_mail_footer')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'contact_mail_footer',
                              'note' => 'ข้อมูลติดต่อสอบถาม (ข้อมูลติดต่อในอีเมล)',
                              'data' => '<p>  
                                            <b>สอบถามข้อมูลเพิ่มเติมได้ที่ : กองกฏหมาย</b><br>
                                                    &nbsp; -Tel. : 0-2430-6830 ต่อ 2000 <br>  
                                                    &nbsp; -E-mail. : law2022@tisi.go.th <br>  
                                                    &nbsp; -Line. : @law2022
                                            </p>',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'sso_law_app_name')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'sso_law_app_name',
                              'note' => 'app_name สำหรับไซต์ Law เชื่อมไป SSO',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'sso_law_app_secret')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'sso_law_app_secret',
                              'note' => 'app_secret สำหรับไซต์ Law เชื่อมไป SSO',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }
        $config = Config::where('variable', 'check_deduct_money')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'check_deduct_money',
                              'note' => 'หักเงินเก็บเป็นสวัสดีการ สมอ.',
                              'data' => '1',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }
        $config = Config::where('variable', 'number_deduct_money')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'number_deduct_money',
                              'note' => 'หักเงินเก็บเป็นสวัสดีการ สมอ. (อันตรา)',
                              'data' => '2',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }
        $config = Config::where('variable', 'agency_deduct_money')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'agency_deduct_money',
                              'note' => 'หักเงินเก็บเป็นสวัสดีการ สมอ. (หน่วยงาน)',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }
        $config = Config::where('variable', 'check_deduct_vat')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'check_deduct_vat',
                              'note' => 'หักภาษีมูลค่าเพิ่ม VAT',
                              'data' => '0',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }
        $config = Config::where('variable', 'number_deduct_vat')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'number_deduct_vat',
                              'note' => 'หักภาษีมูลค่าเพิ่ม VAT (อันตรา)',
                              'data' => '7',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }
        $config = Config::where('variable', 'agency_deduct_vat')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'agency_deduct_vat',
                              'note' => 'หักภาษีมูลค่าเพิ่ม VAT  (หน่วยงาน)',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $config = Config::where('variable', 'sso_section5_app_name')->first();
        if(is_null($config)){
            Config::insert([
                              'variable' => 'sso_section5_app_name',
                              'note' => 'app_name สำหรับระบบขึ้นทะเบียนตามมาตรา 5',
                              'data' => '',
                              'created_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $this->command->info('Insert Config Success!');
    }
}
