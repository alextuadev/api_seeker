<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeekerRequest;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class SeekerController extends Controller
{
    /** ?soap_method=QueryByName&name=spiderman
     */

    private $apple_url = "https://itunes.apple.com/";
    private $maze_url = "http://api.tvmaze.com/search/";
    private $people_url = "http://www.crcind.com/csp/samples/SOAP.Demo.cls";


    protected function makeRequest($base_url, $qry_params)
    {
        $client = new Client();
        $response = $client->get("$base_url?$qry_params");
        $data_body = [];

        if ($response->getStatusCode() == 200) {
            $data_body = json_decode($response->getBody(), true);
        }
        $code = $response->getStatusCode();
        return ["data" => $data_body, 'code' => $code];
    }

    protected function createArrayApple($data)
    {
        $elements = [];
        $new_array = [];
        foreach ($data as $item) {
            $elements['name'] =  array_key_exists('trackName', $item) ? $item['trackName'] : $item['collectionName'];
            $elements['type'] = array_key_exists('kind', $item) ? $item['kind'] : 'ebook';
            $elements['image'] = $item['artworkUrl100'];
            $elements['origin'] = 'apple';
            array_push($new_array, $elements);
        }
        return $new_array;
    }


    protected function createArrayMaze($data)
    {
        $elements = [];
        $new_array = [];
        foreach ($data as $item) {
            $elements['name'] = $item['show']['name'];
            $elements['type'] = 'shows';
            $elements['image'] = $item['show']['image'];
            $elements['origin'] = 'maze';
            array_push($new_array, $elements);
        }
        return $new_array;
    }


    public function search(Request $request)
    {
        /*$request->validate([
            'term' => 'required|max:255'
        ]);*/
        $search = $request->get('term');

        if (!$request->has('term')) {
            $response = ['code' => 400, 'message' => 'The field "term" cannot be empty', 'countResult' => 0, 'data' => []];
            return response()->json($response, 400);
        }

        $array_apple = [];
        $apple = $this->makeRequest($this->apple_url . 'search', "term=$search&limit=70");
        if ($apple['code'] == 200) {
            $array_apple = $this->createArrayApple($apple['data']['results']);
        }
        $maze = $this->makeRequest($this->maze_url . 'shows', "q=$search");
        $array_maze = $this->createArrayMaze($maze['data']);

        $full_array = array_merge($array_apple, $array_maze);
        shuffle($full_array);

        /** create new data structure */


        $response = ['countResult' => count($full_array), 'data' => $full_array];
        return response()->json($response);
    }
}
