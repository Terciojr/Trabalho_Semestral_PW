<?php
class Livro {
    private $id;
    private $titulo;
    private $autor;
    private $genero;
    private $preco;
    private $estoque;
    private $descricao;
    private $capa;

    // Getters e Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getTitulo() { return $this->titulo; }
    public function setTitulo($titulo) { $this->titulo = $titulo; }
    
    public function getAutor() { return $this->autor; }
    public function setAutor($autor) { $this->autor = $autor; }
    
    public function getGenero() { return $this->genero; }
    public function setGenero($genero) { $this->genero = $genero; }
    
    public function getPreco() { return $this->preco; }
    public function setPreco($preco) { $this->preco = $preco; }
    
    public function getEstoque() { return $this->estoque; }
    public function setEstoque($estoque) { $this->estoque = $estoque; }

    public function getDescricao() {
        return $this->descricao;
    }
    
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }
    
    public function getCapa() { return $this->capa; }
    public function setCapa($capa) { $this->capa = $capa; }
}