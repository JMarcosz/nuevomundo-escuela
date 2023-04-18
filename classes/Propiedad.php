<?php

namespace App;

class Propiedad
{

    protected static $db; //Base de datos
    protected static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedorId'];
    protected static $errores = [];

    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamiento;
    public $creado;
    public $vendedorId;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? '';
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date('Y,m,d');
        $this->vendedorId = $args['vendedorId'] ?? '';
    }

    public static function setDB($database)
    {
        self::$db = $database;
    }

    public function atributos()
    {
        $atributos = [];
        foreach (self::$columnasDB as $columna) {
            if ($columna == 'id') continue;
            $atributos[$columna] = $this->$columna;
        }

        return $atributos;
    }

    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        foreach ($atributos as $key => $value) {
            $atributos[$key] = self::$db->escape_string($value);
        }
        return ($atributos);
    }

    public static function getErrores()
    {
        return self::$errores;
    }

    public function validar()
    {
        //Validación del formulario
        if (!$this->titulo) {
            self::$errores[] = "Debes añadir un titulo";
        }

        if (!$this->precio) {
            self::$errores[] = "El precio es obligatorio";
        }

        if (strlen($this->descripcion) < 50) {
            self::$errores[] = "La descripción es obligatoria  y debe tener mínimo 50 caracteres.";
        }

        if (!$this->habitaciones) {
            self::$errores[] = "La cantidad de habitaciones es obligatoria";
        }

        if (!$this->wc) {
            self::$errores[] = "La cantidad de baños es obligatoria";
        }

        if (!$this->estacionamiento) {
            self::$errores[] = "La cantidad de estacionamientos es obligatoria";
        }

        if (!$this->vendedorId) {
            self::$errores[] = "Elige un vendedor";
        }

        if (!$this->imagen) {
            self::$errores[] = "La imagen es obligatoria";
        }

        return self::$errores;
    }

    //Subir Imagenes
    public function setImagen($imagen)
    {
        if ($imagen) {
            $this->imagen = $imagen;
        }
    }

    public function guardar()
    {
        //Sanitizar atributos
        $atributos = $this->sanitizarAtributos();
        $columna = join(', ', array_keys($atributos));
        $fila = join("', '", array_values($atributos));
        $query = " INSERT INTO propiedades ($columna) values('$fila'); ";
        $resultado = self::$db->query($query);
        return $resultado;
    }

    public static function viewAll()
    {
        $query = "SELECT * FROM propiedades";
        $resultado = self::$db->query($query);

        $array = [];
        while ($registro = $resultado->fetch_assoc()) {
            $objecto = (object)$registro;
            $array[] = $objecto;
        }
        $resultado->free();
        return $array;
    }
}
