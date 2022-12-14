<section data-toggle="wy-nav-shift" class="wy-nav-content-wrap">

<div class="wy-nav-content">

<div class="rst-content">

<div role="main" class="document">

<div class="section" id="shopping-cart-class">

# Shopping Cart Class

The Cart Class permits items to be added to a session that stays active while a user is browsing your site. These items
can be retrieved and displayed in a standard “shopping cart” format, allowing the user to update the quantity or remove
items from the cart.

<div class="admonition important">

##### Important

The Cart library is DEPRECATED and should not be used. It is currently only kept for backwards compatibility.

</div>

Please note that the Cart Class ONLY provides the core “cart” functionality. It does not provide shipping, credit card
authorization, or other processing components.

<div class="contents local topic" id="contents">

* [Using the Cart Class](#using-the-cart-class)
    * [Initializing the Shopping Cart Class](#initializing-the-shopping-cart-class)
    * [Adding an Item to The Cart](#adding-an-item-to-the-cart)
    * [Adding Multiple Items to The Cart](#adding-multiple-items-to-the-cart)
    * [Displaying the Cart](#displaying-the-cart)
    * [Updating The Cart](#updating-the-cart)
        * [What is a Row ID?](#what-is-a-row-id)
* [Class Reference](#class-reference)

</div>

<div class="section" id="using-the-cart-class">

## [Using the Cart Class](#using-the-cart-class)

<div class="section" id="initializing-the-shopping-cart-class">

### [Initializing the Shopping Cart Class](#initializing-the-shopping-cart-class)

<div>

#### Important

The Cart class utilizes CodeIgniter’s Session Class to save the cart information to a database, so before using the Cart
class you must set up a database table as indicated in the Session Documentation, and set the session preferences in
your .env file to utilize a database.

</div>

---

#### Composer installation

```composer require bertugfahriozer/ci4shoppingcart```

When you install composer, you will see example directory. You can copy and paste your project or how do you want.

---

To initialize the Shopping Cart Class in your controller constructor, use
the `$cart=new Cart()` class:

<div class="highlight-ci">

<div class="highlight">

```PHP
use ci4shoppingcart\Libraries\Cart;

public $cart

public function __contruct(){
  $this->cart=new Cart();
}
```

</div>

</div>

Once loaded, the Cart object will be available using:

<div class="highlight-ci">

<div class="highlight">

```PHP
$this->cart
```

</div>

</div>

<div class="admonition note">

Note

The Cart Class will load and initialize the Session Class automatically, so unless you are using sessions elsewhere in
your application, you do not need to load the Session class.

</div>

</div>

<div class="section" id="adding-an-item-to-the-cart">

### [Adding an Item to The Cart](#adding-an-item-to-the-cart)

To add an item to the shopping cart, simply pass an array with the product information to
the `$this->cart->insert()` method, as shown below:

<div class="highlight-ci">

<div class="highlight">

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

</div>

</div>

<div class="admonition important">

Important

The first four array indexes above (id, qty, price, and name) are **required**. If you omit any of them the data will
not be saved to the cart. The fifth index (options) is optional. It is intended to be used in cases where your product
has options associated with it. Use an array for options, as shown above.

</div>

The five reserved indexes are:

* **id** - Each product in your store must have a unique identifier. Typically this will be an “sku” or other such
  identifier.
* **qty** - The quantity being purchased.
* **price** - The price of the item.
* **name** - The name of the item.
* **options** - Any additional attributes that are needed to identify the product. These must be passed via an array.

In addition to the five indexes above, there are two reserved words: rowid and subtotal. These are used internally by
the Cart class, so please do NOT use those words as index names when inserting data into the cart.

Your array may contain additional data. Anything you include in your array will be stored in the session. However, it is
best to standardize your data among all your products in order to make displaying the information in a table easier.

<div class="highlight-ci">

<div class="highlight">

```PHP
$data = array(
        'id'      => 'sku_123ABC',
        'qty'     => 1,
        'price'   => 39.95,
        'name'    => 'T-Shirt',
        'coupon'         => 'XMAS-50OFF'
);

$this->cart->insert($data);
```

</div>

</div>

The `insert()` method will return the $rowid if you successfully insert a single item.

</div>

<div class="section" id="adding-multiple-items-to-the-cart">

### [Adding Multiple Items to The Cart](#adding-multiple-items-to-the-cart)

By using a multi-dimensional array, as shown below, it is possible to add multiple products to the cart in one action.
This is useful in cases where you wish to allow people to select from among several items on the same page.

<div class="highlight-ci">

<div class="highlight">

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

</div>

</div>

</div>

<div class="section" id="displaying-the-cart">

### [Displaying the Cart](#displaying-the-cart)

To display the cart you will create a view file with code similar to
the one shown below.

<div class="highlight-ci">

<div class="highlight">


```PHP
<?php
/*add your controller */
  public function yourMethod(){
    return view('path/view',['cart'=>$this->cart]);
  }
```

```PHP
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
                <td><input type="number" value="<?php echo $items['qty'] ?>"></td>
                <td>
                        <?php echo $items['name']; ?>
                        <?php if ($cart->has_options($items['rowid']) == TRUE): ?>
                                <p>
                                        <?php foreach ($cart->product_options($items['rowid']) as $option_name => $option_value): ?>
                                                <strong><?php echo $option_name; ?>:</strong> <?php echo $option_value; ?><br />
                                        <?php endforeach; ?>
                                </p>
                        <?php endif; ?>
                </td>
                <td style="text-align:right">₺ <?php echo $cart->format_number($items['price']); ?></td>
                <td style="text-align:right">₺ <?php echo $cart->format_number($items['subtotal']); ?></td>
        </tr>
<?php endforeach; ?>

<tr>
        <td colspan="2"></td>
        <td class="right"><strong>Total</strong></td>
        <td class="right">₺ <?php echo $cart->format_number($cart->total()); ?></td>
</tr>

</table>
<button type="submit">Save Cart</button>
</form>
```

</div>

</div>

</div>

<div class="section" id="updating-the-cart">

### [Updating The Cart](#updating-the-cart)

To update the information in your cart, you must pass an array containing the Row ID and one or more pre-defined
properties to the `$this->cart->update()` method.

<div class="admonition note">

Note

If the quantity is set to zero, the item will be removed from the cart.

</div>

<div class="highlight-ci">

<div class="highlight">

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

</div>

</div>

You may also update any property you have previously defined when inserting the item such as options, price or other
custom fields.

<div class="highlight-ci">

<div class="highlight">

```PHP
$data = array(
        'rowid'  => 'b99ccdf16028f015540f341130b6d8ec',
        'qty'    => 1,
        'price'  => 49.95,
        'coupon' => NULL
);

$this->cart->update($data);
```

</div>

</div>

<div class="section" id="what-is-a-row-id">

#### [What is a Row ID?](#what-is-a-row-id)

The row ID is a unique identifier that is generated by the cart code when an item is added to the cart. The reason a
unique ID is created is so that identical products with different options can be managed by the cart.

For example, let’s say someone buys two identical t-shirts (same product ID), but in different sizes. The product ID (
and other attributes) will be identical for both sizes because it’s the same shirt. The only difference will be the
size. The cart must therefore have a means of identifying this difference so that the two sizes of shirts can be managed
independently. It does so by creating a unique “row ID” based on the product ID and any options associated with it.

In nearly all cases, updating the cart will be something the user does via the “view cart” page, so as a developer, it
is unlikely that you will ever have to concern yourself with the “row ID”, other than making sure your “view cart” page
contains this information in a hidden form field, and making sure it gets passed to
the `update()` method when the update form is submitted. Please examine the construction of the
“view cart” page above for more information.

</div>

</div>

</div>

<div class="section" id="class-reference">

## [Class Reference](#class-reference)

<dl class="class">

<dt id="Cart">class `Cart`</dt>

<dd>

<dl class="attribute">

<dt>`$product_id_rules = '.a-z0-9_-'`</dt>

<dd>

These are the regular expression rules that we use to validate the product ID - alpha-numeric, dashes, underscores, or
periods by default

</dd>

</dl>

<dl class="attribute">

<dt>`$product_name_rules = 'w -.:'`</dt>

<dd>

These are the regular expression rules that we use to validate the product ID and product name - alpha-numeric, dashes,
underscores, colons or periods by default

</dd>

</dl>

<dl class="attribute">

<dt>`$product_name_safe = TRUE`</dt>

<dd>

Whether or not to only allow safe product names. Default TRUE.

</dd>

</dl>

<dl class="method">

<dt id="Cart::insert">`insert`([$items = array()])</dt>

<dd>

<table class="docutils field-list" frame="void" rules="none"><colgroup><col class="field-name"> <col class="field-body"></colgroup> 

<tbody valign="top">

<tr class="field-odd field">

<th class="field-name">Parameters:</th>

<td class="field-body">

* **$items** (_array_) – Items to insert into the cart

</td>

</tr>

<tr class="field-even field">

<th class="field-name">Returns:</th>

<td class="field-body">

TRUE on success, FALSE on failure

</td>

</tr>

<tr class="field-odd field">

<th class="field-name">Return type:</th>

<td class="field-body">

bool

</td>

</tr>

</tbody>

</table>

Insert items into the cart and save it to the session table. Returns TRUE on success and FALSE on failure.

</dd>

</dl>

<dl class="method">

<dt id="Cart::update">`update`([$items = array()])</dt>

<dd>

<table class="docutils field-list" frame="void" rules="none"><colgroup><col class="field-name"> <col class="field-body"></colgroup> 

<tbody valign="top">

<tr class="field-odd field">

<th class="field-name">Parameters:</th>

<td class="field-body">

* **$items** (_array_) – Items to update in the cart

</td>

</tr>

<tr class="field-even field">

<th class="field-name">Returns:</th>

<td class="field-body">

TRUE on success, FALSE on failure

</td>

</tr>

<tr class="field-odd field">

<th class="field-name">Return type:</th>

<td class="field-body">

bool

</td>

</tr>

</tbody>

</table>

This method permits changing the properties of a given item. Typically it is called from the “view cart” page if a user
makes changes to the quantity before checkout. That array must contain the rowid for each item.

</dd>

</dl>

<dl class="method">

<dt id="Cart::remove">`remove`($rowid)</dt>

<dd>

<table class="docutils field-list" frame="void" rules="none"><colgroup><col class="field-name"> <col class="field-body"></colgroup> 

<tbody valign="top">

<tr class="field-odd field">

<th class="field-name">Parameters:</th>

<td class="field-body">

* **$rowid** (_int_) – ID of the item to remove from the cart

</td>

</tr>

<tr class="field-even field">

<th class="field-name">Returns:</th>

<td class="field-body">

TRUE on success, FALSE on failure

</td>

</tr>

<tr class="field-odd field">

<th class="field-name">Return type:</th>

<td class="field-body">

bool

</td>

</tr>

</tbody>

</table>

Allows you to remove an item from the shopping cart by passing it the `$rowid`.

</dd>

</dl>

<dl class="method">

<dt id="Cart::total">`total`()</dt>

<dd>

<table class="docutils field-list" frame="void" rules="none"><colgroup><col class="field-name"> <col class="field-body"></colgroup> 

<tbody valign="top">

<tr class="field-odd field">

<th class="field-name">Returns:</th>

<td class="field-body">Total amount</td>

</tr>

<tr class="field-even field">

<th class="field-name">Return type:</th>

<td class="field-body">int</td>

</tr>

</tbody>

</table>

Displays the total amount in the cart.

</dd>

</dl>

<dl class="method">

<dt id="Cart::total_items">`total_items`()</dt>

<dd>

<table class="docutils field-list" frame="void" rules="none"><colgroup><col class="field-name"> <col class="field-body"></colgroup> 

<tbody valign="top">

<tr class="field-odd field">

<th class="field-name">Returns:</th>

<td class="field-body">Total amount of items in the cart</td>

</tr>

<tr class="field-even field">

<th class="field-name">Return type:</th>

<td class="field-body">int</td>

</tr>

</tbody>

</table>

Displays the total number of items in the cart.

</dd>

</dl>

<dl class="method">

<dt id="Cart::contents">`contents`([$newest_first = FALSE])</dt>

<dd>

<table class="docutils field-list" frame="void" rules="none"><colgroup><col class="field-name"> <col class="field-body"></colgroup> 

<tbody valign="top">

<tr class="field-odd field">

<th class="field-name">Parameters:</th>

<td class="field-body">

* **$newest_first** (_bool_) – Whether to order the array with newest items first

</td>

</tr>

<tr class="field-even field">

<th class="field-name">Returns:</th>

<td class="field-body">

An array of cart contents

</td>

</tr>

<tr class="field-odd field">

<th class="field-name">Return type:</th>

<td class="field-body">

array

</td>

</tr>

</tbody>

</table>

Returns an array containing everything in the cart. You can sort the order by which the array is returned by passing it
TRUE where the contents will be sorted from newest to oldest, otherwise it is sorted from oldest to newest.

</dd>

</dl>

<dl class="method">

<dt id="Cart::get_item">`get_item`($row_id)</dt>

<dd>

<table class="docutils field-list" frame="void" rules="none"><colgroup><col class="field-name"> <col class="field-body"></colgroup> 

<tbody valign="top">

<tr class="field-odd field">

<th class="field-name">Parameters:</th>

<td class="field-body">

* **$row_id** (_int_) – Row ID to retrieve

</td>

</tr>

<tr class="field-even field">

<th class="field-name">Returns:</th>

<td class="field-body">

Array of item data

</td>

</tr>

<tr class="field-odd field">

<th class="field-name">Return type:</th>

<td class="field-body">

array

</td>

</tr>

</tbody>

</table>

Returns an array containing data for the item matching the specified row ID, or FALSE if no such item exists.

</dd>

</dl>

<dl class="method">

<dt id="Cart::has_options">`has_options`($row_id = '')</dt>

<dd>

<table class="docutils field-list" frame="void" rules="none"><colgroup><col class="field-name"> <col class="field-body"></colgroup> 

<tbody valign="top">

<tr class="field-odd field">

<th class="field-name">Parameters:</th>

<td class="field-body">

* **$row_id** (_int_) – Row ID to inspect

</td>

</tr>

<tr class="field-even field">

<th class="field-name">Returns:</th>

<td class="field-body">

TRUE if options exist, FALSE otherwise

</td>

</tr>

<tr class="field-odd field">

<th class="field-name">Return type:</th>

<td class="field-body">

bool

</td>

</tr>

</tbody>

</table>

Returns TRUE (boolean) if a particular row in the cart contains options. This method is designed to be used in a loop
with `contents()`, since you must pass the rowid to this method, as shown in the Displaying the
Cart example above.

</dd>

</dl>

<dl class="method">

<dt id="Cart::product_options">`product_options`([$row_id = ''])</dt>

<dd>

<table class="docutils field-list" frame="void" rules="none"><colgroup><col class="field-name"> <col class="field-body"></colgroup> 

<tbody valign="top">

<tr class="field-odd field">

<th class="field-name">Parameters:</th>

<td class="field-body">

* **$row_id** (_int_) – Row ID

</td>

</tr>

<tr class="field-even field">

<th class="field-name">Returns:</th>

<td class="field-body">

Array of product options

</td>

</tr>

<tr class="field-odd field">

<th class="field-name">Return type:</th>

<td class="field-body">

array

</td>

</tr>

</tbody>

</table>

Returns an array of options for a particular product. This method is designed to be used in a loop
with `contents()`, since you must pass the rowid to this method, as shown in the Displaying the
Cart example above.

</dd>

</dl>

<dl class="method">

<dt id="Cart::destroy">`destroy`()</dt>

<dd>

<table class="docutils field-list" frame="void" rules="none"><colgroup><col class="field-name"> <col class="field-body"></colgroup> 

<tbody valign="top">

<tr class="field-odd field">

<th class="field-name">Return type:</th>

<td class="field-body">void</td>

</tr>

</tbody>

</table>

Permits you to destroy the cart. This method will likely be called when you are finished processing the customer’s
order.

</dd>

</dl>

</dd>

</dl>

</div>

</div>

</div>

</div>

</div>

</section>