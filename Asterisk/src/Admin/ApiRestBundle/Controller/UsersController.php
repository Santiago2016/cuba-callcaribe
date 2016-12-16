<?php

namespace Admin\ApiRestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Admin\ApiRestBundle\Entity\Users;
use Admin\ApiRestBundle\Entity\TiendaCuenta;
use Exception;

class UsersController extends Controller {

    public function getUserAction($id) {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('ApiRestBundle:Users')->find($id);
        return array('user' => $users);
    }

    public function getUsersAction() {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('ApiRestBundle:Users')->findAll();
        return array('users' => $users);
    }

    public function deleteUserAction($id) {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('ApiRestBundle:Users')->find($id);
        if ($users->getRole() == 'ROLE_CLIENTE'){
            $tiendas = $em->getRepository('ApiRestBundle:Users')->findBy(array('role'=>'ROLE_TIENDA'));
            foreach ($tiendas as $t){
                $clientes = $t->getClientes();
                    $t->setClientes(str_replace(','.$users->getUsername().',', ',', $clientes));
                    $em->persist($t);
                    $em->flush();
            }
        }
        if ($users->getRole() == 'ROLE_TIENDA'){
            $admins = $em->getRepository('ApiRestBundle:Users')->findBy(array('role'=>'ROLE_ADMIN'));
            foreach ($admins as $a){
                $tiendas = $a->getAdmintiendas();
                    $a->setAdmintiendas(str_replace(','.$users->getUsername().',', ',', $tiendas));
                    $em->persist($a);
                    $em->flush();
            }
        }
        $em->remove($users);
        $em->flush();
        $output = array("nombre"=>$users->getNombre(), "email"=>$users->getEmail(), "telefono"=>$users->getTelefono(), "username"=>$users->getUsername(), "id"=>$users->getId(), "role"=>$users->getRole());           
        return array($output);
    }

    public function postUserAction(Request $request) {
        $json = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $users = new Users();
        $encoder = $this->get('security.encoder_factory')->getEncoder($users);
        $users->setUsername($json['username']);
        $users->setPassword($encoder->encodePassword($json['password'], ''));
        $users->setRole($json['role']);
        $users->setNombre($json['nombre']);
        $users->setEmail($json['email']);
        $users->setTelefono($json['telefono']);
        try {
            $prueba = $em->getRepository('ApiRestBundle:Users')->findBy(array('username'=>$json['username']));
            if ($prueba != null){
                throw new Exception('bla');
            }
            if ($users->getRole() == 'ROLE_CLIENTE') {
                $tienda = $em->getRepository('ApiRestBundle:Users')->find($json['user']);
                if (strlen($users->getClientetiendas()) > 0){
                    $users->setClientetiendas($users->getClientetiendas() . ',' . $tienda->getUsername());
                }else{
                    $users->setClientetiendas($tienda->getUsername());
                }
                $em->persist($users);
                $em->flush(); 
                if (strlen($tienda->getClientes()) > 0){
                    $tienda->setClientes($tienda->getClientes() . $users->getUsername() . ',');
                }else{
                    $tienda->setClientes(','.$users->getUsername().',');
                }
                $em->persist($tienda);
                $em->flush();
            } else if ($users->getRole() == 'ROLE_TIENDA') {
                $admin = $em->getRepository('ApiRestBundle:Users')->find($json['user']);
                $users->setAdmin($admin->getUsername());
                $em->persist($users);
                $em->flush();              
                $tiendacuenta = new TiendaCuenta();
                $tiendacuenta->setComision((float)$json['comision']);
                $tiendacuenta->setCuentaid($users->getUsername());
                $tiendacuenta->setSaldo((float)$json['saldoinicial']);
                $tiendacuenta->setTienda($users);
                $em->persist($tiendacuenta);
                $em->flush();
                if (strlen($admin->getAdmintiendas()) > 0){
                    $admin->setAdmintiendas($admin->getAdmintiendas() . $users->getUsername() . ',');
                }else{
                    $admin->setAdmintiendas(','.$users->getUsername().',');
                }
                $em->persist($admin);
                $em->flush();
            }
        } catch (Exception $e) {
            return null;
        }
        $ch = curl_init("http://a2billing.callcaribe.com:3000/sendSMS");
        curl_setopt($ch, CURLOPT_POST, true);
        $password = $json['password'];
        $telefono = $users->getTelefono();
        $username = $users->getUsername();
        $data = "{'to':'$telefono', 'message':'Su cuenta ha sido creada, su username es $username y su password es $password'}";
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//enviar el username y el password por sms
        $output = array("nombre"=>$users->getNombre(), "email"=>$users->getEmail(), "telefono"=>$users->getTelefono(), "username"=>$users->getUsername(), "id"=>$users->getId(), "role"=>$users->getRole());           
        return array($output);
    }

    public function putUserAction($id, Request $request) {
        $json = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('ApiRestBundle:Users')->find($id);
        $encoder = $this->get('security.encoder_factory')->getEncoder($users);
        $users->setUsername($json['username']);
        $users->setPassword($encoder->encodePassword($json['password'], ''));
        $users->setRole($json['role']);
        $users->setNombre($json['nombre']);
        $users->setEmail($json['email']);
        $users->setTelefono($json['telefono']);
        $em->persist($users);
        $em->flush();
        return array($users);
    }

}
