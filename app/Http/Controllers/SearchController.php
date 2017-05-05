<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;

class SearchController extends Controller
{
    public function test() {
        return view('elasticsearch');
    }
    public function index(Request $request) {
        $key = null;
        $total = 0;
        $results = array(); 
        $key_full_text = $request->key_full_text;
        $key_one_word = $request->key_one_word;
        $key_all_word = $request->key_all_word;

        if (!empty($request->all())) {    
            $r = null;
            if ($request->optradio == 1) {
                $key = $key_full_text;
                $r = SearchController::search($request->search_param, $key, 1);
            } 
            if ($request->optradio == 2) {
                $key = $key_one_word;
                $r = SearchController::search($request->search_param, $key, 2);
            } 
            if ($request->optradio == 3) {
                $key = $key_all_word;
                $r = SearchController::search($request->search_param, $key, 3);
            }      
            // $r = SearchController::search($request->search_param, $key);

            // paginate
            // Get current page form url e.x. &page=1
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            // Create a new Laravel collection from the array data
            $itemCollection = collect($r);
            $total = $itemCollection->count();
            // Define how many items we want to be visible in each page
            $perPage = 20;
            // Slice the collection to get the items to display in current page
            $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
            // Create our paginator and pass it to the view
            $results= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);

            // set url path for generted links
            $results->appends([
                                'search_param' => $request->search_param, 
                                'key_full_text' => $key_full_text,
                                'key_one_word' => $key_one_word,
                                'key_all_word' => $key_all_word,
                                'optradio' => $request->optradio
                                ])->setPath($request->url());
            // return view('items_view', ['r' => $paginatedItems]);
        }
        return view('search', compact('results', 'key', 'total', 'key_one_word', 'key_full_text', 'key_all_word'));
    }

    public function searchFullText ($search_param, $q) {
        $data = array();
        if ($search_param == 'title') {
            $data = array(
                "from" => 0, "size" => 5000,
                "query" =>array(
                    "match_phrase" => array("title" => $q)
                    )
                ,
                "highlight" => array(
                    "fields" => array(
                        "title" => [
                        "require_field_match"=> false,
                                "fragment_size" => 150, 
                                "number_of_fragments" => 1,
                                "no_match_size"=> 0,
                                "pre_tags" => ["<b>"],
                                "post_tags" => ["</b>"],
                            ]
                    )
                )
            );
        }

        if ($search_param == 'content') {
            $data = array(
                "from" => 0, "size" => 5000,
                "query" =>array(
                    "match_phrase" => array("content" => $q)
                    )
                ,
                "highlight" => array(
                    "fields" => array(
                        "content" => [
                                "fragment_size" => 150, 
                                "number_of_fragments" => 1,
                                "no_match_size"=> 0,
                                "pre_tags" => ["<b>"],
                                "post_tags" => ["</b>"],
                            ]
                    )
                )
            );
        }
        
        if ($search_param == 'all') {
            $data = array(
                "from" => 0, "size" => 5000,
                "query" => array(
                    "bool" => array(
                        "should" => [
                            array("match_phrase" => array("title" => $q ) ),
                            array("match_phrase" => array("content" => $q ) ),
                            ]
                        )
                    )
                    ,
                    "highlight" => array(
                        "fields" => array(
                            "content" => [
                                    "fragment_size" => 150, 
                                    "number_of_fragments" => 1,
                                    "no_match_size"=> 0,
                                    "pre_tags" => ["<b>"],
                                    "post_tags" => ["</b>"],
                                ]
                        )
                    )
                );
        }

        return $data;
    }

    public function searchOneWord ($search_param, $q) {
        $data = array();
        if ($search_param == 'title') {
            $data = array(
                "from" => 0, "size" => 5000,
                // "query" => array("match" => array( "title" => $q )) 
                "query" =>array(
                    "match" => array("title" => $q)
                    )
                ,
                "highlight" => array(
                    "fields" => array(
                        "title" => [
                                "fragment_size" => 150, 
                                "number_of_fragments" => 1,
                                "no_match_size"=> 0,
                                "pre_tags" => ["<b>"],
                                "post_tags" => ["</b>"],
                            ]
                    )
                )
            );
        }

        if ($search_param == 'content') {
            $data = array(
                "from" => 0, "size" => 5000,
                "query" =>array(
                    "match" => array("content" => $q)
                    )
                ,
                "highlight" => array(
                    "fields" => array(
                        "content" => [
                                "fragment_size" => 150, 
                                "number_of_fragments" => 1,
                                "no_match_size"=> 0,
                                "pre_tags" => ["<b>"],
                                "post_tags" => ["</b>"],
                            ]
                    )
                )
            );
        }
        
        if ($search_param == 'all') {
            $data = array(
                "from" => 0, "size" => 5000,
                "query" => array(
                    "bool" => array(
                        "should" => [
                            array("match" => array("title" => $q ) ),
                            array("match" => array("content" => $q ) ),
                            ]
                        )
                    )
                    ,
                    "highlight" => array(
                        "fields" => array(
                            "content" => [
                                    "fragment_size" => 150, 
                                    "number_of_fragments" => 1,
                                    "no_match_size"=> 0,
                                    "pre_tags" => ["<b>"],
                                    "post_tags" => ["</b>"],
                                ]
                        )
                    )
                );
        }
        return $data;
    }

    public function searchAllWord ($search_param, $q) {
        $data = array();
        if ($search_param == 'title') {
            $data = array(
                "from" => 0, "size" => 5000,
                "query" =>array(
                    "match" => array(
                        "title" => array(
                            "query" => $q,
                            "operator" => "and"
                            )
                        ),
                    )
                    ,
                    "highlight" => array(
                        "fields" => array(
                            "title" => [
                                    "fragment_size" => 150, 
                                    "number_of_fragments" => 1,
                                    "no_match_size"=> 0,
                                    "pre_tags" => ["<b>"],
                                    "post_tags" => ["</b>"],
                                ]
                        )
                    )
            );
        }

        if ($search_param == 'content') {
            $data = array(
                "from" => 0, "size" => 5000,
                "query" =>array(
                    "match" => array(
                        "content" => array(
                            "query" => $q,
                            "operator" => "and"
                            )
                        ),
                    )
                ,
                "highlight" => array(
                    "fields" => array(
                        "content" => [
                                "fragment_size" => 150, 
                                "number_of_fragments" => 1,
                                "no_match_size"=> 0,
                                "pre_tags" => ["<b>"],
                                "post_tags" => ["</b>"],
                            ]
                    )
                )
            );
        }
        
        if ($search_param == 'all') {
            $data = array(
                "from" => 0, "size" => 5000,
                "query" => array(
                    "bool" => array(
                        "should" => [
                            array("match" => array(
                                "title" => array(
                                    "query" => $q,
                                    "operator" => "and"
                                    )
                                ) 
                            ),
                            array("match" => array(
                                "content" => array(
                                    "query" => $q,
                                    "operator" => "and"
                                    )
                                ) 
                            )
                            ]
                        )
                    )
                    ,
                    "highlight" => array(
                        "fields" => array(
                            "content" => [
                                    "fragment_size" => 150, 
                                    "number_of_fragments" => 1,
                                    "no_match_size"=> 0,
                                    "pre_tags" => ["<b>"],
                                    "post_tags" => ["</b>"],
                                ]
                        )
                    )
                );
        }
        return $data;
    }


    public function search($search_param, $q, $search_bool) {
        $data = array();
        if ($search_bool == 1) {
            $data = SearchController::searchFullText($search_param, $q);
        } elseif ($search_bool == 2) {
            $data = SearchController::searchOneWord($search_param,$q);
        } else {
            $data = SearchController::searchAllWord($search_param, $q);
        }      

        $results = array();
        $url = 'node_1:9200/btl/_search?pretty';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, false); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $return = curl_exec($ch) or die(curl_error($ch));
        curl_close($ch);
        // dd($return);
        //return is json_encoded, you can decode it to have an array
        $array_return = json_decode($return,true);

        // $i = 0;
        // foreach($array_return['hits']['hits'] as $person) 
        // {
        //     $results[$i++] = $person['_source']; 
        // }
        // dd($array_return['hits']['hits'][0]['highlight']);
        // dd($array_return['hits']['hits']);
        // dd($results);
        return $array_return['hits']['hits'];
        // return $results;
    }
}
