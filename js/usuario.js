var add = document.querySelector('#nuevoUsuario')
if (add != null) {

  add.addEventListener('click', function(e){
    e.preventDefault()
    $.confirm({
      title: '<i class="fa fa-user-plus text-primary me-2"></i>Nuevo usuario',
      content: '<form>'
      +'<div class="row g-3">'
            +'<div class="col-12">'
              +'<label class="form-label small fw-semibold text-muted" for="itemNombre">Nombre</label>'
              +'<input type="text" class="form-control" id="itemNombre" placeholder="Nombre completo">'
            +'</div>'
            +'<div class="col-md-6">'
              +'<label class="form-label small fw-semibold text-muted" for="itemPhone">Teléfono</label>'
              +'<input type="text" class="form-control" id="itemPhone" placeholder="Teléfono">'
            +'</div>'
            +'<div class="col-md-6">'
              +'<label class="form-label small fw-semibold text-muted" for="itemEmail">Email</label>'
              +'<input type="email" class="form-control" id="itemEmail" placeholder="correo@ejemplo.com">'
            +'</div>'
          +'</div>'
        +'</form>',
        boxWidth: '500px',
        useBootstrap: false,
        closeIcon: true,
        columnClass: 'small',
        theme: 'material',
        buttons: {
            confirm: {
              text: '<i class="fa fa-check me-1"></i>Guardar',
              btnClass: 'btn-primary',
              action: function () {
                var name = this.$content.find('#itemNombre').val();
                var phone = this.$content.find('#itemPhone').val();
                var email = this.$content.find('#itemEmail').val();
                var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
                var errors = [];
                if(!name) errors.push('Nombre');
                if(!phone) errors.push('Teléfono');
                if(!email) errors.push('Email');
                if(errors.length > 0){
                    $.alert({
                      title: '<i class="fa fa-exclamation-triangle text-warning me-2"></i>Campos requeridos',
                      content: 'Complete: <strong>'+errors.join(', ')+'</strong>',
                      type: 'orange',
                      theme: 'material',
                      buttons: { ok: 'Entendido' }
                    });
                    return false;
                }
                if(!caract.test(email)){
                    $.alert({
                      title: '<i class="fa fa-times-circle text-danger me-2"></i>Email inválido',
                      content: 'Formato de correo electrónico no válido',
                      type: 'red',
                      theme: 'material',
                      buttons: { ok: 'Entendido' }
                    });
                    return false;
                }
                $.ajax({
                  url: 'controller/controller.php',
                  type: 'POST',
                    data: {request: 'altaUsuarios', arg: JSON.stringify({ name: name, phone: phone, email: email })},
                    success: function(response){
                    allUsuarios = JSON.parse(response);
                    printTable();
                  }
                })
                .always(function() {
                  $.alert({
                    title: '<i class="fa fa-check-circle text-success me-2"></i>Completado',
                    content: 'Nuevo usuario registrado',
                    type: 'green',
                    theme: 'material',
                    buttons: { ok: 'Cerrar' }
                  });
                });
              }
            },
            cancel: {
              text: '<i class="fa fa-times me-1"></i>Cancelar',
              btnClass: 'btn-light',
              action: function(){}
            },
        }
    });
  })


}

