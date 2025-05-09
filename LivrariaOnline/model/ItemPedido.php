<?php
class ItemPedido {
    private $id;
    private $pedidoId;
    private $livroId;
    private $quantidade;
    private $precoUnitario;
    
    // Getters e Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getPedidoId() { return $this->pedidoId; }
    public function setPedidoId($pedidoId) { $this->pedidoId = $pedidoId; }
    
    public function getLivroId() { return $this->livroId; }
    public function setLivroId($livroId) { $this->livroId = $livroId; }
    
    public function getQuantidade() { return $this->quantidade; }
    public function setQuantidade($quantidade) { $this->quantidade = $quantidade; }
    
    public function getPrecoUnitario() { return $this->precoUnitario; }
    public function setPrecoUnitario($precoUnitario) { $this->precoUnitario = $precoUnitario; }
}