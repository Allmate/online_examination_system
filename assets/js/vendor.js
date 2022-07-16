const setFormMessage = (formElement, type, message) => {
  const messageElement = formElement.querySelector('.form__message');
  messageElement.textContent = message;
  messageElement.classList.remove(
    'form__message--success',
    'form__message--error'
  );

  messageElement.classList.add(`form__message--${type}`);
};

const clearFormMessage = (formElement) => {
  const messageElement = formElement.querySelector('.form__message');
  messageElement.textContent = '';
  messageElement.classList.remove(
    'form__message--success',
    'form__message--error'
  );
};

const setInputError = (inputElement, message) => {
  inputElement.classList.add('form__input--error');
  inputElement.parentElement.querySelector(
    '.form__input-error-message'
  ).textContent = message;
};

const clearInputError = (inputElement) => {
  inputElement.classList.remove('form__input--error');
  inputElement.parentElement.querySelector(
    '.form__input-error-message'
  ).textContent = '';
};

const checkEmailValidation = (email) => {
  const re =
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  return re.test(email);
};

const checkPropertiesEmpty = (obj) => {
  const arr = Object.values(obj);
  return arr.some((value) => value === '');
};

const checkAllPropertiesEmpty = (obj) => {
  const arr = Object.values(obj);
  return arr.every((value) => value === '');
};

const clearProperties = (obj) => {
  for (const key of Object.keys(obj)) {
    obj[key] = '';
  }
};

// link create account
document
  .querySelector('#login')
  .querySelector('#linkCreateAccount')
  .addEventListener('click', (event) => {
    event.preventDefault();
    loginForm.classList.add('form--hidden');
    createAccountForm.classList.remove('form--hidden');
  });

// link login
document
  .querySelector('#createAccount')
  .querySelector('#linkLogin')
  .addEventListener('click', (event) => {
    event.preventDefault();
    createAccountForm.classList.add('form--hidden');
    loginForm.classList.remove('form--hidden');
  });
