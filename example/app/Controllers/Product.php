<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use CodeIgniter\API\ResponseTrait;

class Product extends BaseController
{
    use ResponseTrait;

    public function basket()
    {
        return view('products/basket', $this->defData);
    }

    public function addBasket()
    {
        if ($this->request->isAJAX()) {
            $basket = ['id' => $this->request->getPost('id'),
                'qty' => $this->request->getPost('qty'),
                'price' => $this->request->getPost('price'),
                'name' => $this->request->getPost('title')];
            if ($this->defData['cart']->insert($basket))
                return $this->showCart();
            else
                return 'ürün sepete eklenemedi.';
        } else return show_404();
    }

    public function updateBasket()
    {
        if ($this->request->isAJAX()) {
            $basket = ['rowid' => $this->request->getPost('rowid'),
                'qty' => $this->request->getPost('qty')];
            if ($this->defData['cart']->update($basket))
                return $this->showCart();
        } else return show_404();
    }

    public function removeBasketOne()
    {
        if ($this->request->isAJAX()) {
            if ($this->defData['cart']->remove($this->request->getPost('rowid')))
                return $this->showCart();
        } else return show_404();
    }

    public function emptyTheBasket()
    {
        if ($this->request->isAJAX()) {
            return $this->defData['cart']->destroy();
        } else return show_404();
    }

    private function showCart()
    {
        $carts = $this->defData['cart']->contents();
        $array = [];
        foreach ($carts as $cart) {
            $array['carts'][$cart['rowid']] = [
                'id' => $cart['id'],
                'qty' => $cart['qty'],
                'price' => $this->defData['cart']->format_number($cart['price']),
                'name' => $cart['name'],
                'subtotal' => $this->defData['cart']->format_number($cart['subtotal']),
                'rowid' => $cart['rowid']
            ];
        }
        $array['count'] = count($array['carts']);

        return $this->respondCreated($array);
    }


    public function successfullBasket()
    {
            $commonModel = new CommonModel();
            $pnr=$this->generateActivateHash(4);
            foreach ($this->defData['cart']->contents() as $item) {
                $data = ['productPnr' => $pnr,
                    'productTitle'=>$item['name'],
                    'price'=>$item['price'],
                    'qty'=>$item['qty'],
                    'subtotal'=>$item['subtotal']];
                $commonModel->create('products', $data);
            }
            $this->defData['cart']->destroy();
            return view('products/success', $this->defData);
    }

    public function generateActivateHash($bit = 16)
    {
        return bin2hex(random_bytes($bit));
    }
}
