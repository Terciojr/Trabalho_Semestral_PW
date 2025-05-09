<?php
class Pedido {
    private $id;
    private $usuarioId;
    private $data;
    private $total;
    private $status;
    private $itens = [];

    // Getters e Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getUsuarioId() { return $this->usuarioId; }
    public function setUsuarioId($usuarioId) { $this->usuarioId = $usuarioId; }
    
    public function getData() { return $this->data; }
    public function setData($data) { $this->data = $data; }
    
    public function getTotal() { return $this->total; }
    public function setTotal($total) { $this->total = $total; }
    
    public function getStatus() { return $this->status; }
    public function setStatus($status) { $this->status = $status; }
    
    public function getItens() { return $this->itens; }
    public function addItem($item) { $this->itens[] = $item; }
}