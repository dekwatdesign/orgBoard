
<?php

function generateSQLInsert($table_name, $ins_arr)
{
    $col_arr = [];
    $val_arr = [];
    foreach ($ins_arr as $ins_key => $ins_val) {
        $col_arr[] = $ins_key;
        if (strlen($ins_val) == 0) {
            $val_arr[] = " null ";
        } else {
            $val_arr[] = " '$ins_val' ";
        }
    }
    $col_str = implode(',', $col_arr);
    $val_str = implode(',', $val_arr);
    $final_sql = "INSERT INTO $table_name ($col_str) VALUES ($val_str);";
    return $final_sql;
}