<section data-toggle="wy-nav-shift" class="wy-nav-content-wrap">

<div class="wy-nav-content">

<div class="rst-content">

<div role="main" class="document">

<div class="section" id="shopping-cart-class">

# Shopping Cart Class[¶](#shopping-cart-class "Permalink to this headline")

The Cart Class permits items to be added to a session that stays active while a user is browsing your site. These items
can be retrieved and displayed in a standard “shopping cart” format, allowing the user to update the quantity or remove
items from the cart.

<div class="admonition important">

Important

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

## [Using the Cart Class](#toc-entry-1)[¶](#using-the-cart-class "Permalink to this headline")

<div class="section" id="initializing-the-shopping-cart-class">

### [Initializing the Shopping Cart Class](#toc-entry-2)[¶](#initializing-the-shopping-cart-class "Permalink to this headline")

<div class="admonition important">

Important

The Cart class utilizes CodeIgniter’s Session Class to save the cart information to a database, so before using the Cart
class you must set up a database table as indicated in the Session Documentation, and set the session preferences in
your app/config/config.php file to utilize a database.

</div>

To initialize the Shopping Cart Class in your controller constructor, use
the `<span class="pre">$this->load->library()</span>` method:

<div class="highlight-ci">

<div class="highlight">

<pre style="position: relative;"><span></span><span class="nv">$this</span><span class="o">-></span><span class="na">load</span><span class="o">-></span><span class="na">library</span><span class="p">(</span><span class="s1">'cart'</span><span class="p">);</span>
</pre>

</div>

</div>

Once loaded, the Cart object will be available using:

<div class="highlight-ci">

<div class="highlight">

<pre style="position: relative;"><span></span><span class="nv">$this</span><span class="o">-></span><span class="na">cart</span>
</pre>

</div>

</div>

<div class="admonition note">

Note

The Cart Class will load and initialize the Session Class automatically, so unless you are using sessions elsewhere in
your application, you do not need to load the Session class.

</div>

</div>

<div class="section" id="adding-an-item-to-the-cart">

### [Adding an Item to The Cart](#toc-entry-3)[¶](#adding-an-item-to-the-cart "Permalink to this headline")

To add an item to the shopping cart, simply pass an array with the product information to
the `<span class="pre">$this->cart->insert()</span>` method, as shown below:

<div class="highlight-ci">

<div class="highlight">

<pre style="position: relative;"><span></span><span class="nv">$data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span>
        <span class="s1">'id'</span>      <span class="o">=></span> <span class="s1">'sku_123ABC'</span><span class="p">,</span>
        <span class="s1">'qty'</span>     <span class="o">=></span> <span class="mi">1</span><span class="p">,</span>
        <span class="s1">'price'</span>   <span class="o">=></span> <span class="mf">39.95</span><span class="p">,</span>
        <span class="s1">'name'</span>    <span class="o">=></span> <span class="s1">'T-Shirt'</span><span class="p">,</span>
        <span class="s1">'options'</span> <span class="o">=></span> <span class="k">array</span><span class="p">(</span><span class="s1">'Size'</span> <span class="o">=></span> <span class="s1">'L'</span><span class="p">,</span> <span class="s1">'Color'</span> <span class="o">=></span> <span class="s1">'Red'</span><span class="p">)</span>
<span class="p">);</span>

<span class="nv">$this</span><span class="o">-></span><span class="na">cart</span><span class="o">-></span><span class="na">insert</span><span class="p">(</span><span class="nv">$data</span><span class="p">);</span>
</pre>

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

<pre style="position: relative;"><span></span><span class="nv">$data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span>
        <span class="s1">'id'</span>      <span class="o">=></span> <span class="s1">'sku_123ABC'</span><span class="p">,</span>
        <span class="s1">'qty'</span>     <span class="o">=></span> <span class="mi">1</span><span class="p">,</span>
        <span class="s1">'price'</span>   <span class="o">=></span> <span class="mf">39.95</span><span class="p">,</span>
        <span class="s1">'name'</span>    <span class="o">=></span> <span class="s1">'T-Shirt'</span><span class="p">,</span>
        <span class="s1">'coupon'</span>         <span class="o">=></span> <span class="s1">'XMAS-50OFF'</span>
