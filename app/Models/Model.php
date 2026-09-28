<?php

abstract class Model {
    protected $table;
    protected $primaryKey = 'id';

    protected function db() {
        return db();
    }

    public function find($id) {
        $stmt = $this->db()->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function all($orderBy = 'id DESC') {
        $stmt = $this->db()->query("SELECT * FROM {$this->table} ORDER BY {$orderBy}");
        return $stmt->fetchAll();
    }

    public function where($conditions, $params = [], $orderBy = null, $limit = null) {
        $sql = "SELECT * FROM {$this->table} WHERE {$conditions}";
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function firstWhere($conditions, $params = []) {
        $results = $this->where($conditions, $params, null, 1);
        return $results ? $results[0] : null;
    }

    public function create($data) {
        $fields = array_keys($data);
        $placeholders = array_map(function($f) { return ":{$f}"; }, $fields);
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($data);
        return $this->db()->lastInsertId();
    }

    public function update($id, $data) {
        $sets = array_map(function($f) { return "{$f} = :{$f}"; }, array_keys($data));
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE {$this->primaryKey} = :_id";
        $data['_id'] = $id;
        $stmt = $this->db()->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->db()->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function count($conditions = '1=1', $params = []) {
        $stmt = $this->db()->prepare("SELECT COUNT(*) as cnt FROM {$this->table} WHERE {$conditions}");
        $stmt->execute($params);
        $res = $stmt->fetch();
        return (int)($res['cnt'] ?? 0);
    }
}
