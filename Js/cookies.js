// Función para comprobar si el usuario ha aceptado las cookies
function checkCookie() {
    return localStorage.getItem('cookiesAccepted');
  }
  
  // Función para mostrar la ventana modal si las cookies no han sido aceptadas
  function showModal() {
    var modal = document.getElementById('myModal');
    modal.style.display = 'block';
  }
  
  // Función para guardar la aceptación de cookies en el navegador
  function acceptCookies() {
    localStorage.setItem('cookiesAccepted', true);
    var modal = document.getElementById('myModal');
    modal.style.display = 'none';
  }
  
  // Evento para mostrar la ventana modal al cargar la página si las cookies no han sido aceptadas
  window.onload = function() {
    if (!checkCookie()) {
      showModal();
    }
  };
  
  // Evento para ocultar la ventana modal al hacer clic en el botón de aceptar
  document.getElementById('acceptBtn').addEventListener('click', acceptCookies);
  