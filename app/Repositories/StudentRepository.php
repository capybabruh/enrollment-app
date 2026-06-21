<?php
// app/Repositories/StudentRepository.php

class StudentRepository
{
    public function __construct(private PDO $db) {}

    public function countAll(string $keyword = '', string $statusFilter = ''): int
    {
        $sql = "SELECT COUNT(*) AS total FROM students WHERE deleted_at IS NULL";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND (full_name LIKE :keyword1 OR email LIKE :keyword2 OR phone LIKE :keyword3)";
            $params['keyword1'] = '%' . $keyword . '%';
            $params['keyword2'] = '%' . $keyword . '%';
            $params['keyword3'] = '%' . $keyword . '%';
        }
        if ($statusFilter !== '') {
            $sql .= " AND status = :status_filter";
            $params['status_filter'] = $statusFilter;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) ($stmt->fetch()['total'] ?? 0);
    }

    public function getPaginated(string $keyword, int $limit, int $offset, string $sort, string $direction, string $statusFilter = ''): array
    {
        $allowedSorts = ['id', 'full_name', 'email', 'phone', 'status', 'created_at'];
        $allowedDirections = ['asc', 'desc'];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }
        if (!in_array(strtolower($direction), $allowedDirections, true)) {
            $direction = 'desc';
        }

        $sql = "SELECT id, full_name, email, phone, status, created_at
                FROM students
                WHERE deleted_at IS NULL";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND (full_name LIKE :keyword1 OR email LIKE :keyword2 OR phone LIKE :keyword3)";
            $params['keyword1'] = '%' . $keyword . '%';
            $params['keyword2'] = '%' . $keyword . '%';
            $params['keyword3'] = '%' . $keyword . '%';
        }
        if ($statusFilter !== '') {
            $sql .= " AND status = :status_filter";
            $params['status_filter'] = $statusFilter;
        }

        $sql .= " ORDER BY {$sort} {$direction} LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM students WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO students (full_name, email, phone, status, note)
                VALUES (:full_name, :email, :phone, :status, :note)";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?: null,
                'status' => $data['status'],
                'note' => $data['note'] ?: null,
            ]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('Student email already exists.');
            }
            throw $e;
        }
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE students
                SET full_name = :full_name, email = :email, phone = :phone,
                    status = :status, note = :note, updated_at = NOW()
                WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id' => $id,
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?: null,
                'status' => $data['status'],
                'note' => $data['note'] ?: null,
            ]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('Student email already exists.');
            }
            throw $e;
        }
    }

    // Soft delete: đánh dấu deleted_at thay vì xóa thật khỏi DB
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE students SET deleted_at = NOW() WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}