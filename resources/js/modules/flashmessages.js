let timer;
let messages = Array.from(document.getElementsByClassName('alert'));

// Check if there are any messages.
if (messages.length > 0) {
    // Attach event listener each message.
    messages.forEach(function (message) {
        message.addEventListener('click', clickListener, false);
    });

    // Trigger hiding of next message.
    nextMessage();
}

/**
 * Take last message in array and hide it.
 */
function nextMessage() {
    timer = setTimeout(function () {
        // Trigger hide on last message in DOM.
        hideMessage(messages.pop());

        // Trigger hiding of next message if there are more messages.
        if (messages.length > 0) {
            nextMessage();
        }
    }, 2500);
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

    // Hide current message.
    hideMessage(this);
    messages = messages.filter(function (message) {
        return message.classList.contains('alert-hide') === false;
    });

    // Restart counter for next message.
    nextMessage();

    // Prevent default click behaviour.
    event.preventDefault();
}