function editarUsu(idx) {
  $.confirm({
    title: '<i class="fa fa-pencil-square-o text-primary me-2"></i>Editar Datos de Usuario',
    content: '<form>'
    +'<div class="row g-3">'
          +'<div class="col-12">'
            +'<label class="form-label small fw-semibold text-muted" for="itemNombre">Nombre</label>'
            +'<input value="'+allUsuarios[idx]['user_name']+'" type="text" class="form-control" id="itemNombre" placeholder="Nombre completo">'
          +'</div>'
          +'<div class="col-md-6">'
          +'<label class="form-label small fw-semibold text-muted" for="itemEmail">Email</label>'
          +'<input value="'+ allUsuarios[idx]['email']+'" type="email" class="form-control" id="itemEmail" placeholder="correo@ejemplo.com">'
        +'</div>'
          +'<div class="col-md-6">'
            +'<label class="form-label small fw-semibold text-muted" for="itemPhone">Teléfono</label>'
            +'<input value="'+ allUsuarios[idx]['phone']+'" type="text" class="form-control" id="itemPhone" placeholder="Teléfono">'
          +'</div>'
        +'</div>'
      +'</form>',
      boxWidth: '500px',
      useBootstrap: false,
      closeIcon: true,
      theme: 'material',
    buttons: {
        confirm: {
          text: '<i class="fa fa-save me-1"></i>Actualizar',
          btnClass: 'btn-primary',
          action: function () {
            var name = this.$content.find('#itemNombre').val();
            var phone = this.$content.find('#itemPhone').val();
            var email = this.$content.find('#itemEmail').val();
            var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
            var errors = [];
            if(!name) errors.push('Nombre');
            if(!phone) errors.push('Teléfono');
            if(!email) errors.push('Email');
            if(errors.length > 0){
                $.alert({
                  title: '<i class="fa fa-exclamation-triangle text-warning me-2"></i>Campos requeridos',
                  content: 'Complete: <strong>'+errors.join(', ')+'</strong>',
                  type: 'orange',
                  theme: 'material',
                  buttons: { ok: 'Entendido' }
                });
                return false;
            }
            if(!caract.test(email)){
               $.alert({
                 title: '<i class="fa fa-times-circle text-danger me-2"></i>Email inválido',
                 content: 'Formato de correo electrónico no válido',
                 type: 'red',
                 theme: 'material',
                 buttons: { ok: 'Entendido' }
               });
              return false;
            }

            $.ajax({
              url: 'controller/controller.php',
              type: 'POST',
                data: {request: 'editarUsuarios', arg: JSON.stringify({ name: name, phone: phone, email: email, id_user: allUsuarios[idx]['id_user']})},
                success: function(response){
                allUsuarios = JSON.parse(response);
                printTable();
              }
            })
            .always(function() {
              $.alert({
                title: '<i class="fa fa-check-circle text-success me-2"></i>Actualizado',
                content: 'Usuario actualizado correctamente',
                type: 'green',
                theme: 'material',
                buttons: { ok: 'Cerrar' }
              });
            });
          }
        },
        cancel: {
          text: '<i class="fa fa-times me-1"></i>Cancelar',
          btnClass: 'btn-light',
          action: function(){}
        },
    }
  });
}

 function printTable(){
    var tr = "";
    for (var i = 0; i < allUsuarios.length; i++) {
       var btn = '<button type="button" class="btn-action btn-action-edit" onclick="editarUsu('+i+')" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></button>'
              +'<button type="button" class="btn-action btn-action-delete ms-1" onclick="eliminaUsu('+i+')" title="Eliminar"><i class="fa fa-trash" aria-hidden="true"></i></button>';
        tr += '<tr>'
          +'<td class="ps-4 fw-semibold">'+allUsuarios[i]['user_name']+'</td>'
          +'<td><a href="mailto:'+allUsuarios[i]['email']+'" class="text-decoration-none">'+allUsuarios[i]['email']+'</a></td>'
          +'<td>'+allUsuarios[i]['phone']+'</td>'
          +'<td class="text-center">'
            +'<div class="d-flex justify-content-center gap-1">'
             + btn
            +'</div>'
          +'</td>'
        +'</tr>';
    }
    $('#tbodyAllUsuarios').html(tr);
  }

function eliminaUsu(inx){
   $.confirm({
    title: '<i class="fa fa-trash text-danger me-2"></i>Eliminar Usuario',
    content: '¿Está seguro de eliminar a <strong>'+allUsuarios[inx]['user_name']+'</strong>?',
    theme: 'material',
    type: 'red',
    buttons: {
        confirm: {
          text: '<i class="fa fa-trash me-1"></i>Sí, eliminar',
          btnClass: 'btn-danger',
          action: function () {
            $.ajax({
              url: 'controller/controller.php',
              type: 'POST',
                data: {request: 'eliminaUsuario', arg: JSON.stringify({ id_user: allUsuarios[inx]['id_user']})},
                success: function(response){
                allUsuarios = JSON.parse(response);
                printTable();
              }
            })
            .always(function() {
              $.alert({
                title: '<i class="fa fa-check-circle text-success me-2"></i>Eliminado',
                content: 'Usuario eliminado correctamente',
                type: 'green',
                theme: 'material',
                buttons: { ok: 'Cerrar' }
              });
            });
          }
        },
        cancel: {
          text: '<i class="fa fa-times me-1"></i>Cancelar',
          btnClass: 'btn-light',
          action: function(){}
        },
    }
  });
}

printTable();
