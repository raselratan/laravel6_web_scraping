<?php

namespace App\Http\Controllers\Scrapping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;

class ScrappingController extends Controller
{
    public function Scrap(Request $request){

        $MainArray = [];
        foreach (range('A', 'Z') as $char) {
            $url = 'http://www.squarepharma.com.bd/products-by-tradename.php?type=pharma&char='.$char;
            $client = new Client();


            // get request

            $response = $client->request(
                'GET',
                $url
            );

            $response_status_code = $response->getStatusCode();
            //$response->getHeaderLine('content-type');
            $response_body = $response->getBody();

            if($response_status_code == 200){
                $dom = HtmlDomParser::str_get_html($response_body);
                //$allItems = $dom->find('div[class="pthumb-section"]')->find('div[class="col-xs-12 col-sm-4 col-md-4 col-lg-4"]')->find('div[class="pthumb"]');
                $allItems = $dom->find('div[class="col-xs-6 col-sm-12 col-md-6 col-lg-6 sm-mid row-no-padding-left"]');

                foreach($allItems as $eachItem){
                    $name       = $eachItem->find('h3',0)->text();
                    $group_name = $eachItem->find('h4',0)->text();
                    $subArray = [
                        'name'          => $name,
                        'group_name'    => $group_name
                    ];

                    array_push($MainArray,$subArray);
                }
            }
        }
        dd($MainArray);
    }
}
