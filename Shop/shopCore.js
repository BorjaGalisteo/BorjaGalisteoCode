/**
 * Mediante diccionarios podemos crear Scopes para trabajar las diferentes funciones de la cesta de la compra.
 */
var Cesta = {
    add_item: function(id_product,size,quantity,callback){
        //llamada de ajax para a�adir un item a la session
        $.ajax({
            method: "POST",
            url: "/backoffice/dataaccess/DAshop.php",
            data: { "oper":"add_item","id_product":id_product,"size":size,"quantity":quantity}
        })
            .success(function( info ) {
                Cesta.reload_basket();
                if (callback == "reload") location.reload();
            });
    },
    remove_item: function(id_product,size,callback){
        //llamada de ajax para QUITAR  un item a la session.
        $.ajax({
            method: "POST",
            url: "/backoffice/dataaccess/DAshop.php",
            data: { "oper":"remove_item","id_product":id_product,"size":size}
        })
            .success(function( info ) {
                Cesta.reload_basket();
                if (callback == "reload") location.reload();
            });
    },
    reload_basket : function(){
        //llamada de ajax para obtener los productos de la cesta.
        $.ajax({
            method: "POST",
            url: "/backoffice/dataaccess/DAshop.php",
            data: { "oper":"reload_basket"}
        })
            .success(function( info ) {
                console.log(info);
            });
    },
    price : function(){
        //llamada de ajax para sacar el precio de la cesta.
        $.ajax({
            method: "POST",
            url: "/backoffice/dataaccess/DAshop.php",
            data: { "oper":"get_price"}
        })
            .success(function( info ) {
                $("#pricebasket").html(info);
            });
    },
    create_purchase : function(callback){
        //llamada de ajax para crea la compra y devuelve el hash de la reserva y el precio para el tpv.
        $.ajax({
            method: "POST",
            url: "/backoffice/dataaccess/DAshop.php",
            data: $("#datos_compra").serialize()
        })
            .success(function( info ) {
                console.log(info);
                if(callback == "gotoInvoice"){
                    window.location.href = '/compra.php?id='+info;
                }
                var adata = JSON.parse(info);


                var TPV_codigoComercio = document.getElementById ("Ds_Merchant_MerchantCode").value;
                var TPV_codigoPedido = adata["id"];
                TPV_codigoPedido = TPV_codigoPedido.toString();
                while (TPV_codigoPedido.length<10) {
                    TPV_codigoPedido= "0"+TPV_codigoPedido;
                }
                var TPV_Precio = adata["precio"]*100;
                var TPV_Currency = document.getElementById ("Ds_Merchant_Currency").value;
                var TPV_Url = document.getElementById ("Ds_Merchant_MerchantURL").value;
                var TPV_Url_OK = document.getElementById ("Ds_Merchant_MerchantURL").value;
                var TPV_Url_KO = document.getElementById ("Ds_Merchant_MerchantURL").value;
                var TPV_Signature = document.getElementById ("Ds_Merchant_MerchantSignature").value;
                var TPV_Terminal = document.getElementById ("Ds_Merchant_Terminal").value;
                var TPV_Transaccion = document.getElementById ("Ds_Merchant_TransactionType").value;
                var TPV_Lenguaje = document.getElementById ("Ds_Merchant_ConsumerLanguage").value;
                // AJAX 2 FIRMA
                $.ajax({
                    type: "POST",
                    url: "/generatesignature.php",
                    data: {Ds_Merchant_MerchantCode:TPV_codigoComercio,Ds_Merchant_Order:TPV_codigoPedido, Ds_Merchant_Amount:TPV_Precio, Ds_Merchant_Currency:TPV_Currency,Ds_Merchant_MerchantURL:TPV_Url,Ds_Merchant_UrlOK:TPV_Url_OK,Ds_Merchant_UrlKO: TPV_Url_KO,Ds_Merchant_MerchantSignature:TPV_Signature,Ds_Merchant_Terminal:TPV_Terminal, Ds_Merchant_TransactionType:TPV_Transaccion,Ds_Merchant_ConsumerLanguage:TPV_Lenguaje}
                })
                    .success(function( data ) {
                        $("#Ds_Merchant_MerchantSignature").attr("value", data); //Data aqui devuelve el hash de la reserva.
                        $("#Ds_Merchant_Order").attr("value", TPV_codigoPedido);
                        $("#Ds_Merchant_Amount").attr("value", TPV_Precio);
                        $("#tela").hide();

                        $("#datos_compra").submit();
                    })


                // ACABA AJAX 2
            });
    },
    buy : function(callback){
        //llamada de ajax para ir a pagar.
        $.ajax({
            method: "POST",
            url: "/backoffice/dataaccess/DAshop.php",
            data: $("#datos_compra").serialize()
        })
            .success(function( info ) {
                console.log(info);
                if(callback == "gotoInvoice"){
                    window.location.href = '/compra.php?id='+info;
                }
            });
    }
};
var Producto = {
    add_quantity : function(id_product,size,quantity){
        //llamada para a�adir a la session una unidad de este producto
        $.ajax({
            method: "POST",
            url: "/backoffice/dataaccess/DAshop.php",
            data: { "oper":"add_quantity","id_product":id_product,"size":size,"quantity":quantity }
        })
            .success(function( info ) {
                Cesta.price();
            });
    },
    remove_quantity : function(id_product,size,quantity){
        ////llamada para reducir en una unidad este producto
        $.ajax({
            method: "POST",
            url: "/backoffice/dataaccess/DAshop.php",
            data: { "oper":"remove_quantity","id_product":id_product,"size":size,"quantity":quantity }
        })
            .success(function( info ) {
                Cesta.price();
            });
    }
}
