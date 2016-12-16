<?php

namespace Admin\ApiRestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Admin\ApiRestBundle\Entity\TiendaCuenta;
use Admin\ApiRestBundle\Entity\Recarga;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Users
 * @DoctrineAssert\UniqueEntity("username")
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Admin\ApiRestBundle\Entity\UsersRepository")
 */
class Users implements UserInterface, Serializable {

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
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255)
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255)
     */
    private $telefono;
    
    /**
     * @var string
     *
     * @ORM\Column(name="admin", type="string", length=255, nullable=true)
     */
    protected $admin;
    
    /**
     * @var string
     *
     * @ORM\Column(name="admintiendas", type="string", length=2048, nullable=true)
     */
    protected $admintiendas;
    
    /**
     * @var string
     *
     * @ORM\Column(name="clientes", type="string", length=2048, nullable=true)
     */
    protected $clientes;
    
    /**
     * @var string
     *
     * @ORM\Column(name="clientetiendas", type="string", length=2048, nullable=true)
     */
    protected $clientetiendas;
    
    /**
     * @ORM\OneToMany(targetEntity="TiendaCuenta", mappedBy="tienda")
     */
    protected $cuentas;
    
    /**
     * @ORM\OneToMany(targetEntity="Recarga", mappedBy="admin")
     */
    protected $recargas;
    
    public function __construct() {
        $this->cuentas = new ArrayCollection();
        $this->recargas = new ArrayCollection();
    }
    
    /**
     * Add cuentas
     *
     * @param \Admin\ApiRestBundle\Entity\TiendaCuenta $cuenta
     * @return Users
     */
    public function addCuentas(TiendaCuenta $cuenta){
        $this->cuentas[] = $cuenta;
        return $this;
    }
    
    /**
     * Remove cuentas
     *
     * @param \Admin\ApiRestBundle\Entity\TiendaCuenta $cuenta
     */
    public function removeCuentas(TiendaCuenta $cuenta) {
        $this->cuentas->removeElement($cuenta);
    }
    
    /**
     * Get cuentas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCuentas() {
        return $this->cuentas;
    }
    
    /**
     * Add recargas
     *
     * @param \Admin\ApiRestBundle\Entity\Recarga $recarga
     * @return Users
     */
    public function addRecargas(Recarga $recarga){
        $this->recargas[] = $recarga;
        return $this;
    }
    
    /**
     * Remove recargas
     *
     * @param \Admin\ApiRestBundle\Entity\Recarga $recarga
     */
    public function removeRecargas(Recarga $recarga) {
        $this->recargas->removeElement($recarga);
    }
    
    /**
     * Get recargas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecargas() {
        return $this->recargas;
    }
    
    function getAdmin() {
        return $this->admin;
    }

    function setAdmin($admin) {
        $this->admin = $admin;
    }
    
    function getAdmintiendas() {
        return $this->admintiendas;
    }

    function getClientes() {
        return $this->clientes;
    }

    function getClientetiendas() {
        return $this->clientetiendas;
    }

    function setAdmintiendas($admintiendas) {
        $this->admintiendas = $admintiendas;
    }

    function setClientes($clientes) {
        $this->clientes = $clientes;
    }

    function setClientetiendas($clientetiendas) {
        $this->clientetiendas = $clientetiendas;
    }

        
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Users
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Users
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Users
     */
    public function setRole($role) {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize() {
        // TODO: Implement serialize() method.
        return serialize(array(
            $this->getId()
        ));
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized) {
        // TODO: Implement unserialize() method.
        list(
                $this->id
                ) = unserialize($serialized);
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles() {
        // TODO: Implement getRoles() method.
        return array($this->getRole());
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt() {
        // TODO: Implement getSalt() method.
        return false;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials() {
        // TODO: Implement eraseCredentials() method.
        return false;
    }

    public function equals(Users $user) {
        if ($this->username == $user->getUsername() && $this->password == $user->getPassword()) {
            return true;
        }
        return false;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getEmail() {
        return $this->email;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function __toString() {
        return $this->username;
    }
}
