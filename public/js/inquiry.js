document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('inquiryForm');
    const attachmentInput = document.getElementById('attachment');
    const flashElement = document.getElementById('inquiry-flash');
    const submitButton = form ? form.querySelector('button[type="submit"]') : null;
    const maxFileSize = 2 * 1024 * 1024;
    let isSubmitting = false;

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function showToast(message, type) {
        const existingToast = document.querySelector('.login-toast');
        if (existingToast) {
            existingToast.remove();
        }

        const toast = document.createElement('div');
        toast.className = 'login-toast ' + (type === 'error' ? 'error-toast' : 'success-toast');
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        toast.innerHTML =
            '<div class="login-toast-content">' +
                '<i class="fas ' + (type === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check') + '"></i>' +
                '<span>' + escapeHtml(message) + '</span>' +
            '</div>';

        document.body.appendChild(toast);

        window.setTimeout(function () {
            toast.classList.add('hide');
            window.setTimeout(function () {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 5000);
    }

    function setSubmittingState(submitting) {
        isSubmitting = submitting;

        if (!submitButton) {
            return;
        }

        if (submitting) {
            if (!submitButton.dataset.originalHtml) {
                submitButton.dataset.originalHtml = submitButton.innerHTML;
            }

            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Sending...';
        } else {
            submitButton.disabled = false;

            if (submitButton.dataset.originalHtml) {
                submitButton.innerHTML = submitButton.dataset.originalHtml;
            }
        }
    }

    if (attachmentInput) {
        attachmentInput.addEventListener('change', function () {
            const file = attachmentInput.files && attachmentInput.files[0];

            if (!file) {
                return;
            }

            if (file.size > maxFileSize) {
                attachmentInput.value = '';
                showToast('Attachment must be 2 MB or smaller.', 'error');
            }
        });
    }

    if (form) {
        form.addEventListener('submit', function (event) {
            if (isSubmitting) {
                event.preventDefault();
                return;
            }

            const file = attachmentInput && attachmentInput.files && attachmentInput.files[0];
            if (file && file.size > maxFileSize) {
                event.preventDefault();
                attachmentInput.value = '';
                showToast('Attachment must be 2 MB or smaller.', 'error');
                return;
            }

            setSubmittingState(true);
        });
    }

    if (flashElement) {
        const toastType = flashElement.dataset.toastType || 'success';
        const toastMessage = flashElement.dataset.toastMessage || '';

        if (toastMessage) {
            showToast(toastMessage, toastType === 'error' ? 'error' : 'success');

            if (toastType === 'success' && submitButton) {
                setSubmittingState(false);
            }
        }
    }
});
