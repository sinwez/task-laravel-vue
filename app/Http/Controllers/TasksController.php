<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Repositories\TasksRepository;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $task;
    public function __construct(TasksRepository $task)
    {
        $this->task = $task;
    }

    public function index()
    {
        $toDo = Auth::user()->tasks()->where('completed',0)->paginate(15);
        $Done = Auth::user()->tasks()->where('completed',1)->paginate(15);
        $projects = Project::lists('name','id');
        return view('tasks.index',compact('toDo','Done','projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTaskRequest $request)
    {
        Task::create([
            'title' =>$request -> name,
            'project_id' => $request->project
        ]);

        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.details',compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskRequest $request, $id)
    {
        $task = Task::findOrfail($id);
        $task->title = $request->title;
        $task->project_id = $request->projectList;
        $task -> save();
        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Task::find($id) -> delete();
        return Redirect::back();
    }

    public function check($id){
        $task = Task::findOrfail($id);
        $task -> completed = 1;
        $task -> save();
        return Redirect::back();

    }

    public function charts(){
        $total = $this->task->total();
        $toDoCount = $this->task->toDoCount();
        $doneCount = $this->task->doneCount();
        $names = Project::lists('name');
        $projects = Project::with('tasks')->get();
        return view('tasks.charts',compact('total','toDoCount','doneCount','names','projects'));
    }

    public function searchApi()
    {
        $tasks = Auth :: user()->tasks;
        return $tasks;
    }
}
