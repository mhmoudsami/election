<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Candidate;


class ProxyController extends Controller
{
	/**
	 * get candidate information
	 * @return [type] [description]
	 */
    public function index($nid)
    {
        $candidate = Candidate::where(['uid' => $nid])->first();

        if ( $candidate ) {
        	return [
        		'success' => true,
        		'exist' => true,
        		'message' => 'هذا الناخب مسجل من قبل',
        		'payload' => $candidate,
        	];
        }

        
		if (\App::environment('local')) {
		    $proxy = '';
		}else{
			$proxy = 'socks5://127.0.0.1:9050';
		}

		$response = Http::withOptions([
		    'proxy' => $proxy,
		])->get('https://proxy.elections.eg/election', [
			'nid' => $nid,
		    'location' => 1,
		    'cons' => 1,
		    'external_referrer' => 'https://www.elections.eg/',
		    '_' => '1603193207573',
		]);

		// $decoded = json_decode($response->body());

		// if ( isset($decoded->constituencies) ) {
		// 	if ( in_array($decoded->constituencies->indiv_const->name, ['الخانكه' , 'الخصوص' , 'العبور']) ) {
	 //        	return [
	 //        		'success' => false,
	 //        		'message' => 'هذا الناخب خارج نطاق البحث',
	 //        	];
		// 	}
		// }


		return $response;
    }

    public function hip()
    {
		return Http::withOptions([
		    'proxy' => 'socks5://127.0.0.1:9050',
		])->get('https://api.ipify.org?format=json');
    }

    public function ip()
    {
		return Http::get('https://api.ipify.org?format=json');
    }

}
