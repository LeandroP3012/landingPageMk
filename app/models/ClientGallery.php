<?php

class ClientGallery
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getByClient($clientId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM client_gallery 
             WHERE client_id = ? 
             ORDER BY sort_order ASC"
        );
        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
