<?php
Namespace VoucherPool\Entity;

interface MapperInterface
{
    public function save($entity);
    public function delete($entity);

    public function getOne($id);
    public function getList();
}