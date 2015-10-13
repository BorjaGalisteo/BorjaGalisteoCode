En CalculatePrice.php tenemos la función calcularprecio(), La cual calcula el precio de un paquete vacacional.
El precio es previamente calculado en la parte cliente pero para comprobar que el precio no ha cambiado justo antes de
realizar la operación de reservar comprobamos que el precio sigue siendo el mismo o que nadie ha manipulado el DOM para
cambiar el precio.