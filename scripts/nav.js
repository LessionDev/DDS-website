const openBtn = document.querySelector(".openBtn");
const closeBtn = document.querySelector(".closeBtn");
const menu = document.querySelector(".menu");


document.addEventListener('click', (e) => {
  if (!menu.contains(e.target) && !openBtn.contains(e.target)) {
    menu.classList.remove('active');
      
    document.body.classList.remove('no-scroll');
  }
});

openBtn.addEventListener("click", () => {
  menu.classList.add("active");

  document.body.classList.toggle('no-scroll');
});

closeBtn.addEventListener("click", () => {
  menu.classList.remove("active");
  
  document.body.classList.toggle('no-scroll');
});