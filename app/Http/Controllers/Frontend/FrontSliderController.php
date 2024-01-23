<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\Frontend\FrontSlider;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use Illuminate\Support\Str;


use File;

class FrontSliderController extends Controller
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
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
  
        if (!auth()->user()->can('grade.view')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $sliders = FrontSlider::select(['title', 'id','slider_image','btn_name','btn_url','description','status']);

            return DataTables::of($sliders)
                            ->addColumn(
                                'action',
                                function ($row) {
                                    $html= '<div class="dropdown">
                             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                             <ul class="dropdown-menu" style="">';
                                    $html .= '<li><a  href="#" data-href="' .action('Frontend\FrontSliderController@edit', [$row->id]).'" class=" edit_slider_button dropdown-item"><i class="bx bxs-edit f-16 mr-15"></i>' . __("english.edit") . '</a></li>';
                                    $html .= '<li><a  href="#" data-href="' .action('Frontend\FrontSliderController@destroy', [$row->id]).'" class=" delete_slider_button dropdown-item"><i class="bx bxs-trash f-16 mr-15"></i>' . __("english.delete") . '</a></li>';


                                    $html .= '</ul></div>';

                                    return $html;
                                }
                            )
                            ->editColumn('slider_image', function ($row) {
                                    $image = file_exists(public_path('uploads/front_image/'.$row->slider_image)) ? $row->slider_image : 'default.jpg';
                                    $img=' <img src="'.url('uploads/front_image/' . $image).'" class="img-border " width="50" height="50" alt="" ></div>';
                                    return $img;
                            })
                            ->removeColumn('id')
                            ->rawColumns(['action','slider_image','description'])
                            ->make(true);
        }

        return view('frontend.backend.slider.index');
    }


   public function store(Request $request)
   {
       try {
           $input = $request->only(['title','btn_name','btn_url','description','status']);
           if (!empty($request->input('status'))) {
               $input['status']='publish';
           }
           $filename=$this->commonUtil->uploadFile($request, 'slider_image', 'front_image', 'image');
           $input['slider_image']=$filename;
           FrontSlider::create($input);
           $output = ['success' => true,
                   'msg' => __("english.added_success")
               ];
       } catch (\Exception $e) {
           \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

           $output = ['success' => false,
           'msg' => __("english.something_went_wrong")
           ];
       }
       return redirect('front-sliders')->with('status', $output);
   }
   public function create()
   {
       return view('frontend.backend.slider.create');
   }

   public function edit($id)
   {
       $slider= FrontSlider::find($id);
       return view('frontend.backend.slider.edit')->with(compact('slider'));
       ;
   }
   public function update(Request $request, $id)
   {
       try {
           $input = $request->only(['title','btn_name','btn_url','description']);
           if (!empty($request->input('status'))) {
               $input['status']='publish';
           } else {
               $input['status']='not_publish';
           }
           //dd($input);
           $gallery= FrontSlider::find($id);
           $gallery->title= $input['title'];
           $gallery->btn_name= $input['btn_name'];
           $gallery->btn_url= $input['btn_url'];
           $gallery->status= $input['status'];
           $gallery->description= $input['description'];
           if ($request->hasFile('slider_image') && $request->file('slider_image')->isValid()) {
               if (File::exists(public_path('uploads/front_image/'. $gallery->slider_image))) {
                   File::delete(public_path('uploads/front_image/'. $gallery->slider_image));
               }
               $filename=$this->commonUtil->uploadFile($request, 'slider_image', 'front_image', 'image');
               $gallery->slider_image=$filename;
           }
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
       return $output;
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
              $gallery= FrontSlider::find($id);
             
              if (File::exists(public_path('uploads/front_image/'. $gallery->slider_image))) {
                  File::delete(public_path('uploads/front_image/'. $gallery->slider_image));
              }
              $gallery->delete();
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
}
