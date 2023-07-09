<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Libraries\Mypdf;

    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Admin Documentation",
     *      description="Admin OpenApi description",
     *      @OA\Contact(
     *          email="antonio@gmail.com"
     *      ),
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Admin API Server"
     * )
     *
     * @OA\SecurityScheme(
     *   securityScheme="bearerAuth",
     *   type="http",
     *   scheme="bearer"
     * )
     */
class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
    protected $statusCode;

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function respondWithError($message = null)
    {
        return response()->json(
            ['success' => false, 'msg' => $message]
        );
    }

    /**
     * Returns a Unauthorized response.
     *
     * @param  string  $message
     * @return \Illuminate\Http\Response
     */
    public function respondUnauthorized($message = 'Unauthorized action.')
    {
        return $this->setStatusCode(403)
            ->respondWithError($message);
    }

    /**
     * Returns a went wrong response.
     *
     * @param  object  $exception = null
     * @return \Illuminate\Http\Response
     */
    public function respondWentWrong($exception = null)
    {
        //If debug is enabled then send exception message
        $message = (config('app.debug') && is_object($exception)) ? "File:" . $exception->getFile(). "Line:" . $exception->getLine(). "Message:" . $exception->getMessage() : __('english.something_went_wrong');

        //TODO: show exception error message when error is enabled.
        return $this->setStatusCode(200)
            ->respondWithError($message);
    }

    /**
     * Returns a 200 response.
     *
     * @param  object  $message = null
     * @return \Illuminate\Http\Response
     */
    public function respondSuccess($message = null, $additional_data = [])
    {
        $message = is_null($message) ? __('lang_v.success') : $message;
        $data = ['success' => true, 'msg' => $message];

        if (!empty($additional_data)) {
            $data = array_merge($data, $additional_data);
        }

        return $this->respond($data);
    }

    /**
     * Returns a 200 response.
     *
     * @param  array  $data
     * @return \Illuminate\Http\Response
     */
    public function respond($data)
    {
        return response()->json($data);
    }

    /**
     * Returns new mpdf instance
     *
     */

    public function reportPDF($stylesheet=null, $data=null, $viewpath= null, $mode = 'view', $pagesize = 'a4', $pagetype='portrait')
    {
        $designType = 'LTR';
        $this->data['panel_title'] = '222';
        $html = view($viewpath)->with(compact('data'))->render();
        $my_pdf = new Mypdf();
        $my_pdf->folder('uploads/report/');
        $my_pdf->filename('Report');
        $my_pdf->paper($pagesize, $pagetype);
        $my_pdf->html($html);
       // dd(url('assets/pdf/'.$designType.'/'.$stylesheet));
        if (!empty($stylesheet)) {
            $stylesheet = file_get_contents(url('assets/pdf/'.$designType.'/'.$stylesheet));
            return $my_pdf->create($mode, $this->data['panel_title'], $stylesheet);
        } else {
            return $my_pdf->create($mode, $this->data['panel_title']);
        }
    }

    public function getMpdf()
    {
        return new \Mpdf\Mpdf(['tempDir' => public_path('uploads/temp'),
            'mode' => 'utf-8',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'autoVietnamese' => true,
            'autoArabic' => true,
            'format' => 'A4',
            //'orientation' => 'L',
            'useSubstitutions' => true,
                'default_font_size' => 0,     // font size - default 0
                'default_font' => '',    // default font family
                'margin_left' => 0,    	// 15 margin_left
                'margin_right' => 0,    	// 15 margin right
                'mgt' => 0,     // 16 margin top
                'mgb' =>0,    	// margin bottom
                'margin_header' => 5,     // 9 margin header
                'margin_footer' => '10mm',     // 9 margin footer
                'font_path' => base_path('storage/fonts/'),
    'font_data' => [


        'cairo-black'=>[
            'R'=>'/Cairo/Cairo-Black.ttf',

         ],
        'cairo-bold'=>[
            'R'=>'/Cairo/Cairo-Bold.ttf',

        ],
        'cairo-extra-light'=>[
            'R'=>'/Cairo/Cairo-ExtraLight.ttf',

        ],
        'cairo-light'=>[
            'R'=>'/Cairo/Cairo-Light.ttf',

        ],
        'cairo'=>[
            'R'=>'/Cairo/Cairo-Regular.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        'cairo-semi-bold'=>[
            'R'=>'/Cairo/Cairo-SemiBold.ttf',
        ],
        'ranchers'=>[
            'R'=>'/Ranchers/Ranchers-Regular.ttf',
        ],
        'open-sans'=>[
            'R'=>'/Open-Sans/OpenSans-Regular.ttf',
        ],
        'open-sans-bold'=>[
            'R'=>'/Open-Sans/OpenSans-Bold.ttf',
        ],
        'open-sans-bold-italic'=>[
            'R'=>'/Open-Sans/OpenSans-BoldItalic.ttf',
        ],
        'open-sans-extra-bold'=>[
            'R'=>'/Open-Sans/OpenSans-ExtraBold.ttf',
        ],
        'open-sans-semi-bold'=>[
            'R'=>'/Open-Sans/OpenSans-SemiBold.ttf',

        ],
        'open-sans-light'=>[
            'R'=>'/Open-Sans/OpenSans-Light.ttf',

        ],
        'redressed'=>[
            'R'=>'/Redressed/Redressed-Regular.ttf',

        ],
        'lato-black'=>[
            'R'=>'/Lato/Lato-Black.ttf',

        ],
        'lato-bold'=>[
            'R'=>'/Lato/Lato-Bold.ttf',

        ],
        'lato'=>[
            'R'=>'/Lato/Lato-Regular.ttf',

        ],
        'lato-thin'=>[
            'R'=>'/Lato/Lato-Thin.ttf',

        ],
        'amiri'=>[
            'R'=>'/Amiri/Amiri-Regular.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,

        ],
        'amiri-bold'=>[
            'R'=>'/Amiri/Amiri-Bold.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,

        ],
        'amiri-bold-italic'=>[
            'R'=>'/Amiri/Amiri-BoldItalic.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,

        ],
        'amiri-italic'=>[
            'R'=>'/Amiri/Amiri-Italic.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],

        'janna'=>[
            'R'=>'/Janna/JannaLT-Regular.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        'aref'=>[
            'R'=>'/Aref_Ruqaa/ArefRuqaa-Regular.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],

        'tajawal'=>[
            'R'=>'/Tajawal/Tajawal-Regular.ttf',

        ],
        'markazi'=>[
            'R'=>'/Markazi/Markazi-Regular.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        'elmessiri'=>[
            'R'=>'/El_Messiri/ElMessiri-Regular.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        'mada'=>[
            'R'=>'/Mada/Mada-Regular.ttf',
        ],
        'lemonada'=>[
            'R'=>'/Lemonada/Lemonada-Regular.ttf',

        ],
        'lateef'=>[
            'R'=>'/Lateef/Lateef-Regular.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,

        ],
        'kufam'=>[
            'R'=>'/Kufam/Kufam-Regular.ttf',

        ],
        'jomhuria'=>[
            'R'=>'/Jomhuria/Jomhuria-Regular.ttf',

        ],
        'changa'=>[
            'R'=>'/Changa/Changa-Regular.ttf',

        ],

    ]
        ]);Mpdf\Mpdf(['tempDir' => public_path('uploads/temp'),
            'mode' => 'utf-8',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'autoVietnamese' => true,
            'autoArabic' => true,
            'format' => 'A4',
            //'orientation' => 'L',
            'useSubstitutions' => true,
                'default_font_size' => 0,     // font size - default 0
                'default_font' => '',    // default font family
                'margin_left' => 0,    	// 15 margin_left
                'margin_right' => 0,    	// 15 margin right
                'mgt' => 0,     // 16 margin top
                'mgb' =>0,    	// margin bottom
                'margin_header' => 5,     // 9 margin header
                'margin_footer' => '10mm',     // 9 margin footer
                'font_path' => base_path('storage/fonts/'),
    'font_data' => [


        'cairo-black'=>[
            'R'=>'/Cairo/Cairo-Black.ttf',

         ],
        'cairo-bold'=>[
            'R'=>'/Cairo/Cairo-Bold.ttf',

        ],
        'cairo-extra-light'=>[
            'R'=>'/Cairo/Cairo-ExtraLight.ttf',

        ],
        'cairo-light'=>[
            'R'=>'/Cairo/Cairo-Light.ttf',

        ],
        'cairo'=>[
            'R'=>'/Cairo/Cairo-Regular.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        'cairo-semi-bold'=>[
            'R'=>'/Cairo/Cairo-SemiBold.ttf',
        ],
        'ranchers'=>[
            'R'=>'/Ranchers/Ranchers-Regular.ttf',
        ],
        'open-sans'=>[
            'R'=>'/Open-Sans/OpenSans-Regular.ttf',
        ],
        'open-sans-bold'=>[
            'R'=>'/Open-Sans/OpenSans-Bold.ttf',
        ],
        'open-sans-bold-italic'=>[
            'R'=>'/Open-Sans/OpenSans-BoldItalic.ttf',
        ],
        'open-sans-extra-bold'=>[
            'R'=>'/Open-Sans/OpenSans-ExtraBold.ttf',
        ],
        'open-sans-semi-bold'=>[
            'R'=>'/Open-Sans/OpenSans-SemiBold.ttf',

        ],
        'open-sans-light'=>[
            'R'=>'/Open-Sans/OpenSans-Light.ttf',

        ],
        'redressed'=>[
            'R'=>'/Redressed/Redressed-Regular.ttf',

        ],
        'lato-black'=>[
            'R'=>'/Lato/Lato-Black.ttf',

        ],
        'lato-bold'=>[
            'R'=>'/Lato/Lato-Bold.ttf',

        ],
        'lato'=>[
            'R'=>'/Lato/Lato-Regular.ttf',

        ],
        'lato-thin'=>[
            'R'=>'/Lato/Lato-Thin.ttf',

        ],
        'amiri'=>[
            'R'=>'/Amiri/Amiri-Regular.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,

        ],
        'amiri-bold'=>[
            'R'=>'/Amiri/Amiri-Bold.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,

        ],
        'amiri-bold-italic'=>[
            'R'=>'/Amiri/Amiri-BoldItalic.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,

        ],
        'amiri-italic'=>[
            'R'=>'/Amiri/Amiri-Italic.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],

        'janna'=>[
            'R'=>'/Janna/JannaLT-Regular.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        'aref'=>[
            'R'=>'/Aref_Ruqaa/ArefRuqaa-Regular.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],

        'tajawal'=>[
            'R'=>'/Tajawal/Tajawal-Regular.ttf',

        ],
        'markazi'=>[
            'R'=>'/Markazi/Markazi-Regular.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        'elmessiri'=>[
            'R'=>'/El_Messiri/ElMessiri-Regular.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        'mada'=>[
            'R'=>'/Mada/Mada-Regular.ttf',
        ],
        'lemonada'=>[
            'R'=>'/Lemonada/Lemonada-Regular.ttf',

        ],
        'lateef'=>[
            'R'=>'/Lateef/Lateef-Regular.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,

        ],
        'kufam'=>[
            'R'=>'/Kufam/Kufam-Regular.ttf',

        ],
        'jomhuria'=>[
            'R'=>'/Jomhuria/Jomhuria-Regular.ttf',

        ],
        'changa'=>[
            'R'=>'/Changa/Changa-Regular.ttf',

        ],

    ]
        ]);
    }
}
