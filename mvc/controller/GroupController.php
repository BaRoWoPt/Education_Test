<?php
require_once '../model/Group.php';

class GroupController
{
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tennhom = $_POST['tennhom'];
            $siso = $_POST['siso'];
            $giangvien = $_POST['giangvien'];
            $mamonhoc = $_POST['mamonhoc'];
            $students = $_POST['students'];

            if (Group::createGroup($tennhom, $siso, $giangvien, $mamonhoc, $students)) {
                header("Location: /public/index.php");
            } else {
                echo "Lỗi khi tạo nhóm.";
            }
        }
    }

    public function showAddForm()
    {
        include '../views/add_group.php';
    }
}