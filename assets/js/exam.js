// ======================= sidebar =======================

const sidebar = document.querySelector('.sidebar');
const closeBtn = document.querySelector('#btn');
const searchBtn = document.querySelector('.bx-search');

function menuBtnChange() {
  if (sidebar.classList.contains('open')) {
    closeBtn.classList.replace('bx-menu', 'bx-menu-alt-right');
  } else {
    closeBtn.classList.replace('bx-menu-alt-right', 'bx-menu');
  }
}

closeBtn.addEventListener('click', () => {
  sidebar.classList.toggle('open');
  menuBtnChange();
});

searchBtn.addEventListener('click', () => {
  sidebar.classList.toggle('open');
  menuBtnChange();
});

const dataTable = $('#exam_data_table').DataTable({
  processing: true,
  responsive: true,
  serverSide: true,
  order: [],
  ajax: {
    url: 'ajax_action.php',
    method: 'POST',
    data: { action: 'fetch', page: 'exam' },
    columnDef: [
      {
        targets: [7, 8],
        orderable: false,
      },
    ],
  },
});

// ======================= confirmation modal =======================

const Confirm = {
  open(options) {
    options = Object.assign(
      {},
      {
        title: '',
        message: '',
        okText: 'OK',
        cancelText: 'Cancel',
        onok: function () {},
        oncancel: function () {},
      },
      options
    );

    const html = `
            <div class="confirm">
                <div class="confirm__window">
                    <div class="confirm__titlebar">
                        <span class="confirm__title">${options.title}</span>
                        <button class="confirm__close">&times;</button>
                    </div>
                    <div class="confirm__content">${options.message}</div>
                    <div class="confirm__buttons">
                        <button class="confirm__button confirm__button--ok confirm__button--fill">${options.okText}</button>
                        <button class="confirm__button confirm__button--cancel">${options.cancelText}</button>
                    </div>
                </div>
            </div>
        `;

    const template = document.createElement('template');
    template.innerHTML = html;

    // Elements
    const confirmEl = template.content.querySelector('.confirm');
    const btnClose = template.content.querySelector('.confirm__close');
    const btnOk = template.content.querySelector('.confirm__button--ok');
    const btnCancel = template.content.querySelector(
      '.confirm__button--cancel'
    );

    confirmEl.addEventListener('click', (e) => {
      if (e.target === confirmEl) {
        options.oncancel();
        this._close(confirmEl);
      }
    });

    btnOk.addEventListener('click', () => {
      options.onok();
      this._close(confirmEl);
    });

    [btnCancel, btnClose].forEach((el) => {
      el.addEventListener('click', () => {
        options.oncancel();
        this._close(confirmEl);
      });
    });

    document.body.appendChild(template.content);
  },

  _close(confirmEl) {
    confirmEl.classList.add('confirm--close');

    confirmEl.addEventListener('animationend', () => {
      document.body.removeChild(confirmEl);
    });
  },
};

// ======================= add exam details =======================

function reset_form() {
  document.querySelector('.modal-title').textContent = 'Add Exam Details';
  document.querySelector('#button_action').textContent = 'Add';
  document.querySelector('#action').value = 'add';
  document.querySelector('#exam_form').reset();
}

document.querySelector('#add_button').addEventListener('click', function () {
  reset_form();

  document.querySelector('#message_operation').innerHTML = '';

  const date = new Date();
  date.setDate(date.getDate());

  $('#online_exam_datetime').datetimepicker({
    startDate: date,
  });
});

document
  .querySelector('#exam_form')
  .addEventListener('submit', async function (event) {
    event.preventDefault();

    const formData = new FormData(this);

    const response = await fetch('../php/ajax_action.php', {
      method: 'POST',
      body: formData,
    });

    const data = await response.json();

    if (data.success) {
      const message_operation = document.querySelector('#message_operation');

      $('#message_operation').fadeIn();

      console.log(formData.get('action'));

      if (formData.get('action') === 'add') {
        message_operation.innerHTML = `
        <div class='alert alert-success'>
          New Exam Datails added
        </div>
      `;
      } else if (formData.get('action') === 'edit') {
        message_operation.innerHTML = `
        <div class='alert alert-success'>
          Changes Exam Datails has been saved
        </div>
      `;
      }

      setTimeout(() => {
        $('#message_operation').fadeOut();
      }, 1000);

      reset_form();

      dataTable.ajax.reload();

      $('#examModal').modal('hide');
    }
  });

// ======================= edit & delete exam details =======================
document
  .querySelector('#exam_data_table')
  .addEventListener('click', async (event) => {
    if (event.target.classList.contains('edit')) {
      const exam_id = event.target.parentElement.getAttribute('id');

      const params = new URLSearchParams({
        action: 'edit_fetch',
        exam_id: exam_id,
        page: 'exam',
      });

      const response = await fetch('../php/ajax_action.php', {
        method: 'POST',
        body: params,
      });

      const data = await response.json();

      if (data.success) {
        const {
          online_exam_id,
          online_exam_title,
          online_exam_datetime,
          online_exam_duration,
          total_question,
          marks_per_right_answer,
        } = data;
        const exam_form = document.querySelector('#exam_form');

        exam_form.querySelector('#online_exam_title').value =
          data.online_exam_title;
        exam_form.querySelector('#online_exam_datetime').value =
          online_exam_datetime;
        exam_form.querySelector('#online_exam_duration').value =
          online_exam_duration;
        exam_form.querySelector('#total_question').value = total_question;
        exam_form.querySelector('#marks_per_right_answer').value =
          marks_per_right_answer;
        exam_form.querySelector('#online_exam_id').value = exam_id;

        $('#examModalLabel').textContent = 'Edit Exam Details';

        document.querySelector('#button_action').value = 'Edit';
        document.querySelector('#action').value = 'edit';

        $('#examModal').modal('show');
      }
    } else if (event.target.classList.contains('delete')) {
      Confirm.open({
        title: 'Delete Confirmation',
        message: 'Are you sure you want to remove this?',
        onok: async () => {
          const exam_id = event.target.parentElement.getAttribute('id');
          const params = new URLSearchParams({
            action: 'delete',
            exam_id: exam_id,
            page: 'exam',
          });
          const response = await fetch('../php/ajax_action.php', {
            method: 'POST',
            body: params,
          });

          const data = await response.json();

          if (data.success) {
            const message_operation =
              document.querySelector('#message_operation');

            $('#message_operation').fadeIn();

            message_operation.innerHTML = `
            <div class='alert alert-success'>
              Exam details has been removed
            </div>
            `;

            setTimeout(() => {
              $('#message_operation').fadeOut();
            }, 1000);

            reset_form();

            dataTable.ajax.reload();

            $('#examModal').modal('hide');
          }
        },
      });
    }
  });
