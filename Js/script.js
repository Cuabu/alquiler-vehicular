document.addEventListener("DOMContentLoaded", function () {
    const slider = document.querySelector(".slider");
    const slides = document.querySelectorAll(".slide");
  
    let currentIndex = 0;
  
    function showSlide(index) {
      if (index < 0) {
        currentIndex = slides.length - 1;
      } else if (index >= slides.length) {
        currentIndex = 0;
      } else {
        currentIndex = index;
      }
  
      const translateValue = -currentIndex * 100 + "%";
      slider.style.transform = "translateX(" + translateValue + ")";
    }
  
    function nextSlide() {
      showSlide(currentIndex + 1);
    }
  
    function prevSlide() {
      showSlide(currentIndex - 1);
    }
  
    setInterval(nextSlide, 3000); // Cambia automáticamente cada 3 segundos
  
    // Event listeners para los botones de anterior y siguiente (opcional)
    document.querySelector(".prev").addEventListener("click", prevSlide);
    document.querySelector(".next").addEventListener("click", nextSlide);
  });
  

