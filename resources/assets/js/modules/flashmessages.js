let timer;
let messages = Array.from(document.getElementsByClassName('alert'));

// Check if there are any messages.
if (messages.length > 0) {
    // Attach event listener each message.
    messages.forEach(function (message) {
        message.addEventListener('click', clickListener, false);
    });

    // Set timer to hide a message.
    timer = setTimeout(nextMessage, 3000);
}

/**
 * Take last message in array and hide it.
 */
function nextMessage() {
    // Trigger hide on last message in DOM.
    hideMessage(messages.pop());

    // Retrigger timer if there are more messages.
    if (messages.length > 0) {
        timer = setTimeout(nextMessage, 3000)
    }
}

/**
 * Hide HTML element supplied and remove it from the DOM once hidden.
 *
 * @param messageElement
 */
function hideMessage(messageElement) {
    // Remove message once it has been hidden.
    messageElement.addEventListener('transitionend', function (event) {
        if (event.propertyName === 'visibility') {
            this.remove();
        }
    });

    // Add class to trigger hide.
    messageElement.classList.add('alert-hide');
}

function clickListener(event) {
    // Stop timer.
    clearTimeout(timer);

    // Hide current message and start counter to hide next.
    hideMessage(this);
    nextMessage();

    // Prevent default click behaviour.
    event.preventDefault();
}