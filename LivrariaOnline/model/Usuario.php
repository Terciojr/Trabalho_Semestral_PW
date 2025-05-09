<?php
class Usuario {
    private $id;
    private $nome;
    private $email;
    private $telefone;
    private $endereco;
    private $senha;

    // Getters e Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getNome() { return $this->nome; }
    public function setNome($nome) { $this->nome = $nome; }
    
    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }
    
    public function getTelefone() { return $this->telefone; }
    public function setTelefone($telefone) { $this->telefone = $telefone; }
    
    public function getEndereco() { return $this->endereco; }
    public function setEndereco($endereco) { $this->endereco = $endereco; }
    
    public function getSenha() { return $this->senha; }
    public function setSenha($senha) { $this->senha = password_hash($senha, PASSWORD_DEFAULT); }
}