// components
import Login from './components/pages/Login'
import Dashboard from './components/pages/Dashboard'
import NotFoundPage from './components/pages/404'

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