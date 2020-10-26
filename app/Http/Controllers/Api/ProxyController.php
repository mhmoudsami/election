<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class ProxyController extends Controller
{
	/**
	 * get candidate information
	 * @return [type] [description]
	 */
    public function index($nid)
    {
		$response = Http::get('https://proxy.elections.eg/election', [
			'nid' => $nid,
		    'location' => 1,
		    'cons' => 1,
		    'external_referrer' => 'https://www.elections.eg/',
		    '_' => '1603193207573',
		]);

		return $response;
    }
}
