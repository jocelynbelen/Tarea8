<?php
/* DEfine clases para encapsular los datos y la lógica de filtrado.*/
class Evento {
    public $nombre;
    public $tipo;
    public $lugar;
    public $fecha;
    public $hora;
    public $descripcion;

    public function __construct(
        string $nombre,
        string $tipo,
        string $lugar,
        string $fecha,   
        string $hora,    
        string $descripcion
    ) {
        $this->nombre      = $nombre;
        $this->tipo        = $tipo;
        $this->lugar       = $lugar;
        $this->fecha       = $fecha;
        $this->hora        = $hora;
        $this->descripcion = $descripcion;
    }
}

class EventManager {
    /** @var Evento[] */
    private $eventos = [];

    public function add(Evento $e): void {
        $this->eventos[] = $e;
    }

    /** @return Evento[] */
    public function all(): array {
        return $this->eventos;
    }

    public function filtrar(array $c): array {
        return array_filter($this->eventos, function (Evento $e) use ($c) {
            $ok = true;
            if (!empty($c['tipo']))  { $ok = $ok && strcasecmp($e->tipo,  $c['tipo'])  === 0; }
            if (!empty($c['lugar'])) { $ok = $ok && stripos($e->lugar,    $c['lugar']) !== false; }
            if (!empty($c['desde'])) { $ok = $ok && ($e->fecha >= $c['desde']); }
            if (!empty($c['hasta'])) { $ok = $ok && ($e->fecha <= $c['hasta']); }
            return $ok;
        });
    }
}
