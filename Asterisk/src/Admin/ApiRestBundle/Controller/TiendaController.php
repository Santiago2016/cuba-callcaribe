<?php

namespace Admin\ApiRestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Admin\ApiRestBundle\Entity\Users;

class TiendaController extends Controller {

    public function recargasAction(Request $request) {
        $json = json_decode($request->getContent(), true);
        $recargas = array(array("accountid" => "mi cuenta 1", "amount" => "17.34", "date" => "01-11-2016 08:47"), array("accountid" => "mi cuenta 2", "amount" => "34.65", "date" => "01-11-2016 08:47"));
        return array("recargas" => $recargas);
    }

    public function recargarAction(Request $request) {
        $json = json_decode($request->getContent(), true);
        $cliente = $json['cliente'];
        $importe = $json['importe'];
        $em = $this->getDoctrine()->getManager();
        $tienda = $em->getRepository('ApiRestBundle:Users')->find($json['user']);
        $cuentas = $tienda->getCuentas();
        $cuenta = $cuentas[0];
        if ($cuenta->getSaldo() < (float) $importe) {
            return array("status" => "sin saldo");
        }
        $usuario = $em->getRepository('ApiRestBundle:Users')->findOneBy(array('telefono' => $cliente));
        if ($usuario == null) {
            $users = new Users();
            $encoder = $this->get('security.encoder_factory')->getEncoder($users);
            $users->setUsername($cliente);
            $users->setPassword($encoder->encodePassword($cliente, ''));
            $users->setRole('ROLE_CLIENTE');
            $users->setNombre('');
            $users->setEmail('');
            $users->setTelefono($cliente);
            $em->persist($users);
            $em->flush();
            $usuario = $users;
        }
        //verificar que la tienda no contenga el usuario antes de agregarlo
        if (strstr($tienda->getClientes(), ',' . $usuario->getUsername() . ',') == FALSE) {
            if (strlen($usuario->getClientetiendas()) > 0) {
                $usuario->setClientetiendas($usuario->getClientetiendas() . $tienda->getUsername() . ',');
            } else {
                $usuario->setClientetiendas(',' . $tienda->getUsername() . ',');
            }
            $em->persist($usuario);
            $em->flush();
            if (strlen($tienda->getClientes()) > 0) {
                $tienda->setClientes($tienda->getClientes() . $usuario->getUsername() . ',');
            } else {
                $tienda->setClientes(','.$users->getUsername().',');
            }
        }
        $cuenta->setSaldo($cuenta->getSaldo() - (float) $importe);
        $em->persist($cuenta);
        $em->flush();
        $em->persist($tienda);
        $em->flush();
        //hacer la recarga
//        $ch = curl_init("http://a2billing.callcaribe.com:3000/sendSMS");
//        curl_setopt($ch, CURLOPT_POST, true);
//        $telefono = $usuario->getTelefono();
//        $data = "{'to':'$telefono', 'message':'Su saldo ha sido recargado con $importe'}";
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//        //enviar el sms
        return array("status" => "success");
    }

    public function llamadasAction(Request $request) {
        $json = json_decode($request->getContent(), true);
        $llamadas = array(array("remitente" => "53687818", "receptor" => "58228006", "tiempo" => "03:45", "date" => "01-11-2016 08:47"), array("remitente" => "58562480", "receptor" => "55049071", "tiempo" => "09.45", "date" => "01-11-2016 08:47"));
        return array("llamadas" => $llamadas);
    }

    public function clientesAction(Request $request) {
        $json = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $tienda = $em->getRepository('ApiRestBundle:Users')->find($json['user']);
        $token = strtok($tienda->getClientes(), ",");
        $clientes = array();
        while ($token !== false) {
            $cliente = $em->getRepository('ApiRestBundle:Users')->findBy(array('username' => $token));
            $clientes[] = $cliente[0];
            $token = strtok(",");
        }
        $output = array();
        foreach ($clientes as $t) {
            $output[] = array("nombre" => $t->getNombre(), "email" => $t->getEmail(), "telefono" => $t->getTelefono(), "id" => $t->getId(), "username"=>$t->getUsername());
        }
//        $clientes = $em->getRepository('ApiRestBundle:Users')->findBy(array('role'=>'ROLE_CLIENTE'));
        return array("clientes" => $output);
    }

}
