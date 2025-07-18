# Shopping Cart Class

The Cart Class permits items to be added to a session that stays active while a user is browsing your site. These items can be retrieved and displayed in a standard “shopping cart” format, allowing the user to update the quantity or remove items from the cart.

Please note that the Cart Class ONLY provides the core “cart” functionality. It does not provide shipping, credit card authorization, or other processing components.

- [Shopping Cart Class](#shopping-cart-class) - [Important](#important)
- [Using the Cart Class](#using-the-cart-class)
  - [Initializing the Shopping Cart Class](#initializing-the-shopping-cart-class)
    - [Important](#important-1)
    - [Composer installation](#composer-installation)
- [What is the Row ID](#what-is-a-row-id)
- [Class Reference](#class-reference)

## [Using the Cart Class](#using-the-cart-class)

### [Initializing the Shopping Cart Class](#initializing-the-shopping-cart-class)

#### Important

The Cart class utilizes CodeIgniter’s Session Class to save the cart information to a database, so before using the Cart class you must set up a database table as indicated in the Session Documentation, and set the session preferences in your .env file to utilize a database.

---

#### Composer installation

`composer require bertugfahriozer/ci4shoppingcart`

**When you install composer, you will see example directory. You can copy and paste your project or how do you want.**

To initialize the Shopping Cart Class in your controller constructor, use the `$cart=new Cart()` class:

```PHP
use ci4shoppingcartLibrariesCart;

public $cart

public function __contruct(){
  $this->cart=new Cart();
}
```

Once loaded, the Cart object will be available using:

```PHP
$this->cart
```

Note The Cart Class will load and initialize the Session Class automatically, so unless you are using sessions elsewhere in your application, you do not need to load the Session class.

### [Adding an Item to The Cart](#adding-an-item-to-the-cart)

To add an item to the shopping cart, simply pass an array with the product information to the `$this->cart->insert()` method, as shown below:

```PHP
$data = array(
        'id'      => 'sku_123ABC',
        'qty'     => 1,
        'price'   => 39.95,
        'name'    => 'T-Shirt',
        'options' => array('Size' => 'L', 'Color' => 'Red')
);

$this->cart->insert($data);
```

Important The first four array indexes above (id, qty, price, and name) are **required**. If you omit any of them the data will not be saved to the cart. The fifth index (options) is optional. It is intended to be used in cases where your product has options associated with it. Use an array for options, as shown above.

The five reserved indexes are:

- **id** - Each product in your store must have a unique identifier. Typically this will be an “sku” or other such identifier.
- **qty** - The quantity being purchased.
- **price** - The price of the item.
- **name** - The name of the item.
- **options** - Any additional attributes that are needed to identify the product. These must be passed via an array.

In addition to the five indexes above, there are two reserved words: rowid and subtotal. These are used internally by the Cart class, so please do NOT use those words as index names when inserting data into the cart.

Your array may contain additional data. Anything you include in your array will be stored in the session. However, it is best to standardize your data among all your products in order to make displaying the information in a table easier.

```php

$data = array(
        'id'      => 'sku_123ABC',
        'qty'     => 1,
        'price'   => 39.95,
        'name'    => 'T-Shirt',
        'coupon'         => 'XMAS-50OFF'
);

$this->cart->insert($data);

```

The `insert()` method will return the $rowid if you successfully insert a single item.

### [Adding Multiple Items to The Cart](#adding-multiple-items-to-the-cart)

By using a multi-dimensional array, as shown below, it is possible to add multiple products to the cart in one action. This is useful in cases where you wish to allow people to select from among several items on the same page.

```PHP
$data = array(
        array(
                'id'      => 'sku_123ABC',
                'qty'     => 1,
                'price'   => 39.95,
                'name'    => 'T-Shirt',
                'options' => array('Size' => 'L', 'Color' => 'Red')
        ),
        array(
                'id'      => 'sku_567ZYX',
                'qty'     => 1,
                'price'   => 9.95,
                'name'    => 'Coffee Mug'
        ),
        array(
                'id'      => 'sku_965QRS',
                'qty'     => 1,
                'price'   => 29.95,
                'name'    => 'Shot Glass'
        )
);

$this->cart->insert($data);
```

### [Displaying the Cart](#displaying-the-cart)

To display the cart you will create a view file with code similar to the one shown below.

```PHP
<?php
/*add your controller */
  public function yourMethod(){
    return view('path/view',['cart'=>$this->cart]);
  }
```

```html
/*view*/

<form action="<?php echo route_to('yourRoute')?>" method="post">
  <table cellpadding="6" cellspacing="1" style="width:100%" border="0">
    <tr>
      <th>QTY</th>
      <th>Item Description</th>
      <th style="text-align:right">Item Price</th>
      <th style="text-align:right">Sub-Total</th>
    </tr>

    <?php foreach ($cart->contents() as $items): ?>
    <tr>
      <td><input type="number" value="<?php echo $items['qty'] ?>" /></td>
      <td>
        <?php echo $items['name']; ?>
        <?php if ($cart->has_options($items['rowid']) == TRUE): ?>
        <p>
          <?php foreach ($cart->product_options($items['rowid']) as $option_name
          => $option_value): ?> <strong><?php echo $option_name; ?>:</strong>
          <?php echo $option_value; ?><br />
          <?php endforeach; ?>
        </p>
        <?php endif; ?>
      </td>
      <td style="text-align:right">
        ₺
        <?php echo $cart->format_number($items['price']); ?>
      </td>
      <td style="text-align:right">
        ₺
        <?php echo $cart->format_number($items['subtotal']); ?>
      </td>
    </tr>
    <?php endforeach; ?>

    <tr>
      <td colspan="2"></td>
      <td class="right"><strong>Total</strong></td>
      <td class="right">
        ₺
        <?php echo $cart->format_number($cart->total()); ?>
      </td>
    </tr>
  </table>
  <button type="submit">Save Cart</button>
</form>
```

### [Updating The Cart](#updating-the-cart)

To update the information in your cart, you must pass an array containing the Row ID and one or more pre-defined properties to the `$this->cart->update()` method.

Note If the quantity is set to zero, the item will be removed from the cart.

```PHP
$data = array(
        'rowid' => 'b99ccdf16028f015540f341130b6d8ec',
        'qty'   => 3
);

$this->cart->update($data);

// Or a multi-dimensional array

$data = array(
        array(
                'rowid'   => 'b99ccdf16028f015540f341130b6d8ec',
                'qty'     => 3
        ),
        array(
                'rowid'   => 'xw82g9q3r495893iajdh473990rikw23',
                'qty'     => 4
        ),
        array(
                'rowid'   => 'fh4kdkkkaoe30njgoe92rkdkkobec333',
                'qty'     => 2
        )
);

$this->cart->update($data);
```

You may also update any property you have previously defined when inserting the item such as options, price or other custom fields.

```PHP
$data = array(
        'rowid'  => 'b99ccdf16028f015540f341130b6d8ec',
        'qty'    => 1,
        'price'  => 49.95,
        'coupon' => NULL
);

$this->cart->update($data);
```

#### [What is a Row ID?](#what-is-a-row-id)

The row ID is a unique identifier that is generated by the cart code when an item is added to the cart. The reason a unique ID is created is so that identical products with different options can be managed by the cart.

For example, let’s say someone buys two identical t-shirts (same product ID), but in different sizes. The product ID ( and other attributes) will be identical for both sizes because it’s the same shirt. The only difference will be the size. The cart must therefore have a means of identifying this difference so that the two sizes of shirts can be managed independently. It does so by creating a unique “row ID” based on the product ID and any options associated with it.

In nearly all cases, updating the cart will be something the user does via the “view cart” page, so as a developer, it is unlikely that you will ever have to concern yourself with the “row ID”, other than making sure your “view cart” page contains this information in a hidden form field, and making sure it gets passed to the `update()` method when the update form is submitted. Please examine the construction of the “view cart” page above for more information.

## [Class Reference](#class-reference)

class `Cart`

`$product_id_rules = '.a-z0-9_-'`

These are the regular expression rules that we use to validate the product ID - alpha-numeric, dashes, underscores, or periods by default

`$product_name_rules = 'w -.:'`

These are the regular expression rules that we use to validate the product ID and product name - alpha-numeric, dashes, underscores, colons or periods by default

`$product_name_safe = TRUE`

Whether or not to only allow safe product names. Default TRUE.

`insert`([$items = array()])

|              |                                                    |
| ------------ | -------------------------------------------------- |
| Parameters:  | **$items** (array) – Items to insert into the cart |
| Returns:     | TRUE on success, FALSE on failure                  |
| Return type: | bool                                               |

Insert items into the cart and save it to the session table. Returns TRUE on success and FALSE on failure.

`update`([$items = array()])

|              |                                                    |
| ------------ | -------------------------------------------------- |
| Parameters:  | **$items** (_array_) – Items to update in the cart |
| Returns:     | TRUE on success, FALSE on failure                  |
| Return type: | bool                                               |

This method permits changing the properties of a given item. Typically it is called from the “view cart” page if a user makes changes to the quantity before checkout. That array must contain the rowid for each item.

`remove`($rowid)

|              |                                                           |
| ------------ | --------------------------------------------------------- |
| Parameters:  | **$rowid** (int) – ID of the item to remove from the cart |
| Returns:     | TRUE on success, FALSE on failure                         |
| Return type: | bool                                                      |

Allows you to remove an item from the shopping cart by passing it the `$rowid`.

`total`()

|              |              |
| ------------ | ------------ |
| Returns:     | Total amount |
| Return type: | int          |

Displays the total amount in the cart.

`total_items`()

|              |                                   |
| ------------ | --------------------------------- |
| Returns:     | Total amount of items in the cart |
| Return type: | int                               |

Displays the total number of items in the cart.

`contents`([$newest_first = FALSE])

|              |                                                                                    |
| ------------ | ---------------------------------------------------------------------------------- |
| Parameters:  | \* **$newest_first** (_bool_) – Whether to order the array with newest items first |
| Returns:     | An array of cart contents                                                          |
| Return type: | array                                                                              |

Returns an array containing everything in the cart. You can sort the order by which the array is returned by passing it TRUE where the contents will be sorted from newest to oldest, otherwise it is sorted from oldest to newest.

`get_item`($row_id)

|              |                                        |
| ------------ | -------------------------------------- |
| Parameters:  | **$row_id** (int) – Row ID to retrieve |
| Returns:     | Array of item data                     |
| Return type: | array                                  |

Returns an array containing data for the item matching the specified row ID, or FALSE if no such item exists.

`has_options`($row_id = '')

|              |                                        |
| ------------ | -------------------------------------- |
| Parameters:  | **$row_id** (int) – Row ID to inspect  |
| Returns:     | TRUE if options exist, FALSE otherwise |
| Return type: | bool                                   |

Returns TRUE (boolean) if a particular row in the cart contains options. This method is designed to be used in a loop with `contents()`, since you must pass the rowid to this method, as shown in the Displaying the Cart example above.

`product_options`([$row_id = ''])

|              |                            |
| ------------ | -------------------------- |
| Parameters:  | **$row_id** (int) – Row ID |
| Returns:     | Array of product options   |
| Return type: | array                      |

Returns an array of options for a particular product. This method is designed to be used in a loop with `contents()`, since you must pass the rowid to this method, as shown in the Displaying the Cart example above.

`destroy`()

|              |      |
| ------------ | ---- |
| Return type: | void |

Permits you to destroy the cart. This method will likely be called when you are finished processing the customer’s order.
