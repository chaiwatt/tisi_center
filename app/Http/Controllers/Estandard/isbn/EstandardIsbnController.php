<?php

namespace App\Http\Controllers\Estandard\isbn;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Certify\IsbnRequest;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\RequestException;

class EstandardIsbnController extends Controller
{
    public function uploadData(Request $request)
    {
        // dd($request->all());
        // Validate the request data
        // $request->validate([
        //     'std_id' => 'required|string',
        //     'tistype' => 'required|string',
        //     'tisno' => 'required|string',
        //     'tisname' => 'required|string',
        //     'page' => 'required|string',
        //     'cover_file' => 'required|file',
        // ]);
          $validated = $request->validate([
            'standard_id' => 'required|string',
            'tistype' => 'required|string',
            'tisno' => 'required|string',
            'tisname' => 'required|string',
            'page' => 'required|string',
            'cover_file' => 'required|file|mimes:jpg,png|max:2048',
        ]);

        $coverPath = $request->file('cover_file')->store('covers', 'public');
    
        // Prepare the form data
        $formData = [
            [
                'name' => 'tistype',
                'contents' => $request->tistype,
            ],
            [
                'name' => 'tisno',
                'contents' => $request->tisno,
            ],
            [
                'name' => 'tisname',
                'contents' => $request->tisname,
            ],
            [
                'name' => 'page',
                'contents' => $request->page,
            ],
        ];
    
        if ($request->hasFile('cover_file')) {
            $formData[] = [
                'name' => 'cover_file',
                'contents' => fopen($request->file('cover_file')->getRealPath(), 'r'),
                'filename' => $request->file('cover_file')->getClientOriginalName(),
            ];
        }
        $user= auth()->user();
        $regName = $user->reg_uname;
    //    dd($formData, $user);
        $client = new Client();
        try {
            $response = $client->post(env('TISI_API_URL') . '/tisi-isbn/web/test-api/create', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $regName,
                ],
                'multipart' => $formData,
            ]);
    
            $responseBody = json_decode($response->getBody(), true);

            
            if (isset($responseBody['status']) && $responseBody['status'] === 'success' && isset($responseBody['request_no'])) {
                IsbnRequest::updateOrCreate(
                    ['standard_id' => $validated['standard_id']],
                    [
                        'request_no' => $responseBody['request_no'],
                        'tistype' => $validated['tistype'],
                        'tisno' => $validated['tisno'],
                        'tisname' => $validated['tisname'],
                        'page' => $validated['page'],
                        'cover_file' => $coverPath,
                    ]
                );

                return response()->json([
                    'status' => 'success',
                    'request_no' => $responseBody['request_no'],
                    'message' => $responseBody['message'] ?? 'The request created successfully',
                ]);
            }
            return response()->json($responseBody);
    
        } catch (RequestException $e) {
            return response()->json(['error' => 'Error uploading data'], 500);
        }
    }


public function checkStatus(Request $request)
{
    $requestNo = '020029'; // $request->input('request_no')
    $stdNo = 'aaa-xxxx';   // $request->input('std_no')

    $url = env('TISI_API_URL') . "/tisi-isbn/web/test-api/check-status?request_no={$requestNo}&std_no={$stdNo}";

    // ใช้ Guzzle ในการส่งคำขอแบบ GET
    $client = new Client();
    try {
        $response = $client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer T708',
            ],
        ]);

        // รับข้อมูลและแปลงข้อมูล JSON ที่ตอบกลับจาก API
        $responseBody = json_decode($response->getBody(), true);

        // 1 ร่าง
        // 2 ส่งคำขอแล้ว
        // 3 ถูกตีกลับให้แก้ไขคำขอ
        // 4 อนุมัติเลข isbn
        // 5 ยกเลิกคำขอ

        return response()->json($responseBody);

    } catch (RequestException $e) {
        // ตรวจสอบและส่งข้อผิดพลาดกลับไปในรูปแบบ JSON
        return response()->json(['error' => 'Error checking status'], 500);
    }
}

}
