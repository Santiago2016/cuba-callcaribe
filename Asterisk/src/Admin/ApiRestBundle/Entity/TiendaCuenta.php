<?php

namespace Admin\ApiRestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * TiendaCuenta
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TiendaCuenta
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
     * @ORM\Column(name="cuentaid", type="string", length=255)
     */
    private $cuentaid;

    /**
     * @var float
     *
     * @ORM\Column(name="saldo", type="float")
     */
    private $saldo;

    /**
     * @ORM\ManyToOne(targetEntity = "Users", inversedBy = "cuentas")
     * @ORM\JoinColumn(name="id_tienda", referencedColumnName="id", onDelete = "CASCADE")
     * @Assert\NotBlank(message="Debe seleccionar una tienda")
     */
    protected $tienda;
    
    /**
     * @var float
     * 
     * @ORM\Column(name="comision", type="float")
     */
    private $comision;
    
    function getTienda() {
        return $this->tienda;
    }

    function setTienda($tienda) {
        $this->tienda = $tienda;
    }

    function getComision() {
        return $this->comision;
    }

    function setComision($comision) {
        $this->comision = $comision;
    }
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set cuentaid
     *
     * @param string $cuentaid
     *
     * @return TiendaCuenta
     */
    public function setCuentaid($cuentaid)
    {
        $this->cuentaid = $cuentaid;

        return $this;
    }

    /**
     * Get cuentaid
     *
     * @return string
     */
    public function getCuentaid()
    {
        return $this->cuentaid;
    }

    /**
     * Set saldo
     *
     * @param float $saldo
     *
     * @return TiendaCuenta
     */
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;

        return $this;
    }

    /**
     * Get saldo
     *
     * @return float
     */
    public function getSaldo()
    {
        return $this->saldo;
    }
}

