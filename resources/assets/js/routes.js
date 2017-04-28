// components
import Login from 'pages/Login'
import Dashboard from 'pages/Dashboard'
import NotFoundPage from 'pages/404'

// company
import Companies from 'pages/company/Companies'
import Company from 'pages/company/Company'

// project
import Projects from 'pages/project/Projects'

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
    path: '/projects', component: Projects,
  },
  {
    path: '*', component: NotFoundPage, name: 'not-found'
  },
]