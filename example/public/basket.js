(function (window, document, $) {
    'use strict';
    $('.addCart').on('click', function () {
        var id = $(this).data('id');
        var price = $(this).data('price');
        var title = $(this).data('title');
        var qty = $('#qty' + id).val();
        $.ajax({
            type: 'POST',
            url: 'addBasket',
            dataType: 'json',
            data: {
                qty: qty,
                id: id,
                price:price,
                title:title
            },
            success: function (data) {
                console.log(data);
                var cart = '';
                $.each(data.carts, function (index, element) {
                    cart = cart.concat('<div class="dropdown-item">' +
                        element.name + ' | ' + element.qty + ' | ' + element.subtotal +
                        '</div>');
                });

                cart=cart.concat('' +
                    '<li>' +
                    '<a href="basket">Sepete Git</a>'+
                    '</li>')
                $('#basket').html(cart);
                $('#badge').text(data.count);
            }
        });
    });

    $('.updateCart').on('change', function () {
        var id = $(this).data('rowid');
        var qty = $(this).val();
        $.ajax({
            type: 'POST',
            url: 'updateBasket',
            data: {
                qty: qty,
                rowid: id
            },
            success: function (data) {
                var cart = '';
                $.each(data.carts, function (index, element) {
                    cart = cart.concat('<div class="dropdown-item border">' +
                        element.name + ' | ' + element.qty + ' | ' + element.subtotal +
                        '</div>');
                });
                $('#basket').html(cart);
                alert('sepet güncellendi');
                location.reload();
            }
        });
    });

    $('.removeCart').on('click', function () {
        var id = $(this).data('rowid');
        $.ajax({
            type: 'POST',
            url: 'removeBasketOne',
            data: {
                rowid: id
            },
            success: function (data) {
                var cart = '';
                $.each(data.carts, function (index, element) {
                    cart = cart.concat('<div class="dropdown-item border">' +
                        element.name + ' | ' + element.qty + ' | ' + element.subtotal +
                        '</div>');
                });
                $('#basket').html(cart);
                alert('sepetten ürün silindi.');
                location.reload();
            }
        });
    });

    $('#deleteCart').on('click', function () {
        $.ajax({
            type: 'POST',
            url: 'emptyTheBasket',
            success:function (){
                location.reload();
            }
        });
    });
})(window, document, jQuery);