<?php
class TokenDTO {
    public $usuario_id;
    public $token_valor;
    public $tipo;
    public $fecha_c;

    public function __construct($usuario_id, $token_valor, $tipo, $fecha_c) {
        $this->usuario_id = $usuario_id;
        $this->token_valor = $token_valor;
        $this->tipo = $tipo;
        $this->fecha_c = $fecha_c;
    }

    public function getUsuarioId() { return $this->usuario_id; }
    public function getTokenValue() { return $this->token_valor; }
    public function getType() { return $this->tipo; }
    public function getCreatedAt() { return $this->fecha_c; }
}
