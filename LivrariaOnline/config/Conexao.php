<?php
class Conexao {
    private static $instancia;

    private function __construct() {}

    public static function getConexao() {
        if (!isset(self::$instancia)) {
            try {
                self::$instancia = new PDO(
                    'mysql:host=localhost;dbname=livraria_online;charset=utf8mb4',
                    'root',  // Substitua pelo seu usuário MySQL
                    ''     // Substitua pela sua senha MySQL
                );
                self::$instancia->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro de conexão: " . $e->getMessage());
            }
        }
        return self::$instancia;
    }
}