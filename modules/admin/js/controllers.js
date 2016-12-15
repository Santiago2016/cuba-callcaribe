'use strict'

angular.module('asterisk.admin.controllers', ['ab-base64']).controller('LoginController', ['$scope', 'authService', '$state', function ($scope, authService, $state) {

    $scope.buttonText = "Login";

    $scope.login = function () {

        $scope.buttonText = "Logging in. . .";

        authService.login($scope.credentials.username, $scope.credentials.password).then(function (data) {
            if (data == null) {
                $scope.invalidLogin = true;
            } else {
                if (data.role == 'ROLE_ADMIN') {
                    $state.go('admin.recargas');
                } else if (data.role == 'ROLE_TIENDA') {
                    $state.go('tienda.clientes');
                } else {
                    $state.go('cliente.recargas');
                }
            }
        }, function (err) {
            $scope.invalidLogin = true;
        }).finally(function () {
            $scope.buttonText = "Login";
        });
    }
}]).controller('ForgotController', ['$scope', '$state', '$http', function ($scope, $state, $http) {
    $scope.buttonText = "Aceptar";
    $scope.forgot = function () {
        $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/forgot', {
            username: $scope.credentials.username,
            nombre: $scope.credentials.nombre,
            email: $scope.credentials.email,
            telefono: $scope.credentials.telefono
        }).then(function (response) {
            var response = response.data;
            if (response.status == 'success') {
                $state.go('login');
            } else {
                $scope.invalidforgot = true;
            }
        });
    }
}]).controller('AdminController', ['$scope', 'authService', '$state', 'user', function ($scope, authService, $state, user) {
    $scope.user = user;
    $scope.logout = function () {
        authService.logout().then(function () {
            $state.go('login');
        });
    }
}]).controller('TiendaController', ['$scope', 'authService', '$state', 'user', function ($scope, authService, $state, user) {
    $scope.user = user;
    console.log(user);
    $scope.logout = function () {
        authService.logout().then(function () {
            $state.go('login');
        });
    }
}]).controller('ClienteController', ['$scope', 'authService', '$state', 'user', function ($scope, authService, $state, user) {
    $scope.user = user;
    $scope.logout = function () {
        authService.logout().then(function () {
            $state.go('login');
        });
    }
}]).controller('AdminRecargasController', ['$scope', '$http','user', function ($scope, $http,user) {
    $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/admin/recargas',{user:user.id}).then(function (response) {
        $scope.response = response.data;
    },function (err) {
        $scope.err = err;
    });
}]).controller('AdminLLamadasController', ['$scope', '$http', function ($scope, $http) {
    $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/admin/llamadas').then(function (response) {
        $scope.response = response.data;
    });
}]).controller('TiendaRecargasController', ['$scope', '$http','base64','user', function ($scope, $http,base64,user) {
    $http.defaults.headers.common['Authorization'] = 'Basic ' + base64.encode('admin' + ':' + 'admin');

    //necesitaremos tener una lista de los clientes para luego buscarlos en a2billing y asi obtener el log de sus recargas
    
    //obteniendo lista de clientes
    $scope.recargas = []
    $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/tienda/clientes',{user:user.id}).then(function (response) {
        var clientes = response.data.clientes;
        console.log(clientes)
        clientes.forEach( function(cliente, index) {
            // por cada cliente buscamos sus recargas y lo añadimos al array
            // buscando el id del cliente
            $http.get('http://agents.callcaribe.com:8008/api/card/?phone='+cliente.telefono).then(function(res){
                var clienteAsterisk = res.data.objects[0]
                //buscando sus recarga
                if (clienteAsterisk != undefined || clienteAsterisk != null) {
                    $http.get('http://agents.callcaribe.com:8008/api/logrefill/?card='+clienteAsterisk.id).then(function(res){
                        var recargasCliente = res.data.objects
                        recargasCliente.forEach( function(recarga, index) {
                            $scope.recargas.push({user:cliente.telefono, phone:cliente.telefono, recargado: recarga.credit,saldoActual:clienteAsterisk.credit,fecha:recarga.date })
                        });
                    })
                }
            })
        });


    }, function (err) {
        $scope.err = err;
    });

    $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/tienda/recargas').then(function (response) {
        $scope.response = response.data;
    });
}]).controller('TiendaLLamadasController', ['$scope', '$http','base64','user', function ($scope, $http,base64,user) {
    $http.defaults.headers.common['Authorization'] = 'Basic ' + base64.encode('admin' + ':' + 'admin');

    //obteniendo lista de clientes
    $scope.llamadas = []
    $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/tienda/clientes',{user:user.id}).then(function (response) {
        var clientes = response.data.clientes;
        console.log(clientes)
        clientes.forEach( function(cliente, index) {
            // por cada cliente buscamos sus recargas y lo añadimos al array
            // buscando el id del cliente
            $http.get('http://agents.callcaribe.com:8008/api/card/?phone='+cliente.telefono).then(function(res){
                var clienteAsterisk = res.data.objects[0]
                //buscando sus llamadas
                if (clienteAsterisk != undefined || clienteAsterisk != null) {
                    $http.get('http://agents.callcaribe.com:8080/getCalls/'+clienteAsterisk.id).then(function(res){
                        var recargasCliente = res.data
                        recargasCliente.map(function(value){value.from = clienteAsterisk.phone;value.firstname = clienteAsterisk.firstname; ;value.lastname = clienteAsterisk.lastname;value.sessiontime = parseInt(value.sessiontime/60).toString() + ":" + ((value.sessiontime%60 < 10) ? "0" : "") + (value.sessiontime%60).toString()})
                        $scope.llamadas = $scope.llamadas.concat(recargasCliente)
                    })
                }
            })
        });


    }, function (err) {
        $scope.err = err;
    });
}]).controller('ClienteRecargasController', ['$scope', '$http', function ($scope, $http) {
    $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/cliente/recargas').then(function (response) {
        $scope.response = response.data;
    });
}]).controller('ClienteLLamadasController', ['$scope', '$http', function ($scope, $http) {
    $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/cliente/llamadas').then(function (response) {
        $scope.response = response.data;
    });
}]).controller('AdminRecargarController', ['$scope', '$http', 'user', '$state', function ($scope, $http, user, $state) {
    $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/admin/tiendas',{user: user.username}).then(function (response1) {
        $scope.tiendas = response1.data.tiendas;

    });
    $scope.recargar = function() {
        $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/admin/recargar',{
            user: user.id,
            tienda: $scope.tienda1,
            importe: $scope.importe
        }).then(function (response) {
            $state.go('admin.recargas');
        }, function (err) {
            $scope.err = err;
        })
    }
}]).controller('TiendaRecargarController', ['$scope', '$http', 'user', '$state','base64', function ($scope, $http, user, $state,base64) {
    $scope.error = {};
    $http.defaults.headers.common['Authorization'] = 'Basic ' + base64.encode('admin' + ':' + 'admin');
    $scope.clientUpdated = {};

    $scope.searchClient = function(){
        $http.get('http://agents.callcaribe.com:8008/api/card/?phone=1'+$scope.cliente).then(function(res){
            $scope.clientUpdated = {}
            $scope.clientUpdated.phone = $scope.cliente;
            if (res.data.objects.length == 0){
                $scope.clientUpdated.error = true
            }
            else{
                $scope.clientUpdated.a2billingClient = res.data.objects[0]
            }
        })
    }

    $scope.recargar = function () {

        var refillData = {
            "credit": $scope.importe.toString()
        }
        if (($scope.clientUpdated.a2billingClient == undefined && $scope.importe < 0) ||  ($scope.clientUpdated.a2billingClient != undefined && parseFloat($scope.clientUpdated.a2billingClient.credit) + $scope.importe < 0)){
            $scope.error.negativeRecharge = true
        }
        else{


            //obtener el cliente actual basado en su numero de telefono
            $http.get('http://agents.callcaribe.com:8008/api/card/?phone=1'+$scope.cliente).then(function(res){
                //si el cliente no existe crearlo...
                var cliente = {}
                if (res.data.objects.length == 0){
                    console.log('el cliente no existe, creandolo')
                    crearCliente($scope.cliente).then(function(res){
                    	cliente = res.data
                    	//asignando un callerid al cliente
                    	crearCallerID(cliente).then(function(res){
                            console.log("cliente creado")
                            // una vez el cliente ha sido creardo recargar
                             recargarCliente(cliente,refillData)
                        })                    
                    })
                }
                else{
                    console.log("el cliente existe recargando su cuenta con ", refillData)
                    var cliente = res.data.objects[0]
                    recargarCliente(cliente,refillData)
                }
            })
        }
    }




    function crearCliente(telefono){
    	var post = $http.post('http://agents.callcaribe.com:8008/api/card/',
            {
                "username": "1" + telefono.toString(),
                 "useralias": "1" + telefono.toString(),
                 "lastname": "",
                 "firstname": "",
                 "uipass": "1" + telefono.toString(),
                 "credit": "0",
                 "phone" : "1" + telefono.toString(),
                 "activated":"t",
                 "tariff": "1"
            },
            {
                "headers":{
                    'Content-Type': 'application/json;charset=UTF-8',
                    'Authorization': 'Basic ' + base64.encode('admin' + ':' + 'admin')
                }

            }
        )
        return post;
    }

    function crearCallerID(cliente){
    	var post = $http.post('http://agents.callcaribe.com:8008/api/callerid/',
            {
                id_cc_card: cliente.id,
                cid: cliente.phone.toString()
            },
            {
                "headers":{
                    'Content-Type': 'application/json;charset=UTF-8',
                    'Authorization': 'Basic ' + base64.encode('admin' + ':' + 'admin')
                }

            }
        )
        return post;
    }

	function recargarCliente(cliente,refillData){
		var headers = {
            "headers":{
                'Content-Type': 'application/json;charset=UTF-8',
                'Authorization': 'Basic ' + base64.encode('admin' + ':' + 'admin')
            }

        }
        $http.post('http://agents.callcaribe.com:8008/custom_api/refill/'+cliente.id,refillData,headers).then(function(refill){
            $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/tienda/recargar', {
                user: user.id,
                cliente: "1" + $scope.cliente,
                importe: $scope.importe
            }).then(function (response) {
                user.saldo -= $scope.importe
                 $http.post('http://a2billing.callcaribe.com:8080/sendSMS',{
                    message:"Gracias por su recarga de $" + $scope.importe.toString() + "  Para llamar marque el (561) 594 0116. Disfrute de la excelente tarifa y calidad que le ofrecemos.",
                    to: "+1" + $scope.cliente
                },{"headers":{'Content-Type':'application/json;charset=UTF-8'}}).then(function(sms){
                    $state.go('tienda.recargas');
                })
            }, function (err) {
                $scope.response = err;
            })
        })
	}

}]).controller('ClienteRecargarController', ['$scope', '$http', 'user', '$state', function ($scope, $http, user, $state) {
    $scope.recargar = function () {
        $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/cliente/recargar', {
            user: user.id,
            tarjeta: $scope.tarjeta,
            codigo: $scope.codigo,
            importe: $scope.importe
        }).then(function (response) {
            $state.go('cliente.recargas');
        }, function (err) {
            $scope.response = err;
        })
    }

}]).controller('PasswordController', ['$scope', '$http', 'user', '$state', function ($scope, $http, user, $state) {
    $scope.user = user;
    $scope.updatePassword = function (){
        if ($scope.user.passwordnueva == $scope.user.passwordnueva1) {
            $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/password/update', {
                id: $scope.user.id,
                passwordvieja: $scope.user.passwordvieja,
                passwordnueva: $scope.user.passwordnueva
            }).then(function (response) {
                var retorno = response.data.user;
                if (retorno.role == 'ROLE_ADMIN'){
                $state.go('admin.recargas');
                }else if (retorno.role == 'ROLE_TIENDA'){
                    $state.go('tienda.recargas');
                }else{
                    $state.go('cliente.recargas');
                }
            })
        }else{
            $scope.passwordnoequals = true;
        }
    }
}]).controller('AdminPerfilController', ['$scope', '$http', 'user', '$state', function ($scope, $http, user, $state) {
    $scope.user = user;
    $scope.updatePerfil = function (){
        $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/user/update', {
            id: $scope.user.id,
            username: $scope.user.username,
            role: $scope.user.role,
            nombre: $scope.user.nombre,
            email: $scope.user.email,
            telefono: $scope.user.telefono
        }).then(function (response) {
            $state.go('admin.recargas');
        })
    }
}]).controller('TiendaPerfilController', ['$scope', '$http', 'user', '$state', function ($scope, $http, user, $state) {
    $scope.user = user;
    $scope.updatePerfil = function (){
        $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/user/update', {
            id: $scope.user.id,
            username: $scope.user.username,
            role: $scope.user.role,
            nombre: $scope.user.nombre,
            email: $scope.user.email,
            telefono: $scope.user.telefono
        }).then(function (response) {
            $state.go('tienda.recargas');
        }, function (err) {
            $scope.err = err;
        })
    }
}]).controller('ClientePerfilController', ['$scope', '$http', 'user', '$state',function ($scope, $http, user, $state) {
    $scope.user = user;
    $scope.updatePerfil = function (){
        $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/user/update', {
            id: $scope.user.id,
            username: $scope.user.username,
            role: $scope.user.role,
            nombre: $scope.user.nombre,
            email: $scope.user.email,
            telefono: $scope.user.telefono
        }).then(function (response) {
            $state.go('cliente.recargas');
        })
    }
}]).controller('AdminTiendasController', ['$scope', '$http', 'user', function($scope, $http, user){
    $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/admin/tiendas',{user: user.username}).then(function (response) {
        $scope.response = response.data;
    }, function (err) {
        $scope.err = err;
    });
    $scope.setTienda = function(tienda){
        $scope.tiendaActual = tienda;
    }
    $scope.eliminarTienda = function(id){
        $http.delete('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/users/'+id).then(function (response) {
            $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/admin/tiendas',{user: user.username}).then(function (response) {
                $scope.response = response.data;
            }, function(err){
                $scope.err = err;
            });
        }, function(err){
            $scope.err = err;
        });
    }
}]).controller('AdminCrearTiendaController', ['$scope', '$http','$state',  'user', function($scope, $http, $state, user){
    $scope.saveTienda = function () {
        if ($scope.tienda.password == $scope.tienda.confirmpassword) {
            $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/users', {
                    user: user.id,
                    username: $scope.tienda.username,
                    password: $scope.tienda.password,
                    confirmpassword: $scope.tienda.confirmpassword,
                    role: 'ROLE_TIENDA',
                    nombre: $scope.tienda.nombre,
                    email: $scope.tienda.email,
                    telefono: $scope.tienda.telefono,
                    saldoinicial: 0,
                    comision: $scope.tienda.comision
                }
            ).then(function (response) {
                if (response.data != "") {
                    $state.go('admin.tiendas');
                } else {
                    $scope.invalid = true;
                }
            }, function (err) {
                $scope.err = err;
            });
        }else{
            $scope.noequals = true;
        }
    }
}]).controller('AdminEditarTiendaController', ['$scope', '$http','$stateParams','$state', function($scope, $http, $stateParams,$state){
    $http.get('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/users/'+$stateParams.id).then(function (response){
       $scope.tienda = response.data.user;
    });
    $scope.updateTienda = function (){
        $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/user/update', {
            id: $stateParams.id,
            username: $scope.tienda.username,
            role: 'ROLE_TIENDA',
            nombre: $scope.tienda.nombre,
            email: $scope.tienda.email,
            telefono: $scope.tienda.telefono
        }).then(function (response) {
            if (response.data != ""){
                $state.go('admin.tiendas');
            }else{
                $scope.invalid = true;
            }
        })
    }
}]).controller('TiendaClientesController', ['$scope', '$http', 'user','base64', function($scope, $http, user,base64){
    $http.defaults.headers.common['Authorization'] = 'Basic ' + base64.encode('admin' + ':' + 'admin');
    $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/tienda/clientes',{user:user.id}).then(function (response) {
        $scope.clientes = response.data.clientes;
        console.log($scope.clientes)
        $scope.clientes.forEach( function(cliente) {
            $http.get('http://agents.callcaribe.com:8008/api/card/?username='+cliente.username).then(function(res){
                var a2billingCliente = res.data.objects[0]
                cliente.firstname = a2billingCliente.firstname
                cliente.lastname = a2billingCliente.lastname
                cliente.phone = a2billingCliente.phone
                cliente.credit = a2billingCliente.credit
            })

        });
    }, function (err) {
        $scope.err = err;
    });

    $scope.eliminar = function(id){
        $http.delete('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/users/'+id).then(function (response) {
            $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/tienda/clientes',{user:user.id}).then(function (response) {
                $scope.response = response.data;
            },function(err){
                $scope.err = err;
            });
        }, function (err) {
            $scope.err = err;
        });
    }
}]).controller('TiendaCrearClienteController', ['$scope', '$http','$state', 'user', 'base64',function($scope, $http, $state, user, base64){
    $scope.saveCliente = function () {
        $http.defaults.headers.common['Authorization'] = 'Basic ' + base64.encode('admin' + ':' + 'admin');
        $http.get('http://agents.callcaribe.com:8008/api/card/?username='+$scope.cliente.telefono).then(function(res){
            console.log(res);
            if (res.data.objects.length == 0){
                console.log("the user does not exist, creating...")
                $http.post('http://agents.callcaribe.com:8008/api/card/',
                    {
                        "username": $scope.cliente.telefono,
                         "useralias": $scope.cliente.telefono,
                         "lastname": "",
                         "firstname": "",
                         "uipass": $scope.cliente.telefono,
                         "credit": "0", 
                         "activated":"t",
                         "tariff": "1"
                    },
                    {
                        "headers":{
                            'Content-Type': 'application/json;charset=UTF-8',
                            'Authorization': 'Basic ' + base64.encode('admin' + ':' + 'admin')
                        }

                    }
                 ).then(function(res){
                    console.log(res);

                     $http.post('http://agents.callcaribe.com:8008/api/callerid/',
                        {
                            id_cc_card: res.data.id,
                            cid: $scope.cliente.telefono
                        },
                        {
                            "headers":{
                                'Content-Type': 'application/json;charset=UTF-8',
                                'Authorization': 'Basic ' + base64.encode('admin' + ':' + 'admin')
                            }

                        }
                    ).then(function(res){
                        console.log(res)

                        $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/users',{
                            user: user.id,
                            username: $scope.cliente.telefono,
                            password: $scope.cliente.telefono,
                            role: 'ROLE_CLIENTE',
                            nombre: '',
                            email: '',
                            telefono: $scope.cliente.telefono
                        }
                        ).then(function (response) {
                            if (response.data != ""){
                                $state.go('tienda.clientes');
                            }else{
                                $scope.invalid = true;
                            }
                        }, function (err) {
                            $scope.err = err;
                        });

                    })
                    
                 })
            }
            else{
                console.log("the user does exist, doing nothing")
                $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/users',{
                    user: user.id,
                    username: $scope.cliente.telefono,
                    password: $scope.cliente.telefono,
                    role: 'ROLE_CLIENTE',
                    nombre: '',
                    email: '',
                    telefono: $scope.cliente.telefono
                }
                ).then(function (response) {
                    if (response.data != ""){
                        $state.go('tienda.clientes');
                    }else{
                        $scope.invalid = true;
                    }
                }, function (err) {
                    $scope.err = err;
                });
            }
        })
    }
}]).controller('TiendaEditarClienteController', ['$scope', '$http','$stateParams','$state','base64', function($scope, $http, $stateParams, $state,base64){
    $http.defaults.headers.common['Authorization'] = 'Basic ' + base64.encode('admin' + ':' + 'admin');

    $http.get('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/users/'+$stateParams.id).then(function (response){
        $scope.cliente = response.data.user;

        $http.get('http://agents.callcaribe.com:8008/api/card/?username='+$scope.cliente.username).then(function(res){
            $scope.asteriskApiClient = res.data.objects[0]
        })

    });
    $scope.updateCliente = function (){
        var userdata = {
            username: $scope.cliente.username,
            useralias: $scope.cliente.username,
            firstname: $scope.cliente.nombre,
            email: $scope.cliente.email,
            phone: $scope.cliente.telefono
        }
        console.log(userdata)
        $http.put('http://agents.callcaribe.com:8008/api/card/'+$scope.asteriskApiClient.id+'/',userdata,
        {
            "headers":{
                'Content-Type': 'application/json;charset=UTF-8',
                'Authorization': 'Basic ' + base64.encode('admin' + ':' + 'admin')
            }

        }).then(function(res){
            $http.get('http://agents.callcaribe.com:8008/api/callerid/?cid='+$scope.cliente.telefono).then(function(data){
                if (data.data.objects.length == 0){
                    //si no hay caller id crearlo
                    $http.post('http://agents.callcaribe.com:8008/api/callerid/',
                        {
                            id_cc_card: $scope.asteriskApiClient.id,
                            cid: $scope.cliente.telefono
                        },
                        {
                            "headers":{
                                'Content-Type': 'application/json;charset=UTF-8',
                                'Authorization': 'Basic ' + base64.encode('admin' + ':' + 'admin')
                            }

                        }
                    ).then(function(res){
                        console.log(res)
                        $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/user/update', {
                            id: $stateParams.id,
                            username: $scope.cliente.username,
                            role: 'ROLE_CLIENTE',
                            nombre: $scope.cliente.nombre,
                            email: $scope.cliente.email,
                            telefono: $scope.cliente.telefono
                        }).then(function (response) {
                            if (response.data != ""){
                                $state.go('tienda.clientes');
                            }else{
                                $scope.invalid = true;
                            }
                        })
                    })
                }
                else{
                    //si ya existe el caller id no hay necesidad de volver a crearlo, ademas dara un error
                    $http.post('http://agents.callcaribe.com/Asterisk/web/app_dev.php/api/user/update', {
                        id: $stateParams.id,
                        username: $scope.cliente.username,
                        role: 'ROLE_CLIENTE',
                        nombre: $scope.cliente.nombre,
                        email: $scope.cliente.email,
                        telefono: $scope.cliente.telefono
                    }).then(function (response) {
                        if (response.data != ""){
                            $state.go('tienda.clientes');
                        }else{
                            $scope.invalid = true;
                        }
                    })
                }
            })


            


            
        })


        
    }
}]);
