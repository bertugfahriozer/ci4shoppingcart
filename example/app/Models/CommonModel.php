<?php namespace App\Models;

use CodeIgniter\Model;

class CommonModel
{
    protected $db;

    public function __construct($group='default')
    {
        $this->db=\Config\Database::connect($group);
    }

    public function lists($table,string $select='*',array $where=[])
    {
        $builder=$this->db->table($table);
        return $builder->select($select)->where($where)->get()->getResult();
    }

    public function create($table, $data = [])
    {
        $builder=$this->db->table($table);
        return$builder->insert($data);
    }

    public function edit($table, $data = [], $where = [])
    {
        $builder=$this->db->table($table);
        return $builder ->where($where)-> update($data);
    }

    public function remove($table, $where = [])
    {
        $builder=$this->db->table($table);
        return $builder ->where($where)-> delete();
    }

    public function selectOne($table,$where = [], $select = '*')
    {
        $builder=$this->db->table($table);
        return $builder -> select($select) -> where($where)-> get()->getRow();
    }

    public function whereInCheckData($table,$att, array $where = [])
    {
        $builder=$this->db->table($table);
        return $builder -> whereIn($att, $where,1) ->get()-> getNumRows();

    }

    public function isHave($table,$where)
    {
        $builder=$this->db->table($table);
        return $builder -> getWhere($where,1)->getNumRows();
    }

    public function count($table, $where = [])
    {
        $builder=$this->db->table($table);
        return $builder -> where($where) -> countAllResults();
    }

    public function research($table,$like = [], $select = '*', $where = [])
    {
        $builder=$this->db->table($table);
        return $builder -> select($select) -> where($where) -> like($like) -> get() -> getResult();
    }
}