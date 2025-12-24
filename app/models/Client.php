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
     * Obtener los últimos N clientes activos
     */
    public function getLatest(int $limit = 3): array
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
            LIMIT :limit
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
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

    /**
     * Buscar un cliente por su ID
     */
    public function findById(int $id): ?array
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
            WHERE id = :id
              AND is_active = 1
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        return $client ?: null;
    }

    /**
     * Actualizar cliente
     */
    public function update(int $id, string $name, string $slug, string $short_description, string $description, string $logo): bool
    {
        $sql = "
            UPDATE clients
            SET 
                name = :name,
                slug = :slug,
                short_description = :short_description,
                description = :description,
                logo = :logo
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':slug' => $slug,
            ':short_description' => $short_description,
            ':description' => $description,
            ':logo' => $logo
        ]);
    }

    /**
     * Eliminar cliente (marcar como inactivo)
     */
    public function delete(int $id): bool
    {
        $sql = "UPDATE clients SET is_active = 0 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Crear cliente
     */
    public function create($name, $slug, $short_description, $description, $logo)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO clients (name, slug, short_description, description, logo, created_at) 
         VALUES (?, ?, ?, ?, ?, NOW())"
        );
        $stmt->execute([$name, $slug, $short_description, $description, $logo]);

        // Devuelve el ID del cliente recién creado
        return $this->db->lastInsertId();
    }


}
