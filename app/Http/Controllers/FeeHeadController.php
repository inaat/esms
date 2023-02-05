<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeeHead;
use App\Models\FeeTransactionLine;
use App\Models\Campus;
use App\Models\Classes;

use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use DB;

class FeeHeadController extends Controller
{
    protected $commonUtil;

    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @return void
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
        if (!auth()->user()->can('fee_head.view')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $system_settings_id = session()->get('user.system_settings_id');

            $fee_heads = FeeHead::leftjoin('campuses', 'fee_heads.campus_id', '=', 'campuses.id')
                        ->leftjoin('classes as c', 'fee_heads.class_id', '=', 'c.id')
                        ->whereNotNull('fee_heads.class_id')
                        ->select(['fee_heads.id', 'fee_heads.description', 'fee_heads.amount','campuses.campus_name as campus_name','c.title as class_name']);
            // Check for permitted campuses of a user
            $permitted_campuses = auth()->user()->permitted_campuses();
            if ($permitted_campuses != 'all') {
                $fee_heads->whereIn('fee_heads.campus_id', $permitted_campuses);
            }
            if (request()->has('campus_id')) {
                $campus_id = request()->get('campus_id');
                if (!empty($campus_id)) {
                    $fee_heads->where('campuses.id', $campus_id);
                }
            }
            if (request()->has('class_id')) {
                $class_id = request()->get('class_id');
                if (!empty($class_id)) {
                    $fee_heads->where('fee_heads.class_id', $class_id);
                }
            }


            return DataTables::of($fee_heads)
                           ->addColumn(
                               'action',
                               '<div class="d-flex order-actions">
                                 @can("fee_head.update")
                               <button data-href="{{action(\'FeeHeadController@edit\', [$id])}}" class="btn btn-sm btn-primary edit_fee_head_button"><i class="bx bxs-edit f-16 mr-15 text-white"></i> @lang("english.edit")</button>
                                   &nbsp;
                                    @endcan
                                    @can("fee_head.delete")
                                   <button data-href="{{action(\'FeeHeadController@destroy\', [$id])}}" class="btn btn-sm btn-danger delete_fee_head_button"><i class="bx bxs-trash f-16 text-white"></i> @lang("english.delete")</button>
                                   @endcan
                               </div>'
                           )
                           ->editColumn(
                               'amount',
                               '<span class="amount" data-orig-value="{{$amount}}">@format_currency($amount)</span>'
                           )
                           ->filterColumn('campus_name', function ($query, $keyword) {
                            $query->where(function ($q) use ($keyword) {
                                $q->where('campuses.campus_name', 'like', "%{$keyword}%");
                            });
                        })
                           ->filterColumn('class_name', function ($query, $keyword) {
                            $query->where(function ($q) use ($keyword) {
                                $q->where('c.title', 'like', "%{$keyword}%");
                            });
                        })
                           ->removeColumn('id')
                           ->rawColumns(['action', 'campus_name','class_name','description','amount'])
                           ->make(true);
        }
        $campuses=Campus::forDropdown();
        return view('fee-heads.index')->with(compact('campuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('fee_head.create')) {
            abort(403, 'Unauthorized action.');
        }
        $system_settings_id = session()->get('user.system_settings_id');
        $campuses=Campus::forDropdown();
        return view('fee-heads.create')->with(compact('campuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('fee_head.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['campus_id','class_id','description','amount']);
            $input['amount']=$this->commonUtil->num_uf($input['amount']);
            if (!empty($request->input('check_all'))) {
                $classes= Classes::where('campus_id', $input['campus_id'])->get();
                foreach ($classes as $class) {
                    $input['class_id']=$class->id;
                    $fee_head = FeeHead::create($input);
                }
            } else {
                $fee_head = FeeHead::create($input);
            }
            $output = ['success' => true,
                            'msg' => __("english.added_success")
                        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
        }

        return $output;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('fee_head.update')) {
            abort(403, 'Unauthorized action.');
        }
        $fee_head= FeeHead::find($id);
        $system_settings_id = session()->get('user.system_settings_id');
        $campuses=Campus::forDropdown();
        $classes=Classes::forDropdown($system_settings_id, $fee_head->campus_id);
        return view('fee-heads.edit')->with(compact('classes', 'fee_head', 'campuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('fee_head.update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $input = $request->only(['campus_id','class_id','description','amount']);
            $input['amount']=$this->commonUtil->num_uf($input['amount']);
            $fee_head = FeeHead::find($id);
            $fee_head->fill($input);
            $fee_head->save();
            $output = ['success' => true,
            'msg' => __("english.updated_success")
        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
            'msg' => __("english.something_went_wrong")
            ];
        }

        return  $output;
    }

    /**
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
                $check_fee_head=FeeTransactionLine::where('fee_head_id', $id)->first();
                if (empty($check_fee_head)) {
                    $fee_head =FeeHead::findOrFail($id);
                    $fee_head->delete();
                    $output = ['success' => true,
                    'msg' => __("english.deleted_success")
                    ];
                } else {
                    $output = ['success' => false,
                    'msg' => __("english.you_cannot_delete_this_data")
                    ];
                }
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
