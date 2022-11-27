<?= $this->extend('base') ?>
<?= $this->section('head') ?>
<meta name="description" content="Free Web tutorials">
<meta name="keywords" content="HTML, CSS, JavaScript">
<meta name="author" content="John Doe">
<title>Ürün Listesi</title>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<table class="table table-sm-responsive table-bordered table-striped">
    <thead>
    <td>Ürün Adı</td>
    <td>Ürün Kategori</td>
    <td>Ürün Adet</td>
    <td>Fiyatı</td>
    </thead>
    <tbody>
    <?php
    foreach ($products as $product): ?>
        <!--
        ürün stoğu 10 dan azsa yeşil
        ürün stoğu 5 ve 5 ten azsa turuncu
        ürün stoğu 1 ise kırmızı
        -->
        <tr<?php

        /* if ($product['stock'] < 10 && $product['stock']>5)
             echo ' class="bg-success text-light font-weight-bold"';

         elseif ($product['stock'] <= 5 && $product['stock']>1)
             echo ' class="bg-warning text-light font-weight-bold"';

         elseif ($product['stock'] == 1)
             echo ' class="bg-danger text-light font-weight-bold"';

         else
             echo '';*/

        if (!empty($product['stock'])) {
            switch ($product['stock']) {
                case ($product['stock'] < 10 && $product['stock'] > 5):
                    echo ' class="bg-success text-light font-weight-bold"';
                    break;
                case ($product['stock'] <= 5 && $product['stock'] > 1):
                    echo ' class="bg-warning text-light font-weight-bold"';
                    break;
                case ($product['stock'] == 1):
                    echo ' class="bg-danger text-light font-weight-bold"';
                    break;
                default:
                    echo '';
                    break;
            }
        }
        ?>>
            <td><?= $product['productTitle'] ?></td>
            <td><?= $product['productCategory'] ?></td>
            <td><?= $product['stock'] ?></td>
            <td><?= $cart->format_number($product['price']) ?> ₺</td>
            <td>
                <div class="input-group">
                    <input type="number" class="form-control" id="qty<?= $product['id'] ?>" aria-label="piece"
                           name="piece" aria-describedby="basic-addon1">
                    <button class="btn btn-success addCart"
                            data-id="<?= $product['id'] ?>"
                            data-title="<?= $product['productTitle'] ?>"
                            data-price="<?= $product['price'] ?>"
                    ><i class="bi bi-cart-plus"></i> Sepete Ekle
                    </button>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>