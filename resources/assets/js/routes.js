// components
import Login from 'pages/Login'
import Dashboard from 'pages/Dashboard'
import NotFoundPage from 'pages/404'

// company
import Companies from 'pages/company/Companies'

export default [
  {
    path: '/login', component: Login,
  },
  {
    path: '/', component: Dashboard,
  },
  {
    path: '/companies', component: Companies,
  },
  {
    path: '*', component: NotFoundPage,
  },
]