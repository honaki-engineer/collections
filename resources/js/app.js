import './bootstrap';

// ⭐️ JavaScript共通化
import DeleteService from './services/DeleteService';
window.DeleteService = DeleteService;

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
