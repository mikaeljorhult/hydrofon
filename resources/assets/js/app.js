import resourceList from './modules/resourcelist';
import flashMessages from './modules/flashMessages';
import impersonation from './modules/impersonation';

let segelElement = document.getElementById('segel');
let resources = JSON.parse(segelElement.getAttribute('data-resources'));

for (let resource of resources) {
    console.log({
        id: resource.id,
        name: resource.name
    });

    Segel.resources.add({
        id: resource.id,
        name: resource.name
    });
}

Segel('#segel');
