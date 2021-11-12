<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Http\Client\Response;


class CreateOrder extends Controller
{
    public function index(Request $request)
    {
        $error = null;

        $response = $this->getProducts($request->article, $request->brand);
        $result = json_decode($response, true);
        if(!$result['success'] || !count($result['products'])) {
            $error = 'Ошибка при поиске в каталоге';

            return view('create_order', ['request' => $request,
                                        'error' => $error]);
        }

        $productId = $result['products'][0]['offers'][0]['id'];
        
        $response = $this->createOrder($request, $productId);
        $order = json_decode($response, true);
        if(!$order['success']) {
            $error = 'Ошибка при создании заказа';

            return view('create_order', ['request' => $request,
                                        'error' => $error]);
        }
        

        return view('create_order', ['request' => $request,
        								'order' => $order,
                                        'error' => $error]);
    }


    private function getProducts($article, $brand)
    {
        $url = "https://superposuda.retailcrm.ru/api/v5/store/products?filter[name]=${article}&filter[manufacturer]=${brand}&apiKey=QlnRWTTWw9lv3kjxy1A8byjUmBQedYqb";
        $response = file_get_contents($url);

        return $response;
    }
    
    
    private function buildQuery($request, $productId)
    {
        $fioArr = preg_split("/\s+/", $request->fio);
    	
    	return $postData = http_build_query(array(
            'site' => 'test',
            'order' => json_encode(array(
                'status' => 'trouble',
                'orderType' => 'fizik',
                'orderMethod' => 'test',
                'number' => '07051976',
                'lastName' => $fioArr[0],
                'firstName' => $fioArr[1] ?? '',
                'patronymic' => $fioArr[2] ?? '',
                'customerComment' => $request->comment,
                'items' => array(
                  array(
                  'productName' => 'Наименование товара',
                  'offer'=> array(
                        'id' => $productId,
                    )
                  )
                )
            )),
            'apiKey' => 'QlnRWTTWw9lv3kjxy1A8byjUmBQedYqb',
        ));
    }
    
    
    private function createOrder($request, $productId)
    {
    	$opts = array('http' =>
    		array(
        		'method'  => 'POST',
        		'header'  => 'Content-type: application/x-www-form-urlencoded',
        		'content' => $this->buildQuery($request, $productId)
    		)
		);
		
		$context = stream_context_create($opts);
		
		$response = file_get_contents('https://superposuda.retailcrm.ru/api/v5/orders/create', false, $context);
		
		return $response;
    }
}
