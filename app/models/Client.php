<?php

class Client
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Obtener todos los clientes activos
     */
    public function all(): array
    {
        $sql = "
            SELECT 
                id,
                name,
                slug,
                logo,
                short_description,
                description,
                created_at
            FROM clients
            WHERE is_active = 1
            ORDER BY created_at DESC
        ";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Buscar un cliente por su slug
     */
    public function findBySlug(string $slug): ?array
    {
        $sql = "
            SELECT 
                id,
                name,
                slug,
                logo,
                short_description,
                description,
                created_at
            FROM clients
            WHERE slug = :slug
              AND is_active = 1
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();

        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        return $client ?: null;
    }
}
