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
            addMessage(response.ok ? data.respuesta : 'No pude procesar la consulta. Revisa el texto e inténtalo de nuevo.');
        } catch {
            addMessage('No fue posible consultar Konex en este momento.');
        } finally {
            button.disabled = false;
            input.focus();
        }
    });
});
