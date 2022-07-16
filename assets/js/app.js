const admin_registration_info = {
  admin_username: '',
  admin_email_address: '',
  admin_password: '',
  admin_confirm_password: ''
};

const admin_login_info = {
  admin_email_address: '',
  admin_password: ''
};

const loginForm = document.querySelector('#login');
const createAccountForm = document.querySelector('#createAccount');
let currentInputId = '';

// implementing input validation
document.querySelectorAll('.form__input').forEach((inputElement) => {
  inputElement.addEventListener('blur', (event) => {
    const id = event.target.id;
    currentInputId = id;
    const value = event.target.value.trim();

    if (id === 'register_admin_username') {
      if (value.length >= 0 && value.length < 3) {
        setInputError(
          inputElement,
          'Username must be at least 3 characters in length'
        );
      } else {
        admin_registration_info.admin_username = value;
      }
    }

    if (
      id === 'register_admin_email_address' ||
      id === 'login_admin_email_address'
    ) {
      if (value === '') {
        setInputError(inputElement, 'The email address is required');
      } else if (!checkEmailValidation(value)) {
        setInputError(
          inputElement,
          'The email address you entered is not valid'
        );
      } else {
        if (id === 'register_admin_email_address')
          admin_registration_info.admin_email_address = value;
        else admin_login_info.admin_email_address = value;
      }
    }

    if (id === 'register_admin_password' || id === 'login_admin_password') {
      if (value === '') {
        setInputError(inputElement, 'Password is required');
      } else if (value.length >= 0 && value.length < 5) {
        setInputError(
          inputElement,
          'Password must be at least 5 characters in length'
        );
      } else {
        if (id === 'register_admin_password')
          admin_registration_info.admin_password = value;
        else admin_login_info.admin_password = value;
      }
    }

    if (id === 'register_admin_confirm_password') {
      if (value === '' || value !== admin_registration_info.admin_password) {
        setInputError(inputElement, 'The Password confirmation does not match');
      } else {
        admin_registration_info.admin_confirm_password = value;
      }
    }
  });

  inputElement.addEventListener('focus', () => {
    clearFormMessage(createAccountForm);
    clearFormMessage(loginForm);
  });

  inputElement.addEventListener('keyup', (e) => {
    // exclude enter key
    if (e.keyCode !== 13) {
      clearInputError(inputElement);
      clearFormMessage(createAccountForm);
      clearFormMessage(loginForm);
    }
  });
});

// implementing Registration
createAccountForm.addEventListener('submit', async (event) => {
  event.preventDefault();

  const inputElements = createAccountForm.querySelectorAll('input');

  // enter key does not emit input's blur event so will not check input validation
  for (const key in admin_registration_info) {
    const inputEl = createAccountForm.querySelector(`#register_${key}`);
    const value = inputEl.value.trim();

    if (!value || value !== admin_registration_info[key]) {
      // for unnecessary network request
      admin_registration_info[key] = '';
      inputEl.blur();
    }

    document.getElementById(currentInputId)?.focus();
  }

  inputElements.forEach((inputEl) => {
    if (!inputEl.value.trim()) {
      setFormMessage(
        createAccountForm,
        'error',
        'Please fill out all fields to proceed'
      );
    }
  });

  const { admin_username, admin_email_address, admin_password } =
    admin_registration_info;

  if (!checkPropertiesEmpty(admin_registration_info)) {
    const params = new URLSearchParams({
      admin_username,
      admin_email_address,
      admin_password,
      action: 'registration',
      page: 'register'
    });

    const response = await fetch('../php/ajax_action.php', {
      method: 'POST',
      body: params
    });

    const { success, error_status } = await response.json();

    if (!success && error_status === 'email_duplication') {
      admin_registration_info.admin_email_address = '';
      const adminEmailAddressInput = createAccountForm.querySelector(
        '#register_admin_email_address'
      );
      setInputError(adminEmailAddressInput, 'Email address already exists');

      return;
    }

    setFormMessage(
      createAccountForm,
      'success',
      'your account has been successfully created'
    );

    clearProperties(admin_registration_info);

    createAccountForm.reset();

    // removing the focus after form reset
    const activeEl = document.activeElement;
    document.activeElement.blur();
    clearInputError(activeEl);
  }
});

// implementing Login
loginForm.addEventListener('submit', async (event) => {
  event.preventDefault();

  const inputElements = loginForm.querySelectorAll('input');

  // enter key does not emit input's blur event so will not check input validation
  for (const key in admin_login_info) {
    const inputEl = loginForm.querySelector(`#login_${key}`);
    const value = inputEl.value.trim();

    if (!value || value !== admin_login_info[key]) {
      // for unnecessary network request
      admin_login_info[key] = '';

      inputEl.blur();
    }
    document.getElementById(currentInputId)?.focus();
  }

  inputElements.forEach((inputEl) => {
    if (!inputEl.value.trim()) {
      setFormMessage(
        loginForm,
        'error',
        'Please fill out all fields to proceed'
      );
    }
  });

  const { admin_email_address, admin_password } = admin_login_info;

  if (!checkPropertiesEmpty({ admin_email_address, admin_password })) {
    const params = new URLSearchParams({
      page: 'login',
      action: 'login',
      admin_email_address,
      admin_password
    });

    const response = await fetch('./ajax_action.php', {
      method: 'POST',
      body: params
    });

    const { success, error_status } = await response.json();

    if (!success && error_status === 'unrecognized_email') {
      setFormMessage(
        loginForm,
        'error',
        'Invalid Email & Password combination'
      );
      return;
    }

    if (success) {
      window.location.href = '../php/index.php';
    }
  }
});
