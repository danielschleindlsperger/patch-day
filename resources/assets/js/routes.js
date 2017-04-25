// components
import Login from 'pages/Login'
import Dashboard from 'pages/Dashboard'
import NotFoundPage from 'pages/404'

// company
import Companies from 'pages/company/Companies'
import Company from 'pages/company/Company'

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
    path: '/companies/:id', component: Company
  },
  {
    path: '*', component: NotFoundPage,
  },
]