import Header from './components/page/Header.vue';
// import Home from './components/home/Home.vue';

// import Metrics from './components/metrics/Home';
// import Overview from './components/overview/Home';
// import Leadership from './components/leadership/Home';

// import Test from './components/test/Home';

export const routes = [
    {
        path: '', name: 'home', components: {
            default: Header,
        }
    },
    // { 
    //     path: '/home', 
    //     component: Home,
    //     meta: { title: 'neXus | Welcome to Now!'}
    // },
    // { 
    //     path: '/metrics', 
    //     component: Metrics,
    //     meta: { title: 'Metric Performance | neXus' }
    // },
    // {
    //     path: '/test',
    //     component: Test,
    //     meta: { title: 'Test Page | neXus' }
    // },
    // {
    //     path: '/overview',
    //     component: Overview 
    //     // beforeEnter(to, from, next) {
    //     //     window.location = "https://mynexushub.com/overview"
    //     // }
    // },
    // { 
    //     path: '/leadership', 
    //     component: Leadership,
    //     // beforeEnter(to, from, next) {
    //     //     window.location = "https://mynexushub.com/leadership"
    //     // }
    // },
];