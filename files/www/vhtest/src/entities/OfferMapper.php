<?php
Namespace VoucherPool\Entity;

class OfferMapper extends DbMapper
{    
    protected $entityName = "voucherpool.offer";  

    public function save($entity)
    {
        try
        {   
            if(!$entity->getId())
            {
                $sql = " INSERT INTO ".$this->getEntityName()." ".
                    "  (name, discount,expires_at) ".
                    " VALUES ".
                    "  (:name, :discount,:expires_at) ".
                    " RETURNING offer_id";

                $stmt = $this->db->prepare($sql);
                
                $stmt->execute([
                    "name" => $entity->getName(),
                    "discount" => $entity->getDiscount(),
                    "expires_at" => $entity->getExpiresAt()->format("d/m/Y")
                ]);

                $result = $stmt->fetch(\PDO::FETCH_ASSOC);
                $entity->setId($result['offer_id']);
            }
            else
            {
                $sql = " UPDATE ".$this->getEntityName()." SET ".
                    "  name = :name, ".
                    "  discount = :discount, ".
                    "  expires_at = :expires_at ".
                    " WHERE ".
                    "  offer_id = :id ";

                $stmt = $this->db->prepare($sql);
                
                $result = $stmt->execute([
                    "id" => $entity->getId(),
                    "name" => $entity->getName(),
                    "discount" => $entity->getDiscount(),
                    "expires_at" => $entity->getExpiresAt()->toFormat("d/m/Y")
                ]);            

            }

            if(!$result) {
                throw new \Exception("could not save record");
            }

            return $entity;
        }
        catch (\PDOException $e) 
        {
            if ($e->getCode() == 23505) {
                throw new \Exception("Voucher code already exists");
            } else {
                throw $e;
            }
        }
    }

    public function delete($entity)
    {
        if($entity->getId())
        {
            $sql = " DELETE FROM  ".$this->getEntityName() .
                        " WHERE offer_id = :id ";

            $stmt = $this->db->prepare($sql);
            
            $result = $stmt->execute([
                "id" => $entity->getId()
            ]);

            return $result;
        } 
        else
        {
            throw new \Exception("Voucher does not exist");
        }
    }

    public function getOneByFilter($filter)
    {
        $sql = $this->getSelect($filter);

        $stmt = $this->db->prepare($sql);
    
        $stmt->execute($filter);
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if($result)
            $result = new OfferEntity($result);

        return $result;
    }

    public function getListByFilter($filter)
    {
        $sql = $this->getSelect($filter);

        $stmt = $this->db->prepare($sql);
    
        $stmt->execute($filter);
 
        $results = [];
        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $results[] = new OfferEntity($row);
        }
        return $results;
    }

    public function getOne($id)
    {
        $sql = $this->getSelect($id);

        $stmt = $this->db->prepare($sql);
    
        $stmt->execute([
            "id" => $id
        ]);
 
        $row = $stmt->fetch();
        
        $result = new OfferEntity($row);

        return $result;
    }

    public function getList()
    {
        $sql = $this->getSelect();

        $stmt = $this->db->query($sql);
        $results = [];
        while($row = $stmt->fetch()) {
            $results[] = new OfferEntity($row);
        }
        return $results;
    }

    private function getSelect($filter=false)
    {
        $sql = "SELECT o.offer_id, o.name, o.discount, o.expires_at ".
                " FROM ".$this->getEntityName()." o ";

        if(is_array($filter))
        {
            $i = 0;
            foreach($filter as $k => $v)
            {
                if(!$i)
                    $sql .= " WHERE o.".$k." = :$k";
                else
                    $sql .= " o.".$k." = :$k";
                $i++;
            }
        }else{            
            if($filter)
                $sql .= " WHERE o.offer_id = :id";
        }
        
        $sql .= " GROUP BY offer_id, o.name, o.discount, o.expires_at".
                " ORDER BY offer_id DESC ";

        return $sql;
    }
}