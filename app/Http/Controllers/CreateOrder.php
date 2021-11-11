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
        // $productId = 1;

        //return view('show_order', ['fio' => $request->fio]);
        return view('show_order', ['responseBody' => $resArr,
        							'id' => $productId]);
    }


    private function getProducts($article, $brand)
    {
        $url = "https://superposuda.retailcrm.ru/api/v5/store/products?filter[name]=${article}&filter[manufacturer]=${brand}&apiKey=QlnRWTTWw9lv3kjxy1A8byjUmBQedYqb";
        //$url = "https://cnc-op.ru";
        $response = Http::get($url);

        return $response;
    }
}
