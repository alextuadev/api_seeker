<?php

namespace App\Http\Controllers;
use App\Traits\RestRequestTrait;
use App\Traits\SoapRequestTrait;
use Illuminate\Http\Request;

/**
 * @OA\Info(title="API Seeker", version="1.0")
 *
 * @OA\Server(url="http://localhost:8000")
 */
class SeekerController extends Controller
{

    use RestRequestTrait;
    use SoapRequestTrait;

    private $apple_url = "https://itunes.apple.com/";
    private $maze_url = "http://api.tvmaze.com/search/";
    private $people_url = "http://www.crcind.com/csp/samples/SOAP.Demo.cls";


    protected function createArrayApple($data)
    {
        $elements = [];
        $new_array = [];
        foreach ($data as $item) {
            $elements['name'] =  array_key_exists('trackName', $item) ? $item['trackName'] : $item['collectionName'];
            $elements['type'] = array_key_exists('kind', $item) ? $item['kind'] : 'ebook';
            $elements['image'] = $item['artworkUrl100'];
            $elements['url'] = array_key_exists('previewUrl', $item)  ? $item['previewUrl'] : "";
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
            $elements['image'] = $item['show']['image']['medium'] ?? "";
            $elements['origin'] = 'maze';
            $elements['url'] = $item['show']['url'];
            array_push($new_array, $elements);
        }
        return $new_array;
    }

    /**
     * @OA\Get(
     *     path="/api/search",
     *     summary="Show a list with the request data",
     *     @OA\Parameter(
     *          name="term",
     *          description="key param to search",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Show response"
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Not found"
     *     )
     * )
     */
    public function search(Request $request)
    {
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
