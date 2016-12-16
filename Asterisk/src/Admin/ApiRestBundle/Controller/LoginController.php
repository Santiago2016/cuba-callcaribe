<?php

namespace Admin\ApiRestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use Admin\ApiRestBundle\Entity\Users;

class LoginController extends Controller {

    public function loginUserAction(Request $request) {
        $json = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('ApiRestBundle:Users')->findAll();
        $user = new Users();
        $encoder = $this->get('security.encoder_factory')->getEncoder($user);
        $user->setUsername($json['username']);
        $user->setPassword($encoder->encodePassword($json['password'], ''));
        foreach ($users as $u) {
            if ($user->equals($u)) {
                $output = array("nombre" => $u->getNombre(), "email" => $u->getEmail(), "telefono" => $u->getTelefono(), "username" => $u->getUsername(), "id" => $u->getId(), "role" => $u->getRole());
                if ($u->getRole() == 'ROLE_TIENDA'){
                    $cuentas = $u->getCuentas();
                    $cuenta = $cuentas[0];
                    $output = array("nombre" => $u->getNombre(), "email" => $u->getEmail(), "telefono" => $u->getTelefono(), "username" => $u->getUsername(), "id" => $u->getId(), "role" => $u->getRole(), "saldo"=>$cuenta->getSaldo(), "comision"=>$cuenta->getComision());
                    return array("user" => $output);
                }
                return array("user" => $output);
            }
        }
        return array("user" => null);
    }

    public function forgotAction(Request $request) {
        $json = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('ApiRestBundle:Users')->findOneBy(array('username' => $json['username'], 'nombre' => $json['nombre'], 'email' => $json['email'], 'telefono' => $json['telefono']));
        if ($users != null) {
            $ch = curl_init("http://a2billing.callcaribe.com:3000/sendSMS");
            curl_setopt($ch, CURLOPT_POST, true);
            $password = $users->getUsername();
            $encoder = $this->get('security.encoder_factory')->getEncoder($users);
            $users->setPassword($encoder->encodePassword($users->getUsername(), ''));
            $em->persist($users);
            $em->flush();
            $telefono = $users->getTelefono();
            $data = "{'to':'$telefono', 'message':'Su password se ha reseteado, su nuevo password es $password'}";
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//mandar en un sms la contrasenna del usuario
            return array("status" => "success");
        }
        return array("status" => "failed");
    }

    public function logoutUserAction(Request $request) {
        return array("message" => "Loggged out");
    }

    public function updateUserAction(Request $request) {
        $json = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('ApiRestBundle:Users')->find($json['id']);
        $usernameviejo = $users->getUsername();
        $users->setUsername($json['username']);
        $users->setRole($json['role']);
        $users->setNombre($json['nombre']);
        $users->setEmail($json['email']);
        $users->setTelefono($json['telefono']);
        $em->persist($users);
        $em->flush();
        if ($users->getRole() == 'ROLE_CLIENTE' && $users->getUsername() != $usernameviejo){
            $tiendas = $em->getRepository('ApiRestBundle:Users')->findBy(array('role'=>'ROLE_TIENDA'));
            foreach ($tiendas as $t){
                $clientes = $t->getClientes();
                    $t->setClientes(str_replace(','.$usernameviejo.',', ','.$users->getUsername().',', $clientes));
                    $em->persist($t);
                    $em->flush();
            }
        }
        $output = array("nombre" => $users->getNombre(), "email" => $users->getEmail(), "telefono" => $users->getTelefono(), "username" => $users->getUsername(), "id" => $users->getId(), "role" => $users->getRole());
        return array($output);
    }

    public function updatePasswordAction(Request $request) {
        $json = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('ApiRestBundle:Users')->find($json['id']);
        $encoder = $this->get('security.encoder_factory')->getEncoder($users);
        $passwordvieja = $encoder->encodePassword($json['passwordvieja'], '');
        if ($users->getPassword() == $passwordvieja) {
            $users->setPassword($encoder->encodePassword($json['passwordnueva'], ''));
            $em->persist($users);
            $em->flush();
            $output = array("nombre" => $users->getNombre(), "email" => $users->getEmail(), "telefono" => $users->getTelefono(), "username" => $users->getUsername(), "id" => $users->getId(), "role" => $users->getRole());
            return array('user' => $output);
        }
        return array('user' => null);
    }

}
