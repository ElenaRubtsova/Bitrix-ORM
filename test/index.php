<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
use ORM\TaskTable;
$obj = new TaskTable();
echo $obj->getTableName();
//var_dump($obj->getMap());
//$map = TaskTable::getTableName();
//var_dump($map);
?>

<!--
global $DB;
$results = $DB->Query("SELECT client.ID, 
 client.NAME, 
 sum(IF(status.code = 'P', task.price, 0)) as sum_p,
 sum(IF(status.code = 'F', task.price, 0)) as sum_f,
 count(task.ID) TASK_COUNT
FROM px_task as task
LEFT JOIN px_client client ON task.CLIENT_ID = client.ID
LEFT JOIN px_task_status status ON task.STATUS_ID = status.ID
GROUP BY client.ID, client.NAME
");
?>
    <table>
        <tr>
            <th>ID клиента</th>
            <th>Название клиента</th>
            <th>Сумма по задачам в статусе "Выполнено"</th>
            <th>Сумма по задачам в статусе "В процессе"</th>
            <th>Общее количество задач клиента</th>
        </tr>
        <? while ($row = $results->Fetch()) { ?>
            <tr>
                <td><?= $row['ID'] ?></td>
                <td><?= $row['NAME'] ?></td>
                <td><?= $row['sum_p'] ?></td>
                <td><?= $row['sum_f'] ?></td>
                <td><?= $row['TASK_COUNT'] ?></td>
            </tr>
        <? } ?>
    </table>-->
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>