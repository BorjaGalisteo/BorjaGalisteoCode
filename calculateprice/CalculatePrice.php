<?php
/**
 * La siguiente funcion calcula el precio de un paquete vacacional teniendo en cuenta que elementos contiene.
 * @param $plazas = Plazas del paquete
 * @param $paquete = Paquete del que calcular el precio en esta reserva
 * @param $aeropuerto = Aeropuerto seleccionado para la reserva
 * @param $hotel = Hotel seleccionado para la reserva.
 * @param $habitaciones = Habitaciones seleccionadas para la reserva
 * @param $tarjeta = Tarjeta selecciona para la reserva.
 * @param $complementos = Complementos que pueda contener la reserva.
 * @param $db = Objeto de EZmySQL necesario para la libreria de acceso a BBDD.
 * @return int = Devolvemos el precio de la reserva, teniendo en cuenta todos los elementos que una reserva puede contener.
 *
 */

//Ejemeplo de USO
$info = json_decode($_POST["info"],true);
$precioRecalculado = calcularprecio($info["plazas"],$info["idpaquete"],$info["idaeropuerto"],$info["idhotel"],$info["habitacion"],$info["tarjeta"],$info["complementos"],$db);

if ($precioRecalculado == $info["precio"]) {
    return crearreserva($info,$db);
}else {
    return "El precio no coincide $precioRecalculado | ".$info["precio"];
}
//FIN DE EJMEPLO DE USO

function calcularprecio($plazas, $paquete, $aeropuerto, $hotel, $habitaciones, $tarjeta, $complementos, $db)
{
    function precioaeropuerto($db, $aeropuerto, $paquete, $plazas)
    {
        if ($aeropuerto == null) {
            return 0;
        }
//Hemos seleccionado algun aeropuerto con suplemento.
        $q = "select suplemento from posibles where tipo_complemento = 'aero' and id_complemento = '" . $aeropuerto . "' and id_paquete = " . $paquete;
        $precioaero = $db->get_var($q);
        $precioaero *= $plazas;
        return $precioaero;
    }

    function precioTarjeta($db, $tarjeta, $paquete, $plazas)
    {
        if ($tarjeta == null) {
            return 0;
        }
//Hemos seleccionado algun aeropuerto con suplemento.
        $q = "select suplemento from posibles where tipo_complemento = 'tarjeta' and id_complemento = '" . $tarjeta . "' and id_paquete = " . $paquete;
        $preciotarjeta = $db->get_var($q);
        $preciotarjeta = $preciotarjeta * $plazas;
        return $preciotarjeta;
    }

    function precioHotel($db, $hotel, $paquete, $plazas)
    {
        if ($hotel == null) {
            return 0;
        }
//Hemos seleccionado algun Hotel con suplemento.
        $q = "select suplemento from posibles where tipo_complemento = 'hotel' and id_complemento = '" . $hotel . "' and id_paquete = " . $paquete;
        $preciohotel = $db->get_var($q);
        $preciohotel = $preciohotel * $plazas;
        return $preciohotel;
    }

    function precioHabitaciones($db, $habitaciones, $paquete)
    {
        if ($habitaciones == null) {
            return 0;
        }
        $preciohab = 0;
        $Totalhab = 0;
        $precioTotalhab = 0;
        foreach ($habitaciones as $hab) {
            $q = "select suplemento from posibles where tipo_complemento = 'hab' and id_complemento = '" . $hab["id"] . "' and id_paquete = " . $paquete;
            $preciohab = $db->get_var($q);
            $preciohab = $preciohab * $hab["cantidad"];
            $precioTotalhab += $preciohab;
        }
        return $precioTotalhab;
    }

    function precioComplementos($db, $complementos, $paquete, $plazas)
    {
        if ($complementos == null) {
            return 0;
        }
        $precioComple = 0;
        $precioTotalcomple = 0;
        foreach ($complementos as $complemento) {
            $q = "select suplemento from posibles where tipo_complemento = 'complemento' and id_complemento = '" . $complemento . "' and id_paquete = " . $paquete;
            $precioComple = $db->get_var($q);
            $precioComple = $precioComple * $plazas;
            $precioTotalcomple += $precioComple;
        }
        return $precioTotalcomple;
    }

    $q = "select precio from paquete where id = " . $paquete;

    $preciobasicopaquete = $db->get_var($q);
    $precioTotal = 0;
    $precioTotal = $preciobasicopaquete * $plazas;
//Tenemos el precio básico del paquete.
    $precioTotal += precioaeropuerto($db, $aeropuerto, $paquete, $plazas);
    $precioTotal += precioHotel($db,$hotel,$paquete,$plazas);
    $precioTotal += precioHabitaciones($db, $habitaciones, $paquete);
    $precioTotal += precioTarjeta($db, $tarjeta, $paquete, $plazas);
    $precioTotal += precioComplementos($db, $complementos, $paquete, $plazas);

    return $precioTotal;
}

