<?php
namespace VoucherPool\Entity;

use VoucherPool\Utils\JsonSerializeTrait;

class CustomerEntity implements \JsonSerializable
{
    use JsonSerializeTrait;
    
    protected $id;
    protected $name;
    protected $email;
    protected $usedVouchers;
    protected $createdAt;
    
    public function __construct(array $data)
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }

        $this->name = $data['name'];
        $this->email = $data['email'];

        if (!isset($data['created_at'])) {
            $data['created_at'] = new \DateTime();
        } else {
            $this->createdAt = $data['created_at'];
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt =  \DateTime::createFromFormat("d/m/Y", $createdAt);

        return $this;
    }

}