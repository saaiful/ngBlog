<?php
/**
 * Database Handler
 * @author  Saiful Islam <[me@saiful.im>]>
 * @since 0.1
 */
if (count(get_included_files()) == 1) {
    exit("Direct access not permitted.");
}
class DB
{
    public function __construct()
    {
        try {
            $this->db = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=UTF8", DBUSER, DBPASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            throw new Exception("Connection failed: " . $e->getMessage(), 1);
        }
        $this->table = '';
        $this->reset();
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function where($column, $compare = "=", $value)
    {
        $this->where[] = [$column, $compare, $value];
        return $this;
    }

    public function orWhere($column, $compare = "=", $value)
    {
        $this->orWhere[] = [$column, $compare, $value];
        return $this;
    }

    public function orderBy($column, $order = "ASC")
    {
        $this->order = [$column, $order];
        return $this;
    }

    public function take($number)
    {
        $this->take = $number;
        return $this;
    }

    public function skip($number)
    {
        $this->skip = $number;
        return $this;
    }

    public function select($columns = [])
    {
        $this->select = implode(",", $columns);
        return $this;
    }

    public function join($table, $column, $id = '')
    {
        if (empty($id)) {
            $id = rtrim($table, 's') . "_id";
        }
        $this->join[] = [$table, $column, $id];
        return $this;
    }

    public function leftjoin($table, $column, $id = '')
    {
        if (empty($id)) {
            $id = rtrim($table, 's') . "_id";
        }
        $this->leftjoin[] = [$table, $column, $id];
        return $this;
    }

    public function pagination($display = 10)
    {
        $this->pagination = true;
        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $page = (($page - 1) < 0) ? 0 : ($page - 1);
        $this->page = $page;
        $this->skip($page * $display);
        $this->take($display);
    }

    public function condition($query)
    {
        if (empty($this->table)) {
            throw new Exception("Empty Table Name.", 1);
        }
        $join = [];
        foreach ($this->join as $key => $value) {
            $join[] = "JOIN {$value[0]} ON {$this->table}.{$value[1]} = {$value[0]}.{$value[2]} ";
        }
        foreach ($this->leftjoin as $key => $value) {
            $join[] = "LEFT JOIN {$value[0]} ON {$this->table}.{$value[1]} = {$value[0]}.{$value[2]} ";
        }
        $query .= implode(" ", $join);

        $where = [];
        foreach ($this->where as $key => $value) {
            if ($key == 0) {
                $where[] = "WHERE `" . $value[0] . "`" . $value[1] . "?";
            } else {
                $where[] = " `" . $value[0] . "`" . $value[1] . "?";
            }
            $this->param[] = $value[2];
        }
        $query .= implode(" AND ", $where);

        if (count($this->order) != 0) {
            $query .= " ORDER BY " . $this->order[0] . " " . $this->order[1];
        }

        if ($this->pagination) {
            $this->pQuery = $query;
            $this->pParam = $this->param;
        }

        if (!empty($this->take)) {
            $query .= " LIMIT ? ";
            $this->param[] = intval($this->take);
            if (!empty($this->skip)) {
                $query .= " OFFSET ? ";
                $this->param[] = intval($this->skip);
            }
        }
        $this->query = $query;
        return $this;
    }

    public function debug()
    {
        $this->build();
        var_dump($this->query);
    }
    public function build()
    {
        $query = "SELECT {$this->select} FROM {$this->table} ";
        $this->condition($query);
        return $this;
    }

    public function insert($datas = [])
    {
        $index = $value = [];
        foreach ($datas as $key => $data) {
            $index[] = $key;
            $value[] = "?";
            $this->param[] = $data;
        }
        $this->query = "INSERT INTO $this->table (" . implode(",", $index) . ") VALUES (" . implode(",", $value) . ")";
        try {
            $this->query = $this->db->prepare($this->query);
            foreach ($this->param as $a => $b) {
                if (is_integer($b)) {
                    $this->query->bindValue($a + 1, (int) $b, PDO::PARAM_INT);
                } else {
                    $this->query->bindValue($a + 1, $b, PDO::PARAM_STR);
                }
            }
            $this->query->execute();
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function update($datas = [])
    {
        $query = "UPDATE {$this->table} SET ";
        $update = [];
        $datas['updated_at'] = date("Y-m-d H:i:s");
        foreach ($datas as $key => $value) {
            $update[] = " {$key}=? ";
            $this->param[] = $value;
        }
        $query .= implode(",", $update);
        $this->condition($query);
        $this->query = $this->db->prepare($this->query);
        foreach ($this->param as $a => $b) {
            if (is_integer($b)) {
                $this->query->bindValue($a + 1, (int) $b, PDO::PARAM_INT);
            } else {
                $this->query->bindValue($a + 1, $b, PDO::PARAM_STR);
            }
        }
        $this->query->execute();
        return $this->query;
    }

    public function delete()
    {
        $query = "DELETE FROM {$this->table} ";
        $this->condition($query);
        $this->query = $this->db->prepare($this->query);
        foreach ($this->param as $a => $b) {
            if (is_integer($b)) {
                $this->query->bindValue($a + 1, (int) $b, PDO::PARAM_INT);
            } else {
                $this->query->bindValue($a + 1, $b, PDO::PARAM_STR);
            }
        }
        $this->query->execute();
        return $this->query;
    }

    public function reset()
    {
        $this->pagination = false;
        $this->where = [];
        $this->orWhere = [];
        $this->take = '';
        $this->skip = '';
        $this->select = '*';
        $this->order = [];
        $this->param = [];
        $this->join = [];
        $this->leftjoin = [];
    }

    public function find($id)
    {
        $this->reset();
        $this->where('id', "=", $id);
        $this->build();
        $this->query = $this->db->prepare($this->query);
        foreach ($this->param as $a => $b) {
            if (is_integer($b)) {
                $this->query->bindValue($a + 1, (int) $b, PDO::PARAM_INT);
            } else {
                $this->query->bindValue($a + 1, $b, PDO::PARAM_STR);
            }
        }
        $this->query->execute();
        return $this->query->fetch(PDO::FETCH_ASSOC);
    }

    public function all($columns = [])
    {
        if (count($columns) != 0) {
            $this->select($columns);
        }
        $this->where = [];
        $this->build();
        $this->query = $this->db->prepare($this->query);
        foreach ($this->param as $a => $b) {
            if (is_integer($b)) {
                $this->query->bindValue($a + 1, (int) $b, PDO::PARAM_INT);
            } else {
                $this->query->bindValue($a + 1, $b, PDO::PARAM_STR);
            }
        }
        $this->query->execute();
        return $this->query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get($columns = [])
    {
        if (count($columns) != 0) {
            $this->select($columns);
        }
        $this->build();
        $this->query = $this->db->prepare($this->query);
        foreach ($this->param as $a => $b) {
            if (is_integer($b)) {
                $this->query->bindValue($a + 1, (int) $b, PDO::PARAM_INT);
            } else {
                $this->query->bindValue($a + 1, $b, PDO::PARAM_STR);
            }
        }
        $this->query->execute();
        if ($this->pagination) {
            $this->query1 = $this->db->prepare($this->pQuery);
            $this->query1->execute($this->pParam);
            $count = $this->query1->rowCount();
            return ['currentPage' => $this->page + 1, 'totalItems' => $count, 'totalPages' => ceil($count / $this->take), 'data' => $this->query->fetchAll(PDO::FETCH_ASSOC)];
        }
        return $this->query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function first($columns = [])
    {
        if (count($columns) != 0) {
            $this->select($columns);
        }
        $this->build();
        $this->query = $this->db->prepare($this->query);
        $this->query->execute($this->param);
        return $this->query->fetch(PDO::FETCH_ASSOC);
    }

    public function last($columns = [])
    {
        if (count($columns) != 0) {
            $this->select($columns);
        }
        $this->orderBy('id', 'DESC');
        $this->build();
        $this->query = $this->db->prepare($this->query);
        $this->query->execute($this->param);
        return $this->query->fetch(PDO::FETCH_ASSOC);
    }
}
