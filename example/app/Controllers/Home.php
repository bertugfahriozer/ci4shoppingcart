<?php namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class Home extends BaseController
{
    use ResponseTrait;

    public function productList()
    {
        $this->defData['products'] =
            [
            ['id' => 1,
                'productTitle' => 'NortPas Şınav Tahtası',
                'productCategory' => 'Spor Malzemeleri',
                'price'=>24,
                'stock' => 34],
            ['id' => 2,
                'productTitle' => 'HP Pavilion G135',
                'productCategory' => 'Laptop',
                'price'=>47,
                'stock' => 3],
            ['id' => 3,
                'productTitle' => 'Logitech Sessiz Fare',
                'productCategory' => 'Mouse',
                'price'=>8,
                'stock' => 90]
        ];
        return view('products/productList', $this->defData);
    }
}