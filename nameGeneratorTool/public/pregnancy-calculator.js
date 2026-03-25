// ============================================
// PREGNANCY CALCULATOR JAVASCRIPT
// ============================================

let method = 'lmp';

document.addEventListener('DOMContentLoaded', function() {
    // Set max date to today for all date inputs
    let today = new Date().toISOString().split("T")[0];
    const lmpDate = document.getElementById('lmp-date');
    const conceptionDate = document.getElementById('conception-date');
    const ivfDate = document.getElementById('ivf-date');

    if (lmpDate) lmpDate.max = today;
    if (conceptionDate) conceptionDate.max = today;
    if (ivfDate) ivfDate.max = today;
});

function switchTab(selected) {
    method = selected;
    document.querySelectorAll('.tab-btn').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.method-content').forEach(box => box.classList.remove('active'));
    document.querySelector(`[onclick*="${selected}"]`).classList.add('active');
    document.getElementById(`${selected}-content`).classList.add('active');
    hideResults();
    hideError();
}

function calculateDueDate() {
    hideError();
    try {
        let dueDate, conceptionDate, lmpDate;
        const today = new Date();

        if (method == 'lmp') {
            let lmp = document.getElementById('lmp-date').value;
            let cycle = +document.getElementById('cycle-length').value;
            if (!lmp) throw 'Please enter last menstrual period date.';
            lmpDate = new Date(lmp);
            let adjust = cycle - 28;
            dueDate = new Date(lmpDate);
            dueDate.setDate(dueDate.getDate() + 280 + adjust);
            conceptionDate = new Date(lmpDate);
            conceptionDate.setDate(conceptionDate.getDate() + Math.round(cycle / 2));
        }
        else if (method == 'conception') {
            let con = document.getElementById('conception-date').value;
            if (!con) throw 'Please enter conception date.';
            conceptionDate = new Date(con);
            dueDate = new Date(conceptionDate);
            dueDate.setDate(dueDate.getDate() + 266);
            lmpDate = new Date(conceptionDate);
            lmpDate.setDate(lmpDate.getDate() - 14);
        }
        else if (method == 'ivf') {
            let ivf = document.getElementById('ivf-date').value;
            let tday = +document.getElementById('transfer-day').value;
            if (!ivf) throw 'Please enter IVF transfer date.';
            let ivfDate = new Date(ivf);
            if (tday == 3) {
                dueDate = new Date(ivfDate); 
                dueDate.setDate(dueDate.getDate() + 263);
                conceptionDate = new Date(ivfDate); 
                conceptionDate.setDate(conceptionDate.getDate() - 3);
            } else {
                dueDate = new Date(ivfDate); 
                dueDate.setDate(dueDate.getDate() + 261);
                conceptionDate = new Date(ivfDate); 
                conceptionDate.setDate(conceptionDate.getDate() - 5);
            }
            lmpDate = new Date(conceptionDate); 
            lmpDate.setDate(lmpDate.getDate() - 14);
        }

        const daysSinceStart = Math.max(0, Math.floor((today - lmpDate) / (1000 * 60 * 60 * 24)));
        const weeksSince = Math.floor(daysSinceStart / 7);
        const daysInCurrentWeek = daysSinceStart % 7;
        const daysRem = Math.ceil((dueDate - today) / (1000 * 60 * 60 * 24));
        let trimester = 1; 
        if (weeksSince >= 13 && weeksSince < 27) trimester = 2; 
        else if (weeksSince >= 27) trimester = 3;

        const dueFmt = dueDate.toLocaleDateString('en-US', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'});
        const conFmt = conceptionDate.toLocaleDateString('en-US', {year: 'numeric', month: 'long', day: 'numeric'});

        document.getElementById('due-date').innerText = dueFmt;
        document.getElementById('countdown').innerText = (daysRem > 0 ? daysRem + ' days to go' : daysRem < 0 ? Math.abs(daysRem) + ' days overdue' : 'Due today!');
        document.getElementById('current-week').innerText = `${weeksSince} weeks, ${daysInCurrentWeek} days`;
        document.getElementById('trimester').innerText = [, '1st Trimester', '2nd Trimester', '3rd Trimester'][trimester];
        document.getElementById('days-remaining').innerText = Math.abs(daysRem);
        document.getElementById('conception-result').innerText = conFmt;

        let pct = Math.min(Math.max(0, (daysSinceStart / 280) * 100), 100);
        document.getElementById('progress-bar').style.width = pct + '%';
        document.getElementById('results').style.display = 'block';
    } catch (e) {
        showError(e);
    }
}

function showError(msg) {
    const e = document.getElementById('error-message');
    document.getElementById('error-text').innerText = msg;
    e.style.display = 'block';
}

function hideError() {
    const errorMsg = document.getElementById('error-message');
    if (errorMsg) errorMsg.style.display = 'none';
}

function hideResults() {
    const results = document.getElementById('results');
    if (results) results.style.display = 'none';
}

function resetCalculator() {
    document.getElementById('calculator-form').reset();
    switchTab('lmp');
    hideError();
    hideResults();
    const progressBar = document.getElementById('progress-bar');
    if (progressBar) progressBar.style.width = '0%';
}

// Smooth scrolling for navigation links (if needed)
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});