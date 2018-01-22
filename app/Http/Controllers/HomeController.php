<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Task; 
use Auth;
 
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		if(isset($_GET["did"])){
			
			$did = $_GET["did"];
			$delete = Task::find($did);
			$delete->delete();
		}
		
		$user_id = Auth::user()->id;
		$taskview = Task::all()->where('author_id', $user_id);

		/*
		*To get all task added by user we need to select * task WHERE Auth user id == 
		*/
        return view('home', ['tasks' => $taskview,]);
    }
	
	public function addTask(Request $request)
	{
		$user_id = Auth::user()->id;
		$this->validate($request,[
			'task' => 'required'
		]); 
		$task = new Task;
		$task->task = $request->input('task');
		$task->author_id = Auth::user()->id;
		$task->save();
		return redirect('/home')->with('response', 'Task Added Successfully'); 
	}
	public function editTask() 
	{
		if(isset($_GET["tid"])){
			$id = $_GET["tid"];
			$taskview = Task::all()->where('id', $id);
			return view('edit', ['tasks' => $taskview,]);
		}else{
			return redirect('/home');
		}
		
	}
}
