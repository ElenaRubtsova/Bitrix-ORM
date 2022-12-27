<?

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Entity,
    Bitrix\Main\ORM\Fields\FloatField,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField;

//$task = new ORM\TaskTable();
//echo $task->getTableName();
//echo $task->get();
//echo('1');
//\Pixelplus\OrmAnnotations\Handlers::registerHandler();

// создаём сущность для нашего запроса, в которой будет храниться результат
$fields = array(
    new IntegerField(
        'ID', [
            'title' => 'ID клиента',
        ]
    ),
    new StringField(
        'NAME', [
            'title' => 'Имя клиента',
        ]
    ),
    new FloatField(
        'SUM_F', [
            'title' => 'Сумма по задачам в статусе "Выполнено"',
        ]
    ),
    new FloatField(
        'SUM_P', [
            'title' => 'Сумма по задачам в статусе "В процессе"',
        ]
    ),
    new IntegerField(
        'COUNT', [
            'title' => 'Общее количество задач клиента',
        ]
    ),
);
$entity = Bitrix\Main\ORM\Entity::compileEntity(
    "ResultTable",
    $fields, [
        'namespace' => 'AlexeyGfi',
        'table_name' => $dbTableName,
    ]
);

// создаем объект Query. В качестве параметра он принимает объект сущности, относительно которой мы строим запрос
$query = new Entity\Query(ORM\TaskTable::getEntity());
$query
    ->registerRuntimeField("client", array(
            "data_type" => "ORM\ClientTable",
            'reference' => [
                '=this.CLIENT_ID' => 'ref.ID',
            ],
            'join_type' => 'INNER',
        )
    )
    ->registerRuntimeField("status", array(
            "data_type" => "ORM\StatusTable",
            'reference' => array(
                '=this.STATUS_ID' => 'ref.ID',
            ),
            'join_type' => 'INNER',
        )
    )
    ->registerRuntimeField("cnt(task_ID)", array(
            "data_type" => "integer",
            "expression" => array("COUNT(%s)", "ID"),
        )
    )
    ->registerRuntimeField("sum(task_PRICE)_P", array(
            "data_type" => "float",
            'expression' => ['IF(%s=\'P\',SUM(%s),0)','status.CODE' ,'PRICE']
        )
    )
    ->registerRuntimeField("sum(task_PRICE)_F", array(
            "data_type" => "float",
            'expression' => ['IF(%s=\'F\',SUM(%s),0)','status.CODE' ,'PRICE']
        )
    )
    ->setSelect(array("CLIENT_ID", "client.NAME", "sum(task_PRICE)_P", "sum(task_PRICE)_F", "cnt(task_ID)"/*, "STATUS_ID", "status.NAME"*/))
    ->setGroup(array("CLIENT_ID","STATUS_ID","client.NAME"))
    ->setFilter(array("=CLIENT_ID" => 1))
    ->setOrder(array("CLIENT_ID" => "ASC"))
    ->setLimit(10);
//query2
//->setGroup(array("CLIENT_ID","STATUS_ID","client.NAME","sum(task_PRICE)_P","sum(task_PRICE)_F","cnt(task_ID)"))
//т.к. здесь оно будет уже не вычислчяемым SUM("sum(task_PRICE)_F")
$subQuery
    ->registerRuntimeField("sum(task_PRICE)_F_all", array(
            "data_type" => "float",
            'expression' => ['SUM(%s)', 'sum(task_PRICE)_F']
        )
    )
    ->registerRuntimeField("sum(task_PRICE)_P_all", array(
            "data_type" => "float",
            'expression' => ['SUM(%s)', 'sum(task_PRICE)_P']
        )
    )
;
$result = $query->exec();
var_dump($result->fetchAll());

//$query->dump();
/*
//problem in COUNT()
$taskList = TaskTable::getList(
    array(
        'select' => array('COUNT(ID)', 'CLIENT_ID','SUM(PRICE)'), // имена полей, которые необходимо получить
        //'filter' => array('=ID' => 1), // описание фильтра для WHERE и HAVING
        'group' => array('CLIENT_ID'), // явное указание полей, по которым нужно группировать результат
        //'order' => array('CLIENT_ID' => 'DESC', 'ID' => 'ASC'), // параметры сортировки
        'limit' => 10, // количество записей
        'offset' => 0, // смещение для limit
        //'runtime' => array(new Entity\ExpressionField('CNT', 'COUNT(*)')), // динамически определенные поля
    ),
);
var_dump($taskList);
*/
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
        <?
    while ($row = $results->Fetch()) { ?>
            <tr>
                <td><?= $row['ID'] ?></td>
                <td><?= $row['NAME'] ?></td>
                <td><?= $row['sum_p'] ?></td>
                <td><?= $row['sum_f'] ?></td>
                <td><?= $row['TASK_COUNT'] ?></td>
            </tr>
        <?
    } ?>
    </table>-->
<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>