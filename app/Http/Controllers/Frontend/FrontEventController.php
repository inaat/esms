<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\Frontend\FrontEvent;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use Illuminate\Support\Str;


use File;

class FrontEventController extends Controller
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
            $sliders = FrontEvent::select(['title', 'id','images','from','to','description','status']);

            return DataTables::of($sliders)
                            ->addColumn(
                                'action',
                                function ($row) {
                                    $html= '<div class="dropdown">
                             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                             <ul class="dropdown-menu" style="">';
                                    $html .= '<li><a  href="' .action('Frontend\FrontEventController@edit', [$row->id]).'" class=" dropdown-item"><i class="bx bxs-edit f-16 mr-15"></i>' . __("english.edit") . '</a></li>';
                                    $html .= '<li><a  href="#" data-href="' .action('Frontend\FrontEventController@destroy', [$row->id]).'" class=" delete_event_button dropdown-item"><i class="bx bxs-trash f-16 mr-15"></i>' . __("english.delete") . '</a></li>';


                                    $html .= '</ul></div>';

                                    return $html;
                                }
                            )
                            ->editColumn('images', function ($row) {
                                    $images = file_exists(public_path('uploads/front_image/'.$row->images)) ? $row->images : 'default.jpg';
                                    $img=' <img src="'.url('uploads/front_image/' . $images).'" class="img-border " width="50" height="50" alt="" ></div>';
                                    return $img;
                            })
                            ->removeColumn('id')
                            ->rawColumns(['action','images','description'])
                            ->make(true);
        }

        return view('frontend.backend.event.index');
    }


   public function store(Request $request)
   {
       try {
           $input = $request->only(['title','description','status']);
           if (!empty($request->input('status'))) {
               $input['status']='publish';
           }
           $input['slug']=  Str::slug($input['title']);
           $input['from']=$this->commonUtil->uf_date($request->input('from'));
           $input['to']=$this->commonUtil->uf_date($request->input('to'));

           $filename=$this->commonUtil->uploadFile($request, 'image', 'front_image', 'image');
           $input['images']=$filename;
           FrontEvent::create($input);
           $output = ['success' => true,
                   'msg' => __("english.added_success")
               ];
       } catch (\Exception $e) {
           \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

           $output = ['success' => false,
           'msg' => __("english.something_went_wrong")
           ];
       }
       return redirect('front-events')->with('status', $output);
   }
   public function create()
   {
       return view('frontend.backend.event.create');
   }

   public function edit($id)
   {
       $event= FrontEvent::find($id);
       return view('frontend.backend.event.edit')->with(compact('event'));
       
   }
   public function update(Request $request, $id)
   {
       try {
           $input = $request->only(['title','description','status']);
           if (!empty($request->input('status'))) {
               $input['status']='publish';
           } else {
               $input['status']='not_publish';
           }
           //dd($input);
           $event= FrontEvent::find($id);
           $event->title= $input['title'];
          $event->from=$this->commonUtil->uf_date($request->input('from'));
          $event->to=$this->commonUtil->uf_date($request->input('to'));   
           $event->status= $input['status'];
           $event->description= $input['description'];
           if ($request->hasFile('image') && $request->file('image')->isValid()) {
               if (File::exists(public_path('uploads/front_image/'. $event->image))) {
                   File::delete(public_path('uploads/front_image/'. $event->image));
               }
               $filename=$this->commonUtil->uploadFile($request, 'image', 'front_image', 'image');
               $event->images=$filename;
           }
           $event->save();
           $output = ['success' => true,
           'msg' => __("english.updated_success")
];
       } catch (\Exception $e) {
           \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

           $output = ['success' => false,
           'msg' => __("english.something_went_wrong")
           ];
       }
       return redirect('front-events')->with('status', $output);
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
              $event= FrontEvent::find($id);
             
              if (File::exists(public_path('uploads/front_image/'. $event->images))) {
                  File::delete(public_path('uploads/front_image/'. $event->images));
              }
              $event->delete();
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
