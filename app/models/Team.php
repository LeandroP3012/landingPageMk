<?php

class Team
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM team WHERE is_active = 1 ORDER BY sort_order ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM team WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }

    public function create($name, $role, $photo, $order = 0)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO team (name, role, photo, sort_order) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$name, $role, $photo, $order]);
        return $this->db->lastInsertId();
    }

    public function update($id, $name, $role, $photo, $order)
    {
        $stmt = $this->db->prepare(
            "UPDATE team SET name=?, role=?, photo=?, sort_order=? WHERE id=?"
        );
        return $stmt->execute([$name, $role, $photo, $order, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM team WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
