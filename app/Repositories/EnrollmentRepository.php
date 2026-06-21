<?php
// app/Repositories/EnrollmentRepository.php

class EnrollmentRepository
{
    public function __construct(private PDO $db) {}

    public function countAll(string $keyword = '', string $statusFilter = ''): int
    {
        $sql = "SELECT COUNT(*) AS total FROM enrollments WHERE deleted_at IS NULL";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND (enrollment_code LIKE :keyword1
                      OR student_name LIKE :keyword2
                      OR student_email LIKE :keyword3
                      OR course_name LIKE :keyword4)";
            $params['keyword1'] = '%' . $keyword . '%';
            $params['keyword2'] = '%' . $keyword . '%';
            $params['keyword3'] = '%' . $keyword . '%';
            $params['keyword4'] = '%' . $keyword . '%';
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
        $allowedSorts = ['id', 'enrollment_code', 'student_name', 'student_email', 'course_name', 'fee_amount', 'status', 'created_at'];
        $allowedDirections = ['asc', 'desc'];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }
        if (!in_array(strtolower($direction), $allowedDirections, true)) {
            $direction = 'desc';
        }

        $sql = "SELECT id, enrollment_code, student_name, student_email, course_name, fee_amount, status, created_at
                FROM enrollments
                WHERE deleted_at IS NULL";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND (enrollment_code LIKE :keyword1
                      OR student_name LIKE :keyword2
                      OR student_email LIKE :keyword3
                      OR course_name LIKE :keyword4)";
            $params['keyword1'] = '%' . $keyword . '%';
            $params['keyword2'] = '%' . $keyword . '%';
            $params['keyword3'] = '%' . $keyword . '%';
            $params['keyword4'] = '%' . $keyword . '%';
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
        $stmt = $this->db->prepare("SELECT * FROM enrollments WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO enrollments (enrollment_code, student_name, student_email, course_name, fee_amount, status)
                VALUES (:enrollment_code, :student_name, :student_email, :course_name, :fee_amount, :status)";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'enrollment_code' => $data['enrollment_code'],
                'student_name' => $data['student_name'],
                'student_email' => $data['student_email'] ?: null,
                'course_name' => $data['course_name'],
                'fee_amount' => $data['fee_amount'],
                'status' => $data['status'],
            ]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('Enrollment code already exists.');
            }
            throw $e;
        }
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE enrollments
                SET enrollment_code = :enrollment_code,
                    student_name    = :student_name,
                    student_email   = :student_email,
                    course_name     = :course_name,
                    fee_amount      = :fee_amount,
                    status          = :status,
                    updated_at      = NOW()
                WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id' => $id,
                'enrollment_code' => $data['enrollment_code'],
                'student_name' => $data['student_name'],
                'student_email' => $data['student_email'] ?: null,
                'course_name' => $data['course_name'],
                'fee_amount' => $data['fee_amount'],
                'status' => $data['status'],
            ]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('Enrollment code already exists.');
            }
            throw $e;
        }
    }

    // Soft delete: đánh dấu deleted_at thay vì xóa thật khỏi DB
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE enrollments SET deleted_at = NOW() WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}