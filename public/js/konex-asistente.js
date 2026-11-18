document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#kx-assistant-form');
    const log = document.querySelector('#kx-assistant-log');
    if (!form || !log) return;

    const addMessage = (text, own = false) => {
        const message = document.createElement('div');
        message.className = `kx-assistant-message${own ? ' is-own' : ''}`;
        message.textContent = text;
        log.appendChild(message);
        log.scrollTop = log.scrollHeight;
    };

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const input = form.querySelector('[name="pregunta"]');
        const button = form.querySelector('button');
        const question = input.value.trim();
        if (!question) return;

        addMessage(question, true);
        input.value = '';
        button.disabled = true;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ pregunta: question }),
            });
            const data = await response.json();
            if (!response.ok) {
                addMessage('No pude procesar la consulta. Revisa el texto e inténtalo de nuevo.');
                return;
            }

            const fuentes = Array.isArray(data.fuentes) ? data.fuentes.filter((fuente) => fuente.url) : [];
            const cita = fuentes.length
                ? `\n\nFuente: ${fuentes.map((fuente) => fuente.url).join('\n')}`
                : '';
            addMessage(`${data.respuesta}${cita}`);
        } catch {
            addMessage('No fue posible consultar Konex en este momento.');
        } finally {
            button.disabled = false;
            input.focus();
        }
    });
});