<span class="p">);</span>

<span class="nv">$this</span><span class="o">-></span><span class="na">cart</span><span class="o">-></span><span class="na">insert</span><span class="p">(</span><span class="nv">$data</span><span class="p">);</span>
</pre>

</div>

</div>

The `<span class="pre">insert()</span>` method will return the $rowid if you successfully insert a single item.

</div>

<div class="section" id="adding-multiple-items-to-the-cart">

### [Adding Multiple Items to The Cart](#toc-entry-4)[¶](#adding-multiple-items-to-the-cart "Permalink to this headline")

By using a multi-dimensional array, as shown below, it is possible to add multiple products to the cart in one action.
This is useful in cases where you wish to allow people to select from among several items on the same page.

<div class="highlight-ci">

<div class="highlight">

<pre style="position: relative;"><span></span><span class="nv">$data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span>
        <span class="k">array</span><span class="p">(</span>
                <span class="s1">'id'</span>      <span class="o">=></span> <span class="s1">'sku_123ABC'</span><span class="p">,</span>
                <span class="s1">'qty'</span>     <span class="o">=></span> <span class="mi">1</span><span class="p">,</span>
                <span class="s1">'price'</span>   <span class="o">=></span> <span class="mf">39.95</span><span class="p">,</span>
                <span class="s1">'name'</span>    <span class="o">=></span> <span class="s1">'T-Shirt'</span><span class="p">,</span>
                <span class="s1">'options'</span> <span class="o">=></span> <span class="k">array</span><span class="p">(</span><span class="s1">'Size'</span> <span class="o">=></span> <span class="s1">'L'</span><span class="p">,</span> <span class="s1">'Color'</span> <span class="o">=></span> <span class="s1">'Red'</span><span class="p">)</span>
        <span class="p">),</span>
        <span class="k">array</span><span class="p">(</span>
                <span class="s1">'id'</span>      <span class="o">=></span> <span class="s1">'sku_567ZYX'</span><span class="p">,</span>
                <span class="s1">'qty'</span>     <span class="o">=></span> <span class="mi">1</span><span class="p">,</span>
                <span class="s1">'price'</span>   <span class="o">=></span> <span class="mf">9.95</span><span class="p">,</span>
                <span class="s1">'name'</span>    <span class="o">=></span> <span class="s1">'Coffee Mug'</span>
        <span class="p">),</span>
        <span class="k">array</span><span class="p">(</span>
                <span class="s1">'id'</span>      <span class="o">=></span> <span class="s1">'sku_965QRS'</span><span class="p">,</span>
                <span class="s1">'qty'</span>     <span class="o">=></span> <span class="mi">1</span><span class="p">,</span>
                <span class="s1">'price'</span>   <span class="o">=></span> <span class="mf">29.95</span><span class="p">,</span>
                <span class="s1">'name'</span>    <span class="o">=></span> <span class="s1">'Shot Glass'</span>
        <span class="p">)</span>
<span class="p">);</span>

<span class="nv">$this</span><span class="o">-></span><span class="na">cart</span><span class="o">-></span><span class="na">insert</span><span class="p">(</span><span class="nv">$data</span><span class="p">);</span>
</pre>

</div>

</div>

</div>

<div class="section" id="displaying-the-cart">

### [Displaying the Cart](#toc-entry-5)[¶](#displaying-the-cart "Permalink to this headline")

To display the cart you will create a [<span class="doc">view file</span>](../general/views.html) with code similar to
the one shown below.

Please note that this example uses the [<span class="doc">form helper</span>](../helpers/form_helper.html).

<div class="highlight-ci">

<div class="highlight">

<pre style="position: relative;"><span></span><span class="o"><?</span><span class="nx">php</span> <span class="k">echo</span> <span class="nx">form_open</span><span class="p">(</span><span class="s1">'path/to/controller/update/method'</span><span class="p">);</span> <span class="cp">?></span>

