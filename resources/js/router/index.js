import { createRouter, createWebHistory } from "vue-router";

import Notfound from "../components/pages/Notfound.vue";
import AppLayout from "../components/pages/AppLayout.vue";
import Tasks from "../components/pages/Tasks.vue";

const routes = [
    {
        path: '/:pathMatch(.*)*',
        component: Notfound
    },
    {
        path: '/',
        redirect: '/tasks',
        component: AppLayout,
        children: [
            {
                path: 'tasks',
                name: 'tasks-list',
                component: Tasks
            }
        ]
    }
]
const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router;
