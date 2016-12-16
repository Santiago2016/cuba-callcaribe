<?php

namespace Admin\ApiRestBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Recarga
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Recarga
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="tienda", type="string", length=255)
     */
    private $tienda;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
    
    /**
     * @ORM\ManyToOne(targetEntity = "Users", inversedBy = "recargas")
     * @ORM\JoinColumn(name="id_admin", referencedColumnName="id", onDelete = "CASCADE")
     * @Assert\NotBlank(message="Debe seleccionar un admin")
     */
    protected $admin;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    function getAdmin() {
        return $this->admin;
    }

    function setAdmin($admin) {
        $this->admin = $admin;
    }

    /**
     * Set tienda
     *
     * @param string $tienda
     *
     * @return Recarga
     */
    public function setTienda($tienda)
    {
        $this->tienda = $tienda;

        return $this;
    }

    /**
     * Get tienda
     *
     * @return string
     */
    public function getTienda()
    {
        return $this->tienda;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return Recarga
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Recarga
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}

