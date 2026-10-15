const navToggle = document.querySelector('.mobile-nav-toggle');
navToggle?.addEventListener('click', () => {
    const open = navToggle.getAttribute('aria-expanded') !== 'true';
    navToggle.setAttribute('aria-expanded', String(open));
    document.querySelector('#office-sidebar')?.classList.toggle('is-open', open);
});
document.querySelectorAll('#office-sidebar a').forEach(link => link.addEventListener('click', () => {
    document.querySelector('#office-sidebar')?.classList.remove('is-open');
    navToggle?.setAttribute('aria-expanded', 'false');
}));
const clock = document.querySelector('[data-office-clock]');
function updateOfficeClock() {
    if (!clock) return;
    const now = new Date();
    const date = new Intl.DateTimeFormat('en-US', { timeZone: 'Asia/Singapore', weekday: 'long', month: 'long', day: '2-digit', year: 'numeric' }).format(now);
    const time = new Intl.DateTimeFormat('en-US', { timeZone: 'Asia/Singapore', hour: '2-digit', minute: '2-digit', hour12: true }).format(now);
    clock.replaceChildren(document.createTextNode(date), document.createElement('br'), document.createTextNode(time));
    clock.dateTime = now.toISOString();
}
updateOfficeClock();
if (clock) setInterval(updateOfficeClock, 30000);

const incidentForm = document.querySelector('[data-incident-form]');
incidentForm?.addEventListener('submit', event => {
    if (incidentForm.dataset.submitting === 'true') {
        event.preventDefault();
        return;
    }
    incidentForm.dataset.submitting = 'true';
    const button = incidentForm.querySelector('button');
    button.disabled = true;
    button.textContent = 'Submitting report...';
    button.setAttribute('aria-busy', 'true');
});
window.addEventListener('pageshow', () => {
    if (!incidentForm) return;
    delete incidentForm.dataset.submitting;
    const button = incidentForm.querySelector('button');
    button.disabled = false;
    button.textContent = 'Submit report for processing';
    button.removeAttribute('aria-busy');
});

const violationSelect = document.querySelector('#violation-select');
const otherViolationField = document.querySelector('#other-violation-field');
function updateOtherViolation() {
    if (!violationSelect || !otherViolationField) return;
    const isOther = violationSelect.value === 'other';
    otherViolationField.hidden = !isOther;
    const input = otherViolationField.querySelector('input');
    input.required = isOther;
    input.disabled = !isOther;
}
violationSelect?.addEventListener('change', updateOtherViolation);
window.addEventListener('pageshow', updateOtherViolation);
updateOtherViolation();

document.querySelectorAll('[data-dismiss-notification]').forEach(button => {
    button.addEventListener('click', () => button.closest('[data-notification]')?.remove());
});

const headerMenus = [...document.querySelectorAll('.insights-menu, .account-menu')];
headerMenus.forEach(menu => {
    menu.addEventListener('toggle', () => {
        if (menu.open) headerMenus.forEach(other => { if (other !== menu) other.open = false; });
    });
});
document.addEventListener('click', event => {
    headerMenus.forEach(menu => { if (!menu.contains(event.target)) menu.open = false; });
});
document.addEventListener('keydown', event => {
    if (event.key === 'Escape') headerMenus.forEach(menu => {
        if (menu.open) { menu.open = false; menu.querySelector('summary').focus(); }
    });
});

const reviewAction = document.querySelector('[data-review-action]');
const meetingDate = document.querySelector('[data-meeting-date]');
function updateMeetingRequirement() {
    if (reviewAction && meetingDate) {
        meetingDate.required = reviewAction.value === 'notice';
        meetingDate.disabled = reviewAction.value !== 'notice';
    }
}
reviewAction?.addEventListener('change', updateMeetingRequirement);
updateMeetingRequirement();

document.querySelectorAll('form:has([data-confirm])').forEach(form => {
    form.addEventListener('submit', event => {
        if (!window.confirm(form.querySelector('[data-confirm]').dataset.confirm)) event.preventDefault();
    });
});

document.querySelectorAll('[data-monthly-chart]').forEach(chart => {
    const buttons = [...chart.querySelectorAll('[data-month-label]')];
    const selectMonth = button => {
        buttons.forEach(other => {
            const selected = other === button;
            other.classList.toggle('is-selected', selected);
            other.setAttribute('aria-pressed', String(selected));
        });
        chart.querySelector('[data-chart-month]').textContent = button.dataset.monthLabel;
        const count = Number(button.dataset.monthCount);
        chart.querySelector('[data-chart-count]').textContent = `${count} ${count === 1 ? 'report submitted' : 'reports submitted'}`;
        chart.querySelector('[data-chart-link]').href = button.dataset.monthUrl;
    };
    buttons.forEach((button, index) => {
        button.addEventListener('click', () => selectMonth(button));
        button.addEventListener('keydown', event => {
            let next;
            if (event.key === 'ArrowRight') next = (index + 1) % buttons.length;
            if (event.key === 'ArrowLeft') next = (index - 1 + buttons.length) % buttons.length;
            if (event.key === 'Home') next = 0;
            if (event.key === 'End') next = buttons.length - 1;
            if (next === undefined) return;
            event.preventDefault();
            buttons[next].focus();
            selectMonth(buttons[next]);
        });
    });
});
