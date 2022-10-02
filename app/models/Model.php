<?php

namespace App\models;

use App\db\Database;
use Exception;
use ReflectionClass;

class Model
{
    public Database $ins_db;

    protected string $table;
    protected array $select = [];
    protected array $where = [];
    protected string $from = '';
    protected string $limit = '';
    protected array $orderBy = [];
    protected string $sql = '';
    protected string $typeSql = 'select';

    protected $data;
    protected $id;

    public function __construct($id = null)
    {
        $this->ins_db = Database::getInstance();
        $this->id = $id;

        if (empty($this->table)) {
            $this->table = strtolower((new ReflectionClass($this))->getShortName()) . 's';
        }
    }

    /**
     * Установка условия SELECT
     *
     * @param $fields
     * @return $this
     */
    public function select($fields): static
    {
        if (is_array($fields)) {
            $this->select = $fields;
        } elseif (is_string($fields) && func_num_args() == 1) {
            $this->select[] = $fields;
        } elseif (func_num_args() > 1) {
            $this->select[] = func_get_args();
        }

        return $this;
    }

    /**
     * Установка условия FROM
     *
     * @param $table
     * @return $this
     */
    public function from($table = null): static
    {
        $this->from = $table?? $this->table;
        return $this;
    }

    /**
     * Установка условия LIMIT
     *
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Установка условия ORDER BY
     *
     * @param array $orderBy
     * @return $this
     */
    public function orderBy(array $orderBy = []): static
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * Установка условия WHERE
     *
     * @param $field
     * @param $operator
     * @param $value
     * @return $this
     */
    public function where($field, $operator = '', $value = ''): static
    {
        if (func_num_args() == 2) {
            $this->where[] = $field . '=' . $this->ins_db->getInstanceDB()->real_escape_string($operator);
        } else {
            $operator = in_array($operator, ['!=', '>', '<', '>=', '<=']) ? $operator : '=';
            $this->where[] = $field . ' ' . $operator . ' ' . $this->ins_db->getInstanceDB()->real_escape_string($value);
        }
        return $this;
    }

    /**
     * Сохраниние модели
     *
     * @throws Exception
     */
    public function save()
    {
        if ($this->id) {
            $this->sql = 'UPDATE ' . $this->table . ' SET ';
            foreach ($this->data as $key => $val) {
                $this->sql .= $key . '=\'' . $val . '\',';
            }
            $this->sql = rtrim($this->sql, ',');

            $this->where('id', $this->id);
            $this->sql .= $this->wherePrepare();
            $this->typeSql = 'update';
            return $this->execute();
        } else {
            if (count($this->data)) {
                $keys = array_keys($this->data);
                $values = array_values($this->data);

                $this->sql = 'INSERT INTO ' . $this->table;

                $this->sql .= ' (' . implode(',', $keys) . ') ';

                $this->sql .= 'VALUES (';

                foreach ($values as $val) {
                    $this->sql .= '\'' . $val . '\'' . ',';
                }
                $this->sql = rtrim($this->sql, ',') . ')';
                $this->typeSql = 'insert';

                return $this->execute();
            }
        }
    }

    /**
     * Удаление модели
     *
     * @param int $id
     * @return array|int|void
     * @throws Exception
     */
    public function destroy(int $id)
    {
        if ($this->id) {
            $this->where('id', $this->id);
            $this->sql = 'DELETE FROM ' . $this->table;
            $this->sql .= $this->wherePrepare();
            $this->typeSql = 'delete';

            return $this->execute();
        }
    }

    /**
     * Подготовка строки FROM
     *
     * @return string
     */
    public function fromPrepare(): string
    {
        return ' FROM ' . $this->from;
    }

    /**
     * Подготовка строки ORDER BY
     *
     * @return string
     */
    public function orderByPrepare(): string
    {
        $str = '';

        if (!empty($this->orderBy)) {
            $str .= sprintf(
                ' ORDER BY `%s` %s',
                array_key_first($this->orderBy),
                strtoupper($this->orderBy[array_key_first($this->orderBy)])
            );
        }

        return $str;
    }

    /**
     * Подготовка строки LIMIT
     *
     * @return string
     */
    public function limitPrepare(): string
    {
        return !empty($this->limit) ? ' LIMIT ' . $this->limit : '';
    }

    /**
     * Подготовка строки SELECT
     *
     * @return string
     */
    public function selectPrepare(): string
    {
        return 'SELECT ' . implode(',', $this->select);
    }

    /**
     * Подготовка строки WHERE
     *
     * @return string
     */
    public function wherePrepare(): string
    {
        $str = '';
        if (count($this->where) > 0) {
            $i = 0;
            foreach ($this->where as $key => $val) {
                if ($i == 0) {
                    $str .= ' WHERE ' . $val;
                }
                if ($i > 0) {
                    $str .= ' AND ' . $val;
                }
                $i++;
            }
        }
        return $str;
    }

    /**
     * Метод сборки строки запроса в БД
     *
     * @return array|int|void
     * @throws Exception
     */
    public function get()
    {
        $this->sql = $this->selectPrepare()
            . $this->fromPrepare()
            . $this->wherePrepare()
            . $this->orderByPrepare()
            . $this->limitPrepare();
        $this->typeSql = 'select';

        if ($this->sql) {
            return $this->execute();
        }
    }

    /**
     * Метод запроса в БД
     *
     * @throws Exception
     */
    protected function execute(): int|array
    {
        if ($this->sql) {
            switch ($this->typeSql) {
                case 'select':
                    $result = $this->ins_db->query($this->sql);
                    if (!$result) {
                        throw new Exception('Ошибка запроса');
                    }
                    if ($result->num_rows == 0) {
                        return false;
                    }
                    $row = [];
                    for ($i = 0; $i < $result->num_rows; $i++) {
                        $row[] = $result->fetch_assoc();
                    }
                    return $row;
                case 'delete':
                case 'update':
                    $result = $this->ins_db->query($this->sql);

                    if (!$result) {
                        throw new Exception('Ошибка запроса');
                    }
                    return true;

                case 'insert':
                    $result = $this->ins_db->query($this->sql);
                    if (!$result) {
                        throw new Exception('Ошибка запроса');
                    }

                    return $this->ins_db->getInstanceDB()->insert_id;
            }
        }
        return false;
    }

    /**
     * Статический вызов методов all и find для получения всех и конкретной модели соответственно
     *
     * @throws Exception
     */
    public static function __callStatic($name, $arguments)
    {
        switch ($name) {
            case 'all':
                $obj = new static;
                return $obj->select(['*'])->from()->orderBy($arguments[0])->get();
            case 'find':
                $obj = new static;
                return $obj->select(['*'])->from()->where('id', $arguments[0])->get();
            default:
                throw new Exception('Метод не существует');
        }
    }

    /**
     * магический метод получения свойств модели
     *
     * @param string $name
     * @return mixed|null
     */
    public function __get(string $name)
    {
        return array_key_exists($name, $this->data) ? $this->data[$name] : null;
    }

    /**
     *  Магический метод переопределения свойства модели
     *
     * @param string $name
     * @param $value
     * @return void
     */
    public function __set(string $name, $value)
    {
        $this->data[$name] = $value;
    }
}