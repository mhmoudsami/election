<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supervisor;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supervisors = Supervisor::paginate(20);
        
        return $supervisors;
    }


    public function list()
    {
        $supervisors = Supervisor::all();
        
        return $supervisors;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $supervisor = Supervisor::where(['mobile' => $request->mobile])->first();

        if ( $supervisor ) {
            return [
                'success' => false,
                'message' => 'هذا المسئول مسجل مسبقا',
                'errors' => [
                    'هذا المسئول مسجل مسبقا'
                ]
            ];
        }

        $supervisor = Supervisor::create([
            'mobile' => $request->mobile,
            'name' => $request->name,
        ]);

        $supervisor->save();

        return [
            'success' => true,
            'message' => 'تم حفظ بيانات المسئول بنجاح',
            'errors' => [],
            'payload' => $supervisor,
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supervisor  $supervisor
     * @return \Illuminate\Http\Response
     */
    public function show(Supervisor $supervisor)
    {
        return $supervisor;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supervisor  $supervisor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supervisor $supervisor)
    {
        $supervisor->name = $request->name;
        $supervisor->mobile = $request->mobile;

        if ( ! $supervisor->save() ) {
            return [
                'success' => false,
                'message' => 'لم نتمكن من تحديث بيانات المسئول',
            ];
        }
        return [
            'success' => true,
            'message' => 'تم تحديث بيانات المسئول بنجاح',
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supervisor  $supervisor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supervisor $supervisor)
    {
        $supervisor->delete();
        
        return [
            'success' => true,
            'message' => 'تم حذف بيانات المسئول',
        ];
    }
}
