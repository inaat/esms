<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Http;


use \Mpdf\Mpdf;

class Mypdf {

    var $html;
    var $path;
    var $filename;
    var $paper_size;
    var $orientation;

	/**
	 * Initialize Preferences
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 * @return	void
	 */
    function initialize($params)
	{
        $this->clear();
		if (customCompute($params) > 0)
        {
            foreach ($params as $key => $value)
            {
                if (isset($this->$key))
                {
                    $this->$key = $value;
                }
            }
        }
	}

	// --------------------------------------------------------------------

	/**
	 * Set html
	 *
	 * @access	public
	 * @return	void
	 */
	function html($html = NULL)
	{
        $this->html = $html;
	}

	// --------------------------------------------------------------------

	/**
	 * Set path
	 *
	 * @access	public
	 * @return	void
	 */
	function folder($path)
	{
        $this->path = $path;
	}

	// --------------------------------------------------------------------

	/**
	 * Set path
	 *
	 * @access	public
	 * @return	void
	 */
	function filename($filename)
	{
        $this->filename = $filename;
	}

	// --------------------------------------------------------------------


	/**
	 * Set paper
	 *
	 * @access	public
	 * @return	void
	 */
	function paper($paper_size = NULL, $orientation = NULL)
	{
        $this->paper_size = $paper_size;
        $this->orientation = $orientation;
	}

	// --------------------------------------------------------------------


	/**
	 * Create PDF
	 *
	 * @access	public
	 * @return	void
	 */
	function create($mode = 'view', $title, $stylesheet=null)
	{
        $mpdf = new Mpdf(['tempDir' => public_path('uploads/temp'),
        'mode' => 'utf-8',
        'autoScriptToLang' => true,
        'autoLangToFont' => true,
        'autoVietnamese' => true,
        'autoArabic' => true,
        'format' => $this->paper_size,
        'orientation' => $this->orientation,
        'useSubstitutions' => true,
            'default_font_size' => 0,     // font size - default 0
            'default_font' => '',    // default font family
            'margin_left' => 0,    	// 15 margin_left
            'margin_right' => 0,    	// 15 margin right
            'mgt' => 0,     // 16 margin top
            'mgb' =>0,    	// margin bottom
            'margin_header' => 0,     // 9 margin header
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
  
        // $mpdf->baseScript = 1;
        $mpdf->autoScriptToLang = true;
        // $mpdf->autoVietnamese = true;
        $mpdf->autoLangToFont = true;
        // $mpdf->autoArabic = true;

        if(!empty($stylesheet)) {
            $mpdf->WriteHTML($stylesheet, 1);
        }
//    $mpdf->SetWatermarkImage(public_path('/uploads/business_logos/1664987422_logo.jpg'));
//     $mpdf->showWatermarkImage = true;
//     $mpdf->watermarkImageAlpha = 0.1;
        $mpdf->WriteHTML($this->html);
        if($mode == 'view') {
            $mpdf->Output($this->filename . '.pdf','I'); // D - Force download, I - View in explorer
        } elseif ($mode == 'save') {
            $mpdf->Output($this->path.$this->filename . '.pdf','F');
            return $this->path.$this->filename . '.pdf';
        } elseif ($mode == 'download') {
            $mpdf->Output($this->filename . '.pdf','D');
        }
	}

}

/* End of file Mpdf.php */
