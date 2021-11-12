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
        
        $order = $this->createOrder($productId, $request->fio);
        

        /*return view('show_order', ['responseBody' => $resArr,
        							'id' => $productId]);*/
        return view('create_order', ['request' => $request,
        								'productId' => $productId,
        								'order' => $order]);
    }


    private function getProducts($article, $brand)
    {
        $url = "https://superposuda.retailcrm.ru/api/v5/store/products?filter[name]=${article}&filter[manufacturer]=${brand}&apiKey=QlnRWTTWw9lv3kjxy1A8byjUmBQedYqb";
        $response = Http::get($url);

        return $response;
    }
    
    
    private function buildQuery($productId)
    {
    	return http_build_query([
    		'apiKey' => 'QlnRWTTWw9lv3kjxy1A8byjUmBQedYqb',
    		'order[items][0][status]' => 'notrouble',
    		'order[orderType]' => 'fizik1',
    		'order[site]' => 'test1',
    		'order[orderMethod]' => 'test1',
    		'order[number]' => '5071976',
    		'order[lastName]' => 'Иванов',
    		'order[firstName]' => 'Иван',
    		'order[patronymic]' => 'Иванович',
    		'order[customerComment]' => 'Супер',
    		'order[items][0][offer][id]' => $productId], '', null, PHP_QUERY_RFC3986 );
    }
    
    
    private function createOrder($productId, $fio)
    {
    	$fioArr = explode(' ', $fio);
    	$lastName = trim($fioArr[0]);
    	$firstName = trim($fioArr[1]);
    	$patronymic = trim($fioArr[2]);
    	/*
    	$response = Http::asForm()->post('https://superposuda.retailcrm.ru/api/v5/orders/create', [
    		'apiKey' => 'QlnRWTTWw9lv3kjxy1A8byjUmBQedYqb',
    		'order[items][0][status]' => 'notrouble',
    		'order[orderType]' => 'fizik1',
    		'order[site]' => 'test1',
    		'order[orderMethod]' => 'test1',
    		'order[number]' => '5071976',
    		'order[lastName]' => 'Иванов',
    		'order[firstName]' => 'Иван',
    		'order[patronymic]' => 'Иванович',
    		'order[customerComment]' => 'Супер',
    		'order[items][0][offer][id]' => $productId
		]);*/
		
		$opts = array('https' =>
    		array(
        		'method'  => 'POST',
        		'header'  => 'Content-type: application/x-www-form-urlencoded',
        		'content' => $this->buildQuery($productId)
    		)
		);
		
		$context = stream_context_create($opts);
		
		//$response = file_get_contents('https://superposuda.retailcrm.ru/api/v5/orders/create', false, $context);
		
		//return $response;
		return $opts;
    }
}
