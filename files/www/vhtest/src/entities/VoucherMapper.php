<?php
namespace VoucherPool\Entity;

class VoucherMapper extends DbMapper
{
    protected $entityName = "voucherpool.voucher";

    public function save($entity)
    {
        try {
            if (!$entity->getId()) {
                $sql = " INSERT INTO ".$this->getEntityName()." ".
                    "  (code, expires_at,offer_id,customer_id) ".
                    " VALUES ".
                    "  (:code, :expires_at, :offer_id, :customer_id) ".
                    " RETURNING voucher_id";

                $stmt = $this->db->prepare($sql);

                $stmt->execute([
                    "code" => $entity->getCode(),
                    "offer_id" => $entity->getOfferId(),
                    "customer_id" => $entity->getCustomerId(),
                    "expires_at" => $entity->getexpiresAt()->format('d/m/Y')
                ]);

                $result = $stmt->fetch(\PDO::FETCH_ASSOC);
                $entity->setId($result['voucher_id']);
            } else {
                $sql = " UPDATE ".$this->getEntityName()." SET ".
                    "  code = :code, ".
                    "  offer_id = :offer_id, ".
                    "  customer_id = :customer_id, ".
                    "  used_at = :used_at, ".
                    "  expires_at = :expires_at ".
                    " WHERE ".
                    "  voucher_id = :id ";

                $stmt = $this->db->prepare($sql);
                
                $result = $stmt->execute([
                    "id" => $entity->getId(),
                    "code" => $entity->getCode(),
                    "offer_id" => $entity->getOfferId(),
                    "customer_id" => $entity->getCustomerId(),
                    "used_at" => $entity->getUsedAt(),
                    "expires_at" => $entity->getexpiresAt()->format('d/m/Y')
                ]);
            }

            if (!$result) {
                throw new \Exception("could not save record");
            }

            return $entity;
        } catch (\PDOException $e) {
            if ($e->getCode() == 23505) {
                throw new \Exception("Voucher code already exists");
            } else {
                throw $e;
            }
        }
    }

    public function saveUsage($voucherEntity, $customerEntity)
    {
        try {
            $sql = " UPDATE ".$this->getEntityName()." SET ".
                   "  used_at = :used_at ".
                   " WHERE ".
                   "  voucher_id = :id ";

            $stmt = $this->db->prepare($sql);
            
            $stmt->execute([
                "id" => $voucherEntity->getId(),
                "used_at" => (new \DateTime())->format("d/m/Y")
            ]);

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function delete($entity)
    {
        if ($entity->getId()) {
            $sql = " DELETE FROM  ".$this->getEntityName() .
                        " WHERE voucher_id = :id ";

            $stmt = $this->db->prepare($sql);
            
            $result = $stmt->execute([
                "id" => $entity->getId()
            ]);

            return $result;
        } else {
            throw new \Exception("Voucher does not exist");
        }
    }

    public function getOneByFilter($filter)
    {
        $sql = $this->getSelect($filter);

        $stmt = $this->db->prepare($sql);
    
        $stmt->execute($filter);
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            $result = new VoucherEntity($result);
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
            $results[] = new VoucherEntity($row);
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
        
        $result = new VoucherEntity($row);

        return $result;
    }

    public function getList()
    {
        $sql = $this->getSelect();

        $stmt = $this->db->query($sql);
        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new VoucherEntity($row);
        }
        return $results;
    }

    private function getSelect($filter = false)
    {
        $sql = "SELECT v.voucher_id, v.code, v.offer_id, v.customer_id, TO_CHAR(v.used_at :: DATE,'dd/mm/yyyy')  as used_at ,TO_CHAR(v.expires_at :: DATE,'dd/mm/yyyy')  as expires_at , c.email , o.name as offer_name, o.discount ".
               " FROM ".$this->getEntityName()." v ".
               " LEFT JOIN voucherpool.customer c ON c.customer_id = v.customer_id ".
               " LEFT JOIN voucherpool.offer o ON o.offer_id = v.offer_id ";

        if (is_array($filter)) {
            $i = 0;
            foreach ($filter as $k => $v) {
                if (!$i) {
                    $sql .= " WHERE v.".$k." = :$k";
                } else {
                    $sql .= " v.".$k." = :$k";
                }
                $i++;
            }
        } else {
            if ($filter) {
                $sql .= " WHERE v.voucher_id = :id";
            }
        }
        
        $sql .= " GROUP BY v.voucher_id, v.code, v.expires_at, c.email, o.name, o.discount ".
                " ORDER BY v.voucher_id DESC ";

        return $sql;
    }
}