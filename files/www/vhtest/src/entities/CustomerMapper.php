<?php
Namespace VoucherPool\Entity;

class CustomerMapper extends DbMapper
{
    protected $entityName = "voucherpool.customer";

    public function save($entity)
    {
        try {
            if (!$entity->getId()) {
                $sql = " INSERT INTO ".$this->getEntityName()." ".
                    "  (name, email, created_at) ".
                    " VALUES ".
                    "  (:name,:email, :created_at) ".
                    " RETURNING customer_id";

                $stmt = $this->db->prepare($sql);
                
                $stmt->execute([
                    "name" => $entity->getName(),
                    "email" => $entity->getEmail(),
                    "created_at" => $entity->getCreatedAt()->format('d/m/Y')
                ]);

                $result = $stmt->fetch(\PDO::FETCH_ASSOC);
                $entity->setId($result['id']);
            } else {
                $sql = " UPDATE ".$this->getEntityName()." SET ".
                    "  name = :name, ".
                    "  email = :email, ".
                    "  created_at = :created_at ".
                    " WHERE ".
                    "  customer_id = :id ";

                $stmt = $this->db->prepare($sql);
                
                $result = $stmt->execute([
                    "id" => $entity->getId(),
                    "name" => $entity->getName(),
                    "email" => $entity->getEmail(),
                    "created_at" => $entity->getCreatedAt()->format('d/m/Y')
                ]);
            }

            if (!$result) {
                throw new Exception("could not save record");
            }

            return $entity;
        } catch (\PDOException $e) {
            if ($e->getCode() == 23505) {
                throw new \Exception("E-mail already used");
            } else {
                throw $e;
            }
        }
    }

    public function delete($entity)
    {
        if ($entity->getId()) {
            $sql = " DELETE FROM  ".$this->getEntityName() .
                   "  customer_id = :id ";

            $stmt = $this->db->prepare($sql);
            
            $result = $stmt->execute([
                "id" => $entity->getId()
            ]);
        }

        if (!$result) {
            throw new Exception("could not save record");
        }

    }

    public function getOneByFilter($filter)
    {
        $sql = $this->getSelect($filter);

        $stmt = $this->db->prepare($sql);
        $stmt->execute($filter);
 
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result) {
            $result = new CustomerEntity($result);
        }
            
        return $result;
    }

    public function getListByFilter($filter)
    {
        $sql = $this->getSelect($filter);

        $stmt = $this->db->prepare($sql);
        $stmt->execute($filter);
 
        $results = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $results[] = new CustomerEntity($row);
        }
        return $results;
    }

    public function getOne($id)
    {
        $sql = $this->getSelect($id);

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            "id" => $entity->getId()
        ]);
 
        $row = $stmt->fetch();

        $result = new CustomerEntity($row);
        return $result;
    }

    public function getList()
    {
        $sql = $this->getSelect();

        $stmt = $this->db->query($sql);
        
        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new CustomerEntity($row);
        }
        return $results;
    }

    private function getSelect($filter = false)
    {
        $sql = "SELECT c.customer_id AS id, c.name, c.email, TO_CHAR(c.created_at :: DATE,'dd/mm/yyyy')  as created_at ".
               " FROM ".$this->getEntityName()." c ";

        if (is_array($filter)) {
            $i = 0;
            foreach ($filter as $k => $v) {
                if (!$i) {
                    $sql .= " WHERE c.".$k." = :$k";
                } else {
                    $sql .= " c.".$k." = :$k";
                }
                $i++;
            }
        } else {
            if ($filter)
                $sql .= " WHERE c.customer_id = :id";
        }
        return $sql;
    }
}