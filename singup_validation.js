document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const btn = document.querySelector('.btn');
    
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        let isValid = true;

        // Reset previous errors
        document.querySelectorAll('.form-control').forEach(control => {
            control.classList.remove('error');
        });

        // Firstname validation
        const firstname = document.querySelector('input[name="firstname"]');
        if (!firstname.value.trim()) {
            showError(firstname, 'First name is required');
            isValid = false;
        }

        // Lastname validation
        const lastname = document.querySelector('input[name="lastname"]');
        if (!lastname.value.trim()) {
            showError(lastname, 'Last name is required');
            isValid = false;
        }

        // Username validation
        const username = document.querySelector('input[name="username"]');
        if (!username.value.trim()) {
            showError(username, 'Username is required');
            isValid = false;
        } else if (!/^[a-zA-Z0-9_]{4,20}$/.test(username.value)) {
            showError(username, 'Must be 4-20 characters (letters, numbers, underscores only)');
            isValid = false;
        }

        // Password validation
        const password = document.querySelector('input[name="password"]');
        if (!password.value) {
            showError(password, 'Password is required');
            isValid = false;
        } else if (password.value.length < 8) {
            showError(password, 'Password must be at least 8 characters');
            isValid = false;
        }

        if (isValid) {
            form.submit();
        }
    });

    function showError(input, message) {
        const formControl = input.closest('.form-control');
        formControl.classList.add('error');
        
        let messageEl = formControl.querySelector('.validation-message');
        if (!messageEl) {
            messageEl = document.createElement('div');
            messageEl.className = 'validation-message';
            formControl.appendChild(messageEl);
        }
        messageEl.textContent = message;
    }
});

