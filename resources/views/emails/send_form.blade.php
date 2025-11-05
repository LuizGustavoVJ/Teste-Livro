@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Enviar Relatório por E-mail</h4>
                </div>
                <div class="card-body">
                    <form id="emailForm">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail do Destinatário</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback" id="email-error"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label">Assunto (Opcional)</label>
                            <input type="text" class="form-control" id="subject" name="subject" 
                                   value="Relatório de Livros por Autor" maxlength="255">
                            <div class="invalid-feedback" id="subject-error"></div>
                        </div>
                        
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary" id="sendBtn">
                                <span class="spinner-border spinner-border-sm d-none" id="spinner" role="status" aria-hidden="true"></span>
                                Enviar E-mail
                            </button>
                        </div>
                    </form>
                    
                    <div class="alert alert-success d-none" id="successAlert">
                        E-mail adicionado à fila de envio com sucesso!
                    </div>
                    
                    <div class="alert alert-danger d-none" id="errorAlert">
                        <span id="errorMessage"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('emailForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const sendBtn = document.getElementById('sendBtn');
    const spinner = document.getElementById('spinner');
    const successAlert = document.getElementById('successAlert');
    const errorAlert = document.getElementById('errorAlert');
    const errorMessage = document.getElementById('errorMessage');
    
    // Limpar alertas anteriores
    successAlert.classList.add('d-none');
    errorAlert.classList.add('d-none');
    
    // Mostrar spinner
    spinner.classList.remove('d-none');
    sendBtn.disabled = true;
    
    const formData = new FormData(this);
    
    fetch('/api/v1/send-email-report', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            successAlert.classList.remove('d-none');
            document.getElementById('emailForm').reset();
        } else {
            errorMessage.textContent = data.message || 'Erro ao enviar e-mail';
            errorAlert.classList.remove('d-none');
        }
    })
    .catch(error => {
        errorMessage.textContent = 'Erro de conexão';
        errorAlert.classList.remove('d-none');
    })
    .finally(() => {
        spinner.classList.add('d-none');
        sendBtn.disabled = false;
    });
});
</script>
@endsection