<span class="p"><</span><span class="nt">table</span> <span class="na">cellpadding</span><span class="o">=</span><span class="s">"6"</span> <span class="na">cellspacing</span><span class="o">=</span><span class="s">"1"</span> <span class="na">style</span><span class="o">=</span><span class="s">"width:100%"</span> <span class="na">border</span><span class="o">=</span><span class="s">"0"</span><span class="p">></span>

<span class="p"><</span><span class="nt">tr</span><span class="p">></span>
        <span class="p"><</span><span class="nt">th</span><span class="p">></span>QTY<span class="p"></</span><span class="nt">th</span><span class="p">></span>
        <span class="p"><</span><span class="nt">th</span><span class="p">></span>Item Description<span class="p"></</span><span class="nt">th</span><span class="p">></span>
        <span class="p"><</span><span class="nt">th</span> <span class="na">style</span><span class="o">=</span><span class="s">"text-align:right"</span><span class="p">></span>Item Price<span class="p"></</span><span class="nt">th</span><span class="p">></span>
        <span class="p"><</span><span class="nt">th</span> <span class="na">style</span><span class="o">=</span><span class="s">"text-align:right"</span><span class="p">></span>Sub-Total<span class="p"></</span><span class="nt">th</span><span class="p">></span>
<span class="p"></</span><span class="nt">tr</span><span class="p">></span>

<span class="cp"><?php</span> <span class="nv">$i</span> <span class="o">=</span> <span class="mi">1</span><span class="p">;</span> <span class="cp">?></span>

