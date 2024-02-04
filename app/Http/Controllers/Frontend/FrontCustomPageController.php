<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\Frontend\FrontCustomPage;
use App\Models\Frontend\FrontCustomPageNavbar;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use Illuminate\Support\Str;


use File;

class FrontCustomPageController extends Controller
{
    protected $commonUtil;

    /**
    * Constructor
    *
    */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }

    public function index()
    {
       // dd(FrontCustomPageNavbar::with('custom_pages')->get());
  
        if (!auth()->user()->can('grade.view')) {
            abort(403, 'Unauthorized action.');
        }
       
        if (request()->ajax()) {
            $sliders = FrontCustomPage::leftjoin('front_custom_page_navbars	 as nav', 'front_custom_pages.front_page_navbar_id', '=', 'nav.id')->select(['nav.title as nav_title','nav.type','front_custom_pages.id','front_custom_pages.title','front_custom_pages.description']);

            return DataTables::of($sliders)
                            ->addColumn(
                                'action',
                                function ($row) {
                                    $html= '<div class="dropdown">
                              <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                              <ul class="dropdown-menu" style="">';
                                   if($row->type=='download_and_blink'|| $row->type=='page_with_download'){
                                    $html .= '<li><a  class="dropdown-item  " href="' . action('Frontend\FrontCustomPageController@element', [$row->id]).'"><i class="fas fa-image" aria-hidden="true"></i>' . __("english.upload") . '</a></li>';
                                   }
                                     $html .= '<li><a  href="' .action('Frontend\FrontCustomPageController@edit', [$row->id]).'" class=" dropdown-item"><i class="bx bxs-edit f-16 mr-15"></i>' . __("english.edit") . '</a></li>';
                                     $html .= '<li><a  href="#" data-href="' .action('Frontend\FrontCustomPageController@destroy', [$row->id]).'" class=" delete_event_button dropdown-item"><i class="bx bxs-trash f-16 mr-15"></i>' . __("english.delete") . '</a></li>';


                                     $html .= '</ul></div>';

                                    return $html;
                                }
                            )
                          
                            ->removeColumn('id','type')
                            ->rawColumns(['action','description'])
                            ->make(true);
        }

        return view('frontend.backend.custom_page.index');
}

public function create()
{
    if (!auth()->user()->can('grade.view')) {
        abort(403, 'Unauthorized action.');
    }
    $custom_navbars=FrontCustomPageNavbar::forDropdown();
    return view('frontend.backend.custom_page.create')->with(compact('custom_navbars'));
}

public function store(Request $request)
{
   try {
        $input = $request->only(['title','front_page_navbar_id','description']);
     
        $input['slug']=  Str::slug($input['title']);
  
        FrontCustomPage::create($input);
        $output = ['success' => true,
                'msg' => __("english.added_success")
            ];
    } catch (\Exception $e) {
        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        $output = ['success' => false,
        'msg' => __("english.something_went_wrong")
        ];
    }
    return redirect('front-custom-page')->with('status', $output);
}
public function edit($id)
{
    $custom_page= FrontCustomPage::find($id);
    $custom_navbars=FrontCustomPageNavbar::forDropdown();

    return view('frontend.backend.custom_page.edit')->with(compact('custom_page','custom_navbars'));
    
}
public function update(Request $request, $id)
{
    try {
        $input = $request->only(['title','front_page_navbar_id','description']);
       
        //dd($input);
        $navbar= FrontCustomPage::find($id);
        $navbar->title= $input['title'];
        $navbar->front_page_navbar_id= $input['front_page_navbar_id'];
        $navbar->description= $input['description'];
        $input['slug']=  Str::slug($input['title']);

        $navbar->save();
        $output = ['success' => true,
        'msg' => __("english.updated_success")
];
    } catch (\Exception $e) {
        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        $output = ['success' => false,
        'msg' => __("english.something_went_wrong")
        ];
    }
    return redirect('front-custom-page')->with('status', $output);
}


/*
* Remove the specified resource from storage.
*
* @param  int  $id
* @return \Illuminate\Http\Response
*/
public function destroy($id)
{
   if (!auth()->user()->can('fee_head.delete')) {
       abort(403, 'Unauthorized action.');
   }

   if (request()->ajax()) {
       try {
           $navbar= FrontCustomPage::find($id);
          
      
           $navbar->delete();
           $output = ['success' => true,
           'msg' => __("english.deleted_success")
];
       } catch (\Exception $e) {
           \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

           $output = ['success' => false,
                       'msg' => __("english.something_went_wrong")
                   ];
       }

       return $output;
   }
}
public function addImage($id)
{
    $gallery= FrontCustomPage::find($id);
    $getJson = json_decode($gallery->elements, true);
    return view('frontend.backend.custom_page.add_image')->with(compact('gallery', 'getJson'));
    ;
}
public function element($id)
{
    $gallery= FrontCustomPage::find($id);
    $getJson = json_decode($gallery->elements, true);
    return view('frontend.backend.custom_page.element')->with(compact('gallery', 'getJson'));
    ;
}

public function storeElement(Request $request, $id)
{
    try {
        $arr = [];
        $count = 1;
        $gallery= FrontCustomPage::find($id);
        $getJson = json_decode($gallery->elements, true);
        if (!empty($getJson)) {
            if (!empty(array_keys($getJson))) {
                $count = (max(array_keys($getJson))) + 1;
            }
            foreach ($getJson as $key => $value) {
               
             
                    $arr[$key] = array(
                        'image' => $value['image'],
                        'type' => $value['type'],
                        'date' => $value['date'],
                        'video_url' => $value['video_url'],
                    );  
            
            }
        }
        $type=$request->input('type');
        $video_url=$request->input('video_url');

        if ($request->hasFile('thumb_image')) {
            $files=$request->file('thumb_image');
            foreach ($files as $file) {
                $uploaded_file_name = null;
                $new_file_name = time() . '_' . $file->getClientOriginalName();
                if ($file->storeAs('front_image', $new_file_name)) {
                    $uploaded_file_name = $new_file_name;
                }
                
                $arr[$count] =  array(
                'image' => $uploaded_file_name,
                'type' => $type,
                'video_url' => $video_url,
                'date' => date("Y-m-d H:i:s"),
 );
                $count++;
            }
        }
        $gallery->elements=json_encode($arr);
        $gallery->save();
        $output = ['success' => true,
        'msg' => __("english.updated_success")
];
    } catch (\Exception $e) {
        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        $output = ['success' => false,
        'msg' => __("english.something_went_wrong")
        ];
    }
    return redirect('front-custom-page-element/'.$gallery->id)->with('status', $output);
}

public function upload_delete($id, $elem_id)
{
    try {
        $gallery= FrontCustomPage::find($id);
        if (!empty($gallery->elements)) {
            $getJson = json_decode($gallery->elements, true);
            foreach ($getJson as $key => $value) {
                if ($key == $elem_id) {
                    unset($getJson[$key]);

               
                        $image = $value['image'];

                        if (File::exists(public_path('uploads/front_image/'. $image))) {
                            File::delete(public_path('uploads/front_image/'. $image));
                        }  
                   
                }
            }
            $gallery->elements=json_encode($getJson);
            $gallery->save();
        }

        $output = ['success' => true,
        'msg' => __("english.deleted_success")
];
    } catch (\Exception $e) {
        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

        $output = ['success' => false,
        'msg' => __("english.something_went_wrong")
        ];
    }
    return redirect('front-custom-page-element/'.$gallery->id)->with('status', $output);
}
}