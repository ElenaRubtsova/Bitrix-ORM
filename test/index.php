<?

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Entity,
    Bitrix\Main\Entity\Query,
    Bitrix\Main\ORM\Fields\FloatField,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField;

// создаем объект Query. В качестве параметра он принимает объект сущности, относительно которой мы строим запрос
$subQuery = new Query(ORM\TaskTable::getEntity());
$subQuery
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
            'expression' => ['IF(%s=\'P\',SUM(%s),0)', 'status.CODE', 'PRICE'],
        )
    )
    ->registerRuntimeField("sum(task_PRICE)_F", array(
            "data_type" => "float",
            'expression' => ['IF(%s=\'F\',SUM(%s),0)', 'status.CODE', 'PRICE'],
        )
    )
    ->setSelect(array("CLIENT_ID", "sum(task_PRICE)_P", "sum(task_PRICE)_F", "cnt(task_ID)"))
    ->setGroup(array("CLIENT_ID", "STATUS_ID"))
    ->setFilter(array("=CLIENT_ID" => 1))
    ->setOrder(array("CLIENT_ID" => "ASC"))
    ->setLimit(10);

$result = $subQuery->exec();
var_dump($result->fetchAll());

$outerQuery = new Query(Bitrix\Main\ORM\Entity::getInstanceByQuery($subQuery));
$outerQuery
    ->registerRuntimeField("cnt(task_ID)_all", array(
            "data_type" => "integer",
            "expression" => array("SUM(%s)", "cnt(task_ID)"),
        )
    )
    ->registerRuntimeField("sum(task_PRICE)_F_all", array(
            "data_type" => "float",
            'expression' => ['SUM(%s)', 'sum(task_PRICE)_F'],
        )
    )
    ->registerRuntimeField("sum(task_PRICE)_P_all", array(
            "data_type" => "float",
            'expression' => ['SUM(%s)', 'sum(task_PRICE)_P'],
        )
    )
    ->registerRuntimeField("client", array(
            "data_type" => "ORM\ClientTable",
            'reference' => [
                '=this.CLIENT_ID' => 'ref.ID',
            ],
            'join_type' => 'INNER',
        )
    )
    ->setGroup(array("CLIENT_ID", "client.NAME"))
    ->setSelect(array(
        'ID клиента' => "CLIENT_ID",
        'Имя клиента' => "client.NAME",
        'Сумма по задачам в статусе "Выполнено"' => "sum(task_PRICE)_F_all",
        'Сумма по задачам в статусе "В процессе"' => "sum(task_PRICE)_P_all",
        'Общее количество задач клиента' => "cnt(task_ID)_all",
    ));
$result = $outerQuery->exec();
var_dump($result->fetchAll());

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>