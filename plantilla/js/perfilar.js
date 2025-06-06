function perfilar(idperfil, moduloid, vistaid) {
  let path = window.location.pathname;
  let division = path.split("/");
  let url = "";
  if (division.length >= 5) {
    url = "../opciones/perfilesBackend.php";
  } else {
    url = "../opciones/perfilesBackend.php";
  }
  $.ajax({
    url: url,
    type: "POST",
    dataType: "json",
    data: {
      ind: "9",
      idperfil: idperfil,
      moduloid: moduloid,
      vistaid: vistaid,
    },
  })
    .done(function (respuesta) {
      if (respuesta.respuesta != "NO") {
        respuesta.forEach((respuesta) => {
          if (respuesta.tipoelemento == "LABEL") {
            if (respuesta.permiso == "Ocultar") {
              try {
                let element = document.getElementById(respuesta.elemento);
                $(element).hide();
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }

            if (respuesta.permiso == "Ofuscar") {
              try {
                let element = document.getElementById(respuesta.elemento);
                $(element).text("******" + $(element).text());
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }
          }

          if (respuesta.tipoelemento == "MODAL") {
            if (respuesta.permiso == "Bloquear") {
              try {
                let element = document.getElementById(respuesta.elemento);
                $(element).removeAttr("onclick");
                $(element).removeAttr("href");
                element.style.pointerEvents = "none";
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }

            if (respuesta.permiso == "Ocultar") {
              try {
                let element = document.getElementById(respuesta.elemento);
                $(element).hide();
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }
          }

          if (respuesta.tipoelemento == "TEXTBOX") {
            if (respuesta.permiso == "Bloquear") {
              try {
                let element = document.getElementById(respuesta.elemento);
                if (element.value != "" || element.value != 0) {
                  $(element).attr("disabled", true);
                }
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }

            if (respuesta.permiso == "Ofuscar") {
              try {
                let element = document.getElementById(respuesta.elemento);
                if(element.value != ""){

                  $(element).attr("type", "text");
                  $(element).val("*******");
                  $(element).attr("readonly", true);
                  $(element).attr("disabled", true);
                }
                
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }

            if (respuesta.permiso == "Ocultar") {
              try {
                let element = document.getElementById(respuesta.elemento);
                $(element).hide();
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }
          }
        
          if (respuesta.tipoelemento == "ICONO") {
            if (respuesta.permiso == "Bloquear") {
              try {
                let element = document.getElementById(respuesta.elemento);
                if (element.value != "" || element.value != 0) {
                  $(element).attr("readonly", true);
                }
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }

             if (respuesta.permiso == "Ocultar") {
              try {
                let element = document.getElementById(respuesta.elemento);
                $(element).hide();
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }
          }

          if (respuesta.tipoelemento == "SELECTOR") {
            if (respuesta.permiso == "Bloquear") {
              try {
                let element = document.getElementById(respuesta.elemento);
                $(element).attr("disabled", true);
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }

            if (respuesta.permiso == "Ocultar") {
              try {
                let element = document.getElementById(respuesta.elemento);
                $(element).hide();
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }

            if (respuesta.permiso == "Ofuscar") {
              try {
                let element = document.getElementById(respuesta.elemento);
                $(element).bind("blur", function (e) {
                  $(element).attr("disabled", true);
                  $(element).text("******" + $(element).text());
                });
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }
          }

          if (respuesta.tipoelemento == "BOTON") {
            if (respuesta.permiso == "Bloquear") {
              try {
                let element = document.getElementById(respuesta.elemento);
                $(element).attr("disabled", true);
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }

            if (respuesta.permiso == "Ocultar") {
              try {
                let element = document.getElementById(respuesta.elemento);
                $(element).hide();
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }
          }

          if (respuesta.tipoelemento == "TABLA_MODAL") {
            if (respuesta.permiso == "Bloquear") {
              try {
                let elements = document.getElementsByName(respuesta.elemento);
                elements.forEach(function (element) {
                  $(element).removeAttr("onclick");
                  $(element).removeAttr("href");
                  element.style.pointerEvents = "none";
                });
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }

            if (respuesta.permiso == "Ocultar") {
              try {
                let elements = document.getElementsByName(respuesta.elemento);
                elements.forEach((elements) => {
                  $(elements).hide();
                });
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }
          }

          if (respuesta.tipoelemento == "TAB") {
            if (respuesta.permiso == "Bloquear") {
              try {
                let element = document.getElementById(respuesta.elemento);
                $(element).attr("disabled", true);
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }

            if (respuesta.permiso == "Ocultar") {
              try {
                let element = document.getElementById(respuesta.elemento);
                $(element).hide();
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }
          }

          if (respuesta.tipoelemento == "MENU") {
            try {
                  var menu = document.getElementById(respuesta.elemento);
                  if (respuesta.permiso == "Bloquear") {
                    menu.style.pointerEvents = "none";  
                    menu.style.display = "none";  
                      console.log(error);
                      console.log(document.getElementById(respuesta.elemento));
                      console.log(respuesta.elemento);
                  }
            
            } catch (error) {
            
            }
         }

          if (respuesta.tipoelemento == "TABLA_SELECTOR") {
            if (respuesta.permiso == "Bloquear") {
              try {
                let elements = document.getElementsByName(respuesta.elemento);
                elements.forEach((element) => {
                  $(element).attr("disabled", true);
                });
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }

            if (respuesta.permiso == "Ocultar") {
              try {
                let elements = document.getElementsByName(respuesta.elemento);
                elements.forEach((element) => {
                  $(element).hide();
                });
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }

            if (respuesta.permiso == "Ofuscar") {
              try {
                let elements = document.getElementsByName(respuesta.elemento);
                elements.forEach((element) => {
                  $(element).bind("blur", function () {
                    $(element).text("******" + $(element).text().slice(-3));
                  });
                });
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }
          }

          if (respuesta.tipoelemento == "TABLA_BOTON") {
            if (respuesta.permiso == "Bloquear") {
              try {
                let elements = document.getElementsByName(respuesta.elemento);
                elements.forEach((element) => {
                  $(element).attr("disabled", true);
                });
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }

            if (respuesta.permiso == "Ocultar") {
              try {
                let elements = document.getElementsByName(respuesta.elemento);
                elements.forEach((element) => {
                  $(element).hide();
                });
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }
          }

          if (respuesta.tipoelemento == "CONTENEDOR") {
            if (respuesta.permiso == "Ocultar") {
              try {
                let element = document.getElementById(respuesta.elemento);
                $(element).hide();
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }
          }

          if (respuesta.tipoelemento == "TABLA_CONTENEDOR") {
            if (respuesta.permiso == "Ocultar") {
              try {
                let elements = document.getElementsByName(respuesta.elemento);
                elements.forEach((element) => {
                  $(element).hide();
                });
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }

            if (respuesta.permiso == "Ofuscar") {
              try {
                let elements = document.getElementsByName(respuesta.elemento);
                elements.forEach((element) => {
                  $(element).text("******" + $(element).text().slice(-3));
                });
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }
          }

          if (respuesta.tipoelemento == "SECCION") {
            if (respuesta.permiso == "Bloquear") {
              try {
                $("#" + respuesta.elemento + " :input").attr("disabled", true);
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }

            if (respuesta.permiso == "Ocultar") {
              try {
                $("#" + respuesta.elemento).attr("hidden", true);
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }
          }

          if (respuesta.tipoelemento == "VISTA") {
            if (respuesta.permiso == "Bloquear") {
              try {
                $("#bodi").hide();
                    Swal.fire(
                        'Despacio!!!',
                        'Su perfil actualmente no tiene acceso a este módulo',
                        "error"
                    );
                 
                setTimeout(() => {
                  history.back();
                }, 2000);
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }
          }

          if (respuesta.tipoelemento == "CHECKBOX") {
            if (respuesta.permiso == "Bloquear") {
              try {
                let element = document.getElementsByName(respuesta.elemento);
                element.setAttribute("disabled", "disabled");
                element.removeEventListener('click', null);

              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }

            if (respuesta.permiso == "Ocultar") {
              try {
                let element = document.getElementsByName(respuesta.elemento);
                $(element).hide();
              } catch (error) {
                console.log(error);
                console.log(document.getElementById(respuesta.elemento));
                console.log(respuesta.elemento);
              }
            }
          }

          if (respuesta.tipoelemento == "MODULO") {
            try {
              let modulo = "<?php echo($modulo); ?>";
              if (respuesta.elemento == modulo) {
                $("#bodi").hide();
                    Swal.fire(
                        'Despacio!!!',
                        'Su perfil actualmente no tiene acceso a este módulo',
                        "error"
                    );
                setTimeout(() => {
                  history.back();
                  // window.location("/puntomas/administracion/Asesor_perfil.php");
                }, 4000);
              }
            } catch (error) {
              console.log(error);
              console.log(document.getElementById(respuesta.elemento));
              console.log(respuesta.elemento);
            }
          }
        });
      }
    })
    .fail(function (resp) {
      console.log("fail:" + resp.responseText);
      console.log("fail:" + resp);
    })
    .always(function () {
      // console.log("always:" + "se completó");
    });
}
