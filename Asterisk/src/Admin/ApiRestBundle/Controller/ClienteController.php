<?php

namespace Admin\ApiRestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ClienteController extends Controller
{
    public function recargasAction(Request $request){
        $json = json_decode($request->getContent(), true);
        $recargas = array(array("accountid"=>"mi cuenta 1", "amount"=>"17.34", "date"=>"01-11-2016 08:47"), array("accountid"=>"mi cuenta 2","amount"=>"34.65", "date"=>"01-11-2016 08:47"));
        return array("recargas" => $recargas);
    }
    
    public function llamadasAction(Request $request){
        $json = json_decode($request->getContent(), true);
        $llamadas = array(array("remitente"=>"53687818", "receptor"=>"58228006", "tiempo"=>"03:45", "saldo"=>"01.26", "date"=>"01-11-2016 08:47"), array("remitente"=>"58562480","receptor"=>"55049071","tiempo"=>"09.45","saldo"=>"4.15","date"=>"01-11-2016 08:47"));
        return array("llamadas" => $llamadas);
    }
    
    
}
