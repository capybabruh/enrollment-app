<?php
// app/Controllers/StudentController.php

class StudentController
{
    private function repository(): StudentRepository
    {
        $config = require __DIR__ . '/../../config/database.php';
        $pdo = (new Database($config))->getConnection();
        return new StudentRepository($pdo);
    }

    public function index(): void
    {
        $q             = trim($_GET['q'] ?? '');
        $status_filter = trim($_GET['status_filter'] ?? '');
        $page          = max(1, (int) ($_GET['page'] ?? 1));
        $perPage       = 10;
        $sort          = $_GET['sort'] ?? 'created_at';
        $direction     = $_GET['direction'] ?? 'desc';
        $offset        = ($page - 1) * $perPage;

        $repo       = $this->repository();
        $total      = $repo->countAll($q, $status_filter);
        $totalPages = max(1, (int) ceil($total / $perPage));

        if ($page > $totalPages) {
            $page   = $totalPages;
            $offset = ($page - 1) * $perPage;
        }

        $students = $repo->getPaginated($q, $perPage, $offset, $sort, $direction, $status_filter);

        view('students/index', compact('students', 'q', 'status_filter', 'page', 'perPage', 'total', 'totalPages', 'sort', 'direction'));
    }

    public function create(): void
    {
        $errors = [];
        $old = ['full_name' => '', 'email' => '', 'phone' => '', 'status' => 'new', 'note' => ''];
        view('students/create', compact('errors', 'old'));
    }

    public function store(): void
    {
        if (!csrf_verify()) {
            http_response_code(419);
            $errors = ['csrf' => 'Phiên làm việc đã hết hạn, vui lòng thử lại.'];
            $old = $_POST;
            view('students/create', compact('errors', 'old'));
            return;
        }

        $data   = $this->validate($_POST);
        $errors = $data['errors'];
        $old    = $data['values'];

        if (!empty($errors)) {
            view('students/create', compact('errors', 'old'));
            return;
        }

        try {
            $this->repository()->create($data['values']);
            flash_set('success', 'Student created successfully.');
            redirect('/students');
        } catch (DuplicateRecordException $e) {
            $errors['email'] = 'Email này đã tồn tại trong hệ thống.';
            view('students/create', compact('errors', 'old'));
        } catch (Exception $e) {
            log_error('StudentController::store failed', $e);
            http_response_code(500);
            view('errors/500');
        }
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $student = $this->repository()->findById($id);

        if (!$student) {
            http_response_code(404);
            view('errors/404');
            return;
        }

        $errors = [];
        $old = $student;
        view('students/edit', compact('student', 'errors', 'old'));
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        if (!csrf_verify()) {
            http_response_code(419);
            $student = $this->repository()->findById($id);
            $errors = ['csrf' => 'Phiên làm việc đã hết hạn, vui lòng thử lại.'];
            $old = $_POST;
            view('students/edit', compact('student', 'errors', 'old'));
            return;
        }

        $data   = $this->validate($_POST);
        $errors = $data['errors'];
        $old    = $data['values'];

        if (!empty($errors)) {
            $student = ['id' => $id] + $old;
            view('students/edit', compact('student', 'errors', 'old'));
            return;
        }

        try {
            $this->repository()->update($id, $data['values']);
            flash_set('success', 'Student updated successfully.');
            redirect('/students');
        } catch (DuplicateRecordException $e) {
            $errors['email'] = 'Email này đã tồn tại trong hệ thống.';
            $student = ['id' => $id] + $old;
            view('students/edit', compact('student', 'errors', 'old'));
        } catch (Exception $e) {
            log_error('StudentController::update failed', $e);
            http_response_code(500);
            view('errors/500');
        }
    }

    public function delete(): void
    {
        if (!csrf_verify()) {
            flash_set('success', 'Phiên làm việc đã hết hạn, vui lòng thử lại.');
            redirect('/students');
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);

        try {
            $this->repository()->delete($id);
            flash_set('success', 'Student deleted successfully.');
        } catch (Exception $e) {
            log_error('StudentController::delete failed', $e);
            flash_set('success', 'Có lỗi xảy ra khi xoá học viên.');
        }

        redirect('/students');
    }

    private function validate(array $input): array
    {
        $values = [
            'full_name' => trim($input['full_name'] ?? ''),
            'email' => trim($input['email'] ?? ''),
            'phone' => trim($input['phone'] ?? ''),
            'status' => trim($input['status'] ?? 'new'),
            'note' => trim($input['note'] ?? ''),
        ];
        $errors = [];
        $allowedStatuses = ['new', 'contacted', 'enrolled', 'dropped'];

        if ($values['full_name'] === '') {
            $errors['full_name'] = 'Vui lòng nhập họ tên học viên.';
        }
        if ($values['email'] === '') {
            $errors['email'] = 'Vui lòng nhập email.';
        } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không đúng định dạng.';
        }
        if (!in_array($values['status'], $allowedStatuses, true)) {
            $errors['status'] = 'Trạng thái không hợp lệ.';
        }

        return ['values' => $values, 'errors' => $errors];
    }
}
