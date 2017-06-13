// components
import Login from 'pages/Login'
import Dashboard from 'pages/Dashboard'
import NotFoundPage from 'pages/404'
// company
import Companies from 'pages/company/Companies'
import Company from 'pages/company/Company'
// project
import Projects from 'pages/project/Projects'
import Project from 'pages/project/Project'
// protocols
import Protocol from 'pages/protocol/Protocol'
// users
import UsersIndex from 'pages/user/Index'
import UserDetail from 'pages/user/Detail'
// patch days
import PatchDayIndex from 'pages/patch-day/Index'

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
    path: '/projects/:id', component: Project,
  },
  {
    path: '/protocols/:id', component: Protocol,
  },
  {
    path: '/users', component: UsersIndex,
  },
  {
    path: '/users/:id', component: UserDetail,
  },
  {
    path: '/patch-days', component: PatchDayIndex,
  },
  {
    path: '*', component: NotFoundPage, name: 'not-found'
  },
]