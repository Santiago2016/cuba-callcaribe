<?php

namespace Admin\ApiRestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Admin\ApiRestBundle\Entity\Recarga;

class AdminController extends Controller
{
    public function recargasAction(Request $request){
        $json = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $admin = $em->getRepository('ApiRestBundle:Users')->find($json['user']);
        $recargastodas = $admin->getRecargas();
        $output = array();
        foreach ($recargastodas as $r){
            $output[] = array("tienda"=>$r->getTienda(),"amount"=>$r->getAmount(),"date"=>$r->getDate()->format('d-m-Y H:i:s'));
        }
        return array("recargas" => $output);
    }
    
    public function recargarAction(Request $request){
        $json = json_decode($request->getContent(), true);
        $tiendajson = $json['tienda'];
        $importe = $json['importe'];
        $em = $this->getDoctrine()->getManager();
        $tienda = $em->getRepository('ApiRestBundle:Users')->findOneBy(array("username"=>$tiendajson, "role"=>'ROLE_TIENDA'));
        $cuentas = $tienda->getCuentas();
        $cuenta = $cuentas[0];
        $cuenta->setSaldo($cuenta->getSaldo() + (float)$importe);
        $em->persist($cuenta);
        $em->flush();
        $recarga = new Recarga();
        $date = new \DateTime('now');
        $recarga->setDate($date);
        $recarga->setAmount((float)$importe);
        $recarga->setTienda($tiendajson);
        $admin = $em->getRepository('ApiRestBundle:Users')->find($json['user']);
        $recarga->setAdmin($admin);
        $em->persist($recarga);
        $em->flush();
         $ch = curl_init("http://a2billing.callcaribe.com:3000/sendSMS");
        curl_setopt($ch, CURLOPT_POST, true);
        $telefono = $tienda->getTelefono();
        $data = "{'to':'$telefono', 'message':'Su cuenta ha sido recargada con $importe'}";
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        return array("status"=>"success");
    }
    
    public function llamadasAction(Request $request){
        $json = json_decode($request->getContent(), true);
        $llamadas = array(array("remitente"=>"53687818", "receptor"=>"58228006", "tiempo"=>"03:45", "date"=>"01-11-2016 08:47","tienda"=>"nombre_tienda"), array("remitente"=>"58562480","receptor"=>"55049071","tiempo"=>"09.45","date"=>"01-11-2016 08:47","tienda"=>"nombre_tienda"));
        return array("llamadas" => $llamadas);
    }
    
    public function tiendasAction(Request $request){
        $json = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $tiendas = $em->getRepository('ApiRestBundle:Users')->findBy(array('role'=>'ROLE_TIENDA','admin'=>$json['user']));
        $output = array();
        foreach ($tiendas as $t){
            $cuentas = $t->getCuentas();
            $cuentassalida = array();
            foreach ($cuentas as $c){
                $cuentassalida[] = array("cuentaid"=>$c->getCuentaid(), "saldo"=>$c->getSaldo(), "comision"=>$c->getComision());
            }
            $output[] = array("nombre"=>$t->getNombre(), "email"=>$t->getEmail(), "telefono"=>$t->getTelefono(), "cuentas"=>$cuentassalida, "id"=>$t->getId(), "username"=>$t->getUsername());           
        }
        return array("tiendas" => $output);
    }
}