<span class="cp"><?php</span> <span class="k">foreach</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-></span><span class="na">cart</span><span class="o">-></span><span class="na">contents</span><span class="p">()</span> <span class="k">as</span> <span class="nv">$items</span><span class="p">)</span><span class="o">:</span> <span class="cp">?></span>

        <span class="cp"><?php</span> <span class="k">echo</span> <span class="nx">form_hidden</span><span class="p">(</span><span class="nv">$i</span><span class="o">.</span><span class="s1">'[rowid]'</span><span class="p">,</span> <span class="nv">$items</span><span class="p">[</span><span class="s1">'rowid'</span><span class="p">]);</span> <span class="cp">?></span>

        <span class="p"><</span><span class="nt">tr</span><span class="p">></span>
                <span class="p"><</span><span class="nt">td</span><span class="p">></span><span class="cp"><?php</span> <span class="k">echo</span> <span class="nx">form_input</span><span class="p">(</span><span class="k">array</span><span class="p">(</span><span class="s1">'name'</span> <span class="o">=></span> <span class="nv">$i</span><span class="o">.</span><span class="s1">'[qty]'</span><span class="p">,</span> <span class="s1">'value'</span> <span class="o">=></span> <span class="nv">$items</span><span class="p">[</span><span class="s1">'qty'</span><span class="p">],</span> <span class="s1">'maxlength'</span> <span class="o">=></span> <span class="s1">'3'</span><span class="p">,</span> <span class="s1">'size'</span> <span class="o">=></span> <span class="s1">'5'</span><span class="p">));</span> <span class="cp">?></span><span class="p"></</span><span class="nt">td</span><span class="p">></span>
                <span class="p"><</span><span class="nt">td</span><span class="p">></span>
                        <span class="cp"><?php</span> <span class="k">echo</span> <span class="nv">$items</span><span class="p">[</span><span class="s1">'name'</span><span class="p">];</span> <span class="cp">?></span>

                        <span class="cp"><?php</span> <span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-></span><span class="na">cart</span><span class="o">-></span><span class="na">has_options</span><span class="p">(</span><span class="nv">$items</span><span class="p">[</span><span class="s1">'rowid'</span><span class="p">])</span> <span class="o">==</span> <span class="k">TRUE</span><span class="p">)</span><span class="o">:</span> <span class="cp">?></span>

                                <span class="p"><</span><span class="nt">p</span><span class="p">></span>
                                        <span class="cp"><?php</span> <span class="k">foreach</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-></span><span class="na">cart</span><span class="o">-></span><span class="na">product_options</span><span class="p">(</span><span class="nv">$items</span><span class="p">[</span><span class="s1">'rowid'</span><span class="p">])</span> <span class="k">as</span> <span class="nv">$option_name</span> <span class="o">=></span> <span class="nv">$option_value</span><span class="p">)</span><span class="o">:</span> <span class="cp">?></span>

                                                <span class="p"><</span><span class="nt">strong</span><span class="p">></span><span class="cp"><?php</span> <span class="k">echo</span> <span class="nv">$option_name</span><span class="p">;</span> <span class="cp">?></span>:<span class="p"></</span><span class="nt">strong</span><span class="p">></span> <span class="cp"><?php</span> <span class="k">echo</span> <span class="nv">$option_value</span><span class="p">;</span> <span class="cp">?></span><span class="p"><</span><span class="nt">br</span> <span class="p">/></span>

                                        <span class="cp"><?php</span> <span class="k">endforeach</span><span class="p">;</span> <span class="cp">?></span>
                                <span class="p"></</span><span class="nt">p</span><span class="p">></span>

                        <span class="cp"><?php</span> <span class="k">endif</span><span class="p">;</span> <span class="cp">?></span>

                <span class="p"></</span><span class="nt">td</span><span class="p">></span>
                <span class="p"><</span><span class="nt">td</span> <span class="na">style</span><span class="o">=</span><span class="s">"text-align:right"</span><span class="p">></span><span class="cp"><?php</span> <span class="k">echo</span> <span class="nv">$this</span><span class="o">-></span><span class="na">cart</span><span class="o">-></span><span class="na">format_number</span><span class="p">(</span><span class="nv">$items</span><span class="p">[</span><span class="s1">'price'</span><span class="p">]);</span> <span class="cp">?></span><span class="p"></</span><span class="nt">td</span><span class="p">></span>
                <span class="p"><</span><span class="nt">td</span> <span class="na">style</span><span class="o">=</span><span class="s">"text-align:right"</span><span class="p">></span>$<span class="cp"><?php</span> <span class="k">echo</span> <span class="nv">$this</span><span class="o">-></span><span class="na">cart</span><span class="o">-></span><span class="na">format_number</span><span class="p">(</span><span class="nv">$items</span><span class="p">[</span><span class="s1">'subtotal'</span><span class="p">]);</span> <span class="cp">?></span><span class="p"></</span><span class="nt">td</span><span class="p">></span>
        <span class="p"></</span><span class="nt">tr</span><span class="p">></span>

<span class="cp"><?php</span> <span class="nv">$i</span><span class="o">++</span><span class="p">;</span> <span class="cp">?></span>

<span class="cp"><?php</span> <span class="k">endforeach</span><span class="p">;</span> <span class="cp">?></span>

<span class="p"><</span><span class="nt">tr</span><span class="p">></span>
        <span class="p"><</span><span class="nt">td</span> <span class="na">colspan</span><span class="o">=</span><span class="s">"2"</span><span class="p">></span> <span class="p"></</span><span class="nt">td</span><span class="p">></span>
        <span class="p"><</span><span class="nt">td</span> <span class="na">class</span><span class="o">=</span><span class="s">"right"</span><span class="p">><</span><span class="nt">strong</span><span class="p">></span>Total<span class="p"></</span><span class="nt">strong</span><span class="p">></</span><span class="nt">td</span><span class="p">></span>
        <span class="p"><</span><span class="nt">td</span> <span class="na">class</span><span class="o">=</span><span class="s">"right"</span><span class="p">></span>$<span class="cp"><?php</span> <span class="k">echo</span> <span class="nv">$this</span><span class="o">-></span><span class="na">cart</span><span class="o">-></span><span class="na">format_number</span><span class="p">(</span><span class="nv">$this</span><span class="o">-></span><span class="na">cart</span><span class="o">-></span><span class="na">total</span><span class="p">());</span> <span class="cp">?></span><span class="p"></</span><span class="nt">td</span><span class="p">></span>
