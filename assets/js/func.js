function registrarusuario(){


  var nombre = document.getElementById("name").value;
  var apellido = document.getElementById("lastname").value;    
  var email = document.getElementById("email").value;
  var clave = document.getElementById("password").value;
  var clave2 = document.getElementById("password2").value;
  var compania_id = document.getElementById("compania_id").value;

  if (clave!=clave2){
  	alert("Error: las claves deben ser iguales, por favor verifique");
  }else{  	
  	xajax_registrarusuario(nombre, apellido, email, clave, compania_id);	
  }
  
}


function ingresarusuario(){

  var email = document.getElementById("email").value;
  var clave = document.getElementById("password").value;
  var compania_id = document.getElementById("compania_id").value;
  
  xajax_ingresarusuario(email, clave, null, compania_id);
}