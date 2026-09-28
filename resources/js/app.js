import { createPopper } from '@popperjs/core';
import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
window.createPopper = createPopper;

async function startApplication() {
    // Load the date-picker only on pages that actually render one.
    if (document.querySelector('[x-ref="datepicker"], [data-datepicker]')) {
        const [{ default: flatpickr }] = await Promise.all([
            import('flatpickr'),
            import('flatpickr/dist/flatpickr.min.css'),
        ]);
        window.flatpickr = flatpickr;
    }

    Alpine.start();

    // Map imports
    if (document.querySelector('#mapOne')) {
        import('./components/map').then(module => module.initMap());
    }

    // Chart imports
    if (document.querySelector('#chartOne')) {
        import('./components/chart/chart-1').then(module => module.initChartOne());
    }
    if (document.querySelector('#chartTwo')) {
        import('./components/chart/chart-2').then(module => module.initChartTwo());
    }
    if (document.querySelector('#chartThree')) {
        import('./components/chart/chart-3').then(module => module.initChartThree());
    }
    if (document.querySelector('#chartSix')) {
        import('./components/chart/chart-6').then(module => module.initChartSix());
    }
    if (document.querySelector('#chartEight')) {
        import('./components/chart/chart-8').then(module => module.initChartEight());
    }
    if (document.querySelector('#chartThirteen')) {
        import('./components/chart/chart-13').then(module => module.initChartThirteen());
    }

    // Calendar init
    if (document.querySelector('#calendar')) {
        import('./components/calendar-init').then(module => module.calendarInit());
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', startApplication, { once: true });
} else {
    startApplication();
}
