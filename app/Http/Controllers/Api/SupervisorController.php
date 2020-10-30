<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supervisors = Supervisor::where([]);
        $supervisors->orderBy('id' , 'DESC');
        
        return $supervisors->paginate(20);
    }


    public function list()
    {
        $supervisors = Supervisor::all(['name' , 'id' , 'mobile']);
        
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
        $user = User::where(['name' => $request->username])->first();


        if ( $supervisor ) {
            return [
                'success' => false,
                'message' => 'هذا المسئول مسجل مسبقا',
                'errors' => [
                    'هذا المسئول مسجل مسبقا'
                ]
            ];
        }

        if ( $user ) {
            return [
                'success' => false,
                'message' => 'اسم المستخدم مسجل',
            ];
        }

        $supervisor = Supervisor::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'mobile_2' => $request->mobile_2,
        ]);

        $supervisor->save();

        $user = User::create([
            'email' => time()."@election.info",
            'email_verified_at' => now(),
            'name' => $request->username,
            'password' => Hash::make($request->password),
        ]);
        $user->save();

        $supervisor->user_id = $user->id;
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
        $nameExist = false;

        if ( $supervisor->user ) {
            $nameExist  = User::where(['name' => $request->username])->where('id' , '!=' , $request->user_id)->first();

            if ( $nameExist ) {
                return [
                    'success' => false,
                    'message' => 'اسم المستخدم مسجل من قبل',
                ];
            }
        }
        



        $supervisor->name = $request->name;
        $supervisor->mobile = $request->mobile;
        $supervisor->mobile_2 = $request->mobile_2;
        

        if ( ! $supervisor->save() ) {
            return [
                'success' => false,
                'message' => 'لم نتمكن من تحديث بيانات المسئول',
            ];
        }


        /**
         * update user name and password if exist
         * @var [type]
         */
        $user = $supervisor->user;
        $user->name = $request->username;
        if ( $request->password ) {
            $user->password = Hash::make($request->password);
        }
        $user->save();





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
