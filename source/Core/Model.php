<?php

namespace Source\Core;

use PDO;
use PDOException;

abstract class Model
{

    protected $entity;

    private $massage;
    private $data;

    public function getMessage(): ?string
    {
        return $this->massage;
    }

    public function selectAll (): ?array
    {
        $conn = Connect::getInstance();
        $query = "SELECT * FROM {$this->entity}";
        return $conn->query($query)->fetchAll();
    }

    public function selectById (int $id): ?object
    {
        $conn = Connect::getInstance();
        $query = "SELECT * 
                  FROM {$this->entity}
                  WHERE id = {$id}";
        return $conn->query($query)->fetch();
    }

    /**
     * Método para buscar registros com condições personalizadas
     * @param string|null $terms Condições WHERE da consulta
     * @param string|null $params Parâmetros para bind
     * @param string $columns Colunas a serem retornadas
     * @return Model
     */
    public function find(string $terms = null, string $params = null, string $columns = "*"): Model
    {
        $conn = Connect::getInstance();
        
        $query = "SELECT {$columns} FROM {$this->entity}";
        if ($terms) {
            $query .= " WHERE {$terms}";
        }
        
        try {
            $stmt = $conn->prepare($query);
            
            if ($params) {
                parse_str($params, $paramArray);
                foreach ($paramArray as $key => $value) {
                    $type = is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
                    $stmt->bindValue(":{$key}", $value, $type);
                }
            }
            
            $stmt->execute();
            $this->data = $stmt->fetchAll();
            return $this;
            
        } catch (PDOException $exception) {
            $this->massage = "Erro ao consultar: {$exception->getMessage()}";
            return $this;
        }
    }
    
    /**
     * Retorna os resultados da consulta
     * @param bool $all Retorna todos os registros se true, apenas o primeiro se false
     * @return array|object|null
     */
    public function fetch(bool $all = false)
    {
        if (!$this->data) {
            return null;
        }
        
        if ($all) {
            return $this->data;
        }
        
        return $this->data[0] ?? null;
    }

    public function insert(): ?int
    {
        $values = get_object_vars($this);// pegar os valores dos atributos e inserir em um arra
        array_shift($values);
        array_shift($values);

        foreach ($values as $key => $value){
            echo "{$value} => {$key} <br>";
            $values[$key] = is_null($value) ? "NULL" : "'{$value}'";
        }

        $valuesString = implode(",", $values);

        $conn = Connect::getInstance();
        $query = "INSERT INTO {$this->entity} VALUES ({$valuesString})";

        try {
            $result = $conn->query($query);
            $this->massage = "Registro inserido com sucesso!";
            return $result ? $conn->lastInsertId() : null;
        } catch (PDOException $exception) {
            $this->massage = "Erro ao inserir: {$exception->getMessage()}";
            return false;
        }
    }
}