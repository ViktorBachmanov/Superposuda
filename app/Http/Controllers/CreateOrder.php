<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

use Illuminate\Http\Client\Response;


class CreateOrder extends Controller
{
    public function index(Request $request)
    {
        $response = $this->getProducts($request->article, $request->brand);
        $resArr = $response->json();
        $productId = $resArr['products'][0]['offers'][0]['id'];
        
        $order = $this->createOrder($productId);

        /*return view('show_order', ['responseBody' => $resArr,
        							'id' => $productId]);*/
        return view('create_order', ['request' => $request,
        								'productId' => $productId,
        								'order' => $order->json()]);
    }


    private function getProducts($article, $brand)
    {
        $url = "https://superposuda.retailcrm.ru/api/v5/store/products?filter[name]=${article}&filter[manufacturer]=${brand}&apiKey=QlnRWTTWw9lv3kjxy1A8byjUmBQedYqb";
        $response = Http::get($url);

        return $response;
    }
    
    
    private function createOrder($productId)
    {
    	$response = Http::asForm()->post('https://superposuda.retailcrm.ru/api/v5/orders/create', [
    		'apiKey' => 'QlnRWTTWw9lv3kjxy1A8byjUmBQedYqb',
    		'order[items][0][status]' => 'trouble',
    		'order[orderType]' => 'fizik',
    		'order[site]' => 'test',
    		'order[orderMethod]' => 'test',
    		'order[number]' => '05071976',
    		'order[items][0][offer][id]' => $productId
		]);
		
		return $response;
    }
}
