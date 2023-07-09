<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Template;

class TemplateController extends Controller
{
    public function index()
    {
        $view = 'admin_view';
    	$title = "Manage Template List";
    	$templates = Template::whereNull('user_id')->paginate(paginateNumber());
    	return view('admin.template.index', compact('title', 'templates','view'));
    }

    public function store(Request $request)
    {
    	$data = $request->validate([
    		'name' => 'required|max:255',
    		'message' => 'required',
    	]);
        $message = '';
    	Template::create([
			'name' => $request->name,
			'message' => offensiveMsgBlock($request->message),
		]);
        if (offensiveMsgBlock($request->message) != $request->message ){
            $message = session()->get('offsensiveNotify') ;
        }
    	$notify[] = ['success', 'Template has been created'.$message];
    	return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $message = '';
    	$data = $request->validate([
    		'name' => 'required|max:255',
    		'message' => 'required',
    	]);
    	$template = Template::whereNull('user_id')->where('id', $request->id)->firstOrFail();
    	$template->update([
			'name' => $request->name,
			'message' => offensiveMsgBlock($request->message),
		]);
        if (offensiveMsgBlock($request->message) != $request->message ){
            $message = session()->get('offsensiveNotify') ;
        }
    	$notify[] = ['success','Template has been updated'.$message];
    	return back()->withNotify($notify);
    }

    public function delete(Request $request)
    {
    	$template = Template::whereNull('user_id')->where('id', $request->id)->firstOrFail();
        $template->delete();
    	$notify[] = ['success', 'Template has been deleted'];
    	return back()->withNotify($notify);
    }

    /**
     * user template section start
     *
     */
    public function userIndex()
    {
    	$title = "Manage User Template List";
		$view = 'user_view';
    	$templates = Template::whereNotNull('user_id')->paginate(paginateNumber());
    	return view('admin.template.index', compact('title', 'templates', 'view'));
    }

    public function updateStatus(Request $request)
	{
		$request->validate([
			'id' => 'required|exists:templates,id',
			'status' => 'required|in:1,2,3'
		]);
		$template = Template::where('id', $request->id)->first();
		$template->status = $request->status;
		$template->save();
		$notify[] = ['success', 'Status Updated Successfully'];
    	return back()->withNotify($notify);
	}

    /**
     * user template section end
     *
     */

}
