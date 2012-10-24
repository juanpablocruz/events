			function edit(){
			//function to edit info inputs
				$('.perfil_val').css('border','solid');
				$('.perfil_val').css('border-width','1px');
				$('#save').css('visibility','visible');
				$('#canceleditprof').css('visibility','visible');
				$('.perfil_val').removeAttr('readonly');
			}
			function save(){
			//function to save the changed data
				$('.perfil_val').css('border','none');
				$('.perfil_val').attr('readonly','readonly');
				$('#save').css('visibility','hidden');
				var name = document.getElementsByName('perfilname')[0].value;
				var number = document.getElementsByName('perfilnumber')[0].value;
				var email = document.getElementsByName('perfilemail')[0].value;
				location.href = 'editprofile.php'+'?name='+name+'&number='+number+'&email='+email;
			}
			function canceleditprof(){
			//function to cancel edit mode
					$('.perfil_val').css('border','none');
					$('.perfil_val').attr('readonly','readonly');
					$('#save').css('visibility','hidden');	
					$('#canceleditprof').css('visibility','hidden');					
				}
				
			$(function(){
			//Search input function
				$('#inBuscar_users').autocomplete({
					source : 'busqueda.php',
					select : function(event, ui){
							var name = ui.item.value.replace(/ /g,"_");
                            $('#dPerfilview').load(
                                'viewperfil.php?name=' + name
                            );
							}
					});
				});
			function viewperfil(id){
			//view perfil function
				$('#dPerfilview').load(
					'viewperfil.php?id=' + id
                 );
			}

			$(function() {
				$( "#inFecha" ).datepicker({dateFormat: 'yy-mm-dd'});
			});
		/*
		//Usa esto para elegir las horas en la creacion de evento
			function sethora(){
			//set houre for event creation
				var now = new Date();
				var hora = now.getHours();
				var minute  = now.getMinutes();
				if (minute<"10"){
					minute = "00";
				}
				else{
					minute = minute-(minute%10);
				}
				var ele = document.getElementById("EventHora");
				ele.value = hora+":"+minute;
			};
			function loadhora(){
			//display houres panel for event creation
				$('#horas').css('visibility','visible');				
				var now = new Date();
				var hora = now.getHours();
				var cuadrohoras = document.getElementById("horas");
				var i = 0;
				cuadrohoras.innerHTML = "";
				while (i < hora){
					if(i<10){
					cuadrohoras.innerHTML += "<a href=javascript:void(0);>0"+(i)+":00</a><br>";
					}
					else{
					cuadrohoras.innerHTML += "<a href=javascript:void(0);>"+(i)+":00</a><br>";
					}
					i++;
				}
				var j = 0;
				while (hora+j < 24){
					cuadrohoras.innerHTML += "<a href=javascript:void(0);>"+(hora+j)+":00</a><br>";
					j++;
				}
			
			}

				$("#horas").selectable({
					selected: function(event, ui) { 
						var cuadrohoras = document.getElementById("EventHora");
						cuadrohoras.value = (ui.selected.text);
						$('#horas').css('visibility','hidden');
					}
				});
			});*/
			function createevent(){
					var hora = document.getElementById("EventHora").value;
					var dia = document.getElementById("EventDay").value;
					var inputh = document.getElementById("inputhora");
					inputh.setAttribute("name", "hora");
					inputh.setAttribute("value", hora);
					var inputd = document.getElementById("inputdia");
					inputd.setAttribute("name", "dia");
					inputd.setAttribute("value", dia);
					var inputg = document.getElementById("inputgrupo");
					inputg.setAttribute("name", "grupo");
					inputg.setAttribute("value", groupto);
					document.getElementById("FormularioEvento").submit();
				};
				
			function rellenarbolas(idUser,grupo,index1,index2){
				var i=0; 
				var len = grupo.length; 
				for (i=0;i<=len;i++) { 
					if (elemento=document.getElementById('bolita'+i)){
						elemento.parentNode.removeChild(elemento);
					}
				}
				if (elemento=document.getElementById('girarmas')){
					elemento.parentNode.removeChild(elemento);
					elemento=document.getElementById('girarmenos');
					elemento.parentNode.removeChild(elemento);
				}
				if (index2>len){
					index2=len;
				}
				if (index1<0){
					index1=0;
				}		
				var n = index1;
				var i = 0;
				var rbolita= 50;
				granbola = document.getElementById('misgruposfather');
				while (n<index2){	
					var teta= -(Math.PI/4)*(4-i);
					var fi= (Math.PI/6);
					var x = 150+150*Math.cos(teta+fi)-rbolita;
					var y = 150+150*Math.sin(teta+fi)-rbolita;
					var text = grupo[n];
					bolita = document.createElement("div");
					bolita.id='bolita'+n;
					bolita.name=text;
					bolita.className='bolitas drag';
					bolita.style.height = 2*rbolita+'px';
					bolita.style.width = 2*rbolita+'px';
					bolita.style.position='absolute';
					bolita.style.top= (y+'px');
					bolita.style.left= (x+'px');
					bolitachild = document.createElement('div');
					bolitachild.id='bolitachild';
					bolitachild.className='bolagrupochild';
					bolitachild.innerHTML= text;
					bolita.appendChild(bolitachild);
					granbola.appendChild(bolita);
				n++;
				i++;
				}
				$(".bolitas").click(function(){
					$('#seguidores').load(
						'seguidoreslista.php?grupo='+(this).name+'&idUser='+idUser
					);
					$('#foro').load(
						'foro.php?grupo='+(this).name+'&idUser='+idUser
					)					
				});
				girarmenos = document.createElement('div');
				girarmenos.id='girarmenos';
				if (n<=5){
					girarmenos.onclick = function(){rellenarbolas(idUser,grupo,0,5)}
				}
				else{
					girarmenos.onclick = function(){rellenarbolas(idUser,grupo,n-5-i,n-i)}
				}
				granbola.appendChild(girarmenos);
				girarmas = document.createElement('div');
				girarmas.id='girarmas';
				if (n==grupo.length){
					girarmas.onclick = function(){rellenarbolas(idUser,grupo,index1,index2)}
				}
				else{
					girarmas.onclick = function(){rellenarbolas(idUser,grupo,n,n+5)}
				}
					
				granbola.appendChild(girarmas);
			}
			
			function crearbolas(idUser,grupo){
				var index = grupo.length;
				if (document.getElementById('bolitachild')){
					var i=0; 
					for (i=0;i<=index;i++) { 
						if (elemento=document.getElementById('bolita'+i)){
							elemento.parentNode.removeChild(elemento);
						}
					}
					elemento=document.getElementById('girarmas');
					elemento.parentNode.removeChild(elemento);
					elemento=document.getElementById('girarmenos');
					elemento.parentNode.removeChild(elemento);
				}
				
				else {
					if (index>5){
						index=5;
					}
					rellenarbolas(idUser,grupo,0,index);
				}
			}
			function crearbolasSeg(idUser, grupo, index1, index2){
				var i=0; 
				var len = grupo.length; 
				for (i=0;i<=len;i++) { 
					if (elemento=document.getElementById('bolitaSeg'+i)){
						elemento.parentNode.removeChild(elemento);
					}
				}
				if (elemento=document.getElementById('girarmasSeg')){
					elemento.parentNode.removeChild(elemento);
					elemento=document.getElementById('girarmenosSeg');
					elemento.parentNode.removeChild(elemento);
				}
				if (index2>len){
					index2=len;
				}
				if (index1<0){
					index1=0;
				}		
				var n = index1;
				var i = 0;
				var rbolita= 30;
				eje = document.getElementById('ejeHome');
				while (n<index2){	
					var teta=-(Math.PI/4)*(Math.PI/4)*(5-i);
					var fi= (Math.PI/11.4);
					var x = -3*(36-60*Math.cos(teta+fi)-rbolita);
					var y = -45-130*Math.sin(teta+fi)-rbolita;
					var text = grupo[n];
					bolita = document.createElement("div");
					bolita.id='bolitaSeg'+n;
					bolita.name=text;
					bolita.className='bolitasSeg drag';
					bolita.style.height = 2*rbolita+'px';
					bolita.style.width = 2*rbolita+'px';
					bolita.style.position='absolute';
					bolita.style.top= (y+'px');
					bolita.style.left= (x+'px');
					bolitachild = document.createElement('div');
					bolitachild.id='bolitachildSeg';
					bolitachild.className='bolagrupochild';
					bolitachild.innerHTML= text;
					bolita.appendChild(bolitachild);
					eje.appendChild(bolita);
				n++;
				i++;
				}
				$(".bolitasSeg").click(function(){
					$('#seguidores').load(
						'seguidoreslista.php?grupo='+(this).name+'&idUser='+idUser
					);
					$('#foro').load(
						'foro.php?grupo='+(this).name+'&idUser='+idUser
					)					
				});
				
				girarmenosSeg = document.createElement('div');
				girarmenosSeg.id='girarmenosSeg';
				if (n<=5){
					girarmenosSeg.onclick = function(){crearbolasSeg(grupo,0,5)}
				}
				else{
					girarmenosSeg.onclick = function(){crearbolasSeg(grupo,n-5-i,n-i)}
				}
				eje.appendChild(girarmenosSeg);
				girarmasSeg = document.createElement('div');
				girarmasSeg.id='girarmasSeg';
				if (n==grupo.length){
					girarmasSeg.onclick = function(){crearbolasSeg(grupo,index1,index2)}
				}
				else{
					girarmasSeg.onclick = function(){crearbolasSeg(grupo,n,n+5)}
				}
					
				eje.appendChild(girarmasSeg);
			}
		
			function changebola(idUser,grupos){
				var bola = document.getElementById("misgrupos");
				var index = grupos.length;
				bola.onclick = function(){crearbolas(idUser,grupos)};
				if (index>5){
						index=5;
				}
				rellenarbolas(idUser,grupos,0,5);
			}
			
			function cargarpagina(nombrepagina){
				if(nombrepagina == 'inicio' ||nombrepagina == 'perfil' ||nombrepagina == 'contactos' 
				||nombrepagina == 'eventos' ||nombrepagina == 'mensajes'||nombrepagina == 'configuration' ){
				$('#dContenido').load(
						nombrepagina+'.php'
					)
				for(var i=0;i<document.getElementsByTagName('*').length;i++){
				   if(document.getElementsByTagName('*')[i].className == 'botonmenu bcolor2'){
					  document.getElementsByTagName('*')[i].className= 'botonmenu bcolor1';
				   }
				}
				var boton=document.getElementById('d'+nombrepagina);
				boton.className='botonmenu bcolor2';
				}
			}
			
			function ViewMessage(IdMessage)
			{
				var id = "Message"+IdMessage;
				var div = document.getElementById(id);
				div.style.display='block';
			}
			function viewgroup(idgroup){
			//view perfil function
				$('#dPerfilview').load(
					'viewgroup.php?id=' + idgroup
                 );			
				 }
			function SendMessage(){
				$('.Message').css('display','block');
			}

			function SaltarPaso(nombreDiv,inicio,destino){
				document.getElementById('formevent').style.display='block';
				document.getElementById(nombreDiv+inicio).style.display='none';
				document.getElementById(nombreDiv+destino).style.display='block';
			}
			
			function apagar(e,dest){
				if (!e) var e = window.event;
				e.cancelBubble = true;
				if (e.stopPropagation) e.stopPropagation();
				//this.style.display='none'
				if(dest== 0)
					{
					document.getElementById('formevent').style.display='none';
					}
				if(dest==1)
					{
					document.getElementById('NewMessage').style.display='none';
					}
				
				}
				
			function mantener(e){
				if (!e) var e = window.event;
				e.cancelBubble = true;
				if (e.stopPropagation) e.stopPropagation();
				}
			function showEvents(str){
				if (str=="")
				  {
				  document.getElementById("txtHint").innerHTML="";
				  return;
				  } 
				if (window.XMLHttpRequest)
				  {// code for IE7+, Firefox, Chrome, Opera, Safari
				  xmlhttp=new XMLHttpRequest();
				  }
				else
				  {// code for IE6, IE5
				  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				  }
				xmlhttp.onreadystatechange=function()
				  {
				  if (xmlhttp.readyState==4 && xmlhttp.status==200)
					{
					document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
					}
				  }
				if(str==0 |str==1 |str==2 |str==3 | str==4){ 
				xmlhttp.open("GET","getEvents.php?q="+str,true);
				xmlhttp.send();}
			}	
			function editEvents(str){
				if (window.XMLHttpRequest)
				  {// code for IE7+, Firefox, Chrome, Opera, Safari
				  xmlhttp=new XMLHttpRequest();
				  }
				else
				  {// code for IE6, IE5
				  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				  }
				
				xmlhttp.open("GET","editEvent.php?q="+str,true);
				xmlhttp.send();
			}
			function showconfig(e){
				var wnd = document.getElementById("config");
				if ( wnd.style.display != 'none' ) {
				wnd.style.display = 'none';
				}
				else {					
				wnd.style.display="block";
				}
				if (!e) var e = window.event;
				e.cancelBubble = true;
				if (e.stopPropagation) e.stopPropagation();
			}
			function hideconfig(){
				var wnd = document.getElementById("config");
				wnd.style.display="none";
				
			}
			function buscar(nombre){
				$('#dContenido').load
				(
					'contactos.php?search='+nombre
				)
				for(var i=0;i<document.getElementsByTagName('*').length;i++){
				   if(document.getElementsByTagName('*')[i].className == 'botonmenu bcolor2'){
					  document.getElementsByTagName('*')[i].className= 'botonmenu bcolor1';
				   }
				}
			}
			$('#dCalendar').load(
					'calendar.php'
                 );
			