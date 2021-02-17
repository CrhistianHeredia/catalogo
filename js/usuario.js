var add = document.querySelector('#nuevoUsuario')
if (add != null) {

  add.addEventListener('click', function(e){
    e.preventDefault()
    $.confirm({
      title: 'Nuevo usuario',
      content: '<form>'
      +'<div class="form-row">'
            +'<div class="form-group col-md-6">'
              +'<label for="itemNombre">Nombre</label>'
              +'<input type="text" class="form-control" id="itemNombre" placeholder="Nombre">'
            +'</div>'
            +'<div class="form-group col-md-6">'
              +'<label for="itemPhone">Teléfono</label>'
              +'<input type="text" class="form-control" id="itemPhone" placeholder="Teléfono">'
            +'</div>'
            +'<div class="form-group col-md-6">'
              +'<label for="itemEmail">Email</label>'
              +'<input type="email" class="form-control" id="itemEmail" placeholder="Email">'
            +'</div>'
          +'</div>'
        +'</form>',
        boxWidth: '55%',
        useBootstrap: false,
      buttons: {
          confirm: function () {
            var name = this.$content.find('#itemNombre').val();
            var phone = this.$content.find('#itemPhone').val();
            var email = this.$content.find('#itemEmail').val();
            var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
              if(!name && !phone && !email){
                  $.alert('llene todos los campos son requeridos');
                  return false;
              }
              if(!caract.test(email)){
                 $.alert('formato de correo electronico no valido');
                return false;
              }
              $.ajax({
                url: 'controller/controller.php',
                type: 'POST',
                  data: {request: 'altaUsuarios', arg: JSON.stringify({ name : name, phone : phone, email : email})},
                  success : function(response){
                  allUsuarios = JSON.parse(response);
                  printTable();
                }
              })
              .always(function() {
                $.alert('Nuevo usuario registrado');
              });
          },
          cancel: function (){
          },
      }
    });
  })


}

function editarUsu(idx) {
  $.confirm({
    title: 'Editar Datos de Usuario ',
    content: '<form>'
    +'<div class="form-row">'
          +'<div class="form-group col-md-6">'
            +'<label for="itemNombre">Nombre</label>'
            +'<input value="'+allUsuarios[idx]['user_name']+'" type="text" class="form-control" id="itemNombre" placeholder="Nombre">'
          +'</div>'
          +'<div class="form-group col-md-6">'
          +'<label for="itemEmail">Email</label>'
          +'<input value="'+ allUsuarios[idx]['email']+'" type="email" class="form-control" id="itemEmail" placeholder="Email">'
        +'</div>'
          +'<div class="form-group col-md-6">'
            +'<label for="itemPhone">Teléfono</label>'
            +'<input value="'+ allUsuarios[idx]['phone']+'" type="text" class="form-control" id="itemPhone" placeholder="Teléfono">'
          +'</div>'
        +'</div>'
      +'</form>',
      boxWidth: '55%',
      useBootstrap: false,
    buttons: {
        confirm: function () {
          var name = this.$content.find('#itemNombre').val();
          var phone = this.$content.find('#itemPhone').val();
          var email = this.$content.find('#itemEmail').val();
          var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
            if(!name && !phone && !email && !edad){
                $.alert('llene todos los campos son requeridos');
                return false;
            }
            if(!caract.test(email)){
               $.alert('formato de correo electronico no valido');
              return false;
            }

            $.ajax({
              url: 'controller/controller.php',
              type: 'POST',
                data: {request: 'editarUsuarios', arg: JSON.stringify({ name : name, phone : phone, email : email, id_user : allUsuarios[idx]['id_user']})},
                success : function(response){
                allUsuarios = JSON.parse(response);
                printTable();
              }
            })
            .always(function() {
              $.alert('Usuario actualizado');
            });
        },
        cancel: function (){
        },
    }
  });
}

 function printTable(){
    var tr = "";
    for (var i = 0; i < allUsuarios.length; i++) {
       var btn = '<button type="button" class="btn btn-secondary" style="background: cornflowerblue;" onclick="editarUsu('+i+')"><i class="fa fa-pencil" aria-hidden="true"></i></button>'
              +'<button type="button" class="btn btn-secondary" style="background: #F44336;" onclick="eliminaUsu('+i+')"><i class="fa fa-trash" aria-hidden="true"></i></button>';
        tr += '<tr>'
          +'<td>'+allUsuarios[i]['user_name']+'</td>'
          +'<td>'+allUsuarios[i]['email']+'</td>'
          +'<td>'+allUsuarios[i]['phone']+'</td>'
          +'<td>'
            +'<div class="btn-group btn-group-sm" role="group" aria-label="First group">'
             + btn
            +'</div>'
          +'</td>'
        +'</tr>';
    }
    $('#tbodyAllUsuarios').html(tr);
  }

function eliminaUsu(inx){
   $.confirm({
    title: 'Elimina Usuario ',
    content: '¿Esta seguro de eliminar a '+allUsuarios[inx]['user_name']+'  ?',
    buttons: {
        confirm: function () {
            $.ajax({
              url: 'controller/controller.php',
              type: 'POST',
                data: {request: 'eliminaUsuario', arg: JSON.stringify({ id_user : allUsuarios[inx]['id_user']})},
                success : function(response){
                allUsuarios = JSON.parse(response);
                printTable();
              }
            })
            .always(function() {
              $.alert('Usuario Eliminado');
            });
        },
        cancel: function (){
        },
    }
  });
}

printTable();