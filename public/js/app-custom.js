// Formatação de moeda para campos de valor
document.addEventListener('DOMContentLoaded', function() {
    // Função para formatar valor como moeda brasileira
    function formatCurrency(value) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(value);
    }

    // Função para remover formatação e retornar apenas números
    function unformatCurrency(value) {
        return value.replace(/[^\d,]/g, '').replace(',', '.');
    }

    // Aplicar formatação aos campos de preço
    const priceInputs = document.querySelectorAll('input[name="price"]');
    
    priceInputs.forEach(function(input) {
        // Formatação durante a digitação
        input.addEventListener('input', function(e) {
            let value = e.target.value;
            
            // Remove caracteres não numéricos exceto vírgula e ponto
            value = value.replace(/[^\d.,]/g, '');
            
            // Substitui vírgula por ponto para cálculos
            value = value.replace(',', '.');
            
            // Limita a 2 casas decimais
            if (value.includes('.')) {
                const parts = value.split('.');
                if (parts[1] && parts[1].length > 2) {
                    value = parts[0] + '.' + parts[1].substring(0, 2);
                }
            }
            
            e.target.value = value;
        });

        // Validação ao sair do campo
        input.addEventListener('blur', function(e) {
            let value = parseFloat(e.target.value);
            if (isNaN(value) || value < 0) {
                value = 0;
            }
            e.target.value = value.toFixed(2);
        });
    });

    // Confirmação de exclusão com SweetAlert (se disponível) ou alert padrão
    const deleteButtons = document.querySelectorAll('form[onsubmit*="confirm"]');
    deleteButtons.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const confirmed = confirm('Tem certeza que deseja excluir este item? Esta ação não pode ser desfeita.');
            
            if (confirmed) {
                form.submit();
            }
        });
        
        // Remove o atributo onsubmit para evitar dupla confirmação
        form.removeAttribute('onsubmit');
    });

    // Auto-hide alerts após 5 segundos
    const alerts = document.querySelectorAll('.alert:not(.alert-danger)');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert.classList.contains('show')) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(function() {
                    alert.remove();
                }, 150);
            }
        }, 5000);
    });

    // Melhorar seleção múltipla com Ctrl+Click info
    const multiSelects = document.querySelectorAll('select[multiple]');
    multiSelects.forEach(function(select) {
        // Adicionar tooltip ou texto de ajuda
        const helpText = select.parentNode.querySelector('.form-text');
        if (helpText) {
            helpText.innerHTML += '<br><small class="text-muted">💡 Dica: Use Ctrl+Click para selecionar múltiplos itens</small>';
        }
    });
});

