import resourceList from './modules/resourcelist';
import flashMessages from './modules/flashMessages';
import impersonation from './modules/impersonation';

let segelElement = document.getElementById('segel');
let resources = JSON.parse(segelElement.getAttribute('data-resources'));

for (let resource of resources) {
    Segel.resources.add({
        id: resource.id,
        name: resource.name
    });

    if (resource.bookings.length > 0) {
        for (let booking of resource.bookings) {
            Segel.bookings.add({
                id: booking.id,
                resource: resource.id,
                start: booking.start_time,
                end: booking.end_time
            });
        }
    }
}

Segel.instance.$store.state.start = parseInt(segelElement.getAttribute('data-start'));
Segel.instance.$store.state.end = parseInt(segelElement.getAttribute('data-end'));

Segel('#segel');