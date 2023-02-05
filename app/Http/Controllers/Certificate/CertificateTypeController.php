<?php

namespace App\Http\Controllers\Certificate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Certificate\CertificateType;
use Yajra\DataTables\Facades\DataTables;
class CertificateTypeController extends Controller
{
    /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
               
  public function index()
  {
      if (!auth()->user()->can('term.view') && !auth()->user()->can('term.create')) {
          abort(403, 'Unauthorized action.');
      }

      if (request()->ajax()) {

          $exam_terms = CertificateType::select(['name', 'id']);
          return Datatables::of($exam_terms)
              ->addColumn(
                  'action',
                  '
                  <div class="d-flex order-actions">
                  <button data-href="{{action(\'certificate\certificate_typeController@edit\', [$id])}}" class="btn btn-sm btn-primary edit_exam_term_button"><i class="bx bxs-edit f-16 mr-15 text-white"></i> @lang("english.edit")</button>
                      &nbsp;
                      <button data-href="{{action(\'certificate\certificate_typeController@destroy\', [$id])}}" class="btn btn-sm btn-danger delete_exam_term_button"><i class="bx bxs-trash f-16 text-white"></i> @lang("english.delete")</button>
                  </div>
                  '

              )
              
              ->removeColumn('id')
              ->rawColumns(['action','name'])
              ->make(true);
      }

      return view('certificate.certificate_type.index');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
      // if (!auth()->user()->can('term.create')) {
      //     abort(403, 'Unauthorized action.');
      // }
      return view('certificate.certificate_type.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
      // if (!auth()->user()->can('term.create')) {
      //     abort(403, 'Unauthorized action.');
      // }

      try {
          $input = $request->only(['name']);

          $exam_term = CertificateType::create($input);
        

          $output = ['success' => true,
                          'data' => $exam_term,
                          'msg' => __("term.added_success")
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
      // if (!auth()->user()->can('term.update')) {
      //     abort(403, 'Unauthorized action.');
      // }

      if (request()->ajax()) {

          $exam_term = CertificateType::find($id);
          return view('certificate.certificate_type.edit')
              ->with(compact('exam_term'));
      }
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
      // if (!auth()->user()->can('term.update')) {
      //     abort(403, 'Unauthorized action.');
      // }

      if (request()->ajax()) {
          try {
              $input = $request->only(['name']);

              $exam_term = CertificateType::findOrFail($id);
              $exam_term->name = $input['name'];
              $exam_term->save();

              $output = ['success' => true,
                          'msg' => __("term.updated_success")
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

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {

      // if (!auth()->user()->can('term.delete')) {
      //     abort(403, 'Unauthorized action.');
      // }

      if (request()->ajax()) {
          try {


              $exam_term = CertificateType::findOrFail($id);
              $exam_term->delete();

              $output = ['success' => true,
                          'msg' => __("term.deleted_success")
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
