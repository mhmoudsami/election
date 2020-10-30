<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $candidates = Candidate::where([]);

        
        if ( $request->supervisor ) {
            $candidates->where(['supervisor_id' => $request->supervisor]);
        }
        if ( $request->candidateName ) {
            $candidates->where('name'  , 'LIKE' , "%$request->candidateName%");
        }

        $candidates = $candidates->orderBy('id' , 'DESC')->paginate(20);
        
        return $candidates;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $candidate = Candidate::where(['uid' => $request->uid])->first();

        if ( $candidate ) {
            return [
                'success' => false,
                'message' => 'هذا الناخب مسجل مسبقا',
                'errors' => [
                    'هذا الناخب مسجل مسبقا'
                ]
            ];
        }

        $candidate = Candidate::create([
            'boxnumber' => $request->boxnumber,
            'address' => $request->address,
            'citizen_number' => $request->citizen_number,
            'citizen_number' => $request->citizen_number,
            'date_round_1' => $request->date_round_1,
            'date_round_2' => $request->date_round_2,
            'indiv_const' => $request->indiv_const,
            'list_const' => $request->list_const,
            'location' => $request->location,
            'name' => $request->name,
            'supervisor_id' => $request->supervisor,
            'police' => $request->police,
            'state' => $request->state,
            'uid' => $request->uid,
            'info' => json_encode($request->all),
        ]);

        $candidate->save();

        return [
            'success' => true,
            'message' => 'تم حفظ بيانات الناخب بنجاح',
            'errors' => [],
            'payload' => $candidate,
        ];
    }


    public function search(Request $request)
    {
        return Candidate::where('uid' , 'LIKE' , "%{$request->term}%")->get();
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function show(Candidate $candidate)
    {
        return $candidate;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Candidate $candidate)
    {
        $candidate->name = $request->name;
        $candidate->supervisor_id = $request->supervisor_id;

        if ( ! $candidate->save() ) {
            return [
                'success' => false,
                'message' => 'لم نتمكن من تحديث بيانات الناخب',
            ];
        }
        return [
            'success' => true,
            'message' => 'تم تحديث بيانات الناخب بنجاح',
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Candidate $candidate)
    {
        $candidate->delete();
        return [
            'success' => true,
            'message' => 'تم حذف بيانات الناخب',
        ];
    }
}
