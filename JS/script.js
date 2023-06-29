function confirmDelete() {
  return confirm("Tem certeza de que deseja excluir?");
}

function verificarCamposObrigatorios() {
  var camposObrigatorios = document.querySelectorAll('input[required], textarea[required], select[required]');
  var camposPreenchidos = true;

  for (var i = 0; i < camposObrigatorios.length; i++) {
    if (!camposObrigatorios[i].value) {
      camposPreenchidos = false;
      break;
    }
  }

  if (!camposPreenchidos) {
    alert('Por favor, preencha todos os campos obrigatórios.');
    return false;
  }

  return true;
}