<span class="p"></</span><span class="nt">tr</span><span class="p">></span>

<span class="p"></</span><span class="nt">table</span><span class="p">></span>

<span class="p"><</span><span class="nt">p</span><span class="p">></span><span class="cp"><?php</span> <span class="k">echo</span> <span class="nx">form_submit</span><span class="p">(</span><span class="s1">''</span><span class="p">,</span> <span class="s1">'Update your Cart'</span><span class="p">);</span> <span class="cp">?></span><span class="p"></</span><span class="nt">p</span><span class="p">></span>
</pre>

</div>

</div>

</div>

<div class="section" id="updating-the-cart">

### [Updating The Cart](#toc-entry-6)[¶](#updating-the-cart "Permalink to this headline")

To update the information in your cart, you must pass an array containing the Row ID and one or more pre-defined
properties to the `<span class="pre">$this->cart->update()</span>` method.

<div class="admonition note">

Note

If the quantity is set to zero, the item will be removed from the cart.

</div>

<div class="highlight-ci">

<div class="highlight">

<pre style="position: relative;"><span></span><span class="nv">$data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span>
        <span class="s1">'rowid'</span> <span class="o">=></span> <span class="s1">'b99ccdf16028f015540f341130b6d8ec'</span><span class="p">,</span>
        <span class="s1">'qty'</span>   <span class="o">=></span> <span class="mi">3</span>
<span class="p">);</span>

<span class="nv">$this</span><span class="o">-></span><span class="na">cart</span><span class="o">-></span><span class="na">update</span><span class="p">(</span><span class="nv">$data</span><span class="p">);</span>

<span class="c1">// Or a multi-dimensional array</span>

<span class="nv">$data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span>
        <span class="k">array</span><span class="p">(</span>
                <span class="s1">'rowid'</span>   <span class="o">=></span> <span class="s1">'b99ccdf16028f015540f341130b6d8ec'</span><span class="p">,</span>
                <span class="s1">'qty'</span>     <span class="o">=></span> <span class="mi">3</span>
        <span class="p">),</span>
        <span class="k">array</span><span class="p">(</span>
                <span class="s1">'rowid'</span>   <span class="o">=></span> <span class="s1">'xw82g9q3r495893iajdh473990rikw23'</span><span class="p">,</span>
                <span class="s1">'qty'</span>     <span class="o">=></span> <span class="mi">4</span>
        <span class="p">),</span>
        <span class="k">array</span><span class="p">(</span>
                <span class="s1">'rowid'</span>   <span class="o">=></span> <span class="s1">'fh4kdkkkaoe30njgoe92rkdkkobec333'</span><span class="p">,</span>
                <span class="s1">'qty'</span>     <span class="o">=></span> <span class="mi">2</span>
        <span class="p">)</span>
<span class="p">);</span>

<span class="nv">$this</span><span class="o">-></span><span class="na">cart</span><span class="o">-></span><span class="na">update</span><span class="p">(</span><span class="nv">$data</span><span class="p">);</span>
</pre>

</div>

</div>

You may also update any property you have previously defined when inserting the item such as options, price or other
custom fields.

<div class="highlight-ci">

<div class="highlight">

<pre style="position: relative;"><span></span><span class="nv">$data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span>
        <span class="s1">'rowid'</span>  <span class="o">=></span> <span class="s1">'b99ccdf16028f015540f341130b6d8ec'</span><span class="p">,</span>
        <span class="s1">'qty'</span>    <span class="o">=></span> <span class="mi">1</span><span class="p">,</span>
        <span class="s1">'price'</span>  <span class="o">=></span> <span class="mf">49.95</span><span class="p">,</span>
        <span class="s1">'coupon'</span> <span class="o">=></span> <span class="k">NULL</span>
<span class="p">);</span>

<span class="nv">$this</span><span class="o">-></span><span class="na">cart</span><span class="o">-></span><span class="na">update</span><span class="p">(</span><span class="nv">$data</span><span class="p">);</span>
</pre>

</div>

</div>

<div class="section" id="what-is-a-row-id">

