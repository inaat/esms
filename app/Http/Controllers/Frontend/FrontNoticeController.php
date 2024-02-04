<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Frontend\FrontSlider;
use App\Models\Frontend\FrontNotice;
use Illuminate\Http\Request;
use App\Models\Frontend\FrontAboutUs;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use Illuminate\Support\Str;


use File;

class FrontNoticeController extends Controller
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
               $notice = FrontNotice::select(['title', 'id','description','status','link']);
   
               return DataTables::of($notice)
                               ->addColumn(
                                   'action',
                                   function ($row) {
                                       $html= '<div class="dropdown">
                                <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                                <ul class="dropdown-menu" style="">';
                                       $html .= '<li><a  href="' .action('Frontend\FrontNoticeController@edit', [$row->id]).'" class=" dropdown-item"><i class="bx bxs-edit f-16 mr-15"></i>' . __("english.edit") . '</a></li>';
                                       $html .= '<li><a  href="#" data-href="' .action('Frontend\FrontNoticeController@destroy', [$row->id]).'" class=" delete_notice_button dropdown-item"><i class="bx bxs-trash f-16 mr-15"></i>' . __("english.delete") . '</a></li>';
   
   
                                       $html .= '</ul></div>';
   
                                       return $html;
                                   }
                               )
                               
                               ->removeColumn('id')
                               ->rawColumns(['action','description'])
                               ->make(true);
           }
   
           return view('frontend.backend.notice.index');
       }
   
   
 
   public function show($slug, $id){
      $data = FrontNotice::where('id', $id)
     // ->orWhere('slug', $slug)
      ->firstOrFail();
      $nav=FrontNotice::select('title','id','slug')->get();
      //dd();
    return view('frontend.notice.show')->with(compact('data','nav'));

   }
   public function create()
   {
       return view('frontend.backend.notice.create');
   }
   public function store(Request $request)
   {
       try {
           $input = $request->only(['title','description','status','link']);
           if (!empty($request->input('status'))) {
               $input['status']='publish';
           }
           $input['slug']=  Str::slug($input['title']);

           FrontNotice::create($input);
           $output = ['success' => true,
                   'msg' => __("english.added_success")
               ];
       } catch (\Exception $e) {
           \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

           $output = ['success' => false,
           'msg' => __("english.something_went_wrong")
           ];
       }
       return redirect('front-notices')->with('status', $output);
   }
   public function edit($id)
   {
       $notice= FrontNotice::find($id);
       return view('frontend.backend.notice.edit')->with(compact('notice'));
   }
   public function update(Request $request, $id)
   {
       try {
         $input = $request->only(['title','description','status','link']);
         if (!empty($request->input('status'))) {
               $input['status']='publish';
           } else {
               $input['status']='not_publish';
           }
           //dd($input);
           $notice= FrontNotice::find($id);
           $notice->title= $input['title'];
           $notice->slug=  Str::slug($input['title']);
           $notice->status= $input['status'];
           $notice->link= $input['link'];
           $notice->description= $input['description'];
           $notice->save();
           $output = ['success' => true,
           'msg' => __("english.updated_success")
];
       } catch (\Exception $e) {
           \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

           $output = ['success' => false,
           'msg' => __("english.something_went_wrong")
           ];
       }
       return redirect('front-notices')->with('status', $output);
   }


    
     public function destroy($id)
     {
         if (!auth()->user()->can('fee_head.delete')) {
             abort(403, 'Unauthorized action.');
         }
   
         if (request()->ajax()) {
             try {
                 $notice= FrontNotice::find($id);
                
                
                 $notice->delete();
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
