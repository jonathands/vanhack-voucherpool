<?php
Namespace VoucherPool\Entity;

abstract class DbMapper implements MapperInterface
{
    protected $db;
    protected $entityName;
    
    public function __construct($db) {
        $this->db = $db;
    }

    public function getEntityName()
    {
        return $this->entityName;
    }
}