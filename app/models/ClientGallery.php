<?php

class ClientGallery
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Obtener todas las imágenes de un cliente
     */
    public function getByClient(int $client_id): array
    {
        $sql = "
            SELECT *
            FROM client_gallery
            WHERE client_id = :client_id
            ORDER BY sort_order ASC, created_at ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':client_id' => $client_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Agregar imagen
     */
    public function create(int $client_id, string $image, string $caption = '', int $sort_order = 0): bool
    {
        $sql = "
            INSERT INTO client_gallery (client_id, image, caption, sort_order, created_at)
            VALUES (:client_id, :image, :caption, :sort_order, NOW())
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':client_id' => $client_id,
            ':image' => $image,
            ':caption' => $caption,
            ':sort_order' => $sort_order
        ]);
    }

    /**
     * Eliminar imagen
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM client_gallery WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
