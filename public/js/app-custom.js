// Formatação de moeda brasileira (R$) para campos de valor
document.addEventListener('DOMContentLoaded', function() {

    /**
     * Formata número para formato de moeda brasileira (R$ 1.234,56)
     * @param {string} value - String numérica (apenas dígitos)
     * @returns {string} - Valor formatado como moeda brasileira
     */
    function formatCurrency(value) {
        if (!value || value === '') return '';

        // Remove tudo que não é número
        let numericValue = value.toString().replace(/\D/g, '');

        // Se vazio, retorna vazio
        if (numericValue === '' || numericValue === '0') return '';

        // Converte para número dividindo por 100 (centavos)
        let amount = parseFloat(numericValue) / 100;

        // Formata com Intl.NumberFormat
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount);
    }

    /**
     * Remove formatação e retorna apenas números (para envio ao servidor)
     * @param {string} formattedValue - Valor formatado (R$ 1.234,56)
     * @returns {string} - Valor numérico (1234.56)
     */
    function unformatCurrency(formattedValue) {
        if (!formattedValue) return '0';

        // Remove símbolos e mantém apenas números e vírgula/ponto
        let value = formattedValue
            .replace(/[R$\s]/g, '')  // Remove R$, espaços
            .replace(/\./g, '')       // Remove pontos (separadores de milhar)
            .replace(',', '.');       // Converte vírgula para ponto

        // Retorna como float formatado
        let numValue = parseFloat(value);
        return isNaN(numValue) ? '0' : numValue.toFixed(2);
    }

    // Aplica máscara em todos os campos de preço
    const priceInputs = document.querySelectorAll('input[name="price"], input[id="price"]');

    priceInputs.forEach(function(input) {
        // Se já tem valor, formata na inicialização
        if (input.value && input.value !== '') {
            let currentValue = input.value.toString();

            // Se já está formatado (contém R$), mantém como está
            if (currentValue.includes('R$')) {
                // Já está formatado, apenas garante que está correto
                let numericValue = currentValue.replace(/\D/g, '');
                if (numericValue && numericValue !== '0') {
                    input.value = formatCurrency(numericValue);
                }
            } else {
                // Se é um número simples (ex: 29.90 ou 29,90), converte para centavos e formata
                let numericStr = currentValue.replace(/[^\d.,]/g, '').replace(',', '.');
                let numericValue = parseFloat(numericStr);

                if (!isNaN(numericValue) && numericValue > 0) {
                    // Converte para centavos (multiplica por 100)
                    let cents = Math.round(numericValue * 100).toString();
                    input.value = formatCurrency(cents);
                } else {
                    input.value = '';
                }
            }
        }

        // Formatação durante a digitação
        input.addEventListener('input', function(e) {
            let value = e.target.value;
            let cursorPosition = e.target.selectionStart;

            // Remove formatação para calcular posição do cursor
            let unformatted = value.replace(/\D/g, '');
            let digitsBeforeCursor = value.substring(0, cursorPosition).replace(/\D/g, '').length;

            // Formata o valor
            let formatted = formatCurrency(unformatted);

            // Atualiza o valor
            e.target.value = formatted;

            // Calcula nova posição do cursor
            let newPosition = formatted.length;
            if (digitsBeforeCursor > 0) {
                let count = 0;
                for (let i = 0; i < formatted.length; i++) {
                    if (/\d/.test(formatted[i])) {
                        count++;
                        if (count === digitsBeforeCursor) {
                            newPosition = i + 1;
                            break;
                        }
                    }
                }
            }

            // Restaura posição do cursor
            setTimeout(() => {
                e.target.setSelectionRange(newPosition, newPosition);
            }, 0);
        });

        // Formatação ao colar (Ctrl+V)
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            let paste = (e.clipboardData || window.clipboardData).getData('text');

            // Remove formatação do valor colado
            let numericValue = paste.replace(/\D/g, '');
            if (numericValue) {
                e.target.value = formatCurrency(numericValue);

                // Mova cursor para o final
                setTimeout(() => {
                    e.target.setSelectionRange(e.target.value.length, e.target.value.length);
                }, 0);
            }
        });

        // Validação e formatação ao perder foco
        input.addEventListener('blur', function(e) {
            let value = e.target.value;

            if (value && value.trim() !== '') {
                // Garante que está formatado corretamente
                let numericValue = value.replace(/\D/g, '');
                if (numericValue && numericValue !== '0') {
                    e.target.value = formatCurrency(numericValue);
                } else {
                    e.target.value = '';
                }
            }
        });

        // Previne entrada de caracteres inválidos (exceto números)
        input.addEventListener('keydown', function(e) {
            // Permite: Backspace, Delete, Tab, Escape, Enter, Home, End, setas
            if ([46, 8, 9, 27, 13, 35, 36, 37, 38, 39, 40].indexOf(e.keyCode) !== -1 ||
                // Permite: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true)) {
                return;
            }

            // Permite apenas números
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

        // Intercepta submit do formulário para converter valor formatado
        let form = input.closest('form');
        if (form) {
            // Evita múltiplos listeners
            if (!form.hasAttribute('data-currency-listener')) {
                form.setAttribute('data-currency-listener', 'true');

                form.addEventListener('submit', function(e) {
                    // Converte valor formatado para numérico e atualiza o campo
                    let numericValue = unformatCurrency(input.value);

                    // Atualiza o valor do campo antes de enviar
                    input.value = numericValue;
                });
            }
        }

        // Marca o input como campo de moeda
        input.setAttribute('data-currency-input', 'true');
    });

    // Formata valores monetários exibidos na tela (apenas leitura)
    const priceDisplays = document.querySelectorAll('[data-price-display]');
    priceDisplays.forEach(function(element) {
        let value = parseFloat(element.getAttribute('data-price-display'));
        if (!isNaN(value)) {
            element.textContent = formatCurrency(Math.round(value * 100).toString());
        }
    });

    // Confirmação de exclusão
    const deleteButtons = document.querySelectorAll('form[onsubmit*="confirm"]');
    deleteButtons.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const confirmed = confirm('Tem certeza que deseja excluir este item? Esta ação não pode ser desfeita.');

            if (confirmed) {
                form.submit();
            }
        });

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
        const helpText = select.parentNode.querySelector('.form-text');
        if (helpText && !helpText.textContent.includes('Ctrl+Click')) {
            helpText.innerHTML += '<br><small class="text-muted">💡 Dica: Use Ctrl+Click para selecionar múltiplos itens</small>';
        }
    });
});
