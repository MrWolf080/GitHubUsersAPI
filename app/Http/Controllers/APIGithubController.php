<?php

namespace App\Http\Controllers;

use App\Models\GitHubUser;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class APIGithubController extends Controller
{
    private function getUsersFromAPI($per_page, $since)
    {
        $client = new Client([
            'verify' => false
        ]);
        $response = $client->get('https://api.github.com/users?per_page=' . $per_page . '&since=' . $since);
        return $response->getBody()->getContents();
    }

    public function prev(Request $req)
    {
        $data_req=$req->all();
        $users=GitHubUser::where('id', '<', $data_req['first_id'])->orderBy('id', 'desc')->take($data_req['per_page'])->get();
        return response()->json($users);
    }

    public function next(Request $req)
    {
        $data_req=$req->all();
        $users=GitHubUser::where('id','>',$data_req['last_id'])->take($data_req['per_page'])->get();
        if($users->count()<$data_req['per_page'])
        {
            $data=$this->getUsersFromAPI($data_req['per_page'], $data_req['last_id']);
            $decoded_data=json_decode($data);
            foreach($decoded_data as $dat) {
                if(!GitHubUser::find($dat->id))
                    GitHubUser::create((array)$dat);
            }
            return response()->json($decoded_data);
        }
        else
            return response()->json($users);
    }
}
