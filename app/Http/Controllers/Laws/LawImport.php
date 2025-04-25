<?php

namespace App\Http\Controllers\Laws;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use HP;
use HP_Law;

use App\Models\Law\Cases\LawCasesForm;
use App\Models\Law\Basic\LawSection;;

use App\Models\Sso\User AS SSO_USER;
use App\Models\Basic\TisiLicense;
use App\Models\Basic\Tis;

use App\Models\Law\Offense\LawOffender;
use App\Models\Law\Offense\LawOffenderCases;
use App\Models\Law\Offense\LawOffenderStandard;
use App\Models\Law\Offense\LawOffenderLicense;
use App\Models\Law\Offense\LawOffenderProduct;

use App\Models\Law\Books\LawBookManage;
use App\Models\Law\Books\LawBookManageAccess;
use App\Models\Law\Books\LawBookManageVisit;
class LawImport extends Controller
{
    public function index(Request $request)
    {
        set_time_limit(0);


        $law_section = LawSection::pluck('number', 'id')->toArray();

        $status_arr  = [ 1 => 'รอดำเนินการ', 2 => 'อยู่ระหว่างดำเนินการ', 3 => 'ดำเนินการเรียบร้อย' ];

        $users = SSO_USER::where(function($query) {
                                $query->WhereNotNull('tax_number');
                            })
                            ->where(function($query) {
                                // $query->where( 'name', 'NOT LIKE', 'บริษัท%');
                            })
                            // ->select(

                            //     'id','tax_number', 'name', 'applicanttype_id',

                            //     //ที่อยู่
                            //     'address_no', 'moo', 'soi', 'building', 'street', 'subdistrict', 'district', 'province', 'zipcode', 'tel', 'fax', 'email'
                            // )
                            ->get();
        // exit;
        $imp = DB::table('law_offenders_imp')
                        // ->where(function($query) {
                        //     $query->where( 'offender_name', 'NOT LIKE', 'บริษัท%');
                        // })
                        ->where(function($query) {
                            // $query->whereNull('sso_users_id');
                        })
                        ->where(function($query) {
                            // $query->whereNull('case_number');
                        })
                        ->where(function($query) {
                            $query->where('offender_taxid', '0999990001371');
                        })
                        ->where(function($query)  {
                            $offender = LawOffender::select('taxid');
                            // $query->whereNotIn('offender_taxid', $offender );
                        })
                        ->where(function($query) {

                            // $id = DB::table('law_offenders_imp')->groupBy('case_number')
                            //                                     // ->having(DB::raw('case_number'), '>=', 2 )
                            //                                     ->having(DB::raw('count(offender_taxid)'), '=', 1 )
                            //                                     ->pluck('case_number');

                            // $query->whereIn('case_number', $id );
                        })
                        ->orderbyRaw('CONVERT(offender_name USING tis620)')
                        // ->limit(100)
                        ->get();


        $i = 0;
        $tax_gen = '0999990000000';
        foreach( $imp AS $item ){


            $user                                     =  $users->where('id', $item->sso_users_id  )->first();

            if( !empty($user) ){

                $address                              = HP::GetIDAddress( $user->subdistrict, $user->district, $user->province );

                // $offender = LawOffender::updateOrCreate(
                //     [
                //         'sso_users_id'     => $user->id,
                //     ],
                //     [
                //         'sso_users_id'     => $user->id,
                //         'type_id'          => !empty($user->applicanttype_id)?$user->applicanttype_id:null,
                //         'name'             => !empty($user->name)?$user->name:null,
                //         'taxid'            => !empty($user->tax_number)?$user->tax_number:null,
    
                //         //ที่ตั้งสำนักงานใหญ่
                //         'address_no'       => !empty($user->address_no)?$user->address_no:null,
                //         'moo'              => !empty($user->moo)?$user->moo:null,
                //         'soi'              => !empty($user->soi)?$user->soi:null,
                //         'building'         => !empty($user->building)?$user->building:null,
                //         'street'           => !empty($user->street)?$user->street:null,
                //         'subdistrict_id'   => !empty($address->subdistrict_id)?$address->subdistrict_id:null,
                //         'district_id'      => !empty($address->district_id)?$address->district_id:null,
                //         'province_id'      => !empty($address->province_id)?$address->province_id:null,
                //         'zipcode'          => !empty($address->zipcode)?$address->zipcode:null,
    
                //         'tel'              => !empty($user->tel)?$user->tel:null,
                //         'fax'              => !empty($user->fax)?$user->fax:null,
                //         'email'            => !empty($user->email)?$user->email:null,
                        
                //         //ผู้ประสานงาน
                //         'contact_name'     => (!empty($user->contact_first_name)?$user->contact_first_name:null).(!empty($user->contact_last_name)?' '.$user->contact_last_name:null),
                //         'contact_position' => !empty($user->contact_position)?$user->contact_position:null,
                //         'contact_mobile'   => !empty($user->contact_phone_number)?$user->contact_phone_number:null,
                //         'contact_phone'    => !empty($user->contact_tel)?$user->contact_tel:null,
                //         'contact_fax'      => !empty($user->contact_fax)?$user->contact_fax:null,
                //         'contact_email'    => null,
    
                //         'import_data'      => 1,
                //         'remark'           => !empty($item->remark)?$item->remark:null,
                //         'state'            => 1,
    
                //         'created_by'       => Auth::user()->getKey()
                //     ]

                // );

                // LawOffenderCases::updateOrCreate(
                //     [
                //         'law_offender_id'          => $offender->id,
                //         'case_number'              => $item->case_number
                //     ],
                //     [
                //         'law_offender_id'          => $offender->id,
                //         'case_number'              => $item->case_number,
                //         //ฝ่าฝืนตามมาตรา
                //         'section'                  => !empty($item->section)?$item->section:null,
                //         //ดำเนินการทางอาญา
                //         'case_person'              => 1,
                //         //ดำเนินการปกครอง
                //         'case_license'             => 0,
                //         //ดำเนินการของกลาง
                //         'case_product'             => 1,
                //         //นิติกรเจ้าคดี
                //         'lawyer_by'                => !empty($item->lawyer)?$item->lawyer:null,
                //         //สถานะ
                //         'status'                   => !empty($item->status)?$item->status:null,
                //         //ดำเนินคดี
                //         'prosecute'                => !empty($item->prosecute)?1:0,
                //         //ครั้งที่กระทำความผิด
                //         'episode_offenders'        => !empty($item->episode_offenders)?$item->episode_offenders:null,
                //         //มูลค่าของกลาง
                //         'total_price'              => !empty($item->total_price)?$item->total_price:null,
                //         //ค่าปรับ
                //         'total_compare'            => !empty($item->total_compare)?$item->total_compare:null,

                //         'payment_date'             => !empty($item->payment_date)?$item->payment_date:null,

                //         'power'                    => !empty($item->power)?$item->power:null,

                //         'power_present_date'       => !empty($item->power_present_date)?$item->power_present_date:null,

                //         'approve_date'             => !empty($item->approve_date)?$item->approve_date:null,

                //         'assign_date'              => !empty($item->assign_date)?$item->assign_date:null,

                //         'tisi_present'             => !empty($item->tisi_present)?$item->tisi_present:null,

                //         'tisi_dictation_no'        => !empty($item->tisi_dictation_no)?$item->tisi_dictation_no:null,

                //         'tisi_dictation_date'      => !empty($item->tisi_dictation_date)?$item->tisi_dictation_date:null,

                //         'tisi_dictation_cppd'      => !empty($item->tisi_dictation_cppd)?$item->tisi_dictation_cppd:null,

                //         'tisi_dictation_company'   => !empty($item->tisi_dictation_cppd)?$item->tisi_dictation_cppd:null,

                //         'tisi_dictation_committee' => !empty($item->tisi_dictation_committee)?$item->tisi_dictation_committee:null,

                //         'cppd_result'              => !empty($item->cppd_result)?$item->cppd_result:null,

                //         'result_summary'           => !empty($item->result_summary)?$item->result_summary:null,

                //         'destroy_date'             => !empty($item->destroy_date)?$item->destroy_date:null,

                //     ]
                // );

                // if( !empty(  $item->tis_no ) ){
                //     LawOffenderStandard::updateOrCreate(
                //         [
                //             'law_offender_id'          => $offender->id,
                //             'case_number'              => $item->case_number,
                //             'tb3_tisno'                => $item->tis_no,
                //         ],
                //         [
                //             'law_offender_id'          => $offender->id,
                //             'case_number'              => $item->case_number,
                //             'tis_id'                   => $item->tis_id,
                //             'tb3_tisno'                => $item->tis_no,
                //         ]
                //     );
                // }

                // if( !empty(  $item->product ) ){
                //     LawOffenderProduct::updateOrCreate(
                //         [
                //             'law_offender_id'          => $offender->id,
                //             'case_number'              => $item->case_number,
                //             'detail'                   => $item->product,
                //         ],
                //         [
                //             'law_offender_id'          => $offender->id,
                //             'case_number'              => $item->case_number,
                //             'detail'                   => $item->product,
                //             'amount'                   => $item->amount,
                //             'unit'                     => $item->unit,
                //             'total_price'              => $item->total_price,

                //         ]
                //     );
                // }
                // $i++;

            }else{
                // $offender_name = str_replace(' ', '', $item->offender_name);
                // $case_number   = str_replace(' ', '', $item->case_number);


                // $not_in[ $offender_name ][] = $item;
                
                $offender = LawOffender::updateOrCreate(
                    [
                        // 'sso_users_id'     => $user->id,
                        'taxid'            => !empty($item->offender_taxid)?$item->offender_taxid:null,
                    ],
                    [
                        // 'sso_users_id'     => $user->id,
                        // 'type_id'          => !empty($user->applicanttype_id)?$user->applicanttype_id:null,
                        'name'             => !empty($item->offender_name)?$item->offender_name:null,
                        'taxid'            => !empty($item->offender_taxid)?$item->offender_taxid:null,
    
                        //ที่ตั้งสำนักงานใหญ่
                        // 'address_no'       => !empty($user->address_no)?$user->address_no:null,
                        // 'moo'              => !empty($user->moo)?$user->moo:null,
                        // 'soi'              => !empty($user->soi)?$user->soi:null,
                        // 'building'         => !empty($user->building)?$user->building:null,
                        // 'street'           => !empty($user->street)?$user->street:null,
                        // 'subdistrict_id'   => !empty($address->subdistrict_id)?$address->subdistrict_id:null,
                        // 'district_id'      => !empty($address->district_id)?$address->district_id:null,
                        // 'province_id'      => !empty($address->province_id)?$address->province_id:null,
                        // 'zipcode'          => !empty($address->zipcode)?$address->zipcode:null,
    
                        // 'tel'              => !empty($user->tel)?$user->tel:null,
                        // 'fax'              => !empty($user->fax)?$user->fax:null,
                        // 'email'            => !empty($user->email)?$user->email:null,
                        
                        //ผู้ประสานงาน
                        'contact_name'     => !empty($item->offender_name)?$item->offender_name:null,
                        // 'contact_position' => !empty($user->contact_position)?$user->contact_position:null,
                        // 'contact_mobile'   => !empty($user->contact_phone_number)?$user->contact_phone_number:null,
                        // 'contact_phone'    => !empty($user->contact_tel)?$user->contact_tel:null,
                        // 'contact_fax'      => !empty($user->contact_fax)?$user->contact_fax:null,
                        // 'contact_email'    => null,
    
                        'import_data'      => 1,
                        'remark'           => !empty($item->remark)?$item->remark:null,
                        'state'            => 1,
    
                        'created_by'       => Auth::user()->getKey()
                    ]

                );

                LawOffenderCases::updateOrCreate(
                    [
                        'law_offender_id'          => $offender->id,
                        'case_number'              => $item->case_number
                    ],
                    [
                        'law_offender_id'          => $offender->id,
                        'case_number'              => $item->case_number,
                        //ฝ่าฝืนตามมาตรา
                        'section'                  => !empty($item->section)?$item->section:null,
                        //ดำเนินการทางอาญา
                        'case_person'              => 1,
                        //ดำเนินการปกครอง
                        'case_license'             => 0,
                        //ดำเนินการของกลาง
                        'case_product'             => 1,
                        //นิติกรเจ้าคดี
                        'lawyer_by'                => !empty($item->lawyer)?$item->lawyer:null,
                        //สถานะ
                        'status'                   => !empty($item->status)?$item->status:null,
                        //ดำเนินคดี
                        'prosecute'                => !empty($item->prosecute)?1:0,
                        //ครั้งที่กระทำความผิด
                        'episode_offenders'        => !empty($item->episode_offenders)?$item->episode_offenders:null,
                        //มูลค่าของกลาง
                        'total_price'              => !empty($item->total_price)?$item->total_price:null,
                        //ค่าปรับ
                        'total_compare'            => !empty($item->total_compare)?$item->total_compare:null,

                        'payment_date'             => !empty($item->payment_date)?$item->payment_date:null,

                        'power'                    => !empty($item->power)?$item->power:null,

                        'power_present_date'       => !empty($item->power_present_date)?$item->power_present_date:null,

                        'approve_date'             => !empty($item->approve_date)?$item->approve_date:null,

                        'assign_date'              => !empty($item->assign_date)?$item->assign_date:null,

                        'tisi_present'             => !empty($item->tisi_present)?$item->tisi_present:null,

                        'tisi_dictation_no'        => !empty($item->tisi_dictation_no)?$item->tisi_dictation_no:null,

                        'tisi_dictation_date'      => !empty($item->tisi_dictation_date)?$item->tisi_dictation_date:null,

                        'tisi_dictation_cppd'      => !empty($item->tisi_dictation_cppd)?$item->tisi_dictation_cppd:null,

                        'tisi_dictation_company'   => !empty($item->tisi_dictation_cppd)?$item->tisi_dictation_cppd:null,

                        'tisi_dictation_committee' => !empty($item->tisi_dictation_committee)?$item->tisi_dictation_committee:null,

                        'cppd_result'              => !empty($item->cppd_result)?$item->cppd_result:null,

                        'result_summary'           => !empty($item->result_summary)?$item->result_summary:null,

                        'destroy_date'             => !empty($item->destroy_date)?$item->destroy_date:null,

                    ]
                );

                if( !empty(  $item->tis_no ) ){
                    LawOffenderStandard::updateOrCreate(
                        [
                            'law_offender_id'          => $offender->id,
                            'case_number'              => $item->case_number,
                            'tb3_tisno'                => $item->tis_no,
                        ],
                        [
                            'law_offender_id'          => $offender->id,
                            'case_number'              => $item->case_number,
                            'tis_id'                   => $item->tis_id,
                            'tb3_tisno'                => $item->tis_no,
                        ]
                    );
                }

                if( !empty(  $item->product ) ){
                    LawOffenderProduct::updateOrCreate(
                        [
                            'law_offender_id'          => $offender->id,
                            'case_number'              => $item->case_number,
                            'detail'                   => $item->product,
                        ],
                        [
                            'law_offender_id'          => $offender->id,
                            'case_number'              => $item->case_number,
                            'detail'                   => $item->product,
                            'amount'                   => $item->amount,
                            'unit'                     => $item->unit,
                            'total_price'              => $item->total_price,

                        ]
                    );
                }
                $i++;
            }

            // $offender_name            = str_replace(' ', '', $item->offender_name);
            // if( array_key_exists(  $offender_name,  $not_in ) ){
            //     $DataSave['offender_taxid'] = $not_in[  $offender_name  ];
            // }else{
            //     if( !array_key_exists(  $offender_name,  $not_in ) && empty($item->offender_taxid ) ){

            //         $strlen = strlen($item->id);
            //         $Seq = substr(  $tax_gen,0 , (13 - $strlen)  );
            //         $new_tax   =   $Seq.$item->id;

            //         $DataSave['offender_taxid'] = $new_tax;
            //         $not_in[ $offender_name ] = $new_tax;
            //     }
            // }

            // if( !empty($DataSave)){
            //     $i++;
            //     DB::table('law_offenders_imp')->where('id', $item->id )->update($DataSave);
            // }

            // if( !empty($item->power_present_date) && date("Y", strtotime($item->power_present_date)) >= 2500 ){
            //     $DataSave['power_present_date'] = !empty($item->power_present_date)?Carbon::parse($item->power_present_date)->subYear('543')->format('Y-m-d'):null;
            // }

            // if( !empty($item->approve_date) && date("Y", strtotime($item->approve_date)) >= 2500 ){
            //     $DataSave['approve_date'] = !empty($item->approve_date)?Carbon::parse($item->approve_date)->subYear('543')->format('Y-m-d'):null;
            // }

            // if( !empty($item->payment_date) && date("Y", strtotime($item->payment_date)) >= 2500 ){
            //     $DataSave['payment_date'] = !empty($item->payment_date)?Carbon::parse($item->payment_date)->subYear('543')->format('Y-m-d'):null;
            // }

            // if( !empty($item->tisi_dictation_date) && date("Y", strtotime($item->tisi_dictation_date)) >= 2500 ){
            //     $DataSave['tisi_dictation_date'] = !empty($item->tisi_dictation_date)?Carbon::parse($item->tisi_dictation_date)->subYear('543')->format('Y-m-d'):null;
            // }
        
            // if( !empty($item->destroy_date) && date("Y", strtotime($item->destroy_date)) >= 2500 ){
            //     $DataSave['destroy_date'] = !empty($item->destroy_date)?Carbon::parse($item->destroy_date)->subYear('543')->format('Y-m-d'):null;
            // }
            
            // //วันที่หมอบหมาย
            // if( !empty($item->assign_date) && date("Y", strtotime($item->assign_date)) >= 2500 ){
            //     $DataSave['assign_date'] = !empty($item->assign_date)?Carbon::parse($item->assign_date)->subYear('543')->format('Y-m-d'):null;
            // }

            // if( !empty($item->tisi_present) && Carbon::hasFormat($item->tisi_present, 'Y-m-d') ){
            //     $DataSave['tisi_present'] = !empty($item->tisi_present)?Carbon::parse($item->tisi_present)->format('d/m/Y'):null;
            // }

            // if( !empty($item->result_summary) && Carbon::hasFormat($item->result_summary, 'Y-m-d') ){
            //     $DataSave['result_summary'] = !empty($item->result_summary)?Carbon::parse($item->result_summary)->format('d/m/Y'):null;
            // }

            // if( !empty($item->cppd_result) && Carbon::hasFormat($item->cppd_result, 'Y-m-d') ){
            //     $DataSave['cppd_result'] = !empty($item->cppd_result)?Carbon::parse($item->cppd_result)->format('d/m/Y'):null;
            // }

            // if( !empty($item->tisi_dictation_cppd) && Carbon::hasFormat($item->tisi_dictation_cppd, 'Y-m-d') ){
            //     $DataSave['tisi_dictation_cppd'] = !empty($item->tisi_dictation_cppd)?Carbon::parse($item->tisi_dictation_cppd)->format('d/m/Y'):null;
            // }

            // if( !empty($item->tisi_dictation_company) && Carbon::hasFormat($item->tisi_dictation_company, 'Y-m-d') ){
            //     $DataSave['tisi_dictation_company'] = !empty($item->tisi_dictation_company)?Carbon::parse($item->tisi_dictation_company)->format('d/m/Y'):null;
            // }

            // if( !empty($item->tisi_dictation_committee) && Carbon::hasFormat($item->tisi_dictation_committee, 'Y-m-d') ){
            //     $DataSave['tisi_dictation_committee'] = !empty($item->tisi_dictation_committee)?Carbon::parse($item->tisi_dictation_committee)->format('d/m/Y'):null;
            // }

            // if( !empty($DataSave)){
            //     $i++;
            //     DB::table('law_offenders_imp')->where('id', $item->id )->update($DataSave);
            // }

        }

        // foreach( $not_in AS $name => $item ){

        //     if( count($item) >= 2 ){
        //         echo $name;
        //         echo '<br>';
        //         foreach( $item AS $list ){
        //             echo $list->case_number.' | '.$list->id;
        //             echo '<br>';
        //         }

        //         echo '<hr>';
        //     }

        // }

        echo $i;
        // echo '<hr>';
        // print_r(implode(', ',$not_in));
        // dd( $not_in);
        exit;

        
        $imp = DB::table('law_book_manage_imp')->orderBy('id')->get();
        $i = 0;
        foreach( $imp AS $item ){

            $cut = explode(',',$item->tag_txt);
            $tag = null;
            if( is_array( $cut ) ){

                $tag = isset($cut)?json_encode($cut):null;

            }

            // if( !empty($item->operation_date) && date("Y", strtotime($item->operation_date)) >= 2500 ){
            //     $DataSave['operation_date'] = !empty($item->operation_date)?Carbon::parse($item->operation_date)->subYear('543')->format('Y-m-d'):null;
                
            // }

            // if( !empty($DataSave)){
            //     $i++;
            //     DB::table('law_book_manage_imp')->where('id', $item->id )->update($DataSave);
            // }


            $book =  LawBookManage::updateOrCreate(
                [
                    'id_imp' => $item->id
                ],
                [
                    'id_imp' => $item->id,

                    'title'               => $item->title,
                    'important'           => $item->title,
                    'basic_book_group_id' => $item->basic_book_group_id,
                    'basic_book_type_id'  => $item->basic_book_type_id,

                    'owner'               => $item->owner,
                    'lawyer'              => $item->lawyer,
                    'operation_date'      => $item->operation_date,
                    'ordering'            => $item->ordering,

                    'tag'                 =>  $tag,


                    'date_publish'        => date('Y-m-d'),
                    'created_by'          => Auth::user()->getKey(),
                    'state'               => 1,

                ]
            );

            LawBookManageAccess::updateOrCreate(

                [
                    'law_book_manage_id' => $book->id
                ],
                [
                    'law_book_manage_id' => $book->id,
                    'access'             => '["3"]'

                ] 
            );


        }


        dd( $imp );
    }

    
    public static function Imp_case()
    {

        $imp_new = DB::table('law_offenders_cases_copy1')->get();

        // $imp_new = DB::table('law_offenders_imp')->get();
        
        foreach( $imp_new AS $item ){

            // dd($item);

            // if( !empty($item->{'วดป.ได้รับมอบหมาย'}) && date("Y", strtotime($item->{'วดป.ได้รับมอบหมาย'})) >= 2500 ){
            //     $DataSave['assign_date'] = !empty($item->{'วดป.ได้รับมอบหมาย'})?Carbon::parse($item->{'วดป.ได้รับมอบหมาย'})->subYear('543')->format('Y-m-d'):null;
            // }else{
            //     $DataSave['assign_date'] = null;
            // }

            // if( !empty($item->{'วันที่เสนอ'}) && date("Y", strtotime($item->{'วันที่เสนอ'})) >= 2500 ){
            //     $DataSave['power_present_date'] = !empty($item->{'วันที่เสนอ'})?Carbon::parse($item->{'วันที่เสนอ'})->subYear('543')->format('Y-m-d'):null;
            // }else{
            //     $DataSave['power_present_date'] = null;
            // }

            // if( !empty($item->{'วันที่อนุมัติ'}) && date("Y", strtotime($item->{'วันที่อนุมัติ'})) >= 2500 ){
            //     $DataSave['approve_date'] = !empty($item->{'วันที่อนุมัติ'})?Carbon::parse($item->{'วันที่อนุมัติ'})->subYear('543')->format('Y-m-d'):null;
            // }else{
            //     $DataSave['approve_date'] = null;
            // }

            // if( !empty($item->{'วดป.ชำระเงินค่าปรับ'}) && date("Y", strtotime($item->{'วดป.ชำระเงินค่าปรับ'})) >= 2500 ){
            //     $DataSave['payment_date'] = !empty($item->{'วดป.ชำระเงินค่าปรับ'})?Carbon::parse($item->{'วดป.ชำระเงินค่าปรับ'})->subYear('543')->format('Y-m-d'):null;
            // }else{
            //     $DataSave['payment_date'] = null;
            // }
            
            // if( !empty($item->{'คำสั่งกมอ.ที่'}) && Carbon::hasFormat($item->{'คำสั่งกมอ.ที่'}, 'Y-m-d') ){
            //     $DataSave['tisi_dictation_no'] = !empty($item->{'คำสั่งกมอ.ที่'})?Carbon::parse($item->{'คำสั่งกมอ.ที่'})->format('d/m/Y'):null;
            // }else{
            //     $DataSave['tisi_dictation_no'] = !empty($item->{'คำสั่งกมอ.ที่'})?$item->{'คำสั่งกมอ.ที่'}:null;
            // }

            // if( !empty($item->{'วันที่คำสั่งกมอ.ทำให้สิ้นสภาพ'}) && date("Y", strtotime($item->{'วันที่คำสั่งกมอ.ทำให้สิ้นสภาพ'})) >= 2500 ){
            //     $DataSave['tisi_dictation_date'] = !empty($item->{'วันที่คำสั่งกมอ.ทำให้สิ้นสภาพ'})?Carbon::parse($item->{'วันที่คำสั่งกมอ.ทำให้สิ้นสภาพ'})->subYear('543')->format('Y-m-d'):null;
            // }else{
            //     $DataSave['tisi_dictation_date'] = null;
            // }

            // if( !empty($item->{'วันที่ทำลาย/ส่งคืน'}) && date("Y", strtotime($item->{'วันที่ทำลาย/ส่งคืน'})) >= 2500 ){
            //     $DataSave['destroy_date'] = !empty($item->{'วันที่ทำลาย/ส่งคืน'})?Carbon::parse($item->{'วันที่ทำลาย/ส่งคืน'})->subYear('543')->format('Y-m-d'):null;
            // }else{
            //     $DataSave['destroy_date'] = null;
            // }

            // if( !empty($item->{'เสนอลงนามคำสั่งกมอ'}) && Carbon::hasFormat($item->{'เสนอลงนามคำสั่งกมอ.'}, 'Y-m-d') ){
            //     $DataSave['tisi_present'] = !empty($item->{'เสนอลงนามคำสั่งกมอ'})?Carbon::parse($item->{'เสนอลงนามคำสั่งกมอ'})->format('d/m/Y'):null;
            // }else{
            //     $DataSave['tisi_present'] = !empty($item->{'เสนอลงนามคำสั่งกมอ'})?$item->{'เสนอลงนามคำสั่งกมอ'}:null;
            // }

            // if( !empty($item->{'สรุปเรื่องให้ลมอ.ทราบ'}) && Carbon::hasFormat($item->{'สรุปเรื่องให้ลมอ.ทราบ'}, 'Y-m-d') ){
            //     $DataSave['result_summary'] = !empty($item->{'สรุปเรื่องให้ลมอ.ทราบ'})?Carbon::parse($item->{'สรุปเรื่องให้ลมอ.ทราบ'})->format('d/m/Y'):null;
            // }else{
            //     $DataSave['result_summary'] = !empty($item->{'สรุปเรื่องให้ลมอ.ทราบ'})?$item->{'สรุปเรื่องให้ลมอ.ทราบ'}:null;
            // }

            // if( !empty($item->{'แจ้งผลการเปรียบเทียบปรับ(ปคบ.)'}) && Carbon::hasFormat($item->{'แจ้งผลการเปรียบเทียบปรับ(ปคบ.)'}, 'Y-m-d') ){
            //     $DataSave['cppd_result'] = !empty($item->{'แจ้งผลการเปรียบเทียบปรับ(ปคบ.)'})?Carbon::parse($item->{'แจ้งผลการเปรียบเทียบปรับ(ปคบ.)'})->format('d/m/Y'):null;
            // }else{
            //     $DataSave['cppd_result'] = !empty($item->{'แจ้งผลการเปรียบเทียบปรับ(ปคบ.)'})?$item->{'แจ้งผลการเปรียบเทียบปรับ(ปคบ.)'}:null;
            // }

            // if( !empty($item->{'แจ้งคำสั่งกมอ.(ปคบ.)'}) && Carbon::hasFormat($item->{'แจ้งคำสั่งกมอ.(ปคบ.)'}, 'Y-m-d') ){
            //     $DataSave['tisi_dictation_cppd'] = !empty($item->{'แจ้งคำสั่งกมอ.(ปคบ.)'})?Carbon::parse($item->{'แจ้งคำสั่งกมอ.(ปคบ.)'})->format('d/m/Y'):null;
            // }else{
            //     $DataSave['tisi_dictation_cppd'] = !empty($item->{'แจ้งคำสั่งกมอ.(ปคบ.)'})?$item->{'แจ้งคำสั่งกมอ.(ปคบ.)'}:null;
            // }

            // if( !empty($item->{'แจ้งคำสั่งกมอ.(บริษัท)'}) && Carbon::hasFormat($item->{'แจ้งคำสั่งกมอ.(บริษัท)'}, 'Y-m-d') ){
            //     $DataSave['tisi_dictation_company'] = !empty($item->{'แจ้งคำสั่งกมอ.(บริษัท)'})?Carbon::parse($item->{'แจ้งคำสั่งกมอ.(บริษัท)'})->format('d/m/Y'):null;
            // }else{
            //     $DataSave['tisi_dictation_company'] = !empty($item->{'แจ้งคำสั่งกมอ.(บริษัท)'})?$item->{'แจ้งคำสั่งกมอ.(บริษัท)'}:null;
            // }

            // if( !empty($item->{'แจ้งคำสั่งกมอ.คืนเรื่องเดิม(กต.)'}) && Carbon::hasFormat($item->{'แจ้งคำสั่งกมอ.คืนเรื่องเดิม(กต.)'}, 'Y-m-d') ){
            //     $DataSave['tisi_dictation_committee'] = !empty($item->{'แจ้งคำสั่งกมอ.คืนเรื่องเดิม(กต.)'})?Carbon::parse($item->{'แจ้งคำสั่งกมอ.คืนเรื่องเดิม(กต.)'})->format('d/m/Y'):null;
            // }else{
            //     $DataSave['tisi_dictation_committee'] = !empty($item->{'แจ้งคำสั่งกมอ.คืนเรื่องเดิม(กต.)'})?$item->{'แจ้งคำสั่งกมอ.คืนเรื่องเดิม(กต.)'}:null;
            // }

            // if( !empty($DataSave)){
            //     DB::table('law_offenders_imp_new')->where('id', $item->id )->update($DataSave);
            // }
            // unset($DataSave);
            // $updateData['assign_date']              = !empty($item->assign_date)?$item->assign_date:null;
            // $updateData['approve_date']             = !empty($item->approve_date)?$item->approve_date:null;
            // $updateData['power_present_date']       = !empty($item->power_present_date)?$item->power_present_date:null;
            // $updateData['payment_date']             = !empty($item->payment_date)?$item->payment_date:null;
            // $updateData['tisi_dictation_no']        = !empty($item->tisi_dictation_no)?$item->tisi_dictation_no:null;
            // $updateData['tisi_dictation_date']      = !empty($item->tisi_dictation_date)?$item->tisi_dictation_date:null;
            // $updateData['destroy_date']             = !empty($item->destroy_date)?$item->destroy_date:null;
            // $updateData['tisi_present']             = !empty($item->tisi_present)?$item->tisi_present:null;
            // $updateData['result_summary']           = !empty($item->result_summary)?$item->result_summary:null;
            // $updateData['cppd_result']              = !empty($item->cppd_result)?$item->cppd_result:null;
            // $updateData['tisi_dictation_cppd']      = !empty($item->tisi_dictation_cppd)?$item->tisi_dictation_cppd:null;
            // $updateData['tisi_dictation_company']   = !empty($item->tisi_dictation_company)?$item->tisi_dictation_company:null;
            // $updateData['tisi_dictation_committee'] = !empty($item->tisi_dictation_committee)?$item->tisi_dictation_committee:null;
            // $updateData['criminal_case_no'] =  !empty($item->criminal_case_no)?$item->assign_date:null;
            // if( !empty($updateData)){
            //     DB::table('law_offenders_imp')->where('id', $item->id )->update($updateData);
            // }

            // unset($updateData);

            // $offender = LawOffender::where('taxid',  $item->offender_taxid )->first();

            // LawOffenderCases::where(  [
            //     'id'          => $item->id,
            //     // 'case_number'              => $item->case_number
            // ])->update(
            //     [
            //         // 'law_offender_id'          => $offender->id,
            //         // 'case_number'              => $item->case_number,

            //         'payment_date'             => !empty($item->payment_date)?$item->payment_date:null,

            //         'power_present_date'       => !empty($item->power_present_date)?$item->power_present_date:null,

            //         'approve_date'             => !empty($item->approve_date)?$item->approve_date:null,

            //         'assign_date'              => !empty($item->assign_date)?$item->assign_date:null,

            //         'tisi_present'             => !empty($item->tisi_present)?$item->tisi_present:null,

            //         'tisi_dictation_no'        => !empty($item->tisi_dictation_no)?$item->tisi_dictation_no:null,

            //         'tisi_dictation_date'      => !empty($item->tisi_dictation_date)?$item->tisi_dictation_date:null,

            //         'tisi_dictation_cppd'      => !empty($item->tisi_dictation_cppd)?$item->tisi_dictation_cppd:null,

            //         'tisi_dictation_company'   => !empty($item->tisi_dictation_company)?$item->tisi_dictation_company:null,

            //         'tisi_dictation_committee' => !empty($item->tisi_dictation_committee)?$item->tisi_dictation_committee:null,

            //         'cppd_result'              => !empty($item->cppd_result)?$item->cppd_result:null,

            //         'result_summary'           => !empty($item->result_summary)?$item->result_summary:null,

            //         'destroy_date'             => !empty($item->destroy_date)?$item->destroy_date:null,

            //     ]
            // );

            LawOffenderCases::where(  [
                'id'          => $item->id,

            ])->update(
                [
                    'criminal_case_no'         => !empty($item->criminal_case_no)?$item->criminal_case_no:null,

                ]
            );





            // dd($cases);


        }


        dd($imp_new);
        # code...
    }
}
