// Adiciona um evento "submit" ao formulário de reserva para realizar validações
document.getElementById("reservationForm").addEventListener("submit", function(event) {
    const nome = document.getElementById("nome").value.trim();
    const email = document.getElementById("email").value.trim();
    const numeroNoites = parseInt(document.getElementById("numero_noites").value, 10);
    const numeroHospedes = parseInt(document.getElementById("numero_hospedes").value, 10);
    const dataChegada = document.getElementById("data_chegada").value;

    // Validação do nome (mínimo 3 caracteres)
    if (nome.length < 3) {
        alert("O nome deve ter pelo menos 3 caracteres.");
        event.preventDefault();
        return;
    }

    // Validação do email (formato de email simples)
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert("Por favor, insira um endereço de email válido.");
        event.preventDefault();
        return;
    }

    // Validação do número de noites (deve ser um número positivo)
    if (isNaN(numeroNoites) || numeroNoites <= 0) {
        alert("O número de noites deve ser um valor positivo.");
        event.preventDefault();
        return;
    }

    // Validação do número de hóspedes (deve ser um número positivo)
    if (isNaN(numeroHospedes) || numeroHospedes <= 0) {
        alert("O número de hóspedes deve ser um valor positivo.");
        event.preventDefault();
        return;
    }

    // Validação da data de chegada (não pode ser uma data passada)
    const hoje = new Date();
    const dataChegadaObj = new Date(dataChegada);

    // Verifica se a data de chegada é válida e se não está no passado
    if (isNaN(dataChegadaObj.getTime()) || dataChegadaObj < hoje) {
        alert("A data de chegada não pode ser uma data passada.");
        event.preventDefault();
        return;
    }
});