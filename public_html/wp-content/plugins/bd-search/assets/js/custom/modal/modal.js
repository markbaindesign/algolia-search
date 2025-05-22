function modalOpenButton($trigger) {

   // Get all the triggers
   let openButtons = document.querySelectorAll(String($trigger));

   openButtons.forEach((openButton, index) => {
      openButton.setAttribute('href', 'javascript:void(0)');
      openButton.addEventListener("click", modalOpen);
   });
}

function modalCloseButton() {
   let closeButton = document.getElementById('search__wrapper__close');
   closeButton.addEventListener("click", modalClose);
}

function modalCloseOverlay() {
   let closeOverlay = document.getElementById('search_modal_overlay');
   closeOverlay.addEventListener("click", modalClose);
}

function modalClose(event) {
   const html = document.documentElement;
   if (html !== null) {
      html.style.overflow = '';
      html.classList.remove('jsModalSearchOpen');
   }
   event.preventDefault();
}

function modalOpen(event) {
   const html = document.documentElement;
   if (html !== null) {
      html.style.overflow = 'hidden';
      html.classList.add('jsModalSearchOpen');
   }
   event.preventDefault();
}