(function(){
    var app = angular.module('spoortApp', ['ngResource', 'ngRoute','flash', 'ngAnimate', 'ui.bootstrap']);
    //rutas para los templates
    app.config(function($routeProvider){
        $routeProvider
            .when('/',{
                templateUrl: 'home.html'
            })
            .when('/login',{
                templateUrl: 'login.html'
            })
            .when('/signin',{
                templateUrl: 'signin.html'
            })
            .when('/profile',{
                templateUrl: 'profile.html'
            })
            .when('/team',{
                templateUrl: 'team.html'
            })
            .otherwise({
                redirectTo: '/'
            });
    });

    //Factorias para acceder a los webs services
	app.factory('equipo', ['$http', function ($http){
		
		//var urlBase = "/Servidor/busqueda.php";
		var urlBase = "/Servidor/pruebas_conexion.php";
		
		return {
			getEquipos : function(value){
				return  $http({
					    	method: "GET",
							url: urlBase,
							data: value,
							headers: { 'Content-Type': 'application/json' }
							});
			},
			getEquipo : function(id){
				return  $http({
					    	method: "GET",
							url: urlBase + '/' + id,
							data: {},
							headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
							});
			},
			insertEquipos : function(value){
				return  $http({
					    	method: "POST",
							url: urlBase,
							data: value,
							headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
							});
			},
			updateEquipos : function(value){
				return  $http({
					    	method: "PUT",
							url: urlBase + '/' + value.id,
							data: value,
							headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
							});
			},
			deleteEquipos : function(id){
				return  $http({
					    	method: "DELETE",
							url: urlBase + '/' + id,
							data: {},
							headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
							});
			}
		};
	}]);
	
	app.factory('user', ['$http', function ($http){
		
		var urlBase = "/Servidor/user.php";
		
		return {
			validUser : function(value){
				return  $http({
					    	method: "POST",
							url: urlBase,
							data: {usuario: value.user, password: value.pass},
							headers: { 'Content-Type': 'application/json' }
							});
			},
			newUser : function(value){
				return  $http({
					    	method: "POST",
							url: urlBase,
							data: {usuario: value.user, apellido: value.apell, correo: value.email, password: value.pass},
							headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
							});
			}				
				
		};
	}]);

	//Servicios para compartir informacion entre los controladores
	app.service('sessionService', function($rootScope) {
		var optionStatus ={
			status: false
		};
		$rootScope.sessionStatus="Iniciar Sesion";
		return {
			getStatus: function() {
				return optionStatus;
			},
			setStatus: function(value) {
				optionStatus.status = value;
				
				$rootScope.sessionStatus="Cerrar Sesion";
			}
		}
	});
	
		//Servicios para compartir informacion entre los controladores
	app.service('teamService', function($rootScope){
		var equipo ={};
		return {	
			setEquipo: function(_equipo) {
				equipo = _equipo;
			},
			getEquipo: function(){
				return equipo;
			}
		}
	
	});
	
	//Controlador de la pagina para mostrar la info de recibidas por las factorias
	app.controller('homeCtl', ['$scope', 'equipo', 'user', 'teamService','Flash', function ($scope, equipo, user, teamService, Flash){
		var imagenes    = $scope.imagenes    = [];
		$scope.modBusqueda = "";
		
		$scope.searchError = function () {
			var message = '<strong>Lo lamento!</strong> No se han encontrado resultados.';
            Flash.create('danger', message);
		};
		
		$scope.tagError = function () {
			var message = '<strong>Lo lamento!</strong>Debe ingresar los datos.';
            Flash.create('danger', message);
		};
		
		$scope.validSearch = function (tag) {
			if(tag!=""){
				$scope.getEquipos(tag);
				$scope.modBusqueda = "";
			}
			else{
				$scope.tagError();
			}
		};
		
		$scope.getEquipos = function(tag){
			equipo.getEquipos(tag).success(function (datos, status){
				if(datos.tipo_mensaje == "correcto"){
					for(var i = 0; i < datos.parametros.length; i++){
						imagenes.push(datos.parametros[i]); 
			        }
				}
				else{
					$scope.searchError();
				}
			})
		};
		
		$scope.verEquipo = function(id){
			teamService.setEquipo(imagenes[id]);
			location.href= '/#/team';
		};
	}]);
	
	//Controlador de la pagina para obtener el status de sesion
	app.controller('indexCtl', ['$scope', 'equipo', 'user', 'sessionService', function ($scope, equipo, user, sessionService){
	
		$scope.updateStatus = function(){
			sessionService.getStatus(function(res){
				if(res.status == true){
					$scope.sessionStatus="Cerrar Sesion";
				}
				else{
					$scope.sessionStatus="Iniciar Sesion";
				}	
			});
		};
		
		
	}]);
	
	//Controlador de la pagina para obtener los datos de inicio de sesion
	app.controller('loginCtl', ['$scope', 'user', 'Flash', 'sessionService', function ($scope, user, Flash, sessionService){
		
		$scope.loguinError = function () {
			var message = '<strong>Lo lamento!</strong> El usuario o contraseña no es valido.';
            Flash.create('danger', message);
		};
		$scope.serverError = function () {
			var message = '<strong>Lo lamento!</strong> La conexion ha fallado.';
            Flash.create('danger', message);
		};
		$scope.validUser = function () {
		    var message = '<strong> Bien hecho!</strong> Se ha autenticado la sesion de manera correcta.';
    		Flash.create('success', message);
		};
		
		$scope.login = function(){
			var userData = {
				user: $scope.modLoginUser,
				pass: $scope.modLoginPass
			};
			user.validUser(userData).then(function (response){
				if(response.data.mensaje == "correcto"){
				  
					sessionService.setStatus(true);
					console.log(sessionService.getStatus().status);
					location.href= '/#/profile';
					$scope.validUser();
				}
				else{
					$scope.loguinError();
				}
			}, 
			function(response){
				$scope.serverError();
			});
		};
	}]);
	
	//Controlador de la pagina para acceder a los datos de registro
	app.controller('signinCtl', ['$scope', 'equipo', 'user', function ($scope, equipo, user){
     	$scope.horaInicial = new Date();
        $scope.horaInicial = new Date();
		$scope.ismeridian = true;
		$scope.hstep = 1;
		$scope.mstep = 15;
		
		$scope.options = {
		    hstep: [1, 2, 3],
		    mstep: [1, 5, 10, 15, 25, 30]
		};
	
	    $scope.update = function() {
		    
		    console.log($scope.horaInicial);
	    };
	

  
        
		$scope.signin = function(){
			var userData = {
				user: $scope.modRegNombre,
				apell: $scope.modRegApellido,
				//email: $scope-modRegCorreo,
				pass: $scope.modLoginPass
			};
			user.newUser(userData).success(function (datos, status){
				if(datos.tipo_mensaje == "correcto"){
					console.log("valido");
				}
				else{
					console.log("error");
				}
			})
		};	
		
	}]);
	
	//Controlador de la pagina para visualizar l informacion de un equipo
	app.controller('teamCtl', ['$scope', 'teamService', function ($scope, teamService){
		console.log(teamService.getEquipo());
	}]);
	
})();
