<?php
require_once 'models/ClassModel.php';

class ClassController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new ClassModel($db);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tennhom = $_POST['tennhom'];
            $siso = $_POST['siso'];
            $giangvien = $_POST['giangvien'];
            $mamonhoc = $_POST['mamonhoc'];
            $students = $_POST['students'];

            if ($this->model->createClass($tennhom, $siso, $giangvien, $mamonhoc, $students)) {
                header("Location: index.php");
            } else {
                echo "Có lỗi xảy ra khi tạo nhóm học phần.";
            }
        }
    }
}