#### [What is a Row ID?](#toc-entry-7)[¶](#what-is-a-row-id "Permalink to this headline")

The row ID is a unique identifier that is generated by the cart code when an item is added to the cart. The reason a
unique ID is created is so that identical products with different options can be managed by the cart.

For example, let’s say someone buys two identical t-shirts (same product ID), but in different sizes. The product ID (
and other attributes) will be identical for both sizes because it’s the same shirt. The only difference will be the
size. The cart must therefore have a means of identifying this difference so that the two sizes of shirts can be managed
independently. It does so by creating a unique “row ID” based on the product ID and any options associated with it.

In nearly all cases, updating the cart will be something the user does via the “view cart” page, so as a developer, it
is unlikely that you will ever have to concern yourself with the “row ID”, other than making sure your “view cart” page
contains this information in a hidden form field, and making sure it gets passed to
the `<span class="pre">update()</span>` method when the update form is submitted. Please examine the construction of the
“view cart” page above for more information.

</div>

</div>

</div>

<div class="section" id="class-reference">

## [Class Reference](#toc-entry-8)[¶](#class-reference "Permalink to this headline")

<dl class="class">

<dt id="CI_Cart">_class_ `CI_Cart`[¶](#CI_Cart "Permalink to this definition")</dt>

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

<dt id="CI_Cart::insert">`insert`<span class="sig-paren">(</span><span class="optional">[</span>_$items = array()_<span class="optional">]</span><span class="sig-paren">)</span>[¶](#CI_Cart::insert "Permalink to this definition")</dt>

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

<dt id="CI_Cart::update">`update`<span class="sig-paren">(</span><span class="optional">[</span>_$items = array()_<span class="optional">]</span><span class="sig-paren">)</span>[¶](#CI_Cart::update "Permalink to this definition")</dt>

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

<dt id="CI_Cart::remove">`remove`<span class="sig-paren">(</span>_$rowid_<span class="sig-paren">)</span>[¶](#CI_Cart::remove "Permalink to this definition")</dt>

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

Allows you to remove an item from the shopping cart by passing it the `<span class="pre">$rowid</span>`.

</dd>

</dl>

<dl class="method">

<dt id="CI_Cart::total">`total`<span class="sig-paren">(</span><span class="sig-paren">)</span>[¶](#CI_Cart::total "Permalink to this definition")</dt>

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

<dt id="CI_Cart::total_items">`total_items`<span class="sig-paren">(</span><span class="sig-paren">)</span>[¶](#CI_Cart::total_items "Permalink to this definition")</dt>

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

<dt id="CI_Cart::contents">`contents`<span class="sig-paren">(</span><span class="optional">[</span>_$newest_first = FALSE_<span class="optional">]</span><span class="sig-paren">)</span>[¶](#CI_Cart::contents "Permalink to this definition")</dt>

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

<dt id="CI_Cart::get_item">`get_item`<span class="sig-paren">(</span>_$row_id_<span class="sig-paren">)</span>[¶](#CI_Cart::get_item "Permalink to this definition")</dt>

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

<dt id="CI_Cart::has_options">`has_options`<span class="sig-paren">(</span>_$row_id = ''_<span class="sig-paren">)</span>[¶](#CI_Cart::has_options "Permalink to this definition")</dt>

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
with `<span class="pre">contents()</span>`, since you must pass the rowid to this method, as shown in the Displaying the
Cart example above.

</dd>

</dl>

<dl class="method">

<dt id="CI_Cart::product_options">`product_options`<span class="sig-paren">(</span><span class="optional">[</span>_$row_id = ''_<span class="optional">]</span><span class="sig-paren">)</span>[¶](#CI_Cart::product_options "Permalink to this definition")</dt>

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
with `<span class="pre">contents()</span>`, since you must pass the rowid to this method, as shown in the Displaying the
Cart example above.

</dd>

</dl>

<dl class="method">

<dt id="CI_Cart::destroy">`destroy`<span class="sig-paren">(</span><span class="sig-paren">)</span>[¶](#CI_Cart::destroy "Permalink to this definition")</dt>

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