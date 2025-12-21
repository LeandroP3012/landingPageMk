<?php

class ClientLogo
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        $stmt = $this->db->query("
            SELECT logo, width, height
            FROM client_logos
            WHERE is_active = 1
            ORDER BY sort_order ASC, created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByClient(int $clientId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM client_logos
            WHERE client_id = ?
            ORDER BY sort_order ASC
        ");
        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(
        int $clientId,
        string $logo,
        int $width = 80,
        int $height = 40,
        int $sortOrder = 0
    ): bool {
        $stmt = $this->db->prepare("
            INSERT INTO client_logos 
            (client_id, logo, width, height, sort_order, is_active, created_at)
            VALUES (?, ?, ?, ?, ?, 1, NOW())
        ");

        return $stmt->execute([
            $clientId,
            $logo,
            $width,
            $height,
            $sortOrder
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM client_logos WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function allActive(): array
    {
        $stmt = $this->db->query("
        SELECT *
        FROM client_logos
        WHERE is_active = 1
        ORDER BY sort_order ASC, created_at DESC
    ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
