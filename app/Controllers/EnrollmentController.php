<?php
// app/Controllers/EnrollmentController.php

class EnrollmentController
{
    private function repository(): EnrollmentRepository
    {
        $config = require __DIR__ . '/../../config/database.php';
        $pdo = (new Database($config))->getConnection();
        return new EnrollmentRepository($pdo);
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

        $enrollments = $repo->getPaginated($q, $perPage, $offset, $sort, $direction, $status_filter);

        view('enrollments/index', compact('enrollments', 'q', 'status_filter', 'page', 'perPage', 'total', 'totalPages', 'sort', 'direction'));
    }

    public function create(): void
    {
        $errors = [];
        $old = ['enrollment_code' => '', 'student_name' => '', 'student_email' => '', 'course_name' => '', 'fee_amount' => '', 'status' => 'pending'];
        view('enrollments/create', compact('errors', 'old'));
    }

    public function store(): void
    {
        if (!csrf_verify()) {
            http_response_code(419);
            $errors = ['csrf' => 'Phiên làm việc đã hết hạn, vui lòng thử lại.'];
            $old = $_POST;
            view('enrollments/create', compact('errors', 'old'));
            return;
        }

        $data   = $this->validate($_POST);
        $errors = $data['errors'];
        $old    = $data['values'];

        if (!empty($errors)) {
            view('enrollments/create', compact('errors', 'old'));
            return;
        }

        try {
            $this->repository()->create($data['values']);
            flash_set('success', 'Enrollment created successfully.');
            redirect('/enrollments');
        } catch (DuplicateRecordException $e) {
            $errors['enrollment_code'] = 'Mã đăng ký này đã tồn tại.';
            view('enrollments/create', compact('errors', 'old'));
        } catch (Exception $e) {
            log_error('EnrollmentController::store failed', $e);
            http_response_code(500);
            view('errors/500');
        }
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $enrollment = $this->repository()->findById($id);

        if (!$enrollment) {
            http_response_code(404);
            view('errors/404');
            return;
        }

        $errors = [];
        $old = $enrollment;
        view('enrollments/edit', compact('enrollment', 'errors', 'old'));
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        if (!csrf_verify()) {
            http_response_code(419);
            $enrollment = $this->repository()->findById($id);
            $errors = ['csrf' => 'Phiên làm việc đã hết hạn, vui lòng thử lại.'];
            $old = $_POST;
            view('enrollments/edit', compact('enrollment', 'errors', 'old'));
            return;
        }

        $data   = $this->validate($_POST);
        $errors = $data['errors'];
        $old    = $data['values'];

        if (!empty($errors)) {
            $enrollment = ['id' => $id] + $old;
            view('enrollments/edit', compact('enrollment', 'errors', 'old'));
            return;
        }

        try {
            $this->repository()->update($id, $data['values']);
            flash_set('success', 'Enrollment updated successfully.');
            redirect('/enrollments');
        } catch (DuplicateRecordException $e) {
            $errors['enrollment_code'] = 'Mã đăng ký này đã tồn tại.';
            $enrollment = ['id' => $id] + $old;
            view('enrollments/edit', compact('enrollment', 'errors', 'old'));
        } catch (Exception $e) {
            log_error('EnrollmentController::update failed', $e);
            http_response_code(500);
            view('errors/500');
        }
    }

    public function delete(): void
    {
        if (!csrf_verify()) {
            flash_set('success', 'Phiên làm việc đã hết hạn, vui lòng thử lại.');
            redirect('/enrollments');
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);

        try {
            $this->repository()->delete($id);
            flash_set('success', 'Enrollment deleted successfully.');
        } catch (Exception $e) {
            log_error('EnrollmentController::delete failed', $e);
            flash_set('success', 'Có lỗi xảy ra khi xoá đăng ký.');
        }

        redirect('/enrollments');
    }

    private function validate(array $input): array
    {
        $values = [
            'enrollment_code' => trim($input['enrollment_code'] ?? ''),
            'student_name'    => trim($input['student_name'] ?? ''),
            'student_email'   => trim($input['student_email'] ?? ''),
            'course_name'     => trim($input['course_name'] ?? ''),
            'fee_amount'      => trim($input['fee_amount'] ?? ''),
            'status'          => trim($input['status'] ?? 'pending'),
        ];
        $errors          = [];
        $allowedStatuses = ['pending', 'paid', 'cancelled'];

        if ($values['enrollment_code'] === '') {
            $errors['enrollment_code'] = 'Vui lòng nhập mã đăng ký.';
        }
        if ($values['student_name'] === '') {
            $errors['student_name'] = 'Vui lòng nhập tên học viên.';
        }
        if ($values['student_email'] !== '' && !filter_var($values['student_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['student_email'] = 'Email học viên không đúng định dạng.';
        }
        if ($values['course_name'] === '') {
            $errors['course_name'] = 'Vui lòng nhập tên khóa học.';
        }
        if ($values['fee_amount'] === '' || !is_numeric($values['fee_amount'])) {
            $errors['fee_amount'] = 'Vui lòng nhập học phí hợp lệ.';
        } elseif ((float) $values['fee_amount'] < 0) {
            $errors['fee_amount'] = 'Học phí không được âm.';
        } else {
            $values['fee_amount'] = (float) $values['fee_amount'];
        }
        if (!in_array($values['status'], $allowedStatuses, true)) {
            $errors['status'] = 'Trạng thái không hợp lệ.';
        }

        return ['values' => $values, 'errors' => $errors];
    }
}
