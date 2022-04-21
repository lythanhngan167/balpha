<?php
    function updateitemnav($host,$user,$pass, $name) {
        $link = mysqli_connect($host,$user,$pass, $name);
        $link->set_charset("utf8");

        $result = $link->query("SELECT id,params FROM wmspj_k2_categories");

        while($row = $result->fetch_row()){
            $json = json_decode($row[1]);
            $json->itemNavigation = 1;
            $string = json_encode($json);
            $sql = "UPDATE wmspj_k2_categories SET params = " . "'" . $string . "'" . " WHERE id = " . $row[0];
            $updateResult = $link->query("UPDATE wmspj_k2_categories SET params = " . "'" . $string . "'" . " WHERE id = " . $row[0]);
            if(!$updateResult) {
                echo 'Cập nhật lỗi' . $row[0];
                die();
            }
        }
        echo 'Cập nhật thành công.';
    }

    updateitemnav("localhost","root","root","bcavietnam7");
?>
