// Public site: Bootstrap components plus small, page-agnostic behaviours.
// Each module looks for its own data-* hooks, so pages only need the markup.
import { Alert, Collapse, Dropdown, Modal, Offcanvas, Tab, Toast } from 'bootstrap';
import { initLeadForms } from './site/lead-forms';
import { initConsult } from './site/consult';
import { initCatalogs } from './site/catalog';
import { initThemeLibrary } from './site/theme-library';
import { initGalleries } from './site/gallery';
import { initScrollTop } from './site/scroll-top';
import './site/toolkit';

// Only the plugins the site uses; pages reach them through window.bootstrap.
window.bootstrap = { Alert, Collapse, Dropdown, Modal, Offcanvas, Tab, Toast };

initLeadForms();
initConsult();
initCatalogs();
initThemeLibrary();
initGalleries();
initScrollTop();
