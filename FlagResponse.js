 const form = document.querySelector('form');
const result = document.querySelector('#result');

form.addEventListener('submit', function(event) {
  event.preventDefault();
  const flag = form.elements.flag.value;
  fetch('validate.php', {
    method: 'POST',
    body: new URLSearchParams({ flag }),
  })
    .then(response => response.json())
    .then(data => {
      if (data.valid) {
        result.textContent = 'Flag is correct!';
      } else {
        result.textContent = 'Flag is incorrect!';
      }
    })
    .catch(error => console.error(error));
});
