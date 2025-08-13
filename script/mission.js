exported();
function exported() {
  const sidy = document.querySelector('.sidy');
  const content = document.querySelector('.all');
  const ham = document.querySelector('.ham-div');
  const cancel = document.querySelector('.cancel');
  const body = document.querySelector('body');

  ham.addEventListener('click', () => {
    content.classList.add('active');
    sidy.classList.add('active');
    content.style.pointerEvents="none";
    content.classList.add('opaci');
    body.style.overflowX='hidden';
    body.style.overflowY='hidden';
  });

  cancel.addEventListener('click', () => {
    content.classList.remove('active');
    sidy.classList.remove('active');
    content.style.pointerEvents="all";
    content.classList.remove('opaci');
    body.style.overflowX='hidden';
    body.style.overflowY='scroll';
  });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.info-section ul').forEach(function(ul) {
        ul.querySelectorAll('li').forEach(function(li, idx) {
            li.style.setProperty('--li-index', idx);
        });
    });
    setTimeout(function() {
        document.body.classList.add('loaded');
    }, 200);
});