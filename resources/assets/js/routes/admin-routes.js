// components
import Dashboard from 'pages/AdminDashboard'
import NotFoundPage from 'pages/404'
// company
import Companies from 'pages/company/Companies'
import Company from 'pages/company/Company'
// project
import Projects from 'pages/project/admin/Index'
import Project from 'pages/project/admin/Project'
// protocols
import Protocol from 'pages/protocol/admin/Protocol'
// users
import UsersIndex from 'pages/user/Index'
import UserDetail from 'pages/user/Detail'
// patch days
import PatchDayIndex from 'pages/patch-day/Index'
import PatchDayDetail from 'pages/patch-day/Detail'
import PatchDayTodo from 'pages/patch-day/Todo'

export default [
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
    path: '/patch-days/:id', component: PatchDayDetail,
  },
  {
    path: '/patch-days/:id/todo', component: PatchDayTodo,
  },
  {
    path: '*', component: NotFoundPage, name: 'not-found'
  },
]