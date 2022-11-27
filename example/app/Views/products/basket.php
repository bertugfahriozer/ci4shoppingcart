<?= $this->extend('base') ?>
<?= $this->section('content') ?>
<?php if (!empty($cart->contents())) { ?>
    <form action="<?php echo route_to('successfullBasket') ?>" method="post">
        <?= csrf_field() ?>
        <table cellpadding="6" cellspacing="1" style="width:100%" border="0" class="table table-striped table-bordered">
            <tr>
                <th>QTY</th>
                <th>Item Description</th>
                <th style="text-align:right">Item Price</th>
                <th style="text-align:right">Sub-Total</th>
                <th>#</th>
            </tr>
            <?php foreach ($cart->contents() as $items): ?>
                <tr>
                    <td><input type="number" value="<?php echo $items['qty'] ?>" class="form-control updateCart"
                               data-rowid="<?= $items['rowid'] ?>"></td>
                    <td>
                        <?php echo $items['name']; ?>
                        <?php if ($cart->has_options($items['rowid']) == TRUE): ?>
                            <p>
                                <?php foreach ($cart->product_options($items['rowid']) as $option_name => $option_value): ?>
                                    <strong><?php echo $option_name; ?>:</strong> <?php echo $option_value; ?><br/>
                                <?php endforeach; ?>
                            </p>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:right"><?php echo $cart->format_number($items['price']); ?></td>
                    <td style="text-align:right">₺ <?php echo $cart->format_number($items['subtotal']); ?></td>
                    <td>
                        <button class="btn btn-danger removeCart" type="button" data-rowid="<?= $items['rowid'] ?>"><i
                                    class="bi bi-trash2"></i></button>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3"></td>
                <td class="right"><strong>Toplam</strong></td>
                <td class="right">₺ <?php echo $cart->format_number($cart->total()); ?></td>
            </tr>
        </table>
        <button type="submit" class="btn btn-info">Satın Al</button>
    </form>
    <div class="w-100 text-right">
        <button class="btn btn-warning" id="deleteCart">Sepeti Boşalt</button>
    </div>
<?php } else { ?>
    <h1>
        Sepetiniz Boş
    </h1>
<?php } ?>
<?= $this->endSection() ?>
