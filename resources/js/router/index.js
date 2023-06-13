import { createRouter, createWebHistory } from "vue-router";

import Notfound from "../components/pages/Notfound.vue";
import AppLayout from "../components/pages/AppLayout.vue";
import Tasks from "../components/pages/Tasks.vue";
import TaskForm from "../components/pages/TaskForm.vue";

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
            },
            {
                path: 'add-task',
                name: 'add-task',
                component: TaskForm
            },
            {
                path: 'update-task/:id',
                name: 'update-task',
                component: TaskForm
            }
        ]
    }
]
const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router;
