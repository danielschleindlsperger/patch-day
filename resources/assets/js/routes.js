// components
import Login from 'pages/Login'
import Dashboard from 'pages/Dashboard'
import NotFoundPage from 'pages/404'

export default [
  {
    path: '/login', component: Login,
  },
  {
    path: '/', component: Dashboard,
  },
  {
    path: '*', component: NotFoundPage,
  },
]