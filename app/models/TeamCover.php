<?php

class TeamCover
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function get(): ?array
    {
        $stmt = $this->db->query("SELECT * FROM team_cover LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function update($top, $bottom)
    {
        $stmt = $this->db->prepare(
            "UPDATE team_cover SET image_top = ?, image_bottom = ? WHERE id = 1"
        );
        return $stmt->execute([$top, $bottom]);
    }

    public function create($top, $bottom)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO team_cover (id, image_top, image_bottom) VALUES (1, ?, ?)"
        );
        return $stmt->execute([$top, $bottom]);
    }
}
