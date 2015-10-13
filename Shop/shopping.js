$(document).ready(function(){
    $(".addbasket").click(function(){
        var id_product = $(this).data("idproduct");
        var size = $(".size-"+id_product+" option:selected").val();
        var quantity = $(".quantity-"+id_product+" option:selected").val();
        Cesta.add_item(id_product,size,quantity,"reload" );
    });
    $(".deleteitem").click(function(){
        var id_product = $(this).data("idproduct");
        var size = $(this).data("sizeproduct");
        Cesta.remove_item(id_product,size,"reload" );
    });
    $(".addquantity").click(function(){
        $("#pricebasket").html("<img src='../backoffice/images/spin.gif'/>");
        var id_product = $(this).data("idproducto");
        var size = $(this).parent().data("talla");
        var quantity = $(".quantityproduct-"+id_product).html();
        Producto.add_quantity(id_product,size,1,"reload" );
        $(".quantityproduct-"+id_product).html(parseInt(quantity)+1);
    });
    $(".removequantity").click(function(){
        $("#pricebasket").html("<img src='../backoffice/images/spin.gif'/>");
        var id_product = $(this).data("idproducto");
        var size = $(this).parent().data("talla");
        var quantity = $(".quantityproduct-"+id_product).html();
        if(quantity > 1){
            Producto.remove_quantity(id_product,size,1);
            $(".quantityproduct-"+id_product).html(parseInt(quantity)-1);
        }
    });
    // $("#datos_compra").submit(function(e){
    $("#pagar").click(function(e){
        e.preventDefault();
        if (validar_compra_tienda()){
            Cesta.create_purchase()
        }
        ;
    });

});