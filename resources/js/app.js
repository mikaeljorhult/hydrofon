import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import ResourceTree from './components/resourceTree';
import QrScanner from './components/qrScanner';
import Segel from './components/segel';
import Flaggspel from './components/flaggspel';
import MultiBook from './components/multiBook';
import QuickBook from './components/quickBook';
import ItemsTable from './components/itemsTable';
Alpine.data('resourceTree', ResourceTree);
Alpine.data('qrScanner', QrScanner);
Alpine.data('segel', Segel);
Alpine.data('flaggspel', Flaggspel);
Alpine.data('multiBook', MultiBook);
Alpine.data('quickBook', QuickBook);
Alpine.data('itemsTable', ItemsTable);
Livewire.start();

import flatpickr from 'flatpickr';
window.flatpickr = flatpickr;
