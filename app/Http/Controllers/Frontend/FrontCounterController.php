<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\Frontend\FrontCounter;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use Illuminate\Support\Str;


use File;

class FrontCounterController extends Controller
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
            $sliders = FrontCounter::select(['title', 'id','link','number','status']);

            return DataTables::of($sliders)
                            ->addColumn(
                                'action',
                                function ($row) {
                                    $html= '<div class="dropdown">
                             <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'. __("english.actions").'</button>
                             <ul class="dropdown-menu" style="">';
                                    $html .= '<li><a  href="' .action('Frontend\FrontCounterController@edit', [$row->id]).'" class=" dropdown-item"><i class="bx bxs-edit f-16 mr-15"></i>' . __("english.edit") . '</a></li>';
                                    $html .= '<li><a  href="#" data-href="' .action('Frontend\FrontCounterController@destroy', [$row->id]).'" class=" delete_event_button dropdown-item"><i class="bx bxs-trash f-16 mr-15"></i>' . __("english.delete") . '</a></li>';


                                    $html .= '</ul></div>';

                                    return $html;
                                }
                            )
                          
                            ->removeColumn('id')
                            ->rawColumns(['action'])
                            ->make(true);
        }

        return view('frontend.backend.counters.index');
    }


   public function store(Request $request)
   {
       try {
           $input = $request->only(['title','link','number','status']);
           if (!empty($request->input('status'))) {
               $input['status']='publish';
           }
           $input['slug']=  Str::slug($input['title']);
     
           FrontCounter::create($input);
           $output = ['success' => true,
                   'msg' => __("english.added_success")
               ];
       } catch (\Exception $e) {
           \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

           $output = ['success' => false,
           'msg' => __("english.something_went_wrong")
           ];
       }
       return redirect('front-counters')->with('status', $output);
   }
   public function create()
   {
       return view('frontend.backend.counters.create');
   }

   public function edit($id)
   {
       $counter= FrontCounter::find($id);
       return view('frontend.backend.counters.edit')->with(compact('counter'));
       
   }
   public function update(Request $request, $id)
   {
       try {
           $input = $request->only(['title','link','number','status']);
           if (!empty($request->input('status'))) {
               $input['status']='publish';
           } else {
               $input['status']='not_publish';
           }
           //dd($input);
           $event= FrontCounter::find($id);
           $event->title= $input['title'];
       
           $event->status= $input['status'];
           $event->number= $input['number'];
           $event->link= $input['link'];
        
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
       return redirect('front-counters')->with('status', $output);
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
              $event= FrontCounter::find($id);
             
         
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
