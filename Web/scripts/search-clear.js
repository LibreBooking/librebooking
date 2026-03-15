document.querySelectorAll('.searchclear').forEach(function (element) {
  element.addEventListener('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var refs = element.getAttribute('ref').split(',');
    refs.forEach(function (ref) {
      document.getElementById(ref).value = '';
    });
  });
});
