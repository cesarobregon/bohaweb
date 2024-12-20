<?php
class Conexion extends PDO {
    private $tipo_de_base;
    private $host;
    private $usuario;
    private $pws;
    private $db;
    private $puerto;
    private $dsn;

    public function __construct() {
        $this->tipo_de_base = 'mysql';
        $this->host = "localhost";
        $this->usuario = "cesarobregon";
        $this->pws = "cesarphp";
        $this->db = "boha1";
        $this->puerto = "3306";
        $this->dsn = null;

        // Configurar DSN
        $dsn = "{$this->tipo_de_base}:host={$this->host};dbname={$this->db};charset=utf8";
        parent::__construct($dsn, $this->usuario, $this->pws);
        parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        parent::setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Para usar prepared statements nativos
    }
}
?>
